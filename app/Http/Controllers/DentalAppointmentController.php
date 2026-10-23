<?php

namespace App\Http\Controllers;

use App\Models\AppointmentSchedule;
use App\Models\DentalAppointment;
use App\Models\AppNotification;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DentalAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = DentalAppointment::where('user_id', $user->id)
            ->with('schedule');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('dental.index', compact('appointments'));
    }

    public function create()
    {
        // Get active upcoming schedules with available slots
        $schedules = AppointmentSchedule::where('is_active', true)
            ->where('schedule_date', '>=', now()->toDateString())
            ->orderBy('schedule_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->filter(function ($sched) {
                return $sched->availableSlots() > 0;
            });

        return view('dental.create', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_schedule_id' => ['required', 'exists:appointment_schedules,id'],
            'reason_for_visit' => ['required', 'string', 'max:255'],
            'specific_concerns' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        return DB::transaction(function () use ($validated, $user) {
            $schedule = AppointmentSchedule::lockForUpdate()->findOrFail($validated['appointment_schedule_id']);

            if (!$schedule->is_active || $schedule->schedule_date < now()->toDateString()) {
                return back()->withErrors(['appointment_schedule_id' => 'The selected schedule is no longer active or in the past.'])->withInput();
            }

            // Check if user already has an active appointment for this same schedule
            $existingUserBooking = DentalAppointment::where('user_id', $user->id)
                ->where('appointment_schedule_id', $schedule->id)
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->first();

            if ($existingUserBooking) {
                return back()->withErrors(['appointment_schedule_id' => 'You already have an appointment booked for this time slot.'])->withInput();
            }

            // Check capacity
            $bookedCount = DentalAppointment::where('appointment_schedule_id', $schedule->id)
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->count();

            if ($bookedCount >= $schedule->max_slots) {
                return back()->withErrors(['appointment_schedule_id' => 'Sorry, this schedule slot has just been fully booked. Please select another slot.'])->withInput();
            }

            $appointment = DentalAppointment::create([
                'user_id' => $user->id,
                'appointment_schedule_id' => $schedule->id,
                'appointment_date' => $schedule->schedule_date,
                'appointment_time' => date('h:i A', strtotime($schedule->start_time)) . ' - ' . date('h:i A', strtotime($schedule->end_time)),
                'reason_for_visit' => $validated['reason_for_visit'],
                'specific_concerns' => $validated['specific_concerns'] ?? null,
                'status' => 'pending',
            ]);

            // Notify User
            AppNotification::send(
                $user->id,
                'Dental Appointment Submitted',
                "Your dental appointment request for {$schedule->schedule_date->format('M d, Y')} has been submitted and is pending review.",
                'dental',
                route('dental.show', $appointment->id)
            );

            // Notify Admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                AppNotification::send(
                    $admin->id,
                    'New Dental Appointment Request',
                    "{$user->name} ({$user->id_number}) requested a dental appointment for {$schedule->schedule_date->format('M d, Y')}.",
                    'dental',
                    route('admin.dental.index')
                );
            }

            ActivityLog::log(
                $user->id,
                'Dental',
                'Appointment Requested',
                "Submitted dental appointment request for {$schedule->schedule_date->format('M d, Y')}."
            );

            return redirect()->route('dental.index')->with('success', 'Dental appointment request submitted successfully!');
        });
    }

    public function show(DentalAppointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to appointment details.');
        }

        $appointment->load('schedule');

        return view('dental.show', compact('appointment'));
    }

    public function cancel(DentalAppointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($appointment->status, ['pending', 'approved', 'rescheduled'])) {
            return back()->with('error', 'This appointment can no longer be cancelled.');
        }

        $appointment->update([
            'status' => 'cancelled',
        ]);

        ActivityLog::log(
            Auth::id(),
            'Dental',
            'Appointment Cancelled',
            "Cancelled dental appointment scheduled on {$appointment->appointment_date->format('M d, Y')}."
        );

        return back()->with('success', 'Appointment has been cancelled successfully.');
    }
}
