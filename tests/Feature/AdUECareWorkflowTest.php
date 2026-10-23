<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AppointmentSchedule;
use App\Models\DentalAppointment;
use App\Models\TeleconsultationRequest;
use App\Models\MedicalCertificateRequest;
use App\Models\HealthInquiry;
use App\Models\Announcement;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdUECareWorkflowTest extends TestCase
{
    // Test 1: Student registration and cannot register as admin
    public function test_student_can_register_and_cannot_self_assign_admin(): void
    {
        // Try registering as admin - should fail validation
        $response = $this->post('/register', [
            'name' => 'Hacker Admin',
            'email' => 'hacker@adamson.edu.ph',
            'role' => 'admin', // Not allowed in validation rules!
            'id_number' => '2025-00001',
            'department_or_course' => 'BS IT',
            'contact_number' => '0917-000-0000',
            'emergency_contact_name' => 'Parent',
            'emergency_contact_number' => '0917-000-0001',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
        ]);
        $response->assertSessionHasErrors('role');

        // Valid student registration
        $uniqueEmail = 'student_' . uniqid() . '@adamson.edu.ph';
        $response = $this->post('/register', [
            'name' => 'Maria Santos',
            'email' => $uniqueEmail,
            'role' => 'student',
            'id_number' => '2024-55443',
            'department_or_course' => 'BS Pharmacy',
            'contact_number' => '0917-555-4433',
            'emergency_contact_name' => 'Jose Santos (Father)',
            'emergency_contact_number' => '0917-555-0000',
            'password' => 'StudentPass123!',
            'password_confirmation' => 'StudentPass123!',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => $uniqueEmail,
            'role' => 'student',
        ]);
    }

    // Test 2, 3, 4, 5: Dental Appointment Workflow
    public function test_dental_appointment_workflow(): void
    {
        $student = User::create([
            'name' => 'Dental Test Student',
            'email' => 'dental_' . uniqid() . '@adamson.edu.ph',
            'role' => 'student',
            'id_number' => '2026-' . rand(10000, 99999),
            'department_or_course' => 'BS Dentistry',
            'contact_number' => '0917-111-2222',
            'password' => bcrypt('Password123!'),
            'is_active' => true,
        ]);
        $admin = User::where('role', 'admin')->first();
        $schedule = AppointmentSchedule::where('is_active', true)
            ->where('schedule_date', '>=', now()->toDateString())
            ->get()
            ->filter(fn($s) => $s->availableSlots() > 0)
            ->first();

        if (!$schedule) {
            $schedule = AppointmentSchedule::create([
                'schedule_date' => now()->addDays(rand(20, 50))->toDateString(),
                'start_time' => '15:00:00',
                'end_time' => '16:00:00',
                'max_slots' => 10,
                'is_active' => true,
            ]);
        }

        // 2. Student submits dental appointment request
        $this->actingAs($student);
        $response = $this->post('/dental-appointments', [
            'appointment_schedule_id' => $schedule->id,
            'reason_for_visit' => 'Oral Prophylaxis (Cleaning)',
            'specific_concerns' => 'Routine dental cleaning',
        ]);
        $response->assertRedirect(route('dental.index'));

        // 3. Request appears in MySQL
        $appointment = DentalAppointment::where('user_id', $student->id)
            ->where('appointment_schedule_id', $schedule->id)
            ->latest()
            ->first();

        $this->assertNotNull($appointment);
        $this->assertEquals('pending', $appointment->status);

        // 4. Admin reviews and approves the appointment
        $this->actingAs($admin);
        $response = $this->post("/admin/dental/{$appointment->id}/status", [
            'status' => 'approved',
            'admin_notes' => 'Approved by clinic. Please bring valid student ID.',
        ]);
        $response->assertSessionHas('success');

        // 5. Student sees the updated status
        $this->actingAs($student);
        $response = $this->get("/dental-appointments/{$appointment->id}");
        $response->assertStatus(200);
        $response->assertSee('Approved');
        $response->assertSee('Approved by clinic');
    }

    // Test 6: Teleconsultation request submission
    public function test_teleconsultation_workflow(): void
    {
        $student = User::where('role', 'student')->first();
        $this->actingAs($student);

        $response = $this->post('/teleconsultation', [
            'preferred_date' => now()->addDays(2)->toDateString(),
            'preferred_time' => '10:30 AM - 11:30 AM',
            'category' => 'General Health Consultation',
            'description' => 'Feeling fatigued and persistent cough for 2 days.',
            'preferred_contact_method' => 'Video Meeting Link (Google Meet / Zoom)',
            'contact_details' => '0918-234-5678',
        ]);

        $response->assertRedirect(route('teleconsultation.index'));
        $this->assertDatabaseHas('teleconsultation_requests', [
            'user_id' => $student->id,
            'category' => 'General Health Consultation',
            'status' => 'pending',
        ]);
    }

    // Test 7: Medical Certificate Requisition
    public function test_medical_certificate_workflow(): void
    {
        Storage::fake('local');
        $student = User::where('role', 'student')->first();
        $this->actingAs($student);

        $file = UploadedFile::fake()->create('doctor_note.pdf', 100, 'application/pdf');

        $response = $this->post('/medical-certificates', [
            'certificate_type' => 'Fit to Return to Class / Work',
            'consultation_date' => now()->subDays(2)->toDateString(),
            'purpose_of_request' => 'Excuse slip for missed exams due to fever',
            'additional_information' => 'Recovered from viral infection.',
            'supporting_document' => $file,
        ]);

        $response->assertRedirect(route('certificates.index'));
        $this->assertDatabaseHas('medical_certificate_requests', [
            'user_id' => $student->id,
            'certificate_type' => 'Fit to Return to Class / Work',
            'status' => 'submitted',
        ]);
    }

    // Test 8 & 9: Health Inquiry and Admin Reply
    public function test_inquiry_and_reply_workflow(): void
    {
        $student = User::where('role', 'student')->first();
        $admin = User::where('role', 'admin')->first();

        // 8. User submits inquiry
        $this->actingAs($student);
        $response = $this->post('/inquiries', [
            'category' => 'Clinic Services & Hours',
            'subject' => 'Is the clinic open on Saturdays?',
            'message' => 'Good day, do you have doctors available on Saturday mornings?',
        ]);
        $response->assertRedirect(route('inquiries.index'));

        $inquiry = HealthInquiry::where('user_id', $student->id)
            ->where('subject', 'Is the clinic open on Saturdays?')
            ->first();
        $this->assertNotNull($inquiry);

        // 9. Admin replies to inquiry
        $this->actingAs($admin);
        $response = $this->post("/admin/inquiries/{$inquiry->id}/reply", [
            'message' => 'Hello Juan, the clinic operates Monday to Saturday from 7:00 AM to 9:00 PM.',
            'status' => 'resolved',
        ]);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inquiry_responses', [
            'health_inquiry_id' => $inquiry->id,
            'is_staff_reply' => true,
        ]);
        $this->assertDatabaseHas('health_inquiries', [
            'id' => $inquiry->id,
            'status' => 'resolved',
        ]);
    }

    // Test 10: Admin publishes a health announcement
    public function test_admin_publishes_announcement(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $response = $this->post('/admin/announcements', [
            'title' => 'Dengue Prevention Campaign on Campus',
            'category' => 'Health Advisory',
            'priority' => 'high',
            'content' => 'All community members are requested to dispose of open containers holding stagnant water.',
            'is_published' => 1,
            'notify_users' => 1,
        ]);

        $response->assertRedirect(route('admin.announcements.index'));
        $this->assertDatabaseHas('announcements', [
            'title' => 'Dengue Prevention Campaign on Campus',
            'priority' => 'high',
            'is_published' => true,
        ]);
    }

    // Test 11: Normal user cannot access administrator pages (403 Forbidden)
    public function test_normal_user_cannot_access_admin_portal(): void
    {
        $student = User::where('role', 'student')->first();
        $this->actingAs($student);

        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);

        $response = $this->get('/admin/users');
        $response->assertStatus(403);
    }

    // Test 12: User cannot access another user's private requests (403 Forbidden)
    public function test_user_cannot_access_other_users_private_requests(): void
    {
        $student1 = User::where('email', 'student@adamson.edu.ph')->first();
        $student2 = User::where('email', 'maria.clara@adamson.edu.ph')->first();

        // Create appointment belonging to student2
        $appointment = DentalAppointment::create([
            'user_id' => $student2->id,
            'appointment_date' => now()->addDays(5)->toDateString(),
            'appointment_time' => '10:00 AM - 11:00 AM',
            'reason_for_visit' => 'Private Dental Check',
            'status' => 'pending',
        ]);

        // Student 1 attempts to access Student 2's appointment
        $this->actingAs($student1);
        $response = $this->get("/dental-appointments/{$appointment->id}");
        $response->assertStatus(403);
    }

    // Test 13: Verify all public pages render with HTTP 200
    public function test_all_public_pages_render_successfully(): void
    {
        $routes = [
            '/',
            '/health-services',
            '/health-information',
            '/announcements',
            '/about',
            '/login',
            '/register',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    // Test 14: Verify all student and admin views render with HTTP 200
    public function test_all_authenticated_pages_render_successfully(): void
    {
        $student = User::where('role', 'student')->first();
        $this->actingAs($student);

        $studentRoutes = [
            '/dashboard',
            '/dental-appointments',
            '/dental-appointments/create',
            '/teleconsultation',
            '/teleconsultation/create',
            '/medical-certificates',
            '/medical-certificates/create',
            '/inquiries',
            '/inquiries/create',
            '/notifications',
            '/profile',
        ];

        foreach ($studentRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }

        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $adminRoutes = [
            '/admin/dashboard',
            '/admin/users',
            '/admin/users/create',
            '/admin/dental',
            '/admin/dental/schedules',
            '/admin/teleconsultation',
            '/admin/certificates',
            '/admin/inquiries',
            '/admin/articles',
            '/admin/articles/create',
            '/admin/announcements',
            '/admin/announcements/create',
            '/admin/reports',
            '/admin/logs',
            '/admin/settings',
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    // Test 15: Verify creative white-blue gradient theme toggle & updated clinic hours (7:00 AM - 9:00 PM Mon-Sat)
    public function test_creative_theme_toggle_and_clinic_operating_hours(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('7:00 AM – 9:00 PM');
        $response->assertSee('Monday – Saturday');
        $response->assertSee('theme-toggle-btn');
        $response->assertSee('aduecare_theme');

        $aboutResponse = $this->get('/about');
        $aboutResponse->assertStatus(200);
        $aboutResponse->assertSee('7:00 AM – 9:00 PM');
        $aboutResponse->assertSee('Monday to Saturday');

        $student = User::where('role', 'student')->first();
        $this->actingAs($student);
        $dashResponse = $this->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('7:00 AM – 9:00 PM');
        $dashResponse->assertSee('theme-toggle-btn');
    }
}
