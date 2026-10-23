@extends('layouts.admin')

@section('title', 'Manage Medical Certificates | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Medical Certificate Requisitions</h2>
        <p class="text-muted small mb-0">Review student and faculty medical requests, verify supporting lab documents, assign official certificate numbers, and release credentials.</p>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card card-adu p-4 mb-4">
    <form method="GET" action="{{ route('admin.certificates.index') }}" class="row g-3 align-items-center">
        <div class="col-md-5">
            <label class="form-label small fw-semibold text-muted">Search Patient / Student</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Student name or ID number..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label small fw-semibold text-muted">Status</label>
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                <option value="additional_info_required" {{ request('status') == 'additional_info_required' ? 'selected' : '' }}>Additional Info Required</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="ready_for_release" {{ request('status') == 'ready_for_release' ? 'selected' : '' }}>Ready for Release</option>
                <option value="released" {{ request('status') == 'released' ? 'selected' : '' }}>Released</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                Filter
            </button>
            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Certificates Table -->
<div class="card card-adu p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Ref #</th>
                    <th>Patient / Requisitioner</th>
                    <th>Certificate Type</th>
                    <th>Purpose & Diagnosis</th>
                    <th>Supporting Doc</th>
                    <th>Certificate No.</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td>
                            <span class="font-monospace fw-bold text-muted">#MC-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <strong class="text-dark">{{ $req->user->name }}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ $req->user->id_number }} &bull; {{ $req->user->role_label }}
                            </div>
                        </td>
                        <td>
                            <strong class="text-dark">{{ $req->certificate_type }}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">Submitted: {{ $req->created_at->format('M d, Y') }}</div>
                        </td>
                        <td>
                            <div class="text-dark">{{ Str::limit($req->purpose_of_request, 45) }}</div>
                            @if($req->additional_information)
                                <div class="text-muted" style="font-size: 0.72rem;">{{ Str::limit($req->additional_information, 40) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($req->supporting_document_path)
                                <a href="{{ route('certificates.download', $req->id) }}" class="btn btn-sm btn-outline-primary rounded-pill py-0" style="font-size: 0.75rem;">
                                    <i class="bi bi-download me-1"></i> View File
                                </a>
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td>
                            @if($req->certificate_number)
                                <span class="badge bg-light text-primary border font-monospace fw-bold">
                                    {{ $req->certificate_number }}
                                </span>
                            @else
                                <span class="text-muted small">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $req->status_badge_class }} rounded-pill px-3 py-1">
                                {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#certModal_{{ $req->id }}">
                                Review
                            </button>
                        </td>
                    </tr>

                    <!-- Status & Review Modal -->
                    <div class="modal fade" id="certModal_{{ $req->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fw-bold">Review Medical Certificate Requisition #{{ $req->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.certificates.status', $req->id) }}">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="p-3 bg-light rounded-3 mb-3 small">
                                            <div><strong>Requisitioner:</strong> {{ $req->user->name }} ({{ $req->user->id_number }})</div>
                                            <div><strong>Classification:</strong> {{ $req->certificate_type }}</div>
                                            <div><strong>Purpose:</strong> {{ $req->purpose_of_request }}</div>
                                            @if($req->consultation_date)
                                                <div><strong>Consultation Date:</strong> {{ $req->consultation_date->format('M d, Y') }}</div>
                                            @endif
                                            @if($req->supporting_document_path)
                                                <div class="mt-2">
                                                    <a href="{{ route('certificates.download', $req->id) }}" class="btn btn-sm btn-outline-primary rounded-pill py-0">
                                                        <i class="bi bi-file-earmark-arrow-down me-1"></i> Download Supporting Lab / Excuse Attachment
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Update Processing Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-select" required>
                                                <option value="submitted" {{ $req->status == 'submitted' ? 'selected' : '' }}>Submitted (Pending Triage)</option>
                                                <option value="under_review" {{ $req->status == 'under_review' ? 'selected' : '' }}>Under Review by Physician</option>
                                                <option value="additional_info_required" {{ $req->status == 'additional_info_required' ? 'selected' : '' }}>Require Additional Documents</option>
                                                <option value="approved" {{ $req->status == 'approved' ? 'selected' : '' }}>Approved (Clinical Sign-off)</option>
                                                <option value="ready_for_release" {{ $req->status == 'ready_for_release' ? 'selected' : '' }}>Ready for Release</option>
                                                <option value="released" {{ $req->status == 'released' ? 'selected' : '' }}>Officially Released</option>
                                                <option value="rejected" {{ $req->status == 'rejected' ? 'selected' : '' }}>Reject Requisition</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Official Certificate Control Number</label>
                                            <input type="text" name="certificate_number" class="form-control" value="{{ old('certificate_number', $req->certificate_number ?? 'ADU-MC-' . date('Y') . '-' . str_pad($req->id, 4, '0', STR_PAD_LEFT)) }}" placeholder="e.g. ADU-MC-2026-0042">
                                            <div class="form-text small">Used for faculty and academic department verification.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Clinical & Administrative Notes</label>
                                            <textarea name="admin_notes" rows="3" class="form-control" placeholder="Physician verification remarks or release instructions...">{{ $req->admin_notes }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill btn-sm" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success rounded-pill btn-sm px-4 fw-semibold">Save Status</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            No medical certificate requisitions found.
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
