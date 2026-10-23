@extends('layouts.public')

@section('title', 'AdU eCare+ | Adamson University Digital Healthcare')

@section('content')
    <!-- Creative Hero Section with White and Light Blue Ambient Mesh -->
    <section class="hero-section text-center text-lg-start position-relative">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="hero-badge">
                        <i class="bi bi-shield-check text-info fs-6"></i>
                        <span>Adamson University Health Services Office</span>
                    </div>
                    <h1 class="display-4 fw-bold mb-3 text-white">
                        Your Health, <span style="background: linear-gradient(135deg, #7dd3fc 0%, #ffffff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Our Priority.</span>
                    </h1>
                    <p class="lead mb-4 text-light opacity-90" style="max-width: 620px;">
                        Access university healthcare services and health information in one convenient platform. Designed for Adamson students, faculty, and personnel to connect with clinical services effortlessly.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-gradient-primary btn-lg px-4 rounded-pill fw-semibold shadow-lg">
                                <i class="bi bi-speedometer2 me-2"></i> Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-gradient-primary btn-lg px-4 rounded-pill fw-semibold shadow-lg">
                                <i class="bi bi-arrow-right-circle me-2"></i> Get Started
                            </a>
                        @endauth
                        <a href="#services-section" class="btn btn-outline-light btn-lg px-4 rounded-pill fw-medium">
                            Explore Health Services <i class="bi bi-arrow-down-short ms-1"></i>
                        </a>
                    </div>

                    <!-- Creative Highlight Badges -->
                    <div class="d-flex flex-wrap gap-3 mt-4 pt-2 text-white small opacity-90 justify-content-center justify-content-lg-start">
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-check-circle-fill text-info"></i> Mon–Sat 7am–9pm</span>
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-check-circle-fill text-info"></i> Dental Scheduling</span>
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-check-circle-fill text-info"></i> Virtual Teleconsult</span>
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-check-circle-fill text-info"></i> RA 10173 Verified</span>
                    </div>
                </div>

                <div class="col-lg-5">
                    <!-- Frosted Glass Clinic Info Card with Creative Light-Blue Borders -->
                    <div class="card card-adu p-4 position-relative border-2 shadow-lg">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                            <div>
                                <h5 class="fw-bold text-primary mb-1">Campus Clinic Quick Info</h5>
                                <span class="text-muted small">San Marcelino Main Campus</span>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                <i class="bi bi-dot"></i> Operational
                            </span>
                        </div>
                        <ul class="list-unstyled mb-4 d-flex flex-column gap-3 small">
                            <li class="d-flex align-items-start gap-2">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary flex-shrink-0" style="width: 36px; height: 36px; font-size: 1.1rem;">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div>
                                    <strong class="text-dark">Clinic Operating Hours</strong>
                                    <div class="text-primary fw-semibold">Monday – Saturday: 7:00 AM – 9:00 PM</div>
                                </div>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <div class="stat-icon bg-danger bg-opacity-10 text-danger flex-shrink-0" style="width: 36px; height: 36px; font-size: 1.1rem;">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div>
                                    <strong class="text-dark">Clinic Location</strong>
                                    <div class="text-muted">St. Vincent Building, Ground Floor, Room 104</div>
                                </div>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <div class="stat-icon bg-success bg-opacity-10 text-success flex-shrink-0" style="width: 36px; height: 36px; font-size: 1.1rem;">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div>
                                    <strong class="text-dark">Trunkline & Local</strong>
                                    <div class="text-muted">(02) 8524-2011 local 123 / 124</div>
                                </div>
                            </li>
                        </ul>
                        <div class="p-3 rounded-3 text-center border" style="background: var(--grad-white-blue);">
                            <span class="text-muted small d-block mb-2">Need to see the dentist or talk with a doctor?</span>
                            <a href="{{ route('login') }}" class="btn btn-gradient-primary btn-sm w-100 rounded-pill fw-semibold py-2">
                                Sign In to Book or Request
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Healthcare Services Section with Creative Gradient Cards -->
    <section id="services-section" class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold text-uppercase small border border-primary border-opacity-25">
                    Comprehensive Care
                </span>
                <h2 class="fw-bold mt-2 text-dark">University Healthcare Services</h2>
                <p class="text-muted mx-auto" style="max-width: 650px;">
                    Fast-track your clinical appointments, online teleconsultations, and certified medical requests through modern digital self-service.
                </p>
            </div>

            <div class="row g-4">
                <!-- 1. Dental Appointment -->
                <div class="col-md-6 col-lg-4">
                    <div class="card card-adu h-100 p-4">
                        <div class="card-service-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-calendar2-check"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Dental Appointment</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Schedule dental checkups, cleaning (oral prophylaxis), tooth fillings, and toothache evaluations with accredited university dentists.
                        </p>
                        <a href="{{ route('dental.index') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold mt-auto align-self-start">
                            Book Dental Slot <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- 2. Teleconsultation Request -->
                <div class="col-md-6 col-lg-4">
                    <div class="card card-adu h-100 p-4">
                        <div class="card-service-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-camera-video"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Teleconsultation Request</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Request remote medical checkups, general health consultations, and mental well-being advice via secure video or phone appointments.
                        </p>
                        <a href="{{ route('teleconsultation.index') }}" class="btn btn-outline-info btn-sm rounded-pill fw-semibold mt-auto align-self-start">
                            Request Consultation <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- 3. Medical Certificate Requisition -->
                <div class="col-md-6 col-lg-4">
                    <div class="card card-adu h-100 p-4">
                        <div class="card-service-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-file-earmark-medical"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Medical Certificate Requisition</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Request official university fit-to-return slips, PE clearances, OJT clearances, and excuse slip validations with verified physician review.
                        </p>
                        <a href="{{ route('certificates.index') }}" class="btn btn-outline-success btn-sm rounded-pill fw-semibold mt-auto align-self-start">
                            Request Certificate <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- 4. Health-Related Inquiries -->
                <div class="col-md-6 col-lg-4">
                    <div class="card card-adu h-100 p-4">
                        <div class="card-service-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Health-Related Inquiries</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Direct questions to the Health Services medical and nursing team regarding clinic hours, medical clearance, vaccines, and wellness.
                        </p>
                        <a href="{{ route('inquiries.index') }}" class="btn btn-outline-warning text-dark btn-sm rounded-pill fw-semibold mt-auto align-self-start">
                            Send Inquiry <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- 5. Health Information -->
                <div class="col-md-6 col-lg-4">
                    <div class="card card-adu h-100 p-4">
                        <div class="card-service-icon bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-journal-medical"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Health Information</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Read verified health articles, wellness guides, stress management tips, and seasonal preventive health advisories published by clinic staff.
                        </p>
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-danger btn-sm rounded-pill fw-semibold mt-auto align-self-start">
                            Read Articles <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- 6. Health Announcements -->
                <div class="col-md-6 col-lg-4">
                    <div class="card card-adu h-100 p-4">
                        <div class="card-service-icon bg-secondary bg-opacity-10 text-secondary">
                            <i class="bi bi-megaphone"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Health Announcements</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Stay informed on university vaccination drives, mandatory physical examination schedules, and urgent public health advisories.
                        </p>
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold mt-auto align-self-start">
                            View Announcements <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Health Announcements Section -->
    <section class="py-5" style="background: rgba(14, 165, 233, 0.04);">
        <div class="container py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Latest Health Announcements</h3>
                    <p class="text-muted small mb-0">Official advisories and health updates for the Adamson community.</p>
                </div>
                <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-primary rounded-pill mt-3 mt-md-0 fw-semibold">
                    View All Announcements <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse($latestAnnouncements as $announcement)
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-adu h-100 p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge {{ $announcement->priority_badge_class }} rounded-pill small">
                                    {{ ucfirst($announcement->priority) }} Priority
                                </span>
                                <span class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $announcement->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            <h5 class="fw-bold text-dark mb-2 fs-6">
                                <a href="{{ route('announcements.show', $announcement->id) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $announcement->title }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-3 flex-grow-1">
                                {{ Str::limit($announcement->content, 120) }}
                            </p>
                            <a href="{{ route('announcements.show', $announcement->id) }}" class="text-primary fw-semibold small text-decoration-none">
                                Read full advisory <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 text-muted">
                        <i class="bi bi-info-circle fs-3 d-block mb-2"></i> No announcements posted at the moment.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Health Information Articles Preview -->
    <section class="py-5">
        <div class="container py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Health Information & Wellness</h3>
                    <p class="text-muted small mb-0">Educational healthcare articles prepared by clinic professionals.</p>
                </div>
                <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-primary rounded-pill mt-3 mt-md-0 fw-semibold">
                    Browse All Articles <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse($featuredArticles as $article)
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-adu h-100 overflow-hidden">
                            <div class="p-4 d-flex flex-column h-100">
                                <div class="mb-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill small">
                                        {{ $article->category }}
                                    </span>
                                </div>
                                <h5 class="fw-bold text-dark mb-2 fs-6">
                                    <a href="{{ route('articles.show', $article->slug) }}" class="text-dark text-decoration-none">
                                        {{ $article->title }}
                                    </a>
                                </h5>
                                <p class="text-muted small mb-3 flex-grow-1">
                                    {{ Str::limit($article->summary, 130) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center text-muted small pt-3 border-top mt-auto">
                                    <span><i class="bi bi-person me-1"></i> {{ $article->author_name }}</span>
                                    <span><i class="bi bi-clock me-1"></i> {{ $article->created_at->format('M d') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 text-muted">
                        No articles available currently.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- About AdU eCare+ Section -->
    <section class="py-5 border-top" style="background: rgba(2, 132, 199, 0.03);">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill fw-bold text-uppercase small border border-primary border-opacity-25">About AdU eCare+</span>
                    <h2 class="fw-bold mt-2 text-dark">Empowering University Wellness Through Technology</h2>
                    <p class="text-muted leading-relaxed mb-3">
                        AdU eCare+ is a university health management system developed using Design Thinking principles to address access constraints, long physical queues, and fragmented clinical records.
                    </p>
                    <p class="text-muted leading-relaxed mb-4">
                        The platform operates in strict adherence to Republic Act 10173 (Data Privacy Act of 2012), ensuring all personal health information, medical certificate requisitions, and teleconsultation details remain private, protected, and accessible only to verified medical personnel.
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-3 border card-adu h-100">
                                <i class="bi bi-shield-check text-success fs-4 mb-2 d-block"></i>
                                <h6 class="fw-bold mb-1">Encrypted & Private</h6>
                                <p class="text-muted small mb-0">Role-based access protects sensitive medical records.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-3 border card-adu h-100">
                                <i class="bi bi-lightning-charge text-warning fs-4 mb-2 d-block"></i>
                                <h6 class="fw-bold mb-1">Extended Clinic Coverage</h6>
                                <p class="text-muted small mb-0">Monday to Saturday: 7:00 AM to 9:00 PM.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card card-adu p-4 border shadow-sm">
                        <h5 class="fw-bold text-primary mb-3">Contact University Health Services</h5>
                        <p class="text-muted small mb-4">
                            Have questions or need assistance during clinic operating hours? Reach out through any of our official channels:
                        </p>
                        <div class="d-flex flex-column gap-3 small">
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                                <i class="bi bi-clock-fill fs-4 text-info"></i>
                                <div>
                                    <strong class="text-dark">Extended Clinic Operating Hours</strong>
                                    <div class="text-primary fw-semibold">Monday – Saturday: 7:00 AM – 9:00 PM</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                                <i class="bi bi-geo-alt fs-4 text-primary"></i>
                                <div>
                                    <strong class="text-dark">Main Clinic Address</strong>
                                    <div class="text-muted">SV Building, Ground Floor, Adamson University, 900 San Marcelino St, Ermita, Manila</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                                <i class="bi bi-telephone fs-4 text-primary"></i>
                                <div>
                                    <strong class="text-dark">Telephone Hotline</strong>
                                    <div class="text-muted">(02) 8524-2011 local 123 (Medical) / local 124 (Dental)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
