@extends('layouts.admin')

@section('title', 'Manage Dental Appointments | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dental Appointments Management</h2>
        <p class="text-muted small mb-0">Review requests, approve bookings, reschedule clinic slots, and record dental completion notes.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dental.schedules') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
            <i class="bi bi-clock-history me-1"></i> Configure Schedules
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card card-adu p-4 mb-4">
    <form method="GET" action="{{ route('admin.dental.index') }}" class="row g-3 align-items-center">
        <div class="col-md-4">
            <label class="form-label small fw-semibold text-muted">Search Patient / Student</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Student name or ID number..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label small fw-semibold text-muted">Status</label>
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rescheduled" {{ request('status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label small fw-semibold text-muted">Appointment Date</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                Filter
            </button>
            @if(request()->filled('search') || request()->filled('status') || request()->filled('date'))
                <a href="{{ route('admin.dental.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Appointments Table -->
<div class="card card-adu p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Ref #</th>
                    <th>Patient / Student</th>
                    <th>Date & Time</th>
                    <th>Reason / Procedure</th>
                    <th>Status</th>
                    <th>Clinic Remarks</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $app)
                    <tr>
                        <td>
                            <span class="font-monospace fw-bold text-muted">#DNT-{{ str_pad($app->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <strong class="text-dark">{{ $app->user->name }}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ $app->user->id_number }} &bull; {{ $app->user->role_label }} &bull; {{ $app->user->contact_number }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $app->appointment_date->format('M d, Y (D)') }}</div>
                            <span class="text-muted">{{ $app->appointment_time }}</span>
                            @if($app->status === 'rescheduled' && $app->rescheduled_date)
                                <div class="text-info small fw-semibold">
                                    <i class="bi bi-arrow-repeat"></i> {{ $app->rescheduled_date->format('M d, Y') }} ({{ $app->rescheduled_time }})
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $app->reason_for_visit }}</span>
                            @if($app->specific_concerns)
                                <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($app->specific_concerns, 45) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $app->status_badge_class }} rounded-pill px-3 py-1">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size: 0.78rem;">
                                {{ $app->admin_notes ? Str::limit($app->admin_notes, 35) : '—' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#dentalModal_{{ $app->id }}">
                                Update Status
                            </button>
                        </td>
                    </tr>

                    <!-- Status Update Modal for Appointment -->
                    <div class="modal fade" id="dentalModal_{{ $app->id }}" tabindex="-1" aria-labelledby="modalLabel_{{ $app->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fw-bold" id="modalLabel_{{ $app->id }}">
                                        Update Dental Booking #{{ $app->id }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.dental.status', $app->id) }}">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="p-3 bg-light rounded-3 mb-3 small">
                                            <div><strong>Patient:</strong> {{ $app->user->name }} ({{ $app->user->id_number }})</div>
                                            <div><strong>Original Slot:</strong> {{ $app->appointment_date->format('M d, Y') }} - {{ $app->appointment_time }}</div>
                                            <div><strong>Procedure:</strong> {{ $app->reason_for_visit }}</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Change Booking Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-select status-selector" data-target="resched_fields_{{ $app->id }}" required>
                                                <option value="approved" {{ $app->status == 'approved' ? 'selected' : '' }}>Approve Booking</option>
                                                <option value="rescheduled" {{ $app->status == 'rescheduled' ? 'selected' : '' }}>Reschedule Appointment</option>
                                                <option value="completed" {{ $app->status == 'completed' ? 'selected' : '' }}>Mark as Completed</option>
                                                <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>Reject Request</option>
                                                <option value="cancelled" {{ $app->status == 'cancelled' ? 'selected' : '' }}>Cancel Booking</option>
                                            </select>
                                        </div>

                                        <!-- Reschedule Inputs -->
                                        <div class="reschedule-group {{ $app->status == 'rescheduled' ? '' : 'd-none' }} p-3 bg-light rounded-3 border mb-3" id="resched_fields_{{ $app->id }}">
                                            <h6 class="fw-bold text-info small mb-2"><i class="bi bi-calendar2-range me-1"></i> Rescheduled Date & Time</h6>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label small">New Date</label>
                                                    <input type="date" name="rescheduled_date" class="form-control form-control-sm" value="{{ $app->rescheduled_date?->toDateString() }}">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">New Time Slot</label>
                                                    <input type="text" name="rescheduled_time" class="form-control form-control-sm" placeholder="e.g. 10:00 AM - 11:00 AM" value="{{ $app->rescheduled_time }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Clinical & Administrative Notes</label>
                                            <textarea name="admin_notes" rows="3" class="form-control" placeholder="Internal notes or instructions sent to the student (e.g. Bring toothbrush and valid ID)...">{{ $app->admin_notes }}</textarea>
                                            <div class="form-text small">This message will be included in the student's notification.</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary rounded-pill btn-sm px-4 fw-semibold">Save Status</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No dental appointments found matching the specified filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $appointments->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.status-selector').forEach(function (select) {
            select.addEventListener('change', function () {
                var targetId = this.getAttribute('data-target');
                var targetEl = document.getElementById(targetId);
                if (targetEl) {
                    if (this.value === 'rescheduled') {
                        targetEl.classList.remove('d-none');
                    } else {
                        targetEl.classList.add('d-none');
                    }
                }
            });
        });
    });
</script>
@endsection
