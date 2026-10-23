@extends('layouts.admin')

@section('title', 'Manage Teleconsultations | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Teleconsultations Management</h2>
        <p class="text-muted small mb-0">Review remote healthcare requests, assign attending medical staff, provide video meeting links, and update statuses.</p>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card card-adu p-4 mb-4">
    <form method="GET" action="{{ route('admin.teleconsultation.index') }}" class="row g-3 align-items-center">
        <div class="col-md-5">
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
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="col-md-4 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                Filter
            </button>
            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('admin.teleconsultation.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Teleconsultations Table -->
<div class="card card-adu p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Ref #</th>
                    <th>Patient / Student</th>
                    <th>Date & Preferred Window</th>
                    <th>Category & Concern</th>
                    <th>Contact Method</th>
                    <th>Assigned Staff / Meeting</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td>
                            <span class="font-monospace fw-bold text-muted">#TEL-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <strong class="text-dark">{{ $req->user->name }}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ $req->user->id_number }} &bull; {{ $req->user->role_label }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $req->preferred_date->format('M d, Y') }}</div>
                            <span class="text-muted">{{ $req->preferred_time }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $req->category }}</span>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($req->description, 45) }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $req->preferred_contact_method }}</span>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $req->contact_details }}</div>
                        </td>
                        <td>
                            @if($req->meeting_link)
                                <a href="{{ $req->meeting_link }}" target="_blank" class="badge bg-success text-decoration-none" title="{{ $req->meeting_link }}">
                                    <i class="bi bi-camera-video me-1"></i> Meeting Link
                                </a>
                            @endif
                            <div class="text-dark small fw-semibold">{{ $req->assigned_personnel ?? 'Unassigned' }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $req->status_badge_class }} rounded-pill px-3 py-1">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-info text-white rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#teleModal_{{ $req->id }}">
                                Update
                            </button>
                        </td>
                    </tr>

                    <!-- Status Update Modal -->
                    <div class="modal fade" id="teleModal_{{ $req->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fw-bold">Manage Teleconsultation #{{ $req->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.teleconsultation.status', $req->id) }}">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="p-3 bg-light rounded-3 mb-3 small">
                                            <div><strong>Patient:</strong> {{ $req->user->name }} ({{ $req->user->id_number }})</div>
                                            <div><strong>Category:</strong> {{ $req->category }}</div>
                                            <div><strong>Preferred:</strong> {{ $req->preferred_date->format('M d, Y') }} ({{ $req->preferred_time }})</div>
                                            <div><strong>Description:</strong> {{ $req->description }}</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Update Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-select" required>
                                                <option value="approved" {{ $req->status == 'approved' ? 'selected' : '' }}>Approve Request</option>
                                                <option value="scheduled" {{ $req->status == 'scheduled' ? 'selected' : '' }}>Schedule Consultation</option>
                                                <option value="completed" {{ $req->status == 'completed' ? 'selected' : '' }}>Mark as Completed</option>
                                                <option value="rejected" {{ $req->status == 'rejected' ? 'selected' : '' }}>Reject Request</option>
                                                <option value="cancelled" {{ $req->status == 'cancelled' ? 'selected' : '' }}>Cancel Consultation</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Video Meeting Link (Google Meet / Zoom URL)</label>
                                            <input type="url" name="meeting_link" class="form-control" value="{{ $req->meeting_link }}" placeholder="https://meet.google.com/xxx-xxxx-xxx">
                                            <div class="form-text small">Enter full URL for external virtual consultation. Visible to patient once set.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Assigned Healthcare Personnel</label>
                                            <input type="text" name="assigned_personnel" class="form-control" value="{{ $req->assigned_personnel ?? 'Dr. Maria Ramos, MD' }}" placeholder="e.g. Dr. Maria Ramos, MD (University Physician)">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Administrative / Clinical Notes</label>
                                            <textarea name="admin_notes" rows="3" class="form-control" placeholder="Preparation notes or clinical remarks...">{{ $req->admin_notes }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-info text-white rounded-pill btn-sm px-4 fw-semibold">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            No teleconsultation requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $requests->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
