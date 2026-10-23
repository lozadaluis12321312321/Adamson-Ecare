@extends('layouts.dashboard')

@section('title', 'My Dashboard | AdU eCare+')

@section('content')
<!-- Welcome Banner with Creative White and Light Blue Gradient Accent -->
<div class="card card-adu p-4 mb-4 border-2 shadow-sm">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <span class="badge bg-primary rounded-pill">{{ auth()->user()->role_label }}</span>
                <span class="text-muted small">ID: {{ auth()->user()->id_number }}</span>
                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill small">
                    <i class="bi bi-clock-fill me-1"></i> Clinic Open: Mon–Sat 7:00 AM – 9:00 PM
                </span>
            </div>
            <h2 class="fw-bold text-dark mb-1">Welcome back, <span class="gradient-text">{{ auth()->user()->name }}</span>!</h2>
            <p class="text-muted small mb-0">Here is a live summary of your university healthcare requests and upcoming clinic schedules.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('dental.create') }}" class="btn btn-gradient-primary btn-sm rounded-pill px-3 fw-semibold shadow-sm">
                <i class="bi bi-calendar-plus me-1"></i> Book Dental
            </a>
            <a href="{{ route('teleconsultation.create') }}" class="btn btn-info text-white btn-sm rounded-pill px-3 fw-semibold shadow-sm">
                <i class="bi bi-camera-video me-1"></i> Teleconsult
            </a>
            <a href="{{ route('certificates.create') }}" class="btn btn-success btn-sm rounded-pill px-3 fw-semibold shadow-sm">
                <i class="bi bi-file-earmark-plus me-1"></i> Med Cert
            </a>
            <a href="{{ route('inquiries.create') }}" class="btn btn-warning text-dark btn-sm rounded-pill px-3 fw-semibold shadow-sm">
                <i class="bi bi-chat-left-dots me-1"></i> Inquiry
            </a>
        </div>
    </div>
</div>

<!-- Four Stat Summary Cards (Computed from Real Eloquent Queries) -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold text-uppercase">Total Requests</div>
                <h3 class="fw-bold text-dark my-1">{{ $totalRequests }}</h3>
                <span class="text-muted small">All categories combined</span>
            </div>
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-folder2-open"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold text-uppercase">Pending Requests</div>
                <h3 class="fw-bold text-warning my-1">{{ $pendingRequests }}</h3>
                <span class="text-muted small">Awaiting review/action</span>
            </div>
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold text-uppercase">Approved / Released</div>
                <h3 class="fw-bold text-success my-1">{{ $approvedRequests }}</h3>
                <span class="text-muted small">Confirmed & completed</span>
            </div>
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-check2-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold text-uppercase">Upcoming Visits</div>
                <h3 class="fw-bold text-info my-1">{{ $upcomingDental->count() + $upcomingTeleconsultations->count() }}</h3>
                <span class="text-muted small">Scheduled consultations</span>
            </div>
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-calendar-event"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Column: Upcoming & Active Schedule Tracking -->
    <div class="col-lg-8">
        <!-- Upcoming Appointments Card -->
        <div class="card card-adu p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-calendar2-week text-primary me-2"></i> Upcoming Appointments & Consultations
                </h5>
                <a href="{{ route('dental.index') }}" class="btn btn-sm btn-link text-decoration-none">View All</a>
            </div>

            @if($upcomingDental->isEmpty() && $upcomingTeleconsultations->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                    <p class="mb-2">You currently have no upcoming dental or teleconsultation appointments.</p>
                    <a href="{{ route('dental.create') }}" class="btn btn-outline-primary btn-sm rounded-pill">Book an Appointment</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Type</th>
                                <th>Date & Time</th>
                                <th>Reason / Concern</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @foreach($upcomingDental as $dental)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill">
                                            <i class="bi bi-calendar2-check me-1"></i> Dental
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $dental->appointment_date->format('M d, Y') }}</strong><br>
                                        <span class="text-muted">{{ $dental->appointment_time }}</span>
                                    </td>
                                    <td>{{ $dental->reason_for_visit }}</td>
                                    <td>
                                        <span class="badge {{ $dental->status_badge_class }} rounded-pill">
                                            {{ ucfirst($dental->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('dental.show', $dental->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">Details</a>
                                    </td>
                                </tr>
                            @endforeach

                            @foreach($upcomingTeleconsultations as $tele)
                                <tr>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill">
                                            <i class="bi bi-camera-video me-1"></i> Teleconsult
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $tele->preferred_date->format('M d, Y') }}</strong><br>
                                        <span class="text-muted">{{ $tele->preferred_time }}</span>
                                    </td>
                                    <td>{{ $tele->category }}</td>
                                    <td>
                                        <span class="badge {{ $tele->status_badge_class }} rounded-pill">
                                            {{ ucfirst($tele->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('teleconsultation.show', $tele->id) }}" class="btn btn-sm btn-outline-info rounded-pill">Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Recent Requests & Status Activity -->
        <div class="card card-adu p-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-clock-history text-secondary me-2"></i> Recent Requests Overview
            </h5>

            <ul class="nav nav-pills mb-3 small" id="requestsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-3 py-1" id="dental-tab" data-bs-toggle="pill" data-bs-target="#tab-dental" type="button" role="tab">Dental ({{ $recentDental->count() }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-3 py-1" id="tele-tab" data-bs-toggle="pill" data-bs-target="#tab-tele" type="button" role="tab">Teleconsult ({{ $recentTeleconsult->count() }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-3 py-1" id="certs-tab" data-bs-toggle="pill" data-bs-target="#tab-certs" type="button" role="tab">Med Certs ({{ $recentMedCerts->count() }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-3 py-1" id="inq-tab" data-bs-toggle="pill" data-bs-target="#tab-inq" type="button" role="tab">Inquiries ({{ $recentInquiries->count() }})</button>
                </li>
            </ul>

            <div class="tab-content" id="requestsTabContent">
                <!-- Dental Tab -->
                <div class="tab-pane fade show active" id="tab-dental" role="tabpanel">
                    @forelse($recentDental as $item)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <span class="fw-semibold text-dark">{{ $item->reason_for_visit }}</span>
                                <div class="text-muted small">Date: {{ $item->appointment_date->format('M d, Y') }} ({{ $item->appointment_time }})</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $item->status_badge_class }} rounded-pill">{{ ucfirst($item->status) }}</span>
                                <a href="{{ route('dental.show', $item->id) }}" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-chevron-right"></i></a>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small py-3 text-center">No dental appointments yet.</div>
                    @endforelse
                </div>

                <!-- Teleconsult Tab -->
                <div class="tab-pane fade" id="tab-tele" role="tabpanel">
                    @forelse($recentTeleconsult as $item)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <span class="fw-semibold text-dark">{{ $item->category }}</span>
                                <div class="text-muted small">Date: {{ $item->preferred_date->format('M d, Y') }} ({{ $item->preferred_time }})</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $item->status_badge_class }} rounded-pill">{{ ucfirst($item->status) }}</span>
                                <a href="{{ route('teleconsultation.show', $item->id) }}" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-chevron-right"></i></a>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small py-3 text-center">No teleconsultation requests yet.</div>
                    @endforelse
                </div>

                <!-- Med Certs Tab -->
                <div class="tab-pane fade" id="tab-certs" role="tabpanel">
                    @forelse($recentMedCerts as $item)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <span class="fw-semibold text-dark">{{ $item->certificate_type }}</span>
                                <div class="text-muted small">Purpose: {{ Str::limit($item->purpose_of_request, 45) }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $item->status_badge_class }} rounded-pill">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                <a href="{{ route('certificates.show', $item->id) }}" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-chevron-right"></i></a>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small py-3 text-center">No medical certificate requests yet.</div>
                    @endforelse
                </div>

                <!-- Inquiries Tab -->
                <div class="tab-pane fade" id="tab-inq" role="tabpanel">
                    @forelse($recentInquiries as $item)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <span class="fw-semibold text-dark">{{ $item->subject }}</span>
                                <div class="text-muted small">{{ $item->category }} &bull; {{ $item->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $item->status_badge_class }} rounded-pill">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                <a href="{{ route('inquiries.show', $item->id) }}" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-chevron-right"></i></a>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small py-3 text-center">No health inquiries yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Campus Advisories & Notifications -->
    <div class="col-lg-4">
        <!-- Announcements Widget -->
        <div class="card card-adu p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-megaphone text-danger me-2"></i> Campus Health Advisories</h6>
                <a href="{{ route('announcements.index') }}" class="small text-decoration-none">All</a>
            </div>

            @forelse($announcements as $ann)
                <div class="mb-3 pb-3 border-bottom last-border-0">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge {{ $ann->priority_badge_class }} rounded-pill small" style="font-size: 0.65rem;">
                            {{ ucfirst($ann->priority) }}
                        </span>
                        <span class="text-muted small" style="font-size: 0.72rem;">{{ $ann->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="fw-semibold text-dark small mb-1">
                        <a href="{{ route('announcements.show', $ann->id) }}" class="text-dark text-decoration-none">
                            {{ $ann->title }}
                        </a>
                    </div>
                    <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                        {{ Str::limit($ann->content, 85) }}
                    </p>
                </div>
            @empty
                <div class="text-muted small text-center py-3">No current advisories.</div>
            @endforelse
        </div>

        <!-- Recent In-App Notifications Widget -->
        <div class="card card-adu p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-bell text-warning me-2"></i> Recent Notifications</h6>
                <a href="{{ route('notifications.index') }}" class="small text-decoration-none">View All</a>
            </div>

            @forelse($notifications as $notif)
                <div class="d-flex align-items-start gap-2 mb-3 pb-2 border-bottom last-border-0 {{ !$notif->is_read ? 'bg-light p-2 rounded-2' : '' }}">
                    <i class="bi {{ $notif->is_read ? 'bi-check-circle text-muted' : 'bi-circle-fill text-primary' }} mt-1" style="font-size: 0.6rem;"></i>
                    <div class="flex-grow-1 small">
                        <div class="fw-semibold text-dark">{{ $notif->title }}</div>
                        <div class="text-muted" style="font-size: 0.78rem;">{{ Str::limit($notif->message, 80) }}</div>
                        <div class="text-muted opacity-75" style="font-size: 0.7rem;">{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div class="text-muted small text-center py-3">No notifications.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
