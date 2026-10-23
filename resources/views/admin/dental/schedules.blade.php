@extends('layouts.admin')

@section('title', 'Dental Clinic Schedules | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dental.index') }}">Dental Appointments</a></li>
            <li class="breadcrumb-item active" aria-current="page">Schedules Configuration</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dental Clinic Available Schedules</h2>
            <p class="text-muted small mb-0">Configure available shift dates, time slots, and patient capacities for student bookings.</p>
        </div>
        <a href="{{ route('admin.dental.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Appointments
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> Please check errors:
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4 mb-4">
    <!-- Add New Schedule Form -->
    <div class="col-lg-4">
        <div class="card card-adu p-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-plus-circle text-primary me-2"></i> Add Dental Schedule Slot
            </h5>

            <form method="POST" action="{{ route('admin.dental.schedules.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Clinic Date <span class="text-danger">*</span></label>
                    <input type="date" name="schedule_date" class="form-control form-control-sm" value="{{ old('schedule_date', now()->addDay()->toDateString()) }}" min="{{ now()->toDateString() }}" required>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-dark">Start Time <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" class="form-control form-control-sm" value="{{ old('start_time', '09:00') }}" required>
                    </div>

                    <div class="col-6">
                        <label class="form-label small fw-semibold text-dark">End Time <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" class="form-control form-control-sm" value="{{ old('end_time', '10:00') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Maximum Patient Slots <span class="text-danger">*</span></label>
                    <input type="number" name="max_slots" class="form-control form-control-sm" value="{{ old('max_slots', 4) }}" min="1" max="20" required>
                    <div class="form-text small">Standard shift slot capacity (usually 4 patients per hour).</div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-dark">Internal Clinic Notes</label>
                    <input type="text" name="notes" class="form-control form-control-sm" value="{{ old('notes', 'Room 204 Clinic Shift') }}" placeholder="e.g. Room 204 Dental Clinic">
                </div>

                <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100 fw-semibold py-2">
                    <i class="bi bi-calendar-plus me-1"></i> Add Schedule Slot
                </button>
            </form>
        </div>
    </div>

    <!-- Active Schedules Table -->
    <div class="col-lg-8">
        <div class="card card-adu p-4">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Configured Clinic Schedules</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Clinic Date</th>
                            <th>Time Slot</th>
                            <th>Capacity / Booked</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $sched)
                            <tr>
                                <td>
                                    <strong class="text-dark">{{ $sched->schedule_date->format('M d, Y (D)') }}</strong>
                                    @if($sched->schedule_date < now()->toDateString())
                                        <span class="badge bg-secondary ms-1 small">Past</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-primary fw-semibold">
                                        {{ date('h:i A', strtotime($sched->start_time)) }} – {{ date('h:i A', strtotime($sched->end_time)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="fw-bold text-dark">{{ $sched->bookedCount() }}</span>
                                        <span class="text-muted">/ {{ $sched->max_slots }}</span>
                                        @if($sched->isFullyBooked())
                                            <span class="badge bg-danger rounded-pill ms-1" style="font-size: 0.65rem;">Full</span>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill ms-1" style="font-size: 0.65rem;">{{ $sched->availableSlots() }} open</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($sched->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill">Active</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $sched->notes ?? '—' }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <form method="POST" action="{{ route('admin.dental.schedules.toggle', $sched->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $sched->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-pill py-0" style="font-size: 0.75rem;">
                                                {{ $sched->is_active ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.dental.schedules.destroy', $sched->id) }}" onsubmit="return confirm('Delete this schedule slot?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill py-0" style="font-size: 0.75rem;" {{ $sched->bookedCount() > 0 ? 'disabled title="Cannot delete schedule with active bookings"' : '' }}>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No schedules configured yet. Use the form on the left to add slots.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $schedules->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
