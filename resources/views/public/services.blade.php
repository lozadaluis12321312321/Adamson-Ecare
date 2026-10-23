@extends('layouts.public')

@section('title', 'Health Services | AdU eCare+')

@section('content')
<div class="py-5 text-white" style="background: var(--grad-navbar) !important;">
    <div class="container text-center">
        <span class="badge bg-info text-dark px-3 py-1 rounded-pill mb-2 fw-semibold">Services Directory</span>
        <h1 class="fw-bold mb-2">University Health Services</h1>
        <p class="lead text-light opacity-90 mx-auto" style="max-width: 650px;">
            Comprehensive clinical and digital health solutions designed for students, faculty, and administrative staff of Adamson University.
        </p>
        <div class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-25 rounded-pill px-3 py-2 small mt-2">
            <i class="bi bi-clock-fill me-1 text-info"></i> Clinic Operating Hours: <strong>Monday – Saturday: 7:00 AM – 9:00 PM</strong>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 mb-5">
        <!-- Dental Services -->
        <div class="col-lg-6">
            <div class="card card-adu h-100 p-4 border-start border-primary border-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="card-service-icon bg-primary bg-opacity-10 text-primary mb-0">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">Dental Clinic & Appointments</h4>
                        <span class="text-muted small">Preventive and restorative oral healthcare</span>
                    </div>
                </div>
                <p class="text-muted">
                    The Adamson Dental Clinic provides routine oral prophylaxis, diagnostic examinations, temporary and permanent dental fillings, tooth extractions, and oral hygiene instruction. Book through the system to secure your scheduled time slot.
                </p>
                <div class="bg-light p-3 rounded-3 mb-3 small border">
                    <strong class="text-dark">Clinic Schedule:</strong> <span class="text-primary fw-semibold">Monday – Saturday: 7:00 AM – 9:00 PM</span><br>
                    <strong class="text-dark">Location:</strong> Room 204, St. Vincent Building
                </div>
                <div class="mt-auto">
                    <a href="{{ route('dental.create') }}" class="btn btn-gradient-primary rounded-pill btn-sm px-4 fw-semibold">
                        <i class="bi bi-calendar-plus me-1"></i> Book Dental Appointment
                    </a>
                </div>
            </div>
        </div>

        <!-- Teleconsultation -->
        <div class="col-lg-6">
            <div class="card card-adu h-100 p-4 border-start border-info border-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="card-service-icon bg-info bg-opacity-10 text-info mb-0">
                        <i class="bi bi-camera-video"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">Medical Teleconsultation</h4>
                        <span class="text-muted small">Remote healthcare consultation and guidance</span>
                    </div>
                </div>
                <p class="text-muted">
                    Consult with attending university physicians or guidance counselors from the comfort of home or dorm. Ideal for non-emergency symptoms, follow-up checkups, mental wellness discussions, and preliminary medical triage.
                </p>
                <div class="bg-light p-3 rounded-3 mb-3 small border">
                    <strong class="text-dark">Service Method:</strong> Google Meet video link or direct phone call<br>
                    <strong class="text-dark">Consultation Hours:</strong> <span class="text-info fw-semibold">Monday – Saturday: 7:00 AM – 9:00 PM</span>
                </div>
                <div class="mt-auto">
                    <a href="{{ route('teleconsultation.create') }}" class="btn btn-info text-white rounded-pill btn-sm px-4 fw-semibold">
                        <i class="bi bi-camera-video-fill me-1"></i> Request Teleconsultation
                    </a>
                </div>
            </div>
        </div>

        <!-- Medical Certificates -->
        <div class="col-lg-6">
            <div class="card card-adu h-100 p-4 border-start border-success border-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="card-service-icon bg-success bg-opacity-10 text-success mb-0">
                        <i class="bi bi-file-earmark-medical"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">Medical Certificate Requisition</h4>
                        <span class="text-muted small">Official medical verification and clearances</span>
                    </div>
                </div>
                <p class="text-muted">
                    Request official fit-to-return-to-class slips, excuse slip validation, PE sports clearances, and pre-OJT medical certificates. Submit your supporting documents securely for doctor review and digital verification.
                </p>
                <div class="bg-light p-3 rounded-3 mb-3 small border">
                    <strong class="text-dark">Turnaround Time:</strong> 1-2 business days upon verification<br>
                    <strong class="text-dark">Verification Hours:</strong> Monday – Saturday: 7:00 AM – 9:00 PM
                </div>
                <div class="mt-auto">
                    <a href="{{ route('certificates.create') }}" class="btn btn-success rounded-pill btn-sm px-4 fw-semibold">
                        <i class="bi bi-file-earmark-plus me-1"></i> Requisition Certificate
                    </a>
                </div>
            </div>
        </div>

        <!-- Health Inquiries -->
        <div class="col-lg-6">
            <div class="card card-adu h-100 p-4 border-start border-warning border-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="card-service-icon bg-warning bg-opacity-10 text-warning mb-0">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">Health-Related Inquiries</h4>
                        <span class="text-muted small">Direct clinical question and answer desk</span>
                    </div>
                </div>
                <p class="text-muted">
                    Ask questions about campus vaccination schedules, medication availability, laboratory requirements for physical exams, or health insurance coverage. Receive replies directly from Health Services staff.
                </p>
                <div class="bg-light p-3 rounded-3 mb-3 small border">
                    <strong class="text-dark">Desk Hours:</strong> Monday – Saturday: 7:00 AM – 9:00 PM<br>
                    <strong class="text-dark">Feature:</strong> Interactive threaded communication with clinic staff
                </div>
                <div class="mt-auto">
                    <a href="{{ route('inquiries.create') }}" class="btn btn-warning text-dark rounded-pill btn-sm px-4 fw-semibold">
                        <i class="bi bi-chat-left-text me-1"></i> Send Health Inquiry
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
