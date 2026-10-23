@extends('layouts.public')

@section('title', 'About AdU eCare+ | Adamson University Digital Healthcare')

@section('content')
<div class="bg-primary text-white py-5" style="background: linear-gradient(135deg, var(--adu-navy) 0%, var(--adu-blue) 100%) !important;">
    <div class="container text-center">
        <span class="badge bg-info text-dark px-3 py-1 rounded-pill mb-2 fw-semibold">Design Thinking Healthcare Solution</span>
        <h1 class="fw-bold mb-2">About AdU eCare+</h1>
        <p class="lead text-light opacity-90 mx-auto" style="max-width: 650px;">
            A student-centered digital health platform crafted to modernize health services delivery at Adamson University.
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5 align-items-center mb-5">
        <div class="col-lg-6">
            <h2 class="fw-bold text-dark mb-3">Our Project Mission</h2>
            <p class="text-muted leading-relaxed">
                <strong>AdU eCare+</strong> originated as an academic and institutional Design Thinking initiative created to solve key accessibility challenges in campus healthcare delivery. Traditional paper queues, conflicting schedules, and manual medical excuse filing are transformed into an integrated, paperless, and responsive digital ecosystem.
            </p>
            <p class="text-muted leading-relaxed">
                By enabling real-time appointment booking with university dentists, confidential teleconsultation triaging, digital requisition for medical certificates, and an interactive health inquiry desk, AdU eCare+ places patient convenience, safety, and health literacy at the heart of Adamson University.
            </p>
            <div class="d-flex align-items-center gap-3 mt-4">
                <div class="p-3 bg-light rounded-3 border text-center flex-fill">
                    <h3 class="fw-bold text-primary mb-0">100%</h3>
                    <small class="text-muted">Digital Requisitions</small>
                </div>
                <div class="p-3 bg-light rounded-3 border text-center flex-fill">
                    <h3 class="fw-bold text-success mb-0">24/7</h3>
                    <small class="text-muted">Portal Accessibility</small>
                </div>
                <div class="p-3 bg-light rounded-3 border text-center flex-fill">
                    <h3 class="fw-bold text-info mb-0">RA 10173</h3>
                    <small class="text-muted">Data Privacy Compliant</small>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-adu p-4 border-0 shadow-sm bg-white">
                <h4 class="fw-bold text-primary mb-3">Design Thinking Core Pillars</h4>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3 align-items-start">
                        <span class="badge bg-primary rounded-circle p-2 fs-6">1</span>
                        <div>
                            <h6 class="fw-bold mb-1">Empathize with the Vincentian Community</h6>
                            <p class="text-muted small mb-0">Understanding student class schedules and faculty workload constraints when seeking clinical consultation.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-start">
                        <span class="badge bg-primary rounded-circle p-2 fs-6">2</span>
                        <div>
                            <h6 class="fw-bold mb-1">Define Clear Healthcare Workflows</h6>
                            <p class="text-muted small mb-0">Structuring predictable scheduling rules that avoid clinic overcrowding and double bookings.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-start">
                        <span class="badge bg-primary rounded-circle p-2 fs-6">3</span>
                        <div>
                            <h6 class="fw-bold mb-1">Ideate Transparent Digital Verification</h6>
                            <p class="text-muted small mb-0">Issuing genuine, doctor-validated digital medical clearance reference numbers.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-start">
                        <span class="badge bg-primary rounded-circle p-2 fs-6">4</span>
                        <div>
                            <h6 class="fw-bold mb-1">Implement with Modern Laravel & MySQL</h6>
                            <p class="text-muted small mb-0">Building a robust, secure, and responsive full-stack platform tailored for local university deployment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Privacy Section -->
    <div class="card card-adu p-4 p-md-5 mb-5 border-start border-4 border-success">
        <div class="d-flex align-items-center gap-3 mb-3">
            <i class="bi bi-shield-lock-fill text-success fs-1"></i>
            <div>
                <h4 class="fw-bold mb-0">Data Privacy & Confidentiality Commitment</h4>
                <span class="text-muted small">Adherence to the Philippine Data Privacy Act of 2012 (Republic Act No. 10173)</span>
            </div>
        </div>
        <p class="text-muted small leading-relaxed mb-3">
            The Adamson University Health Services Office is committed to protecting the privacy, security, and integrity of all personal health records (PHR) and sensitive medical documentation collected through the AdU eCare+ web application.
        </p>
        <ul class="text-muted small leading-relaxed mb-4">
            <li><strong>Proportional Collection:</strong> Only medical and personal data necessary for diagnosing, triaging, and processing requested services are recorded.</li>
            <li><strong>Strict Role-Based Confidentiality:</strong> Student and employee consultation records and uploaded medical clearance slips are accessible solely to authorized university clinic physicians, dentists, and health personnel.</li>
            <li><strong>Private Document Storage:</strong> Uploaded supporting medical evidence is saved in restricted, private server storage and served only to authenticated account owners and medical officers.</li>
            <li><strong>No Commercial Exploitation:</strong> University health data is never shared, marketed, or transferred to third-party commercial platforms.</li>
        </ul>
        <div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 p-2 small align-self-start">
            <i class="bi bi-check-circle me-1"></i> Formulated in accordance with the National Privacy Commission (NPC) regulatory guidelines.
        </div>
    </div>

    <!-- Clinic Hours & Campus Facility Card -->
    <div class="card card-adu p-4 p-md-5 mb-5 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(74, 144, 226, 0.08) 0%, rgba(255, 255, 255, 0.95) 100%);">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary text-white px-3 py-1 rounded-pill">Clinic Operations</span>
                    <span class="badge bg-info text-dark px-3 py-1 rounded-pill">Ground Floor, St. Therese Bldg</span>
                </div>
                <h3 class="fw-bold mb-2">University Health Services Operating Schedule</h3>
                <p class="text-muted mb-3">Our medical, dental, and administrative staff are on duty to serve all bona fide students, faculty members, and university personnel during official hours.</p>
                <div class="d-flex flex-wrap gap-3">
                    <div class="p-3 bg-white rounded-3 border shadow-xs d-flex align-items-center gap-3">
                        <i class="bi bi-clock-history fs-2 text-primary"></i>
                        <div>
                            <span class="small text-muted d-block">Monday to Saturday</span>
                            <strong class="text-dark fs-5">7:00 AM – 9:00 PM</strong>
                        </div>
                    </div>
                    <div class="p-3 bg-white rounded-3 border shadow-xs d-flex align-items-center gap-3">
                        <i class="bi bi-calendar-x fs-2 text-danger"></i>
                        <div>
                            <span class="small text-muted d-block">Sundays & Official Holidays</span>
                            <strong class="text-danger fs-5">Closed</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 bg-white p-4 rounded-3 shadow-xs">
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-geo-alt-fill me-1"></i> Physical Clinic Facility</h6>
                    <p class="text-muted small mb-3">Health Services Office, Ground Floor, St. Therese Building, Adamson University, 900 San Marcelino St, Ermita, Manila.</p>
                    <div class="alert alert-warning py-2 px-3 small mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning flex-shrink-0"></i>
                        <span>For life-threatening emergencies outside clinic hours, immediately dial 911 or proceed to the nearest emergency hospital.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
