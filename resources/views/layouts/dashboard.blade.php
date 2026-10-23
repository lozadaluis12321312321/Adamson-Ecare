@extends('layouts.base')

@section('body')
    <!-- Top Navigation Bar -->
    <header class="navbar navbar-expand-lg navbar-dark navbar-adu sticky-top">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand me-4" href="{{ route('dashboard') }}">
                <span class="d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle shadow-sm" style="width: 34px; height: 34px;">
                    <i class="bi bi-heart-pulse-fill text-primary"></i>
                </span>
                <span class="fw-bold">AdU <span class="text-info">eCare+</span></span>
            </a>

            <!-- Mobile Sidebar Toggle -->
            <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="ms-auto d-flex align-items-center gap-2 gap-md-3">
                <!-- Dark Mode Toggle Button -->
                <button type="button" class="btn theme-toggle-btn shadow-sm" title="Toggle Dark / Light Mode" aria-label="Toggle Theme">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>

                <!-- Notifications Bell -->
                @php
                    $unreadCount = auth()->user()->unreadNotifications()->count();
                @endphp
                <a href="{{ route('notifications.index') }}" class="btn btn-outline-light btn-sm position-relative rounded-circle p-2 shadow-sm" title="Notifications" style="width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;">
                    <i class="bi bi-bell-fill"></i>
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <!-- User Dropdown Profile -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-1 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-6"></i>
                        <span class="d-none d-md-inline fw-semibold">{{ auth()->user()->name }}</span>
                        <span class="badge bg-info text-dark ms-1 d-none d-md-inline-block">{{ auth()->user()->role_label }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                            <div class="text-muted small">{{ auth()->user()->id_number ?? auth()->user()->email }}</div>
                            <span class="badge bg-primary mt-1">{{ auth()->user()->role_label }}</span>
                        </li>
                        <li><a class="dropdown-item py-2" href="{{ route('profile.show') }}"><i class="bi bi-person me-2 text-primary"></i> My Profile</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('notifications.index') }}"><i class="bi bi-bell me-2 text-warning"></i> Notifications ({{ $unreadCount }})</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('home') }}" target="_blank"><i class="bi bi-box-arrow-up-right me-2 text-info"></i> Public Portal</a></li>
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

    <!-- Emergency notice bar -->
    <div class="emergency-banner py-1 small">
        <i class="bi bi-info-circle-fill"></i>
        <span>Clinic Hours: <strong>7:00 AM – 9:00 PM (Monday to Saturday)</strong> &bull; For acute emergencies, call 911 or visit the emergency department immediately.</span>
    </div>

    <!-- Main Dashboard Body with Sidebar -->
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <nav id="sidebarMenu" class="dashboard-sidebar collapse d-lg-block">
            <div class="sidebar-heading">Navigation</div>
            <ul class="nav flex-column sidebar-nav mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}" href="{{ route('services') }}">
                        <i class="bi bi-grid-fill"></i> Health Services
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading">Healthcare Requests</div>
            <ul class="nav flex-column sidebar-nav mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dental.*') ? 'active' : '' }}" href="{{ route('dental.index') }}">
                        <i class="bi bi-calendar2-check"></i> Dental Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('teleconsultation.*') ? 'active' : '' }}" href="{{ route('teleconsultation.index') }}">
                        <i class="bi bi-camera-video"></i> Teleconsultation
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('certificates.*') ? 'active' : '' }}" href="{{ route('certificates.index') }}">
                        <i class="bi bi-file-earmark-medical"></i> Medical Certificates
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('inquiries.*') ? 'active' : '' }}" href="{{ route('inquiries.index') }}">
                        <i class="bi bi-chat-dots"></i> My Inquiries
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading">Information & Updates</div>
            <ul class="nav flex-column sidebar-nav mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">
                        <i class="bi bi-journal-medical"></i> Health Information
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">
                        <i class="bi bi-megaphone"></i> Announcements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                        <i class="bi bi-bell"></i> Notifications
                        @if($unreadCount > 0)
                            <span class="badge bg-danger ms-auto rounded-pill">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading">Account</div>
            <ul class="nav flex-column sidebar-nav mt-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                        <i class="bi bi-person-gear"></i> My Profile
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent text-danger">
                            <i class="bi bi-box-arrow-right text-danger"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Main Content Column -->
        <main class="dashboard-main">
            @yield('content')
        </main>
    </div>
@endsection
