@extends('layouts.dashboard')

@section('title', 'Requisition Medical Certificate | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Medical Certificates</a></li>
            <li class="breadcrumb-item active" aria-current="page">Requisition Form</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark mb-1">Requisition Medical Certificate</h2>
    <p class="text-muted small">Submit your request details and supporting documentation for review by Adamson University medical officers.</p>
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
            <form method="POST" action="{{ route('certificates.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="certificate_type" class="form-label small fw-semibold text-dark">Certificate Request Type <span class="text-danger">*</span></label>
                    <select name="certificate_type" id="certificate_type" class="form-select @error('certificate_type') is-invalid @enderror" required>
                        <option value="" disabled selected>Select certificate classification...</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ old('certificate_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="consultation_date" class="form-label small fw-semibold text-dark">Date of Clinic Consultation / Medical Illness (If applicable)</label>
                        <input type="date" name="consultation_date" id="consultation_date" class="form-control @error('consultation_date') is-invalid @enderror" value="{{ old('consultation_date') }}">
                        <div class="form-text small">Date you visited the university clinic or an external physician.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="purpose_of_request" class="form-label small fw-semibold text-dark">Specific Purpose of Request <span class="text-danger">*</span></label>
                        <input type="text" name="purpose_of_request" id="purpose_of_request" class="form-control @error('purpose_of_request') is-invalid @enderror" value="{{ old('purpose_of_request') }}" required placeholder="e.g. Return to classes after viral flu, PE class excuse, OJT clearance">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="additional_information" class="form-label small fw-semibold text-dark">Additional Clinical Information / Physician Diagnoses</label>
                    <textarea name="additional_information" id="additional_information" rows="3" class="form-control @error('additional_information') is-invalid @enderror" placeholder="Describe diagnosis, dates of excused absence, medications prescribed, or notes from attending doctor...">{{ old('additional_information') }}</textarea>
                </div>

                <!-- Supporting Document Upload -->
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <label for="supporting_document" class="form-label small fw-semibold text-dark mb-1">
                        <i class="bi bi-paperclip me-1 text-primary"></i> Upload Supporting Medical Document (Optional / Recommended)
                    </label>
                    <input type="file" name="supporting_document" id="supporting_document" class="form-control @error('supporting_document') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text small text-muted mt-1">
                        Supported formats: PDF, JPG, PNG (Max: 5MB). Files are securely stored in private storage and accessible only to authorized health personnel.
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                        <i class="bi bi-file-earmark-check me-1"></i> Submit Requisition
                    </button>
                    <a href="{{ route('certificates.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Side Guidelines -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-info-circle text-success me-2"></i> Medical Review Policy
            </h5>
            <ul class="text-muted small ps-3 mb-0 d-flex flex-column gap-2">
                <li>Medical certificates are not issued automatically; each request undergoes clinical review by authorized university physicians.</li>
                <li>Falsification of external clinic documents or excuse slips is subject to university disciplinary sanctions.</li>
                <li>Approved digital certificates are issued an official tracking number (e.g. <code>ADU-MC-XXXX</code>).</li>
                <li>Turnaround time is typically 24-48 business hours upon document verification.</li>
            </ul>
        </div>

        <div class="card bg-light border p-3 rounded-3 small">
            <h6 class="fw-bold mb-1"><i class="bi bi-shield-check text-success me-1"></i> RA 10173 Protection</h6>
            <p class="text-muted mb-0">Attached medical records and laboratory diagnostic slips are treated as strictly confidential medical files.</p>
        </div>
    </div>
</div>
@endsection
