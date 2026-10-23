@extends('layouts.dashboard')

@section('title', 'Teleconsultation Requests | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Teleconsultation Requests</h2>
        <p class="text-muted small mb-0">Consult with university physicians and counselors remotely via secure video links or telephone.</p>
    </div>
    <a href="{{ route('teleconsultation.create') }}" class="btn btn-info text-white rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-camera-video me-1"></i> Request Teleconsultation
    </a>
</div>

<!-- Status Filters -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('teleconsultation.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
        All Consultations
    </a>
    <a href="{{ route('teleconsultation.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill">
        Pending
    </a>
    <a href="{{ route('teleconsultation.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill">
        Approved
    </a>
    <a href="{{ route('teleconsultation.index', ['status' => 'scheduled']) }}" class="btn btn-sm {{ request('status') == 'scheduled' ? 'btn-info text-dark' : 'btn-outline-secondary' }} rounded-pill">
        Scheduled
    </a>
    <a href="{{ route('teleconsultation.index', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') == 'completed' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
        Completed
    </a>
</div>

<div class="card card-adu p-4">
    @if($requests->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-camera-video-off fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-dark">No teleconsultation requests found</h5>
            <p class="text-muted small mb-3">You do not have any teleconsultations matching this filter.</p>
            <a href="{{ route('teleconsultation.create') }}" class="btn btn-info text-white btn-sm rounded-pill">Request Teleconsultation</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>Date & Time Window</th>
                        <th>Health Concern Category</th>
                        <th>Contact Method</th>
                        <th>Status</th>
                        <th>Assigned Staff / Meeting</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @foreach($requests as $req)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $req->preferred_date->format('M d, Y (D)') }}</strong>
                                <div class="text-muted">{{ $req->preferred_time }}</div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $req->category }}</span>
                                <div class="text-muted small text-truncate" style="max-width: 240px;">
                                    {{ $req->description }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill">
                                    {{ $req->preferred_contact_method }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $req->status_badge_class }} rounded-pill px-3 py-1">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td>
                                @if($req->meeting_link)
                                    <a href="{{ $req->meeting_link }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-2 py-0 fw-semibold">
                                        <i class="bi bi-camera-video-fill me-1"></i> Join Call
                                    </a>
                                @elseif($req->assigned_personnel)
                                    <span class="text-muted">{{ $req->assigned_personnel }}</span>
                                @else
                                    <span class="text-muted italic">Pending assignment</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('teleconsultation.show', $req->id) }}" class="btn btn-sm btn-outline-info rounded-pill">
                                        Details
                                    </a>
                                    @if(in_array($req->status, ['pending', 'approved', 'scheduled']))
                                        <form method="POST" action="{{ route('teleconsultation.cancel', $req->id) }}" onsubmit="return confirm('Cancel this teleconsultation request?');" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
