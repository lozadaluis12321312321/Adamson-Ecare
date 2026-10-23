@extends('layouts.public')

@section('title', 'Login | AdU eCare+')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="card card-adu p-4 p-md-5 border-0 shadow-sm">
                <div class="text-center mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-fill fs-4"></i>
                    </span>
                    <h3 class="fw-bold text-dark mb-1">Welcome to AdU eCare+</h3>
                    <p class="text-muted small">Sign in to your university healthcare account</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-1"></i>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold text-dark">Institutional Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="name@adamson.edu.ph">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label small fw-semibold text-dark mb-0">Password</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Enter your password">
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Keep me signed in</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold mb-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                    </button>
                </form>

                <div class="text-center pt-3 border-top small text-muted">
                    Don't have an account yet? <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">Register here</a>
                </div>

                <!-- Demonstration Quick Fill Buttons for Testing -->
                <div class="mt-4 p-3 bg-light rounded-3 border">
                    <div class="small fw-bold text-muted mb-2 text-uppercase" style="font-size: 0.72rem;">
                        <i class="bi bi-key-fill text-warning me-1"></i> Demo Accounts (1-Click Fill)
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill flex-fill" onclick="fillCreds('admin@adamson.edu.ph', 'Admin@AdU2026')">
                            Admin Staff
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill flex-fill" onclick="fillCreds('student@adamson.edu.ph', 'Student@AdU2026')">
                            Student (Juan)
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill flex-fill" onclick="fillCreds('faculty@adamson.edu.ph', 'Faculty@AdU2026')">
                            Faculty Staff
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fillCreds(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
