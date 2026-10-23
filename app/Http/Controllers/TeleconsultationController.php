<?php

namespace App\Http\Controllers;

use App\Models\TeleconsultationRequest;
use App\Models\AppNotification;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeleconsultationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = TeleconsultationRequest::where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('preferred_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('teleconsultation.index', compact('requests'));
    }

    public function create()
    {
        $categories = [
            'General Health Consultation',
            'Mental Well-Being Support',
            'Nutritional Advice',
            'Follow-up Checkup',
            'Skin & Allergy Concern',
            'Fever & Respiratory Symptoms',
            'Other Health Inquiries',
        ];

        $timeSlots = [
            '07:30 AM - 09:00 AM (Early Morning)',
            '09:00 AM - 11:00 AM (Morning)',
            '11:00 AM - 01:00 PM (Midday)',
            '01:30 PM - 03:30 PM (Afternoon)',
            '03:30 PM - 05:30 PM (Late Afternoon)',
            '06:00 PM - 08:30 PM (Evening)',
        ];

        return view('teleconsultation.create', compact('categories', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'string'],
            'category' => ['required', 'string'],
            'description' => ['required', 'string', 'min:10', 'max:1500'],
            'preferred_contact_method' => ['required', 'string', 'in:Video Meeting Link (Google Meet / Zoom),Phone Call,In-App Teleconsultation'],
            'contact_details' => ['required', 'string', 'max:100'],
        ]);

        $teleconsult = TeleconsultationRequest::create([
            'user_id' => $user->id,
            'preferred_date' => $validated['preferred_date'],
            'preferred_time' => $validated['preferred_time'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'preferred_contact_method' => $validated['preferred_contact_method'],
            'contact_details' => $validated['contact_details'],
            'status' => 'pending',
        ]);

        AppNotification::send(
            $user->id,
            'Teleconsultation Request Submitted',
            "Your teleconsultation request for {$teleconsult->preferred_date->format('M d, Y')} ({$teleconsult->preferred_time}) is submitted.",
            'teleconsultation',
            route('teleconsultation.show', $teleconsult->id)
        );

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            AppNotification::send(
                $admin->id,
                'New Teleconsultation Request',
                "{$user->name} requested a teleconsultation for {$teleconsult->preferred_date->format('M d, Y')}.",
                'teleconsultation',
                route('admin.teleconsultation.index')
            );
        }

        ActivityLog::log(
            $user->id,
            'Teleconsultation',
            'Request Submitted',
            "Submitted teleconsultation request for {$teleconsult->category}."
        );

        return redirect()->route('teleconsultation.index')->with('success', 'Teleconsultation request submitted successfully!');
    }

    public function show(TeleconsultationRequest $teleconsultation)
    {
        if ($teleconsultation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('teleconsultation.show', compact('teleconsultation'));
    }

    public function cancel(TeleconsultationRequest $teleconsultation)
    {
        if ($teleconsultation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        if (!in_array($teleconsultation->status, ['pending', 'approved', 'scheduled'])) {
            return back()->with('error', 'This teleconsultation request can no longer be cancelled.');
        }

        $teleconsultation->update(['status' => 'cancelled']);

        ActivityLog::log(
            Auth::id(),
            'Teleconsultation',
            'Request Cancelled',
            "Cancelled teleconsultation request for {$teleconsultation->preferred_date->format('M d, Y')}."
        );

        return back()->with('success', 'Teleconsultation request has been cancelled.');
    }
}
