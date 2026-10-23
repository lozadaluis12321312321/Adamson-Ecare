@extends('layouts.dashboard')

@section('title', 'Book Dental Appointment | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dental.index') }}">Dental Appointments</a></li>
            <li class="breadcrumb-item active" aria-current="page">Book Appointment</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark mb-1">Book Dental Appointment</h2>
    <p class="text-muted small">Select an available schedule slot configured by the Adamson University Dental Clinic.</p>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> Please resolve the following errors:
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
            <form method="POST" action="{{ route('dental.store') }}">
                @csrf

                <!-- Schedule Selection Section -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark mb-1">
                        Select Available Dental Clinic Schedule <span class="text-danger">*</span>
                    </label>
                    <p class="text-muted small mb-3">Choose from available clinic dates and time windows:</p>

                    @if($schedules->isEmpty())
                        <div class="alert alert-warning small">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>
                            There are currently no active open schedules configured by Health Services personnel. Please check back soon or submit a health inquiry.
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($schedules as $sched)
                                <div class="col-md-6">
                                    <label class="card h-100 p-3 border rounded-3 cursor-pointer position-relative schedule-card" for="sched_{{ $sched->id }}" style="cursor: pointer;">
                                        <div class="d-flex align-items-center gap-3">
                                            <input class="form-check-input mt-0" type="radio" name="appointment_schedule_id" id="sched_{{ $sched->id }}" value="{{ $sched->id }}" {{ old('appointment_schedule_id') == $sched->id ? 'checked' : '' }} required>
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    {{ $sched->schedule_date->format('M d, Y (D)') }}
                                                </div>
                                                <div class="text-primary small fw-semibold">
                                                    <i class="bi bi-clock me-1"></i> {{ date('h:i A', strtotime($sched->start_time)) }} - {{ date('h:i A', strtotime($sched->end_time)) }}
                                                </div>
                                                <div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 small mt-1">
                                                    {{ $sched->availableSlots() }} slots remaining
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Reason for Visit -->
                <div class="mb-3">
                    <label for="reason_for_visit" class="form-label small fw-semibold text-dark">
                        Reason for Dental Visit <span class="text-danger">*</span>
                    </label>
                    <select name="reason_for_visit" id="reason_for_visit" class="form-select @error('reason_for_visit') is-invalid @enderror" required>
                        <option value="" disabled selected>Select procedure / reason...</option>
                        <option value="Oral Prophylaxis (Cleaning)" {{ old('reason_for_visit') == 'Oral Prophylaxis (Cleaning)' ? 'selected' : '' }}>Oral Prophylaxis (Cleaning)</option>
                        <option value="Dental Examination & Consultation" {{ old('reason_for_visit') == 'Dental Examination & Consultation' ? 'selected' : '' }}>Dental Examination & Consultation</option>
                        <option value="Tooth Filling (Composite / Glass Ionomer)" {{ old('reason_for_visit') == 'Tooth Filling (Composite / Glass Ionomer)' ? 'selected' : '' }}>Tooth Filling (Composite / Glass Ionomer)</option>
                        <option value="Tooth Extraction (Simple)" {{ old('reason_for_visit') == 'Tooth Extraction (Simple)' ? 'selected' : '' }}>Tooth Extraction (Simple)</option>
                        <option value="Toothache & Emergency Dental Check" {{ old('reason_for_visit') == 'Toothache & Emergency Dental Check' ? 'selected' : '' }}>Toothache & Emergency Dental Check</option>
                        <option value="Pre-Clinical / PE Dental Clearance" {{ old('reason_for_visit') == 'Pre-Clinical / PE Dental Clearance' ? 'selected' : '' }}>Pre-Clinical / PE Dental Clearance</option>
                        <option value="Other Dental Concern" {{ old('reason_for_visit') == 'Other Dental Concern' ? 'selected' : '' }}>Other Dental Concern</option>
                    </select>
                </div>

                <!-- Specific Concerns / Notes -->
                <div class="mb-4">
                    <label for="specific_concerns" class="form-label small fw-semibold text-dark">
                        Specific Dental Concerns or Symptoms (Optional)
                    </label>
                    <textarea name="specific_concerns" id="specific_concerns" rows="4" class="form-control @error('specific_concerns') is-invalid @enderror" placeholder="Describe any pain, sensitivity, bleeding gums, or prior treatments...">{{ old('specific_concerns') }}</textarea>
                    <div class="form-text small">Provide any relevant background that will assist the attending dentist.</div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold" {{ $schedules->isEmpty() ? 'disabled' : '' }}>
                        <i class="bi bi-calendar-check me-1"></i> Submit Dental Request
                    </button>
                    <a href="{{ route('dental.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Side Guidance Card -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-info-circle text-primary me-2"></i> Clinic Patient Guidelines
            </h5>
            <ul class="text-muted small ps-3 mb-0 d-flex flex-column gap-2">
                <li>Arrive <strong>10 minutes prior</strong> to your confirmed schedule slot.</li>
                <li>Bring your physical <strong>Adamson University ID card</strong>.</li>
                <li>Brush your teeth and practice proper oral hygiene before coming to the clinic.</li>
                <li>If experiencing acute fever or respiratory symptoms, please reschedule or use teleconsultation first.</li>
                <li>Cancellations should be made at least 24 hours in advance to reopen the slot for fellow students.</li>
            </ul>
        </div>

        <div class="card bg-light border p-3 rounded-3 small">
            <div class="fw-bold text-dark mb-1"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Clinic Location</div>
            <div class="text-muted">Dental Clinic, Room 204, St. Vincent Building, Adamson University</div>
        </div>
    </div>
</div>
@endsection
