@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.dashboard')

@section('title', 'My Notifications | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Notifications & Alerts</h2>
        <p class="text-muted small mb-0">Stay informed on clinical approvals, rescheduling notices, certificate releases, and staff replies.</p>
    </div>

    @if(auth()->user()->unreadNotifications()->count() > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="bi bi-check2-all me-1"></i> Mark All as Read
            </button>
        </form>
    @endif
</div>

<div class="card card-adu p-4">
    @if($notifications->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash fs-1 d-block mb-3 opacity-50"></i>
            <h5>You have no notifications</h5>
            <p class="small">Updates regarding your appointments and requests will appear here.</p>
        </div>
    @else
        <div class="list-group list-group-flush">
            @foreach($notifications as $notif)
                <div class="list-group-item list-group-item-action d-flex align-items-start gap-3 p-3 border-bottom {{ !$notif->is_read ? 'bg-light' : '' }}">
                    <div class="mt-1">
                        @if(!$notif->is_read)
                            <span class="badge bg-primary p-1 rounded-circle" title="Unread"><span class="visually-hidden">Unread</span></span>
                        @else
                            <i class="bi bi-check-circle text-muted"></i>
                        @endif
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold text-dark mb-0">{{ $notif->title }}</h6>
                            <span class="text-muted small" style="font-size: 0.75rem;">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-muted small mb-2">{{ $notif->message }}</p>

                        <div class="d-flex align-items-center gap-2">
                            @if($notif->link_url)
                                <form method="POST" action="{{ route('notifications.read', $notif->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 py-0 small" style="font-size: 0.78rem;">
                                        View Request <i class="bi bi-arrow-right ms-1"></i>
                                    </button>
                                </form>
                            @endif

                            @if(!$notif->is_read)
                                <form method="POST" action="{{ route('notifications.read', $notif->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 small" style="font-size: 0.78rem;">
                                        Mark as read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
