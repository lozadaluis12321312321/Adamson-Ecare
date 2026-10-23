@extends('layouts.admin')

@section('title', 'Register User | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Register User</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark mb-1">Provision New User Account</h2>
    <p class="text-muted small">Create an authenticated student, faculty, or health services administrator profile.</p>
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

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-adu p-4 p-md-5">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Dr. Jane Smith or Juan Dela Cruz">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Institutional Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="user@adamson.edu.ph">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Role Authorization <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="faculty_staff" {{ old('role') == 'faculty_staff' ? 'selected' : '' }}>Faculty / Staff</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Health Services Administrator</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">ID Number <span class="text-danger">*</span></label>
                        <input type="text" name="id_number" class="form-control @error('id_number') is-invalid @enderror" value="{{ old('id_number') }}" required placeholder="e.g. 2024-99881 or EMP-2024">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Department / College / Course <span class="text-danger">*</span></label>
                        <input type="text" name="department_or_course" class="form-control @error('department_or_course') is-invalid @enderror" value="{{ old('department_or_course') }}" required placeholder="e.g. Health Services Office or BS Pharmacy">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror" value="{{ old('contact_number') }}" required placeholder="09XX-XXX-XXXX">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}" placeholder="Relative / Guardian Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Emergency Contact Number</label>
                        <input type="text" name="emergency_contact_number" class="form-control" value="{{ old('emergency_contact_number') }}" placeholder="09XX-XXX-XXXX">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-dark">Initial Account Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimum 8 characters">
                    <div class="form-text small">Provide a secure initial password. The user can update their password from their profile settings.</div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                        <i class="bi bi-person-check-fill me-1"></i> Provision Account
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
