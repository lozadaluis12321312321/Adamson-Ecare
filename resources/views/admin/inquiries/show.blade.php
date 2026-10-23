@extends('layouts.admin')

@section('title', 'Manage Inquiry Thread | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.inquiries.index') }}">Health Inquiries</a></li>
            <li class="breadcrumb-item active" aria-current="page">Inquiry #{{ $healthInquiry->id }}</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1">{{ $healthInquiry->subject }}</h2>
            <div class="text-muted small">
                Category: <strong>{{ $healthInquiry->category }}</strong> &bull; From: <strong>{{ $healthInquiry->user->name }}</strong> ({{ $healthInquiry->user->id_number }})
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge {{ $healthInquiry->status_badge_class }} rounded-pill px-3 py-2 fs-6">
                Status: {{ ucfirst(str_replace('_', ' ', $healthInquiry->status)) }}
            </span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Original Inquiry Message -->
        <div class="card card-adu p-4 mb-4 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center bg-warning text-dark rounded-circle" style="width: 34px; height: 34px;">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <div>
                        <strong class="text-dark small d-block">{{ $healthInquiry->user->name }}</strong>
                        <span class="text-muted small">{{ $healthInquiry->user->role_label }} &bull; {{ $healthInquiry->user->department_or_course }}</span>
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

        <!-- Thread Messages -->
        <h5 class="fw-bold text-dark mb-3">
            <i class="bi bi-chat-left-text text-primary me-2"></i> Inquiry Thread ({{ $healthInquiry->responses->count() }} responses)
        </h5>

        @forelse($healthInquiry->responses as $resp)
            <div class="card card-adu p-4 mb-3 {{ $resp->is_staff_reply ? 'border-start border-4 border-primary bg-white' : 'bg-light' }}">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        @if($resp->is_staff_reply)
                            <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 30px; height: 30px;">
                                <i class="bi bi-hospital small"></i>
                            </span>
                            <div>
                                <strong class="text-primary small d-block">{{ $resp->user->name }} (Staff Reply)</strong>
                                <span class="badge bg-primary bg-opacity-10 text-primary small">Health Services Office</span>
                            </div>
                        @else
                            <span class="d-inline-flex align-items-center justify-content-center bg-secondary text-white rounded-circle" style="width: 30px; height: 30px;">
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
                No replies have been posted to this inquiry yet.
            </div>
        @endforelse

        <!-- Staff Reply Form -->
        <div class="card card-adu p-4 mt-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Post Official Staff Reply</h5>

            <form method="POST" action="{{ route('admin.inquiries.reply', $healthInquiry->id) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Staff Response Message <span class="text-danger">*</span></label>
                    <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror" placeholder="Type verified guidance, schedule details, or clinical information for the student..." required></textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 align-items-center mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Set Inquiry Status After Reply <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="in_progress" {{ $healthInquiry->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="awaiting_user_response" {{ $healthInquiry->status == 'awaiting_user_response' ? 'selected' : '' }}>Awaiting User Response</option>
                            <option value="resolved" {{ $healthInquiry->status == 'resolved' ? 'selected' : '' }}>Resolved (Satisfied)</option>
                            <option value="closed" {{ $healthInquiry->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold w-100">
                            <i class="bi bi-send-fill me-1"></i> Send Reply to User
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Inquiries List
            </a>
        </div>
    </div>

    <!-- Right Summary Box -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">User Contact Card</h5>
            <div class="d-flex flex-column gap-2 small">
                <div><strong>Full Name:</strong> {{ $healthInquiry->user->name }}</div>
                <div><strong>Email:</strong> {{ $healthInquiry->user->email }}</div>
                <div><strong>ID Number:</strong> {{ $healthInquiry->user->id_number }}</div>
                <div><strong>Classification:</strong> {{ $healthInquiry->user->role_label }}</div>
                <div><strong>Department:</strong> {{ $healthInquiry->user->department_or_course }}</div>
                <div><strong>Contact Number:</strong> {{ $healthInquiry->user->contact_number }}</div>
            </div>
        </div>

        <!-- Quick Status Change Form -->
        <div class="card card-adu p-4">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">Quick Status Change</h6>
            <form method="POST" action="{{ route('admin.inquiries.status', $healthInquiry->id) }}">
                @csrf
                <div class="mb-3">
                    <select name="status" class="form-select form-select-sm" required>
                        <option value="submitted" {{ $healthInquiry->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="in_progress" {{ $healthInquiry->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="awaiting_user_response" {{ $healthInquiry->status == 'awaiting_user_response' ? 'selected' : '' }}>Awaiting User Response</option>
                        <option value="resolved" {{ $healthInquiry->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $healthInquiry->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">
                    Update Status Only
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
