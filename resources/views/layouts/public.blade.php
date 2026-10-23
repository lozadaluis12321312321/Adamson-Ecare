@extends('layouts.base')

@section('body')
    <!-- Emergency Warning Ribbon -->
    <div class="emergency-banner">
        <i class="bi bi-exclamation-octagon-fill"></i>
        <span><strong>Notice:</strong> This platform is designed for non-emergency university healthcare services. In life-threatening emergencies, proceed immediately to the nearest hospital or contact emergency hotlines (911).</span>
    </div>

    <!-- Navigation Bar with Creative White and Light Blue Accents -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-adu sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle shadow-sm" style="width: 38px; height: 38px;">
                    <i class="bi bi-heart-pulse-fill text-primary fs-5"></i>
                </span>
                <span class="fw-bold">AdU <span class="text-info">eCare+</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}" href="{{ route('services') }}">Health Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">Health Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <!-- Dark Mode Toggle Button -->
                    <button type="button" class="btn theme-toggle-btn shadow-sm" title="Toggle Dark / Light Mode" aria-label="Toggle Theme">
                        <i class="bi bi-moon-stars-fill"></i>
                    </button>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-warning text-dark fw-semibold btn-sm px-3 rounded-pill shadow-sm">
                                <i class="bi bi-speedometer2 me-1"></i> Admin Portal
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-gradient-white btn-sm px-3 rounded-pill">
                                <i class="bi bi-person-circle me-1"></i> My Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm" title="Logout">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill fw-medium">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-gradient-primary btn-sm px-3 rounded-pill fw-semibold shadow-sm">
                            <i class="bi bi-person-plus me-1"></i> Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- University Footer -->
    <footer class="footer-adu">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle shadow-sm" style="width: 32px; height: 32px;">
                            <i class="bi bi-heart-pulse-fill text-primary"></i>
                        </span>
                        <h5 class="text-white mb-0 fw-bold">AdU eCare+</h5>
                    </div>
                    <p class="text-light small opacity-75">
                        A digital healthcare management solution for the Adamson University community. Bridging accessibility, dental scheduling, teleconsultations, and verified medical documentation for students, faculty, and personnel.
                    </p>
                    <div class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-25 p-2 small">
                        <i class="bi bi-shield-check me-1"></i> Republic Act 10173 Compliant
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('services') }}">Health Services</a></li>
                        <li><a href="{{ route('articles.index') }}">Health Articles</a></li>
                        <li><a href="{{ route('announcements.index') }}">Announcements</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6>University Healthcare</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><span class="text-light">Dental Appointments</span></li>
                        <li><span class="text-light">Teleconsultation Requests</span></li>
                        <li><span class="text-light">Medical Certificate Verification</span></li>
                        <li><span class="text-light">Clinic Health Inquiries</span></li>
                        <li><span class="text-light">Preventive Health Screenings</span></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6>Health Services Office</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0 text-light opacity-90">
                        <li><i class="bi bi-geo-alt me-2 text-info"></i> Adamson University, 900 San Marcelino St, Ermita, Manila, 1000</li>
                        <li><i class="bi bi-clock-fill me-2 text-info"></i> <strong>Monday – Saturday: 7:00 AM – 9:00 PM</strong></li>
                        <li><i class="bi bi-telephone me-2 text-info"></i> Trunkline: (02) 8524-2011 loc. 123</li>
                        <li><i class="bi bi-envelope me-2 text-info"></i> healthservices@adamson.edu.ph</li>
                    </ul>
                </div>
            </div>

            <hr class="border-secondary opacity-25">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-light opacity-75 pt-2">
                <p class="mb-2 mb-md-0">
                    &copy; {{ date('Y') }} Adamson University – Health Services Office. All Rights Reserved.
                </p>
                <p class="mb-0">
                    Clinic Hours: <strong>7:00 AM – 9:00 PM (Monday – Saturday)</strong> &bull; Design Thinking Framework.
                </p>
            </div>
        </div>
    </footer>
@endsection
