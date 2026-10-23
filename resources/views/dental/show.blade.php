@extends('layouts.dashboard')

@section('title', 'Appointment Details | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dental.index') }}">Dental Appointments</a></li>
            <li class="breadcrumb-item active" aria-current="page">Appointment #{{ $appointment->id }}</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dental Appointment Details</h2>
            <span class="text-muted small">Tracking Reference: #DNT-{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div>
            <span class="badge {{ $appointment->status_badge_class }} rounded-pill px-3 py-2 fs-6">
                Status: {{ ucfirst($appointment->status) }}
            </span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Booking Summary</h5>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <span class="text-muted small d-block">Scheduled Appointment Date</span>
                    <strong class="text-dark fs-6">{{ $appointment->appointment_date->format('F d, Y (l)') }}</strong>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Confirmed Time Window</span>
                    <strong class="text-primary fs-6">{{ $appointment->appointment_time }}</strong>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Reason for Dental Visit</span>
                    <strong class="text-dark">{{ $appointment->reason_for_visit }}</strong>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Submission Date</span>
                    <span class="text-muted">{{ $appointment->created_at->format('M d, Y - h:i A') }}</span>
                </div>
            </div>

            @if($appointment->status === 'rescheduled' && $appointment->rescheduled_date)
                <div class="p-3 bg-info bg-opacity-10 border border-info rounded-3 mb-4">
                    <h6 class="fw-bold text-info mb-1"><i class="bi bi-clock-history me-1"></i> Rescheduled Schedule Notice</h6>
                    <p class="small text-dark mb-0">
                        The attending dental clinic staff has updated your schedule to <strong>{{ $appointment->rescheduled_date->format('F d, Y') }}</strong> at <strong>{{ $appointment->rescheduled_time }}</strong>.
                    </p>
                </div>
            @endif

            <div class="mb-4">
                <span class="text-muted small d-block mb-1">Reported Symptoms or Specific Concerns:</span>
                <div class="p-3 bg-light rounded-3 text-dark small border">
                    {{ $appointment->specific_concerns ?? 'No specific remarks reported upon submission.' }}
                </div>
            </div>

            @if($appointment->admin_notes)
                <div class="mb-4">
                    <span class="text-muted small d-block mb-1">Health Services Administrative & Clinical Notes:</span>
                    <div class="p-3 bg-warning bg-opacity-10 border border-warning rounded-3 text-dark small">
                        <i class="bi bi-chat-square-text-fill text-warning me-1"></i>
                        {{ $appointment->admin_notes }}
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <a href="{{ route('dental.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Appointments
                </a>

                @if(in_array($appointment->status, ['pending', 'approved', 'rescheduled']))
                    <form method="POST" action="{{ route('dental.cancel', $appointment->id) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                            <i class="bi bi-x-circle me-1"></i> Cancel Appointment
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Timeline / Flow -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Booking Lifecycle</h5>

            <div class="d-flex flex-column gap-3 small">
                <div class="d-flex gap-2 align-items-start">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>
                        <strong>Request Submitted</strong>
                        <div class="text-muted">{{ $appointment->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>

                <div class="d-flex gap-2 align-items-start">
                    @if(in_array($appointment->status, ['approved', 'rescheduled', 'completed']))
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    @elseif($appointment->status === 'rejected')
                        <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                    @else
                        <i class="bi bi-circle text-muted fs-5"></i>
                    @endif
                    <div>
                        <strong>Clinic Review & Approval</strong>
                        <div class="text-muted">Reviewed by dental staff</div>
                    </div>
                </div>

                <div class="d-flex gap-2 align-items-start">
                    @if($appointment->status === 'completed')
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    @else
                        <i class="bi bi-circle text-muted fs-5"></i>
                    @endif
                    <div>
                        <strong>Clinical Treatment & Completion</strong>
                        <div class="text-muted">Service conducted at Room 204</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light border p-3 rounded-3 small">
            <h6 class="fw-bold mb-1"><i class="bi bi-hospital text-primary me-1"></i> Adamson Dental Clinic</h6>
            <p class="text-muted mb-0">For questions or inquiries regarding this booking, contact local 124 during operating hours (Mon-Sat 7:00am-9:00pm).</p>
        </div>
    </div>
</div>
@endsection
