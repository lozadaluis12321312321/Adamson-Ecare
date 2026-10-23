@extends('layouts.dashboard')

@section('title', 'Health Inquiries | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Health Inquiries & Helpdesk</h2>
        <p class="text-muted small mb-0">Ask questions, clarify clinic requirements, and interact with Adamson University Health Services staff.</p>
    </div>
    <a href="{{ route('inquiries.create') }}" class="btn btn-warning text-dark rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-chat-plus me-1"></i> Send New Inquiry
    </a>
</div>

<!-- Status Filters -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('inquiries.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
        All Inquiries
    </a>
    <a href="{{ route('inquiries.index', ['status' => 'submitted']) }}" class="btn btn-sm {{ request('status') == 'submitted' ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill">
        Submitted
    </a>
    <a href="{{ route('inquiries.index', ['status' => 'in_progress']) }}" class="btn btn-sm {{ request('status') == 'in_progress' ? 'btn-info text-dark' : 'btn-outline-secondary' }} rounded-pill">
        In Progress
    </a>
    <a href="{{ route('inquiries.index', ['status' => 'awaiting_user_response']) }}" class="btn btn-sm {{ request('status') == 'awaiting_user_response' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
        Awaiting Your Reply
    </a>
    <a href="{{ route('inquiries.index', ['status' => 'resolved']) }}" class="btn btn-sm {{ request('status') == 'resolved' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill">
        Resolved
    </a>
</div>

<div class="card card-adu p-4">
    @if($inquiries->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-chat-left-dots fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-dark">No inquiries found</h5>
            <p class="text-muted small mb-3">You don't have any health inquiries matching this filter.</p>
            <a href="{{ route('inquiries.create') }}" class="btn btn-warning text-dark btn-sm rounded-pill">Send a Health Inquiry</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>Subject & Category</th>
                        <th>Initial Message</th>
                        <th>Responses</th>
                        <th>Status</th>
                        <th>Date Sent</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @foreach($inquiries as $inq)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $inq->subject }}</strong>
                                <div class="text-muted small">{{ $inq->category }}</div>
                            </td>
                            <td>
                                <span class="text-muted">{{ Str::limit($inq->message, 60) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill">
                                    <i class="bi bi-chat-text me-1"></i> {{ $inq->responses()->count() }} replies
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $inq->status_badge_class }} rounded-pill px-3 py-1">
                                    {{ ucfirst(str_replace('_', ' ', $inq->status)) }}
                                </span>
                            </td>
                            <td>{{ $inq->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('inquiries.show', $inq->id) }}" class="btn btn-sm btn-outline-warning text-dark rounded-pill">
                                    View Thread
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $inquiries->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
