@extends('layouts.admin')

@section('title', 'Manage Health Inquiries | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Health Inquiries Helpdesk</h2>
        <p class="text-muted small mb-0">Respond to student and faculty clinical questions, vaccine inquiries, and clinic schedule clarifications.</p>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card card-adu p-4 mb-4">
    <form method="GET" action="{{ route('admin.inquiries.index') }}" class="row g-3 align-items-center">
        <div class="col-md-5">
            <label class="form-label small fw-semibold text-muted">Search Inquiries</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Subject, message, or sender name..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label small fw-semibold text-muted">Status</label>
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted (Pending Response)</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="awaiting_user_response" {{ request('status') == 'awaiting_user_response' ? 'selected' : '' }}>Awaiting User Response</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                Filter
            </button>
            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Inquiries Table -->
<div class="card card-adu p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Ref #</th>
                    <th>User / Sender</th>
                    <th>Category & Subject</th>
                    <th>Latest Message Preview</th>
                    <th>Replies</th>
                    <th>Status</th>
                    <th>Date Received</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $inq)
                    <tr>
                        <td>
                            <span class="font-monospace fw-bold text-muted">#INQ-{{ str_pad($inq->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <strong class="text-dark">{{ $inq->user->name }}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ $inq->user->id_number }} &bull; {{ $inq->user->role_label }}
                            </div>
                        </td>
                        <td>
                            <strong class="text-dark">{{ $inq->subject }}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $inq->category }}</div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size: 0.78rem;">
                                {{ Str::limit($inq->message, 50) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill">
                                <i class="bi bi-chat-text me-1"></i> {{ $inq->responses->count() }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $inq->status_badge_class }} rounded-pill px-3 py-1">
                                {{ ucfirst(str_replace('_', ' ', $inq->status)) }}
                            </span>
                        </td>
                        <td>{{ $inq->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.inquiries.show', $inq->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                Reply & Manage
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            No health inquiries found matching the specified criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $inquiries->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
