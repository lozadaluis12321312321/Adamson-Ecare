@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.dashboard')

@section('title', 'My Profile | AdU eCare+')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold text-dark mb-1">My Account Profile</h2>
    <p class="text-muted small">Manage your contact records and healthcare account credentials.</p>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> Please check the form errors below.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Profile & Healthcare Details</h5>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted">Email Address (Read-only)</label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">ID Number</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->id_number }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Classification</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->role_label }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Degree / Office</label>
                        <input type="text" name="department_or_course" class="form-control" value="{{ old('department_or_course', $user->department_or_course) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Contact Mobile</label>
                        <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $user->contact_number) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select gender...</option>
                            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Prefer not to say" {{ old('gender', $user->gender) == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Date of Birth</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $user->birth_date?->toDateString()) }}">
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 border mb-4">
                    <h6 class="fw-bold text-primary small mb-3">Emergency Contact Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Contact Person & Relationship</label>
                            <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Emergency Mobile</label>
                            <input type="text" name="emergency_contact_number" class="form-control" value="{{ old('emergency_contact_number', $user->emergency_contact_number) }}" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                    <i class="bi bi-check-circle me-1"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    <!-- Password Change Column -->
    <div class="col-lg-5">
        <div class="card card-adu p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Security & Password</h5>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required placeholder="Enter current password">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimum 8 characters">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-muted">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Confirm new password">
                </div>

                <button type="submit" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
                    <i class="bi bi-shield-lock me-1"></i> Update Password
                </button>
            </form>
        </div>

        <div class="card bg-light border p-3 rounded-3 small">
            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check text-success me-1"></i> Privacy Notice</h6>
            <p class="text-muted mb-0">
                Your emergency contact and health profile information are safeguarded in compliance with RA 10173 and are accessible only to authorized clinic health officers during urgent incidents.
            </p>
        </div>
    </div>
</div>
@endsection
