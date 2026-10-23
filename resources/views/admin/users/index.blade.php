@extends('layouts.admin')

@section('title', 'User Management | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">User & Role Management</h2>
        <p class="text-muted small mb-0">Manage registered students, faculty members, and authorized health services personnel.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-person-plus-fill me-1"></i> Register New User
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card card-adu p-4 mb-4">
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, ID number, course..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-4">
            <select name="role" class="form-select">
                <option value="">All Roles / Classifications</option>
                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Students</option>
                <option value="faculty_staff" {{ request('role') == 'faculty_staff' ? 'selected' : '' }}>Faculty & Staff</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrators</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                Filter
            </button>
            @if(request()->filled('search') || request()->filled('role'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Users Table Card -->
<div class="card card-adu p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>User / Full Name</th>
                    <th>ID Number</th>
                    <th>Role Classification</th>
                    <th>Department / College</th>
                    <th>Contact Info</th>
                    <th>Account Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <strong class="text-dark">{{ $user->name }}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $user->email }}</div>
                        </td>
                        <td>
                            <span class="font-monospace fw-semibold">{{ $user->id_number ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'faculty_staff' ? 'bg-success' : 'bg-primary') }} rounded-pill">
                                {{ $user->role_label }}
                            </span>
                        </td>
                        <td>{{ $user->department_or_course ?? '—' }}</td>
                        <td>
                            <div>{{ $user->contact_number ?? '—' }}</div>
                            @if($user->emergency_contact_name)
                                <div class="text-muted" style="font-size: 0.72rem;">Emerg: {{ $user->emergency_contact_name }}</div>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Active
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill">
                                    <i class="bi bi-x-circle-fill me-1"></i> Deactivated
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill" title="Edit User">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}" onsubmit="return confirm('Toggle status for {{ $user->name }}?');" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-pill" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No users found matching your search criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
