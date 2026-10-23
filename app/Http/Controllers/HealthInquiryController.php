<?php

namespace App\Http\Controllers;

use App\Models\HealthInquiry;
use App\Models\InquiryResponse;
use App\Models\AppNotification;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthInquiryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = HealthInquiry::where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inquiries = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('inquiries.index', compact('inquiries'));
    }

    public function create()
    {
        $categories = [
            'General Health Concern',
            'Clinic Services & Hours',
            'Vaccination & Immunization',
            'Medical Records & Clearance',
            'Medication & Pharmacy Inquiries',
            'Health Insurance & University Coverage',
            'Other Concerns',
        ];

        return view('inquiries.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'category' => ['required', 'string'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $inquiry = HealthInquiry::create([
            'user_id' => $user->id,
            'category' => $validated['category'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'submitted',
        ]);

        AppNotification::send(
            $user->id,
            'Health Inquiry Submitted',
            "Your inquiry regarding '{$inquiry->subject}' has been submitted to Health Services.",
            'inquiry',
            route('inquiries.show', $inquiry->id)
        );

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            AppNotification::send(
                $admin->id,
                'New Health Inquiry',
                "{$user->name} submitted an inquiry: '{$inquiry->subject}'.",
                'inquiry',
                route('admin.inquiries.show', $inquiry->id)
            );
        }

        ActivityLog::log(
            $user->id,
            'Health Inquiry',
            'Inquiry Created',
            "Created inquiry: {$inquiry->subject}."
        );

        return redirect()->route('inquiries.index')->with('success', 'Your inquiry has been sent to the Health Services staff.');
    }

    public function show(HealthInquiry $healthInquiry)
    {
        if ($healthInquiry->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $healthInquiry->load(['responses.user']);

        return view('inquiries.show', compact('healthInquiry'));
    }

    public function reply(Request $request, HealthInquiry $healthInquiry)
    {
        if ($healthInquiry->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($healthInquiry->status === 'closed') {
            return back()->with('error', 'This inquiry thread is closed.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:2000'],
        ]);

        InquiryResponse::create([
            'health_inquiry_id' => $healthInquiry->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_staff_reply' => false,
        ]);

        $healthInquiry->update(['status' => 'in_progress']);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            AppNotification::send(
                $admin->id,
                'User Response on Inquiry',
                Auth::user()->name . " replied to inquiry: {$healthInquiry->subject}.",
                'inquiry',
                route('admin.inquiries.show', $healthInquiry->id)
            );
        }

        ActivityLog::log(
            Auth::id(),
            'Health Inquiry',
            'User Reply',
            "Replied to inquiry #{$healthInquiry->id}."
        );

        return back()->with('success', 'Your response has been added.');
    }
}
