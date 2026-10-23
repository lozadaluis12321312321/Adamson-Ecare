@extends('layouts.admin')

@section('title', 'Reports & Clinical Analytics | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Health Services Reports & Analytics</h2>
        <p class="text-muted small mb-0">Monthly clinical throughput, service requisition volumes, and patient demographic distribution.</p>
    </div>
    <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> Print Clinical Report
    </button>
</div>

<!-- User Demographics & Clinical Summary -->
<div class="row g-4 mb-4">
    <!-- Patient Distribution -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 h-100">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-people text-primary me-2"></i> Patient Demographic Pool
            </h5>
            <div class="text-center my-3">
                <h1 class="display-5 fw-bold text-primary mb-1">{{ $totalUsers }}</h1>
                <span class="text-muted small">Total Registered Clinic Patients</span>
            </div>

            <div class="d-flex flex-column gap-3 mt-4 small">
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Students</span>
                        <strong class="text-dark">{{ $studentCount }} ({{ $totalUsers > 0 ? round(($studentCount / $totalUsers) * 100) : 0 }}%)</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalUsers > 0 ? ($studentCount / $totalUsers) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Faculty & Staff</span>
                        <strong class="text-dark">{{ $facultyCount }} ({{ $totalUsers > 0 ? round(($facultyCount / $totalUsers) * 100) : 0 }}%)</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalUsers > 0 ? ($facultyCount / $totalUsers) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dental Clinic Throughput -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 h-100">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-calendar2-check text-primary me-2"></i> Dental Clinic Throughput
            </h5>
            <div class="text-center my-3">
                <h1 class="display-5 fw-bold text-primary mb-1">{{ $dentalTotal }}</h1>
                <span class="text-muted small">Total Dental Bookings Recorded</span>
            </div>

            <ul class="list-group list-group-flush small mt-3">
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Approved & Active</span>
                    <strong class="text-success">{{ $dentalApproved }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Pending Staff Review</span>
                    <strong class="text-warning">{{ $dentalPending }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Completed Treatments</span>
                    <strong class="text-primary">{{ $dentalCompleted }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Cancelled / Rejected</span>
                    <strong class="text-muted">{{ $dentalCancelled }}</strong>
                </li>
            </ul>
        </div>
    </div>

    <!-- Teleconsultation & Inquiries -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 h-100">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-camera-video text-info me-2"></i> Remote Consultations
            </h5>
            <div class="text-center my-3">
                <h1 class="display-5 fw-bold text-info mb-1">{{ $teleTotal }}</h1>
                <span class="text-muted small">Total Teleconsultation Sessions</span>
            </div>

            <ul class="list-group list-group-flush small mt-3">
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Scheduled / Approved</span>
                    <strong class="text-info">{{ $teleScheduled }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Pending Triage</span>
                    <strong class="text-warning">{{ $telePending }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Completed Sessions</span>
                    <strong class="text-primary">{{ $teleCompleted }}</strong>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Medical Certificates Breakdown -->
    <div class="col-lg-6">
        <div class="card card-adu p-4 h-100">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-file-earmark-medical text-success me-2"></i> Medical Clearance Verification Volumes
            </h5>
            <div class="row g-3 text-center my-2">
                <div class="col-4">
                    <h3 class="fw-bold text-dark mb-0">{{ $medCertTotal }}</h3>
                    <small class="text-muted">Total Requisitions</small>
                </div>
                <div class="col-4">
                    <h3 class="fw-bold text-warning mb-0">{{ $medCertPending }}</h3>
                    <small class="text-muted">In Verification</small>
                </div>
                <div class="col-4">
                    <h3 class="fw-bold text-success mb-0">{{ $medCertReleased }}</h3>
                    <small class="text-muted">Official Releases</small>
                </div>
            </div>

            <div class="mt-4 p-3 bg-light rounded-3 small">
                <i class="bi bi-shield-check text-success me-1"></i>
                Official certificates bear an authenticated control tracking number (e.g. <code>ADU-MC-XXXX</code>) ensuring integrity when submitted to academic deans and PE directors.
            </div>
        </div>
    </div>

    <!-- Health Helpdesk Inquiries Summary -->
    <div class="col-lg-6">
        <div class="card card-adu p-4 h-100">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-chat-dots text-warning me-2"></i> Health Inquiries Resolution Status
            </h5>
            <div class="row g-3 text-center my-2">
                <div class="col-4">
                    <h3 class="fw-bold text-dark mb-0">{{ $inquiriesTotal }}</h3>
                    <small class="text-muted">Total Inquiries</small>
                </div>
                <div class="col-4">
                    <h3 class="fw-bold text-warning mb-0">{{ $inquiriesPending }}</h3>
                    <small class="text-muted">Pending Replies</small>
                </div>
                <div class="col-4">
                    <h3 class="fw-bold text-success mb-0">{{ $inquiriesResolved }}</h3>
                    <small class="text-muted">Resolved Threads</small>
                </div>
            </div>

            <div class="mt-4 p-3 bg-light rounded-3 small">
                <i class="bi bi-clock-history text-primary me-1"></i>
                Resolution rate: <strong>{{ $inquiriesTotal > 0 ? round(($inquiriesResolved / $inquiriesTotal) * 100) : 100 }}%</strong> of health-related inquiries have reached satisfied closure.
            </div>
        </div>
    </div>
</div>
@endsection
