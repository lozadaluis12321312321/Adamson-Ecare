<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DentalAppointment;
use App\Models\AppointmentSchedule;
use App\Models\TeleconsultationRequest;
use App\Models\MedicalCertificateRequest;
use App\Models\HealthInquiry;
use App\Models\InquiryResponse;
use App\Models\HealthArticle;
use App\Models\Announcement;
use App\Models\ActivityLog;
use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    // 1. Dashboard
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalFacultyStaff = User::where('role', 'faculty_staff')->count();
        
        $pendingDental = DentalAppointment::where('status', 'pending')->count();
        $pendingTeleconsultations = TeleconsultationRequest::where('status', 'pending')->count();
        $pendingMedCerts = MedicalCertificateRequest::whereIn('status', ['submitted', 'under_review'])->count();
        $unresolvedInquiries = HealthInquiry::whereIn('status', ['submitted', 'in_progress', 'awaiting_user_response'])->count();
        $totalAnnouncements = Announcement::count();

        $recentDental = DentalAppointment::with('user')->latest()->take(5)->get();
        $recentTeleconsult = TeleconsultationRequest::with('user')->latest()->take(5)->get();
        $recentMedCerts = MedicalCertificateRequest::with('user')->latest()->take(5)->get();
        $recentInquiries = HealthInquiry::with('user')->latest()->take(5)->get();
        $recentLogs = ActivityLog::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStudents',
            'totalFacultyStaff',
            'pendingDental',
            'pendingTeleconsultations',
            'pendingMedCerts',
            'unresolvedInquiries',
            'totalAnnouncements',
            'recentDental',
            'recentTeleconsult',
            'recentMedCerts',
            'recentInquiries',
            'recentLogs'
        ));
    }

    // 2. Users Management
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%")
                  ->orWhere('department_or_course', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'in:student,faculty_staff,admin'],
            'id_number' => ['required', 'string', 'max:50'],
            'department_or_course' => ['required', 'string', 'max:150'],
            'contact_number' => ['required', 'string', 'max:20'],
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_number' => ['nullable', 'string', 'max:20'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'id_number' => $validated['id_number'],
            'department_or_course' => $validated['department_or_course'],
            'contact_number' => $validated['contact_number'],
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_number' => $validated['emergency_contact_number'] ?? null,
            'is_active' => true,
        ]);

        ActivityLog::log(
            Auth::id(),
            'User Management',
            'User Created',
            "Admin created user {$user->name} with role {$user->role}."
        );

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' created successfully.");
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'in:student,faculty_staff,admin'],
            'id_number' => ['required', 'string', 'max:50'],
            'department_or_course' => ['required', 'string', 'max:150'],
            'contact_number' => ['required', 'string', 'max:20'],
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_number' => ['nullable', 'string', 'max:20'],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', Rules\Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->id_number = $validated['id_number'];
        $user->department_or_course = $validated['department_or_course'];
        $user->contact_number = $validated['contact_number'];
        $user->emergency_contact_name = $validated['emergency_contact_name'] ?? null;
        $user->emergency_contact_number = $validated['emergency_contact_number'] ?? null;
        $user->is_active = $validated['is_active'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        ActivityLog::log(
            Auth::id(),
            'User Management',
            'User Updated',
            "Admin updated user {$user->name} ({$user->id_number})."
        );

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' updated successfully.");
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own administrative account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusStr = $user->is_active ? 'activated' : 'deactivated';

        ActivityLog::log(
            Auth::id(),
            'User Management',
            'Status Toggle',
            "User {$user->name} was {$statusStr}."
        );

        return back()->with('success', "User '{$user->name}' has been {$statusStr}.");
    }

    // 3. Dental Appointments Management
    public function dentalAppointments(Request $request)
    {
        $query = DentalAppointment::with(['user', 'schedule']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('appointment_date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%");
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'asc')
            ->paginate(12)
            ->withQueryString();

        return view('admin.dental.index', compact('appointments'));
    }

    public function updateDentalStatus(Request $request, DentalAppointment $appointment)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:approved,rescheduled,rejected,completed,cancelled'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'rescheduled_date' => ['nullable', 'required_if:status,rescheduled', 'date'],
            'rescheduled_time' => ['nullable', 'required_if:status,rescheduled', 'string'],
        ]);

        $oldStatus = $appointment->status;
        $appointment->status = $validated['status'];
        $appointment->admin_notes = $validated['admin_notes'] ?? null;

        if ($validated['status'] === 'rescheduled') {
            $appointment->rescheduled_date = $validated['rescheduled_date'];
            $appointment->rescheduled_time = $validated['rescheduled_time'];
        }

        $appointment->save();

        // Send Notification to User
        $statusMsg = match ($validated['status']) {
            'approved' => "Your dental appointment on {$appointment->appointment_date->format('M d, Y')} has been APPROVED by Health Services.",
            'rescheduled' => "Your dental appointment has been RESCHEDULED to " . date('M d, Y', strtotime($validated['rescheduled_date'])) . " ({$validated['rescheduled_time']}).",
            'completed' => "Your dental appointment on {$appointment->appointment_date->format('M d, Y')} has been marked as COMPLETED.",
            'rejected' => "Your dental appointment request for {$appointment->appointment_date->format('M d, Y')} was not approved. Please check the clinic notes.",
            default => "Your dental appointment status has been updated to: {$validated['status']}.",
        };

        AppNotification::send(
            $appointment->user_id,
            'Dental Appointment Update: ' . ucfirst($validated['status']),
            $statusMsg,
            'dental',
            route('dental.show', $appointment->id)
        );

        ActivityLog::log(
            Auth::id(),
            'Dental',
            'Status Update',
            "Admin updated dental appointment #{$appointment->id} from {$oldStatus} to {$appointment->status}."
        );

        return back()->with('success', 'Dental appointment status updated successfully.');
    }

    // 4. Appointment Schedules
    public function schedules()
    {
        $schedules = AppointmentSchedule::orderBy('schedule_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        return view('admin.dental.schedules', compact('schedules'));
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'schedule_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'max_slots' => ['required', 'integer', 'min:1', 'max:20'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $exists = AppointmentSchedule::where('schedule_date', $validated['schedule_date'])
            ->where('start_time', $validated['start_time'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['schedule_date' => 'A schedule already exists for this date and starting time.'])->withInput();
        }

        AppointmentSchedule::create([
            'schedule_date' => $validated['schedule_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_slots' => $validated['max_slots'],
            'notes' => $validated['notes'] ?? null,
            'is_active' => true,
        ]);

        ActivityLog::log(
            Auth::id(),
            'Dental Schedules',
            'Schedule Created',
            "Created dental schedule on {$validated['schedule_date']}."
        );

        return back()->with('success', 'Dental schedule slot added successfully.');
    }

    public function toggleSchedule(AppointmentSchedule $schedule)
    {
        $schedule->is_active = !$schedule->is_active;
        $schedule->save();

        return back()->with('success', 'Schedule availability status updated.');
    }

    public function destroySchedule(AppointmentSchedule $schedule)
    {
        if ($schedule->bookedCount() > 0) {
            return back()->with('error', 'Cannot delete a schedule that already has active student bookings. You can deactivate it instead.');
        }

        $schedule->delete();

        return back()->with('success', 'Schedule deleted successfully.');
    }

    // 5. Teleconsultation Management
    public function teleconsultations(Request $request)
    {
        $query = TeleconsultationRequest::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('preferred_date', 'desc')->paginate(12)->withQueryString();

        return view('admin.teleconsultation.index', compact('requests'));
    }

    public function updateTeleconsultationStatus(Request $request, TeleconsultationRequest $teleconsultation)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:approved,scheduled,completed,rejected,cancelled'],
            'meeting_link' => ['nullable', 'url', 'max:255'],
            'assigned_personnel' => ['nullable', 'string', 'max:150'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $teleconsultation->update($validated);

        // Notify user
        $msg = "Your teleconsultation request status is now: " . strtoupper($validated['status']) . ".";
        if (!empty($validated['meeting_link'])) {
            $msg .= " Video consultation link: " . $validated['meeting_link'];
        }

        AppNotification::send(
            $teleconsultation->user_id,
            'Teleconsultation Update: ' . ucfirst($validated['status']),
            $msg,
            'teleconsultation',
            route('teleconsultation.show', $teleconsultation->id)
        );

        ActivityLog::log(
            Auth::id(),
            'Teleconsultation',
            'Status Update',
            "Updated teleconsultation #{$teleconsultation->id} status to {$validated['status']}."
        );

        return back()->with('success', 'Teleconsultation updated successfully.');
    }

    // 6. Medical Certificates Management
    public function medicalCertificates(Request $request)
    {
        $query = MedicalCertificateRequest::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('admin.certificates.index', compact('requests'));
    }

    public function updateMedicalCertificateStatus(Request $request, MedicalCertificateRequest $medicalCertificate)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:submitted,under_review,additional_info_required,approved,ready_for_release,released,rejected'],
            'certificate_number' => ['nullable', 'string', 'max:100'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validated['status'] === 'released' && !$medicalCertificate->released_at) {
            $medicalCertificate->released_at = now();
        }

        $medicalCertificate->update($validated);

        $statusMsg = match ($validated['status']) {
            'approved' => "Your medical certificate request for {$medicalCertificate->certificate_type} has been APPROVED.",
            'ready_for_release' => "Your medical certificate is READY FOR RELEASE. Certificate No: " . ($validated['certificate_number'] ?? 'Pending release counter.'),
            'released' => "Your medical certificate (#{$medicalCertificate->certificate_number}) has been officially RELEASED by Health Services.",
            'additional_info_required' => "Health Services requires additional information to verify your medical certificate request.",
            'rejected' => "Your medical certificate request could not be approved. Please review the clinic remarks.",
            default => "Your medical certificate status is: {$validated['status']}.",
        };

        AppNotification::send(
            $medicalCertificate->user_id,
            'Medical Certificate Update: ' . ucfirst(str_replace('_', ' ', $validated['status'])),
            $statusMsg,
            'certificate',
            route('certificates.show', $medicalCertificate->id)
        );

        ActivityLog::log(
            Auth::id(),
            'Medical Certificate',
            'Status Update',
            "Updated medical certificate #{$medicalCertificate->id} status to {$validated['status']}."
        );

        return back()->with('success', 'Medical certificate request updated successfully.');
    }

    // 7. Health Inquiries Management
    public function inquiries(Request $request)
    {
        $query = HealthInquiry::with(['user', 'responses']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('id_number', 'like', "%{$search}%");
                  });
            });
        }

        $inquiries = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function showInquiry(HealthInquiry $healthInquiry)
    {
        $healthInquiry->load(['user', 'responses.user']);
        return view('admin.inquiries.show', compact('healthInquiry'));
    }

    public function replyInquiry(Request $request, HealthInquiry $healthInquiry)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:2500'],
            'status' => ['required', 'string', 'in:in_progress,awaiting_user_response,resolved,closed'],
        ]);

        InquiryResponse::create([
            'health_inquiry_id' => $healthInquiry->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_staff_reply' => true,
        ]);

        $healthInquiry->update(['status' => $validated['status']]);

        AppNotification::send(
            $healthInquiry->user_id,
            'Health Services Replied to Your Inquiry',
            "The Health Services team has responded to: '{$healthInquiry->subject}'. Status is now " . ucfirst(str_replace('_', ' ', $validated['status'])) . ".",
            'inquiry',
            route('inquiries.show', $healthInquiry->id)
        );

        ActivityLog::log(
            Auth::id(),
            'Health Inquiry',
            'Staff Reply',
            "Replied to inquiry #{$healthInquiry->id} and set status to {$validated['status']}."
        );

        return back()->with('success', 'Your reply has been posted and sent to the student/staff member.');
    }

    public function updateInquiryStatus(Request $request, HealthInquiry $healthInquiry)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:submitted,in_progress,awaiting_user_response,resolved,closed'],
        ]);

        $healthInquiry->update(['status' => $validated['status']]);

        return back()->with('success', 'Inquiry status updated.');
    }

    // 8. Health Articles Management
    public function articles(Request $request)
    {
        $query = HealthArticle::with('author');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('admin.articles.index', compact('articles'));
    }

    public function createArticle()
    {
        $categories = [
            'General Wellness',
            'Mental Well-Being',
            'Dental Health',
            'Hygiene and Prevention',
            'Healthy Lifestyle',
            'Preventive Healthcare',
        ];

        return view('admin.articles.create', compact('categories'));
    }

    public function storeArticle(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'summary' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'author_name' => ['required', 'string', 'max:150'],
            'image_url' => ['nullable', 'url', 'max:255'],
            'is_published' => ['required', 'boolean'],
        ]);

        $article = HealthArticle::create([
            'author_id' => Auth::id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'summary' => $validated['summary'],
            'content' => $validated['content'],
            'author_name' => $validated['author_name'],
            'image_url' => $validated['image_url'] ?? null,
            'is_published' => $validated['is_published'],
            'published_at' => $validated['is_published'] ? now() : null,
        ]);

        ActivityLog::log(
            Auth::id(),
            'Health Articles',
            'Article Created',
            "Published health article: '{$article->title}'."
        );

        return redirect()->route('admin.articles.index')->with('success', 'Health article published successfully.');
    }

    public function editArticle(HealthArticle $article)
    {
        $categories = [
            'General Wellness',
            'Mental Well-Being',
            'Dental Health',
            'Hygiene and Prevention',
            'Healthy Lifestyle',
            'Preventive Healthcare',
        ];

        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function updateArticle(Request $request, HealthArticle $article)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'summary' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'author_name' => ['required', 'string', 'max:150'],
            'image_url' => ['nullable', 'url', 'max:255'],
            'is_published' => ['required', 'boolean'],
        ]);

        $article->title = $validated['title'];
        $article->category = $validated['category'];
        $article->summary = $validated['summary'];
        $article->content = $validated['content'];
        $article->author_name = $validated['author_name'];
        $article->image_url = $validated['image_url'] ?? null;
        
        if ($validated['is_published'] && !$article->published_at) {
            $article->published_at = now();
        }
        $article->is_published = $validated['is_published'];
        $article->save();

        ActivityLog::log(
            Auth::id(),
            'Health Articles',
            'Article Updated',
            "Updated article: '{$article->title}'."
        );

        return redirect()->route('admin.articles.index')->with('success', 'Health article updated successfully.');
    }

    public function destroyArticle(HealthArticle $article)
    {
        $title = $article->title;
        $article->delete();

        ActivityLog::log(
            Auth::id(),
            'Health Articles',
            'Article Deleted',
            "Deleted article: '{$title}'."
        );

        return back()->with('success', 'Health article deleted successfully.');
    }

    // 9. Announcements Management
    public function announcements(Request $request)
    {
        $query = Announcement::with('author');

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $announcements = $query->orderBy('priority', 'desc')->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function createAnnouncement()
    {
        $categories = [
            'Urgent Advisory',
            'Clinic Schedule Update',
            'Health Advisory',
            'Vaccination Drive',
            'General Announcement',
        ];

        return view('admin.announcements.create', compact('categories'));
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'priority' => ['required', 'string', 'in:low,medium,high,urgent'],
            'content' => ['required', 'string'],
            'expires_at' => ['nullable', 'date'],
            'is_published' => ['required', 'boolean'],
            'notify_users' => ['nullable', 'boolean'],
        ]);

        $announcement = Announcement::create([
            'author_id' => Auth::id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'content' => $validated['content'],
            'expires_at' => $validated['expires_at'] ?? null,
            'is_published' => $validated['is_published'],
            'published_at' => $validated['is_published'] ? now() : null,
        ]);

        if ($request->boolean('notify_users') && $validated['is_published']) {
            $users = User::where('is_active', true)->get();
            foreach ($users as $user) {
                AppNotification::send(
                    $user->id,
                    'New Announcement: ' . $announcement->title,
                    Str::limit($announcement->content, 120),
                    'announcement',
                    route('announcements.show', $announcement->id)
                );
            }
        }

        ActivityLog::log(
            Auth::id(),
            'Announcements',
            'Announcement Published',
            "Created announcement: '{$announcement->title}' with priority {$announcement->priority}."
        );

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement published successfully.');
    }

    public function editAnnouncement(Announcement $announcement)
    {
        $categories = [
            'Urgent Advisory',
            'Clinic Schedule Update',
            'Health Advisory',
            'Vaccination Drive',
            'General Announcement',
        ];

        return view('admin.announcements.edit', compact('announcement', 'categories'));
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'priority' => ['required', 'string', 'in:low,medium,high,urgent'],
            'content' => ['required', 'string'],
            'expires_at' => ['nullable', 'date'],
            'is_published' => ['required', 'boolean'],
        ]);

        $announcement->title = $validated['title'];
        $announcement->category = $validated['category'];
        $announcement->priority = $validated['priority'];
        $announcement->content = $validated['content'];
        $announcement->expires_at = $validated['expires_at'] ?? null;

        if ($validated['is_published'] && !$announcement->published_at) {
            $announcement->published_at = now();
        }
        $announcement->is_published = $validated['is_published'];
        $announcement->save();

        ActivityLog::log(
            Auth::id(),
            'Announcements',
            'Announcement Updated',
            "Updated announcement: '{$announcement->title}'."
        );

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroyAnnouncement(Announcement $announcement)
    {
        $title = $announcement->title;
        $announcement->delete();

        ActivityLog::log(
            Auth::id(),
            'Announcements',
            'Announcement Deleted',
            "Deleted announcement: '{$title}'."
        );

        return back()->with('success', 'Announcement deleted successfully.');
    }

    // 10. Reports & Analytics
    public function reports()
    {
        $totalUsers = User::count();
        $studentCount = User::where('role', 'student')->count();
        $facultyCount = User::where('role', 'faculty_staff')->count();

        // Dental Stats
        $dentalTotal = DentalAppointment::count();
        $dentalPending = DentalAppointment::where('status', 'pending')->count();
        $dentalApproved = DentalAppointment::where('status', 'approved')->count();
        $dentalCompleted = DentalAppointment::where('status', 'completed')->count();
        $dentalCancelled = DentalAppointment::whereIn('status', ['cancelled', 'rejected'])->count();

        // Teleconsultation Stats
        $teleTotal = TeleconsultationRequest::count();
        $telePending = TeleconsultationRequest::where('status', 'pending')->count();
        $teleScheduled = TeleconsultationRequest::whereIn('status', ['approved', 'scheduled'])->count();
        $teleCompleted = TeleconsultationRequest::where('status', 'completed')->count();

        // Medical Certificates Stats
        $medCertTotal = MedicalCertificateRequest::count();
        $medCertPending = MedicalCertificateRequest::whereIn('status', ['submitted', 'under_review'])->count();
        $medCertApproved = MedicalCertificateRequest::where('status', 'approved')->count();
        $medCertReleased = MedicalCertificateRequest::where('status', 'released')->count();

        // Inquiries Stats
        $inquiriesTotal = HealthInquiry::count();
        $inquiriesResolved = HealthInquiry::where('status', 'resolved')->count();
        $inquiriesPending = HealthInquiry::whereIn('status', ['submitted', 'in_progress'])->count();

        return view('admin.reports.index', compact(
            'totalUsers',
            'studentCount',
            'facultyCount',
            'dentalTotal',
            'dentalPending',
            'dentalApproved',
            'dentalCompleted',
            'dentalCancelled',
            'teleTotal',
            'telePending',
            'teleScheduled',
            'teleCompleted',
            'medCertTotal',
            'medCertPending',
            'medCertApproved',
            'medCertReleased',
            'inquiriesTotal',
            'inquiriesResolved',
            'inquiriesPending'
        ));
    }

    // 11. Activity Logs
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('id_number', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $modules = ActivityLog::select('module')->distinct()->pluck('module');

        return view('admin.logs.index', compact('logs', 'modules'));
    }

    // 12. Settings Page
    public function settings()
    {
        return view('admin.settings.index');
    }
}
