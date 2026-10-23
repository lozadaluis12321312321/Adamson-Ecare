@extends('layouts.dashboard')

@section('title', 'Request Teleconsultation | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('teleconsultation.index') }}">Teleconsultations</a></li>
            <li class="breadcrumb-item active" aria-current="page">Request Consultation</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark mb-1">Request Medical Teleconsultation</h2>
    <p class="text-muted small">Connect remotely with an Adamson University medical physician or guidance counselor.</p>
</div>

<!-- Critical Emergency Care Warning Banner -->
<div class="alert alert-danger border-danger border-2 rounded-3 mb-4 shadow-sm">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-shield-fill-exclamation fs-2 text-danger"></i>
        <div>
            <h5 class="fw-bold text-danger mb-1">Emergency Medical Notice</h5>
            <p class="mb-0 small text-dark">
                <strong>AdU eCare+ teleconsultation is strictly for non-life-threatening medical conditions, triage, follow-ups, and general wellness counseling.</strong>
                If you or someone around you is experiencing severe chest pain, shortness of breath, acute traumatic injury, severe allergic reaction, or loss of consciousness, proceed immediately to the nearest hospital emergency room or dial emergency services (911).
            </p>
        </div>
    </div>
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
            <form method="POST" action="{{ route('teleconsultation.store') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="preferred_date" class="form-label small fw-semibold text-dark">Preferred Date <span class="text-danger">*</span></label>
                        <input type="date" name="preferred_date" id="preferred_date" class="form-control @error('preferred_date') is-invalid @enderror" value="{{ old('preferred_date', now()->addDay()->toDateString()) }}" min="{{ now()->toDateString() }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="preferred_time" class="form-label small fw-semibold text-dark">Preferred Consultation Time Window <span class="text-danger">*</span></label>
                        <select name="preferred_time" id="preferred_time" class="form-select @error('preferred_time') is-invalid @enderror" required>
                            <option value="" disabled selected>Select time window...</option>
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}" {{ old('preferred_time') == $slot ? 'selected' : '' }}>{{ $slot }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label small fw-semibold text-dark">Health Concern Category <span class="text-danger">*</span></label>
                    <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="" disabled selected>Select category...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold text-dark">Description of Concern / Symptoms <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Describe symptoms, when they began, current severity, and any medications already taken..." required>{{ old('description') }}</textarea>
                    <div class="form-text small">Detailed information assists clinic staff in triaging your consultation.</div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="preferred_contact_method" class="form-label small fw-semibold text-dark">Preferred Consultation Method <span class="text-danger">*</span></label>
                        <select name="preferred_contact_method" id="preferred_contact_method" class="form-select @error('preferred_contact_method') is-invalid @enderror" required>
                            <option value="Video Meeting Link (Google Meet / Zoom)" {{ old('preferred_contact_method') == 'Video Meeting Link (Google Meet / Zoom)' ? 'selected' : '' }}>Video Meeting Link (Google Meet / Zoom)</option>
                            <option value="Phone Call" {{ old('preferred_contact_method') == 'Phone Call' ? 'selected' : '' }}>Direct Phone Call</option>
                            <option value="In-App Teleconsultation" {{ old('preferred_contact_method') == 'In-App Teleconsultation' ? 'selected' : '' }}>In-App Teleconsultation</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="contact_details" class="form-label small fw-semibold text-dark">Active Contact Number / Email <span class="text-danger">*</span></label>
                        <input type="text" name="contact_details" id="contact_details" class="form-control @error('contact_details') is-invalid @enderror" value="{{ old('contact_details', auth()->user()->contact_number ?? auth()->user()->email) }}" required placeholder="e.g. 0918-234-5678 or email">
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-info text-white rounded-pill px-4 fw-semibold">
                        <i class="bi bi-camera-video me-1"></i> Submit Teleconsultation Request
                    </button>
                    <a href="{{ route('teleconsultation.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Guidance Box -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-camera-video-fill text-info me-2"></i> How Teleconsultation Works
            </h5>
            <ol class="text-muted small ps-3 mb-0 d-flex flex-column gap-2">
                <li>Submit your preferred date, time, and concern description.</li>
                <li>The University Health Services Office assigns an attending doctor or counselor.</li>
                <li>Once scheduled, a secure <strong>Google Meet or Zoom video link</strong> will be generated and posted to your details page.</li>
                <li>Join the meeting 5 minutes prior in a quiet, private area.</li>
            </ol>
        </div>

        <div class="card bg-light border p-3 rounded-3 small">
            <h6 class="fw-bold mb-1"><i class="bi bi-shield-lock-fill text-success me-1"></i> Confidential Session</h6>
            <p class="text-muted mb-0">Consultations are private medical interactions governed by patient-physician confidentiality and RA 10173.</p>
        </div>
    </div>
</div>
@endsection
