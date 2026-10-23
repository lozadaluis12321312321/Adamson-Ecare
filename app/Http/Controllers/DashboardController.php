<?php

namespace App\Http\Controllers;

use App\Models\DentalAppointment;
use App\Models\TeleconsultationRequest;
use App\Models\MedicalCertificateRequest;
use App\Models\HealthInquiry;
use App\Models\Announcement;
use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $userId = $user->id;

        // Dental Stats
        $dentalCount = DentalAppointment::where('user_id', $userId)->count();
        $dentalPending = DentalAppointment::where('user_id', $userId)->where('status', 'pending')->count();
        $dentalApproved = DentalAppointment::where('user_id', $userId)->whereIn('status', ['approved', 'completed'])->count();

        // Teleconsultation Stats
        $teleconsultCount = TeleconsultationRequest::where('user_id', $userId)->count();
        $teleconsultPending = TeleconsultationRequest::where('user_id', $userId)->where('status', 'pending')->count();
        $teleconsultApproved = TeleconsultationRequest::where('user_id', $userId)->whereIn('status', ['approved', 'scheduled', 'completed'])->count();

        // Medical Certificate Stats
        $medCertCount = MedicalCertificateRequest::where('user_id', $userId)->count();
        $medCertPending = MedicalCertificateRequest::where('user_id', $userId)->whereIn('status', ['submitted', 'under_review'])->count();
        $medCertApproved = MedicalCertificateRequest::where('user_id', $userId)->whereIn('status', ['approved', 'ready_for_release', 'released'])->count();

        // Health Inquiry Stats
        $inquiryCount = HealthInquiry::where('user_id', $userId)->count();
        $inquiryPending = HealthInquiry::where('user_id', $userId)->whereIn('status', ['submitted', 'in_progress'])->count();
        $inquiryResolved = HealthInquiry::where('user_id', $userId)->where('status', 'resolved')->count();

        // Totals
        $totalRequests = $dentalCount + $teleconsultCount + $medCertCount + $inquiryCount;
        $pendingRequests = $dentalPending + $teleconsultPending + $medCertPending + $inquiryPending;
        $approvedRequests = $dentalApproved + $teleconsultApproved + $medCertApproved + $inquiryResolved;

        // Upcoming Appointments
        $upcomingDental = DentalAppointment::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved', 'rescheduled'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date', 'asc')
            ->take(3)
            ->get();

        $upcomingTeleconsultations = TeleconsultationRequest::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved', 'scheduled'])
            ->where('preferred_date', '>=', now()->toDateString())
            ->orderBy('preferred_date', 'asc')
            ->take(3)
            ->get();

        // Recent Requests
        $recentDental = DentalAppointment::where('user_id', $userId)->latest()->take(3)->get();
        $recentTeleconsult = TeleconsultationRequest::where('user_id', $userId)->latest()->take(3)->get();
        $recentMedCerts = MedicalCertificateRequest::where('user_id', $userId)->latest()->take(3)->get();
        $recentInquiries = HealthInquiry::where('user_id', $userId)->latest()->take(3)->get();

        // Latest Announcements
        $announcements = Announcement::published()
            ->orderBy('priority', 'desc')
            ->latest()
            ->take(4)
            ->get();

        // Notifications
        $notifications = AppNotification::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'upcomingDental',
            'upcomingTeleconsultations',
            'recentDental',
            'recentTeleconsult',
            'recentMedCerts',
            'recentInquiries',
            'announcements',
            'notifications'
        ));
    }
}
