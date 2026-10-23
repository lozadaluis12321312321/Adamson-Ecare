@extends('layouts.public')

@section('title', 'Register | AdU eCare+')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-adu p-4 p-md-5 border-0 shadow-sm">
                <div class="text-center mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-info text-white rounded-circle mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-plus-fill fs-4"></i>
                    </span>
                    <h3 class="fw-bold text-dark mb-1">Create Your AdU eCare+ Account</h3>
                    <p class="text-muted small">Register as an Adamson University student or faculty/staff member</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Please resolve the following errors:
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label small fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Juan Dela Cruz">
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label small fw-semibold text-dark">University Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="e.g. juan.delacruz@adamson.edu.ph">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="role" class="form-label small fw-semibold text-dark">Role / Classification <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="" disabled selected>Select classification...</option>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="faculty_staff" {{ old('role') == 'faculty_staff' ? 'selected' : '' }}>Faculty / Staff Member</option>
                            </select>
                            <div class="form-text small" style="font-size: 0.72rem;">Admin accounts are restricted and provisioned by the Health Services Office.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="id_number" class="form-label small fw-semibold text-dark">Student / Employee ID <span class="text-danger">*</span></label>
                            <input type="text" name="id_number" id="id_number" class="form-control @error('id_number') is-invalid @enderror" value="{{ old('id_number') }}" required placeholder="e.g. 2023-14521 or EMP-2018">
                        </div>

                        <div class="col-md-4">
                            <label for="department_or_course" class="form-label small fw-semibold text-dark">Degree Program / College <span class="text-danger">*</span></label>
                            <input type="text" name="department_or_course" id="department_or_course" class="form-control @error('department_or_course') is-invalid @enderror" value="{{ old('department_or_course') }}" required placeholder="e.g. BS Nursing / CoE">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="contact_number" class="form-label small fw-semibold text-dark">Personal Mobile No. <span class="text-danger">*</span></label>
                            <input type="text" name="contact_number" id="contact_number" class="form-control @error('contact_number') is-invalid @enderror" value="{{ old('contact_number') }}" required placeholder="09XX-XXX-XXXX">
                        </div>

                        <div class="col-md-4">
                            <label for="gender" class="form-label small fw-semibold text-dark">Gender</label>
                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select gender...</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Prefer not to say" {{ old('gender') == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="birth_date" class="form-label small fw-semibold text-dark">Date of Birth</label>
                            <input type="date" name="birth_date" id="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}">
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="fw-semibold text-primary small mb-2"><i class="bi bi-telephone-plus me-1"></i> Emergency Contact Information</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="emergency_contact_name" class="form-label small fw-semibold text-dark">Emergency Contact Person & Relationship <span class="text-danger">*</span></label>
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror" value="{{ old('emergency_contact_name') }}" required placeholder="e.g. Maria Dela Cruz (Mother)">
                            </div>
                            <div class="col-md-6">
                                <label for="emergency_contact_number" class="form-label small fw-semibold text-dark">Emergency Contact Mobile <span class="text-danger">*</span></label>
                                <input type="text" name="emergency_contact_number" id="emergency_contact_number" class="form-control @error('emergency_contact_number') is-invalid @enderror" value="{{ old('emergency_contact_number') }}" required placeholder="09XX-XXX-XXXX">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label small fw-semibold text-dark">Account Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimum 8 characters">
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label small fw-semibold text-dark">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Re-enter password">
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="terms" class="form-check-input @error('terms') is-invalid @enderror" id="terms" required {{ old('terms') ? 'checked' : '' }}>
                        <label class="form-check-label small text-muted" for="terms">
                            I certify that the information provided is true and correct, and I agree to the processing of my healthcare records in compliance with the <strong>Data Privacy Act of 2012 (RA 10173)</strong>.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold mb-3">
                        <i class="bi bi-person-check me-1"></i> Complete Registration
                    </button>
                </form>

                <div class="text-center pt-3 border-top small text-muted">
                    Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Sign in here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
