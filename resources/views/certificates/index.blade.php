@extends('layouts.dashboard')

@section('title', 'Medical Certificates | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Medical Certificate Requisitions</h2>
        <p class="text-muted small mb-0">Requisition, track, and verify official university medical certificates, excuse slips, and clearances.</p>
    </div>
    <a href="{{ route('certificates.create') }}" class="btn btn-success rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-file-earmark-plus me-1"></i> New Certificate Requisition
    </a>
</div>

<!-- Status Filters -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('certificates.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
        All Requisitions
    </a>
    <a href="{{ route('certificates.index', ['status' => 'submitted']) }}" class="btn btn-sm {{ request('status') == 'submitted' ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill">
        Submitted
    </a>
    <a href="{{ route('certificates.index', ['status' => 'under_review']) }}" class="btn btn-sm {{ request('status') == 'under_review' ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill">
        Under Review
    </a>
    <a href="{{ route('certificates.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') == 'approved' ? 'btn-info text-dark' : 'btn-outline-secondary' }} rounded-pill">
        Approved
    </a>
    <a href="{{ route('certificates.index', ['status' => 'ready_for_release']) }}" class="btn btn-sm {{ request('status') == 'ready_for_release' ? 'btn-info text-dark' : 'btn-outline-secondary' }} rounded-pill">
        Ready for Release
    </a>
    <a href="{{ route('certificates.index', ['status' => 'released']) }}" class="btn btn-sm {{ request('status') == 'released' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill">
        Released
    </a>
</div>

<div class="card card-adu p-4">
    @if($requests->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-x fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-dark">No medical certificate requests found</h5>
            <p class="text-muted small mb-3">You don't have any certificate requisitions matching this filter.</p>
            <a href="{{ route('certificates.create') }}" class="btn btn-success btn-sm rounded-pill">Requisition a Certificate</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>Certificate Type</th>
                        <th>Purpose of Request</th>
                        <th>Consultation Date</th>
                        <th>Status</th>
                        <th>Certificate No.</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @foreach($requests as $req)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $req->certificate_type }}</strong>
                                <div class="text-muted small">Submitted: {{ $req->created_at->format('M d, Y') }}</div>
                            </td>
                            <td>
                                <span class="text-dark">{{ Str::limit($req->purpose_of_request, 50) }}</span>
                                @if($req->supporting_document_path)
                                    <div class="text-muted small">
                                        <i class="bi bi-paperclip text-primary me-1"></i> Document attached
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{ $req->consultation_date ? $req->consultation_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td>
                                <span class="badge {{ $req->status_badge_class }} rounded-pill px-3 py-1">
                                    {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($req->certificate_number)
                                    <span class="badge bg-light text-primary border font-monospace fw-bold">
                                        {{ $req->certificate_number }}
                                    </span>
                                @else
                                    <span class="text-muted small italic">Pending verification</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('certificates.show', $req->id) }}" class="btn btn-sm btn-outline-success rounded-pill">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $requests->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
