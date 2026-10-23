@extends('layouts.dashboard')

@section('title', 'Send Health Inquiry | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inquiries.index') }}">Health Inquiries</a></li>
            <li class="breadcrumb-item active" aria-current="page">New Inquiry</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark mb-1">Send Health Inquiry</h2>
    <p class="text-muted small">Submit general clinic questions, health requirement inquiries, or medical guidance to the clinic staff.</p>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> Please check form inputs:
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-adu p-4 p-md-5">
            <form method="POST" action="{{ route('inquiries.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="category" class="form-label small fw-semibold text-dark">Inquiry Topic Category <span class="text-danger">*</span></label>
                    <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="" disabled selected>Select category...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label small fw-semibold text-dark">Subject Title <span class="text-danger">*</span></label>
                    <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required placeholder="e.g. Inquiring about Annual Physical Exam schedule or Vaccination drive">
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label small fw-semibold text-dark">Detailed Message <span class="text-danger">*</span></label>
                    <textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="State your inquiry clearly so the healthcare team can assist you accurately..." required>{{ old('message') }}</textarea>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-semibold">
                        <i class="bi bi-send me-1"></i> Send Inquiry
                    </button>
                    <a href="{{ route('inquiries.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Side Guidance Box -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-chat-heart text-warning me-2"></i> Inquiry Helpdesk Notes
            </h5>
            <ul class="text-muted small ps-3 mb-0 d-flex flex-column gap-2">
                <li>Clinic staff monitor inquiries during regular clinic hours (Monday – Saturday, 7:00 AM – 9:00 PM).</li>
                <li>You will receive an in-app notification when the clinic staff posts a reply.</li>
                <li>You can reply back directly on the message thread if you need more clarification.</li>
                <li>Do not use inquiries for urgent medical emergencies; seek immediate clinic or hospital assistance instead.</li>
            </ul>
        </div>

        <div class="card bg-light border p-3 rounded-3 small">
            <h6 class="fw-bold mb-1"><i class="bi bi-clock-history text-primary me-1"></i> Average Response Time</h6>
            <p class="text-muted mb-0">Most general questions are answered within 1-2 business days.</p>
        </div>
    </div>
</div>
@endsection
