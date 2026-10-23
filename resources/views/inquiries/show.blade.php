@extends('layouts.dashboard')

@section('title', 'Inquiry Thread | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inquiries.index') }}">Health Inquiries</a></li>
            <li class="breadcrumb-item active" aria-current="page">Inquiry #{{ $healthInquiry->id }}</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1">{{ $healthInquiry->subject }}</h2>
            <div class="text-muted small">
                Category: <strong>{{ $healthInquiry->category }}</strong> &bull; Tracking Ref: #INQ-{{ str_pad($healthInquiry->id, 5, '0', STR_PAD_LEFT) }}
            </div>
        </div>
        <div>
            <span class="badge {{ $healthInquiry->status_badge_class }} rounded-pill px-3 py-2 fs-6">
                Status: {{ ucfirst(str_replace('_', ' ', $healthInquiry->status)) }}
            </span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Original Inquiry Message Card -->
        <div class="card card-adu p-4 mb-4 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center bg-warning text-dark rounded-circle" style="width: 34px; height: 34px;">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <div>
                        <strong class="text-dark small d-block">{{ auth()->user()->name }} (You)</strong>
                        <span class="text-muted small">Original Question</span>
                    </div>
                </div>
                <span class="text-muted small">
                    <i class="bi bi-clock me-1"></i> {{ $healthInquiry->created_at->format('M d, Y h:i A') }}
                </span>
            </div>

            <div class="text-dark small leading-relaxed" style="white-space: pre-line;">
                {{ $healthInquiry->message }}
            </div>
        </div>

        <!-- Replies Thread -->
        <h5 class="fw-bold text-dark mb-3">
            <i class="bi bi-chat-left-dots text-primary me-2"></i> Responses & Discussion ({{ $healthInquiry->responses->count() }})
        </h5>

        @forelse($healthInquiry->responses as $resp)
            <div class="card card-adu p-4 mb-3 {{ $resp->is_staff_reply ? 'border-start border-4 border-primary bg-white' : 'bg-light' }}">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        @if($resp->is_staff_reply)
                            <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 32px; height: 32px;">
                                <i class="bi bi-hospital-fill small"></i>
                            </span>
                            <div>
                                <strong class="text-primary small d-block">AdU Health Services Staff ({{ $resp->user->name }})</strong>
                                <span class="badge bg-primary bg-opacity-10 text-primary small">Clinic Team</span>
                            </div>
                        @else
                            <span class="d-inline-flex align-items-center justify-content-center bg-secondary text-white rounded-circle" style="width: 32px; height: 32px;">
                                <i class="bi bi-person small"></i>
                            </span>
                            <div>
                                <strong class="text-dark small d-block">{{ $resp->user->name }}</strong>
                                <span class="text-muted small">{{ $resp->user->role_label }}</span>
                            </div>
                        @endif
                    </div>
                    <span class="text-muted small">
                        {{ $resp->created_at->format('M d, Y - h:i A') }}
                    </span>
                </div>

                <div class="text-dark small leading-relaxed" style="white-space: pre-line;">
                    {{ $resp->message }}
                </div>
            </div>
        @empty
            <div class="card card-adu p-4 text-center text-muted small mb-4">
                <i class="bi bi-hourglass-split fs-3 text-warning d-block mb-2"></i>
                Your inquiry has been submitted and is in the queue for staff response. You will receive an in-app alert when a clinic member replies.
            </div>
        @endforelse

        <!-- Reply Form if Not Closed -->
        @if($healthInquiry->status !== 'closed')
            <div class="card card-adu p-4 mt-4">
                <h6 class="fw-bold text-dark mb-2">Post a Follow-up Message</h6>
                <form method="POST" action="{{ route('inquiries.reply', $healthInquiry->id) }}">
                    @csrf
                    <div class="mb-3">
                        <textarea name="message" rows="3" class="form-control @error('message') is-invalid @enderror" placeholder="Type your follow-up reply or clarification here..." required></textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm fw-semibold">
                            <i class="bi bi-send me-1"></i> Send Reply
                        </button>
                        <span class="text-muted small">Replies update thread status to In Progress</span>
                    </div>
                </form>
            </div>
        @else
            <div class="alert alert-secondary small mt-4 text-center">
                <i class="bi bi-lock-fill me-1"></i> This health inquiry has been marked as closed. If you have a new question, please submit a new inquiry.
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('inquiries.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Inquiries
            </a>
        </div>
    </div>

    <!-- Right Summary Box -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Thread Information</h5>
            <div class="d-flex flex-column gap-2 small text-muted">
                <div><strong>Category:</strong> {{ $healthInquiry->category }}</div>
                <div><strong>Status:</strong> <span class="badge {{ $healthInquiry->status_badge_class }}">{{ ucfirst(str_replace('_', ' ', $healthInquiry->status)) }}</span></div>
                <div><strong>Opened:</strong> {{ $healthInquiry->created_at->format('M d, Y h:i A') }}</div>
                <div><strong>Total Responses:</strong> {{ $healthInquiry->responses->count() }}</div>
            </div>
        </div>

        <div class="card bg-light border p-3 rounded-3 small">
            <h6 class="fw-bold mb-1"><i class="bi bi-telephone-fill text-primary me-1"></i> Urgent Clinical Clarifications</h6>
            <p class="text-muted mb-0">For immediate assistance during office hours, call the Health Services Office at (02) 8524-2011 local 123.</p>
        </div>
    </div>
</div>
@endsection
