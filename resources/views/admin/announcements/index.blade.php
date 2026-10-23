@extends('layouts.admin')

@section('title', 'Manage Announcements | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Campus Health Announcements</h2>
        <p class="text-muted small mb-0">Broadcast critical clinical advisories, vaccination schedules, and operating hours updates.</p>
    </div>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-megaphone-fill me-1"></i> Post Announcement
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card card-adu p-4 mb-4">
    <form method="GET" action="{{ route('admin.announcements.index') }}" class="row g-3 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search announcement title or text..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <option value="Urgent Advisory" {{ request('category') == 'Urgent Advisory' ? 'selected' : '' }}>Urgent Advisory</option>
                <option value="Clinic Schedule Update" {{ request('category') == 'Clinic Schedule Update' ? 'selected' : '' }}>Clinic Schedule Update</option>
                <option value="Health Advisory" {{ request('category') == 'Health Advisory' ? 'selected' : '' }}>Health Advisory</option>
                <option value="Vaccination Drive" {{ request('category') == 'Vaccination Drive' ? 'selected' : '' }}>Vaccination Drive</option>
                <option value="General Announcement" {{ request('category') == 'General Announcement' ? 'selected' : '' }}>General Announcement</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="priority" class="form-select">
                <option value="">All Priorities</option>
                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                Filter
            </button>
            @if(request()->filled('search') || request()->filled('category') || request()->filled('priority'))
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Announcements Table -->
<div class="card card-adu p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Announcement Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Expiration</th>
                    <th>Date Posted</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $ann)
                    <tr>
                        <td>
                            <strong class="text-dark">{{ $ann->title }}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($ann->content, 65) }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border rounded-pill">{{ $ann->category }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $ann->priority_badge_class }} rounded-pill px-2 py-1">
                                {{ ucfirst($ann->priority) }}
                            </span>
                        </td>
                        <td>
                            @if($ann->is_published)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill">Published</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">Draft</span>
                            @endif
                        </td>
                        <td>{{ $ann->expires_at ? $ann->expires_at->format('M d, Y') : 'None' }}</td>
                        <td>{{ $ann->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('announcements.show', $ann->id) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill" title="View Public Post">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.announcements.edit', $ann->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}" onsubmit="return confirm('Delete this announcement?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No announcements found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $announcements->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
