<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DentalAppointmentController;
use App\Http\Controllers\TeleconsultationController;
use App\Http\Controllers\MedicalCertificateController;
use App\Http\Controllers\HealthInquiryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes - AdU eCare+
|--------------------------------------------------------------------------
*/

// Public Homepage & Info Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/health-services', [HomeController::class, 'healthServices'])->name('services');
Route::get('/health-information', [HomeController::class, 'articles'])->name('articles.index');
Route::get('/health-information/{slug}', [HomeController::class, 'articleDetail'])->name('articles.show');
Route::get('/announcements', [HomeController::class, 'announcements'])->name('announcements.index');
Route::get('/announcements/{id}', [HomeController::class, 'announcementDetail'])->name('announcements.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Student & Faculty/Staff Dashboard & Request Modules
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dental Appointments
    Route::get('/dental-appointments', [DentalAppointmentController::class, 'index'])->name('dental.index');
    Route::get('/dental-appointments/create', [DentalAppointmentController::class, 'create'])->name('dental.create');
    Route::post('/dental-appointments', [DentalAppointmentController::class, 'store'])->name('dental.store');
    Route::get('/dental-appointments/{appointment}', [DentalAppointmentController::class, 'show'])->name('dental.show');
    Route::post('/dental-appointments/{appointment}/cancel', [DentalAppointmentController::class, 'cancel'])->name('dental.cancel');

    // Teleconsultation
    Route::get('/teleconsultation', [TeleconsultationController::class, 'index'])->name('teleconsultation.index');
    Route::get('/teleconsultation/create', [TeleconsultationController::class, 'create'])->name('teleconsultation.create');
    Route::post('/teleconsultation', [TeleconsultationController::class, 'store'])->name('teleconsultation.store');
    Route::get('/teleconsultation/{teleconsultation}', [TeleconsultationController::class, 'show'])->name('teleconsultation.show');
    Route::post('/teleconsultation/{teleconsultation}/cancel', [TeleconsultationController::class, 'cancel'])->name('teleconsultation.cancel');

    // Medical Certificates
    Route::get('/medical-certificates', [MedicalCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/medical-certificates/create', [MedicalCertificateController::class, 'create'])->name('certificates.create');
    Route::post('/medical-certificates', [MedicalCertificateController::class, 'store'])->name('certificates.store');
    Route::get('/medical-certificates/{medicalCertificate}', [MedicalCertificateController::class, 'show'])->name('certificates.show');
    Route::get('/medical-certificates/{medicalCertificate}/download', [MedicalCertificateController::class, 'downloadDocument'])->name('certificates.download');

    // Health Inquiries
    Route::get('/inquiries', [HealthInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/create', [HealthInquiryController::class, 'create'])->name('inquiries.create');
    Route::post('/inquiries', [HealthInquiryController::class, 'store'])->name('inquiries.store');
    Route::get('/inquiries/{healthInquiry}', [HealthInquiryController::class, 'show'])->name('inquiries.show');
    Route::post('/inquiries/{healthInquiry}/reply', [HealthInquiryController::class, 'reply'])->name('inquiries.reply');
});

// Admin-Only Routes (Role: admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');

    // Dental Appointments
    Route::get('/dental', [AdminController::class, 'dentalAppointments'])->name('dental.index');
    Route::post('/dental/{appointment}/status', [AdminController::class, 'updateDentalStatus'])->name('dental.status');
    Route::get('/dental/schedules', [AdminController::class, 'schedules'])->name('dental.schedules');
    Route::post('/dental/schedules', [AdminController::class, 'storeSchedule'])->name('dental.schedules.store');
    Route::post('/dental/schedules/{schedule}/toggle', [AdminController::class, 'toggleSchedule'])->name('dental.schedules.toggle');
    Route::delete('/dental/schedules/{schedule}', [AdminController::class, 'destroySchedule'])->name('dental.schedules.destroy');

    // Teleconsultations
    Route::get('/teleconsultation', [AdminController::class, 'teleconsultations'])->name('teleconsultation.index');
    Route::post('/teleconsultation/{teleconsultation}/status', [AdminController::class, 'updateTeleconsultationStatus'])->name('teleconsultation.status');

    // Medical Certificates
    Route::get('/certificates', [AdminController::class, 'medicalCertificates'])->name('certificates.index');
    Route::post('/certificates/{medicalCertificate}/status', [AdminController::class, 'updateMedicalCertificateStatus'])->name('certificates.status');

    // Health Inquiries
    Route::get('/inquiries', [AdminController::class, 'inquiries'])->name('inquiries.index');
    Route::get('/inquiries/{healthInquiry}', [AdminController::class, 'showInquiry'])->name('inquiries.show');
    Route::post('/inquiries/{healthInquiry}/reply', [AdminController::class, 'replyInquiry'])->name('inquiries.reply');
    Route::post('/inquiries/{healthInquiry}/status', [AdminController::class, 'updateInquiryStatus'])->name('inquiries.status');

    // Health Articles
    Route::get('/articles', [AdminController::class, 'articles'])->name('articles.index');
    Route::get('/articles/create', [AdminController::class, 'createArticle'])->name('articles.create');
    Route::post('/articles', [AdminController::class, 'storeArticle'])->name('articles.store');
    Route::get('/articles/{article}/edit', [AdminController::class, 'editArticle'])->name('articles.edit');
    Route::put('/articles/{article}', [AdminController::class, 'updateArticle'])->name('articles.update');
    Route::delete('/articles/{article}', [AdminController::class, 'destroyArticle'])->name('articles.destroy');

    // Announcements
    Route::get('/announcements', [AdminController::class, 'announcements'])->name('announcements.index');
    Route::get('/announcements/create', [AdminController::class, 'createAnnouncement'])->name('announcements.create');
    Route::post('/announcements', [AdminController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [AdminController::class, 'editAnnouncement'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [AdminController::class, 'updateAnnouncement'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AdminController::class, 'destroyAnnouncement'])->name('announcements.destroy');

    // Reports, Logs & Settings
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
    Route::get('/logs', [AdminController::class, 'activityLogs'])->name('logs.index');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});
