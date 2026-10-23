@extends('layouts.dashboard')

@section('title', 'Certificate Requisition Details | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Medical Certificates</a></li>
            <li class="breadcrumb-item active" aria-current="page">Requisition #{{ $medicalCertificate->id }}</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1">Medical Certificate Requisition</h2>
            <span class="text-muted small">Tracking Reference: #MC-{{ str_pad($medicalCertificate->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div>
            <span class="badge {{ $medicalCertificate->status_badge_class }} rounded-pill px-3 py-2 fs-6">
                Status: {{ ucfirst(str_replace('_', ' ', $medicalCertificate->status)) }}
            </span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Certificate Ready or Released Box -->
        @if($medicalCertificate->certificate_number)
            <div class="card bg-success bg-opacity-10 border border-success p-4 rounded-4 mb-4 shadow-sm">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="badge bg-success mb-2 px-3 py-1">Verified Medical Certificate</div>
                        <h4 class="fw-bold text-success mb-1">
                            Certificate No: {{ $medicalCertificate->certificate_number }}
                        </h4>
                        <p class="text-dark small mb-0">Officially verified and approved by Adamson University Health Services.</p>
                        @if($medicalCertificate->released_at)
                            <div class="text-muted small mt-1">Released on: {{ $medicalCertificate->released_at->format('F d, Y h:i A') }}</div>
                        @endif
                    </div>
                    <button class="btn btn-outline-success rounded-pill px-4 fw-semibold" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Print Slip
                    </button>
                </div>
            </div>
        @endif

        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Requisition Summary</h5>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <span class="text-muted small d-block">Certificate Classification</span>
                    <strong class="text-dark fs-6">{{ $medicalCertificate->certificate_type }}</strong>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Specific Purpose</span>
                    <strong class="text-dark">{{ $medicalCertificate->purpose_of_request }}</strong>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Consultation / Illness Date</span>
                    <strong class="text-dark">{{ $medicalCertificate->consultation_date ? $medicalCertificate->consultation_date->format('F d, Y') : 'Not indicated' }}</strong>
                </div>

                <div class="col-sm-6">
                    <span class="text-muted small d-block">Submission Date</span>
                    <span class="text-muted">{{ $medicalCertificate->created_at->format('M d, Y - h:i A') }}</span>
                </div>
            </div>

            <div class="mb-4">
                <span class="text-muted small d-block mb-1">Additional Clinical Information / Physician Diagnoses:</span>
                <div class="p-3 bg-light rounded-3 text-dark small border" style="white-space: pre-line;">
                    {{ $medicalCertificate->additional_information ?? 'No additional information provided.' }}
                </div>
            </div>

            <!-- Supporting Document Download Section -->
            <div class="mb-4">
                <span class="text-muted small d-block mb-2">Attached Supporting Document:</span>
                @if($medicalCertificate->supporting_document_path)
                    <div class="p-3 bg-white border rounded-3 d-flex align-items-center justify-content-between shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-pdf-fill text-danger fs-3"></i>
                            <div>
                                <strong class="text-dark small d-block">{{ $medicalCertificate->supporting_document_name ?? 'supporting_document' }}</strong>
                                <span class="text-muted small">Encrypted Private Storage</span>
                            </div>
                        </div>
                        <a href="{{ route('certificates.download', $medicalCertificate->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-download me-1"></i> Download File
                        </a>
                    </div>
                @else
                    <div class="p-3 bg-light rounded-3 text-muted small border">
                        No supporting document uploaded with this requisition.
                    </div>
                @endif
            </div>

            @if($medicalCertificate->admin_notes)
                <div class="mb-4">
                    <span class="text-muted small d-block mb-1">Health Services Physician Remarks & Status Notes:</span>
                    <div class="p-3 bg-warning bg-opacity-10 border border-warning rounded-3 text-dark small">
                        <i class="bi bi-chat-square-text text-warning me-1"></i>
                        {{ $medicalCertificate->admin_notes }}
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <a href="{{ route('certificates.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Requisitions
                </a>
            </div>
        </div>
    </div>

    <!-- Right Side Status Progression -->
    <div class="col-lg-4">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Requisition Lifecycle</h5>

            <div class="d-flex flex-column gap-3 small">
                <div class="d-flex gap-2 align-items-start">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>
                        <strong>1. Requisition Submitted</strong>
                        <div class="text-muted">{{ $medicalCertificate->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>

                <div class="d-flex gap-2 align-items-start">
                    @if(in_array($medicalCertificate->status, ['under_review', 'additional_info_required', 'approved', 'ready_for_release', 'released']))
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    @else
                        <i class="bi bi-circle text-muted fs-5"></i>
                    @endif
                    <div>
                        <strong>2. Physician Review</strong>
                        <div class="text-muted">Clinical validation of excuse & records</div>
                    </div>
                </div>

                <div class="d-flex gap-2 align-items-start">
                    @if(in_array($medicalCertificate->status, ['approved', 'ready_for_release', 'released']))
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    @else
                        <i class="bi bi-circle text-muted fs-5"></i>
                    @endif
                    <div>
                        <strong>3. Approved & Numbered</strong>
                        <div class="text-muted">Unique certificate ID generated</div>
                    </div>
                </div>

                <div class="d-flex gap-2 align-items-start">
                    @if($medicalCertificate->status === 'released')
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    @else
                        <i class="bi bi-circle text-muted fs-5"></i>
                    @endif
                    <div>
                        <strong>4. Released & Ready</strong>
                        <div class="text-muted">Available for department presentation</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light border p-3 rounded-3 small">
            <h6 class="fw-bold mb-1"><i class="bi bi-info-circle text-primary me-1"></i> Release Inquiries</h6>
            <p class="text-muted mb-0">For release window assistance, visit the Health Services Office Release Desk (Room 104, SV Building).</p>
        </div>
    </div>
</div>
@endsection
