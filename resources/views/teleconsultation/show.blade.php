@extends('layouts.dashboard')

@section('title', 'Teleconsultation Details | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('teleconsultation.index') }}">Teleconsultations</a></li>
            <li class="breadcrumb-item active" aria-current="page">Consultation #{{ $teleconsultation->id }}</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1">Teleconsultation Details</h2>
            <span class="text-muted small">Tracking Reference: #TEL-{{ str_pad($teleconsultation->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div>
            <span class="badge {{ $teleconsultation->status_badge_class }} rounded-pill px-3 py-2 fs-6">
                Status: {{ ucfirst($teleconsultation->status) }}
            </span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Meeting Link Alert Banner if Scheduled -->
        @if($teleconsultation->meeting_link)
            <div class="card bg-success bg-opacity-10 border border-success p-4 rounded-4 mb-4 shadow-sm">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold text-success mb-1">
                            <i class="bi bi-camera-video-fill me-2"></i> External Video Consultation Link Ready
                        </h5>
                        <p class="text-dark small mb-0">Your attending healthcare professional has configured the virtual consultation session room.</p>
                        <div class="text-muted small mt-1 font-monospace text-break">{{ $teleconsultation->meeting_link }}</div>
                    </div>
                    <a href="{{ $teleconsultation->meeting_link }}" target="_blank" class="btn btn-success rounded-pill px-4 py-2 fw-semibold shadow-sm flex-shrink-0">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Join Video Consultation
                    </a>
                </div>
            </div>
        @elseif($teleconsultation->status === 'approved' || $teleconsultation->status === 'scheduled')
            <div class="card bg-info bg-opacity-10 border border-info p-3 rounded-3 mb-4 small">
                <i class="bi bi-info-circle-fill text-info me-1"></i> Your teleconsultation is scheduled. The video link or phone call will be initiated at the agreed time window.
            </div>
        @endif

        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Consultation Summary</h5>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <span class="text-muted small d-block">Scheduled / Preferred Date</span>
                    <strong class="text-dark fs-6">{{ $teleconsultation->preferred_date->format('F d, Y (l)') }}</strong>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Time Window</span>
                    <strong class="text-info fs-6">{{ $teleconsultation->preferred_time }}</strong>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Health Concern Category</span>
                    <strong class="text-dark">{{ $teleconsultation->category }}</strong>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Contact Method & Details</span>
                    <strong class="text-dark">{{ $teleconsultation->preferred_contact_method }}</strong>
                    <div class="text-muted small">{{ $teleconsultation->contact_details }}</div>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Assigned Medical Personnel</span>
                    <strong class="text-primary">{{ $teleconsultation->assigned_personnel ?? 'Pending assignment by Health Services' }}</strong>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Request Submission Timestamp</span>
                    <span class="text-muted">{{ $teleconsultation->created_at->format('M d, Y - h:i A') }}</span>
                </div>
            </div>

            <div class="mb-4">
                <span class="text-muted small d-block mb-1">Description of Concern / Symptoms Reported:</span>
                <div class="p-3 bg-light rounded-3 text-dark small border" style="white-space: pre-line;">
                    {{ $teleconsultation->description }}
                </div>
            </div>

            @if($teleconsultation->admin_notes)
                <div class="mb-4">
                    <span class="text-muted small d-block mb-1">Clinic Administrative Remarks & Guidance:</span>
                    <div class="p-3 bg-warning bg-opacity-10 border border-warning rounded-3 text-dark small">
                        <i class="bi bi-chat-left-text text-warning me-1"></i>
                        {{ $teleconsultation->admin_notes }}
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <a href="{{ route('teleconsultation.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Teleconsultations
                </a>

                @if(in_array($teleconsultation->status, ['pending', 'approved', 'scheduled']))
                    <form method="POST" action="{{ route('teleconsultation.cancel', $teleconsultation->id) }}" onsubmit="return confirm('Are you sure you want to cancel this teleconsultation?');">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                            <i class="bi bi-x-circle me-1"></i> Cancel Consultation
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Side Consultation Help -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Patient Preparation</h5>
            <ul class="text-muted small ps-3 mb-0 d-flex flex-column gap-2">
                <li>Prepare a list of your current symptoms and current medications.</li>
                <li>Ensure you have a reliable internet connection if using Google Meet.</li>
                <li>Find a private room to discuss medical symptoms comfortably.</li>
                <li>Have a pen and notebook ready for doctor recommendations.</li>
            </ul>
        </div>

        <div class="card bg-light border p-3 rounded-3 small">
            <h6 class="fw-bold mb-1 text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Emergency Disclaimer</h6>
            <p class="text-muted mb-0">This remote service does not provide emergency medical intervention. For immediate urgent crises, visit a hospital emergency department.</p>
        </div>
    </div>
</div>
@endsection
