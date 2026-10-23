@extends('layouts.base')

@section('body')
    <!-- Top Administrative Navigation Bar -->
    <header class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm" style="background: linear-gradient(135deg, #091322 0%, #0f1d36 50%, #152b4f 100%) !important;">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand me-4 d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                <span class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle shadow-sm" style="width: 34px; height: 34px;">
                    <i class="bi bi-shield-lock-fill"></i>
                </span>
                <span class="fw-bold">AdU <span class="text-info">eCare+</span> <span class="badge bg-danger ms-1 text-uppercase font-monospace" style="font-size: 0.65rem;">Staff Admin</span></span>
            </a>

            <!-- Mobile Sidebar Toggle -->
            <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminSidebarMenu" aria-controls="adminSidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="ms-auto d-flex align-items-center gap-2 gap-md-3">
                <!-- Dark Mode Toggle Button -->
                <button type="button" class="btn theme-toggle-btn shadow-sm" title="Toggle Dark / Light Mode" aria-label="Toggle Theme">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>

                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-light btn-sm rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-1">
                    <i class="bi bi-globe"></i> View Public Website
                </a>

                <!-- User Dropdown Profile -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-1 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-shield-shaded text-danger"></i>
                        <span class="d-none d-md-inline fw-semibold">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                            <div class="text-muted small">{{ auth()->user()->email }}</div>
                            <span class="badge bg-danger mt-1">Health Administrator</span>
                        </li>
                        <li><a class="dropdown-item py-2" href="{{ route('admin.settings') }}"><i class="bi bi-gear me-2 text-primary"></i> System Settings</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('admin.logs.index') }}"><i class="bi bi-journal-text me-2 text-info"></i> Activity Logs</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Dashboard Body with Administrative Sidebar -->
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <nav id="adminSidebarMenu" class="dashboard-sidebar collapse d-lg-block border-end bg-white" style="width: 280px;">
            <div class="sidebar-heading text-uppercase fw-bold text-muted" style="font-size: 0.72rem;">Operations Overview</div>
            <ul class="nav flex-column sidebar-nav mb-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Admin Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people-fill"></i> Users & Roles
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading text-uppercase fw-bold text-muted" style="font-size: 0.72rem;">Clinical Management</div>
            <ul class="nav flex-column sidebar-nav mb-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dental.index') ? 'active' : '' }}" href="{{ route('admin.dental.index') }}">
                        <i class="bi bi-calendar2-heart"></i> Dental Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dental.schedules*') ? 'active' : '' }}" href="{{ route('admin.dental.schedules') }}">
                        <i class="bi bi-clock-history"></i> Appointment Schedules
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.teleconsultation.*') ? 'active' : '' }}" href="{{ route('admin.teleconsultation.index') }}">
                        <i class="bi bi-camera-video"></i> Teleconsultations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}" href="{{ route('admin.certificates.index') }}">
                        <i class="bi bi-file-earmark-medical"></i> Medical Certificates
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}" href="{{ route('admin.inquiries.index') }}">
                        <i class="bi bi-chat-left-dots"></i> Health Inquiries
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading text-uppercase fw-bold text-muted" style="font-size: 0.72rem;">Content Publishing</div>
            <ul class="nav flex-column sidebar-nav mb-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}" href="{{ route('admin.articles.index') }}">
                        <i class="bi bi-journal-plus"></i> Health Articles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">
                        <i class="bi bi-megaphone-fill"></i> Announcements
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading text-uppercase fw-bold text-muted" style="font-size: 0.72rem;">Analytics & Audit</div>
            <ul class="nav flex-column sidebar-nav mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="bi bi-bar-chart-line-fill"></i> Reports & Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" href="{{ route('admin.logs.index') }}">
                        <i class="bi bi-shield-check"></i> Activity Logs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                        <i class="bi bi-sliders"></i> System Settings
                    </a>
                </li>
            </ul>

            <div class="mt-auto pt-3 border-top">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content Column -->
        <main class="dashboard-main">
            @yield('content')
        </main>
    </div>
@endsection
