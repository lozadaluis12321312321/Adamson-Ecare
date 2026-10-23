@extends('layouts.admin')

@section('title', 'Administrative Dashboard | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-danger rounded-pill">Health Administrator</span>
            <span class="text-muted small">Adamson University Health Services Office</span>
        </div>
        <h2 class="fw-bold text-dark mb-0">Clinic Operations Dashboard</h2>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.dental.schedules') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
            <i class="bi bi-clock-history me-1"></i> Add Dental Slot
        </a>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-danger btn-sm rounded-pill px-3 fw-semibold">
            <i class="bi bi-megaphone-fill me-1"></i> Post Announcement
        </a>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-semibold">
            <i class="bi bi-journal-plus me-1"></i> New Health Article
        </a>
    </div>
</div>

<!-- Key Metric Summary Cards -->
<div class="row g-3 mb-4">
    <!-- Total Users -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Total Users</div>
                <h4 class="fw-bold text-dark my-1">{{ $totalUsers }}</h4>
                <span class="text-muted small" style="font-size: 0.72rem;">{{ $totalStudents }} stu / {{ $totalFacultyStaff }} fac</span>
            </div>
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>

    <!-- Pending Dental Appointments -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Dental Pending</div>
                <h4 class="fw-bold text-warning my-1">{{ $pendingDental }}</h4>
                <a href="{{ route('admin.dental.index', ['status' => 'pending']) }}" class="small text-decoration-none">Review &raquo;</a>
            </div>
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-calendar2-heart"></i>
            </div>
        </div>
    </div>

    <!-- Pending Teleconsultations -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Teleconsults</div>
                <h4 class="fw-bold text-info my-1">{{ $pendingTeleconsultations }}</h4>
                <a href="{{ route('admin.teleconsultation.index', ['status' => 'pending']) }}" class="small text-decoration-none">Review &raquo;</a>
            </div>
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-camera-video"></i>
            </div>
        </div>
    </div>

    <!-- Pending Med Certs -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Med Certs</div>
                <h4 class="fw-bold text-success my-1">{{ $pendingMedCerts }}</h4>
                <a href="{{ route('admin.certificates.index', ['status' => 'submitted']) }}" class="small text-decoration-none">Review &raquo;</a>
            </div>
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-file-earmark-medical"></i>
            </div>
        </div>
    </div>

    <!-- Unresolved Inquiries -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Inquiries</div>
                <h4 class="fw-bold text-danger my-1">{{ $unresolvedInquiries }}</h4>
                <a href="{{ route('admin.inquiries.index', ['status' => 'submitted']) }}" class="small text-decoration-none">Review &raquo;</a>
            </div>
            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                <i class="bi bi-chat-dots"></i>
            </div>
        </div>
    </div>

    <!-- Announcements -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card">
            <div>
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.7rem;">Announcements</div>
                <h4 class="fw-bold text-dark my-1">{{ $totalAnnouncements }}</h4>
                <a href="{{ route('admin.announcements.index') }}" class="small text-decoration-none">Manage &raquo;</a>
            </div>
            <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                <i class="bi bi-megaphone"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Column: Pending Clinical Actions -->
    <div class="col-lg-8">
        <!-- Dental Appointments Pending Action -->
        <div class="card card-adu p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-calendar2-check text-primary me-2"></i> Recent Dental Appointment Requests
                </h5>
                <a href="{{ route('admin.dental.index') }}" class="small text-decoration-none">Manage All</a>
            </div>

            @if($recentDental->isEmpty())
                <p class="text-muted small text-center py-3 mb-0">No dental appointment requests.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Patient / Student</th>
                                <th>Schedule Date & Time</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDental as $app)
                                <tr>
                                    <td>
                                        <strong class="text-dark">{{ $app->user->name }}</strong>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $app->user->id_number }} &bull; {{ $app->user->department_or_course }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $app->appointment_date->format('M d, Y') }}</div>
                                        <span class="text-muted">{{ $app->appointment_time }}</span>
                                    </td>
                                    <td>{{ $app->reason_for_visit }}</td>
                                    <td>
                                        <span class="badge {{ $app->status_badge_class }} rounded-pill">{{ ucfirst($app->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.dental.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Manage</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Teleconsultation & Med Certs Quick Overview Tabs -->
        <div class="card card-adu p-4">
            <ul class="nav nav-pills mb-3 small" id="adminQuickTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-3 py-1" id="tab-tele-btn" data-bs-toggle="pill" data-bs-target="#admin-tele" type="button" role="tab">Teleconsultations ({{ $recentTeleconsult->count() }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-3 py-1" id="tab-certs-btn" data-bs-toggle="pill" data-bs-target="#admin-certs" type="button" role="tab">Med Certs ({{ $recentMedCerts->count() }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-3 py-1" id="tab-inq-btn" data-bs-toggle="pill" data-bs-target="#admin-inq" type="button" role="tab">Inquiries ({{ $recentInquiries->count() }})</button>
                </li>
            </ul>

            <div class="tab-content" id="adminQuickTabsContent">
                <!-- Teleconsultations Tab -->
                <div class="tab-pane fade show active" id="admin-tele" role="tabpanel">
                    @forelse($recentTeleconsult as $tc)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                            <div>
                                <strong class="text-dark">{{ $tc->user->name }}</strong> ({{ $tc->user->id_number }})
                                <div class="text-muted">{{ $tc->category }} &bull; {{ $tc->preferred_date->format('M d, Y') }} ({{ $tc->preferred_time }})</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $tc->status_badge_class }} rounded-pill">{{ ucfirst($tc->status) }}</span>
                                <a href="{{ route('admin.teleconsultation.index') }}" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-chevron-right"></i></a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small text-center py-3 mb-0">No teleconsultation requests.</p>
                    @endforelse
                </div>

                <!-- Certificates Tab -->
                <div class="tab-pane fade" id="admin-certs" role="tabpanel">
                    @forelse($recentMedCerts as $mc)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                            <div>
                                <strong class="text-dark">{{ $mc->user->name }}</strong> &bull; {{ $mc->certificate_type }}
                                <div class="text-muted">Purpose: {{ Str::limit($mc->purpose_of_request, 45) }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $mc->status_badge_class }} rounded-pill">{{ ucfirst(str_replace('_', ' ', $mc->status)) }}</span>
                                <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-chevron-right"></i></a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small text-center py-3 mb-0">No medical certificate requests.</p>
                    @endforelse
                </div>

                <!-- Inquiries Tab -->
                <div class="tab-pane fade" id="admin-inq" role="tabpanel">
                    @forelse($recentInquiries as $iq)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                            <div>
                                <strong class="text-dark">{{ $iq->subject }}</strong> &bull; {{ $iq->user->name }}
                                <div class="text-muted">{{ $iq->category }} &bull; {{ $iq->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $iq->status_badge_class }} rounded-pill">{{ ucfirst(str_replace('_', ' ', $iq->status)) }}</span>
                                <a href="{{ route('admin.inquiries.show', $iq->id) }}" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-chevron-right"></i></a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small text-center py-3 mb-0">No health inquiries.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Audit Logs & Administrative Shortcuts -->
    <div class="col-lg-4">
        <!-- Quick Administrative Shortcuts -->
        <div class="card card-adu p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-lightning-charge text-warning me-2"></i> Quick Administrative Shortcuts
            </h6>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary btn-sm text-start rounded-pill">
                    <i class="bi bi-person-plus me-2"></i> Register New User Account
                </a>
                <a href="{{ route('admin.dental.schedules') }}" class="btn btn-outline-info text-dark btn-sm text-start rounded-pill">
                    <i class="bi bi-calendar-plus me-2"></i> Configure Clinic Shift Schedules
                </a>
                <a href="{{ route('admin.announcements.create') }}" class="btn btn-outline-danger btn-sm text-start rounded-pill">
                    <i class="bi bi-megaphone me-2"></i> Publish Campus Health Advisory
                </a>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-success btn-sm text-start rounded-pill">
                    <i class="bi bi-file-earmark-bar-graph me-2"></i> View Monthly Clinical Reports
                </a>
            </div>
        </div>

        <!-- Recent Activity Audit Logs -->
        <div class="card card-adu p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-journal-text text-secondary me-2"></i> System Activity Trail
                </h6>
                <a href="{{ route('admin.logs.index') }}" class="small text-decoration-none">All Logs</a>
            </div>

            @forelse($recentLogs as $log)
                <div class="mb-3 pb-2 border-bottom last-border-0 small">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge bg-light text-dark border rounded-pill" style="font-size: 0.68rem;">{{ $log->module }}</span>
                        <span class="text-muted" style="font-size: 0.72rem;">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-dark fw-semibold" style="font-size: 0.8rem;">{{ $log->action }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($log->description, 70) }}</div>
                </div>
            @empty
                <p class="text-muted small text-center py-3 mb-0">No logged activity yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
