@extends('layouts.public')

@section('title', 'Health Announcements | AdU eCare+')

@section('content')
<div class="bg-primary text-white py-5" style="background: linear-gradient(135deg, var(--adu-navy) 0%, var(--adu-blue) 100%) !important;">
    <div class="container text-center">
        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mb-2 fw-semibold">Campus Advisories</span>
        <h1 class="fw-bold mb-2">Health Announcements & Advisories</h1>
        <p class="lead text-light opacity-90 mx-auto" style="max-width: 650px;">
            Stay informed with real-time operational updates, vaccination drives, clinic schedules, and public health advisories from Adamson University.
        </p>
    </div>
</div>

<div class="container py-5">
    <!-- Filter and Search Row -->
    <div class="card card-adu p-4 mb-4">
        <form method="GET" action="{{ route('announcements.index') }}" class="row g-3 align-items-center">
            <div class="col-md-5">
                <label class="form-label small fw-semibold text-muted">Search Announcements</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Keywords in advisories..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Priority</label>
                <select name="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                    <i class="bi bi-filter"></i> Filter
                </button>
                @if(request()->filled('search') || request()->filled('category') || request()->filled('priority'))
                    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Announcements Listing -->
    <div class="row g-4 mb-5">
        @forelse($announcements as $announcement)
            <div class="col-lg-6">
                <div class="card card-adu h-100 p-4 border-start border-4 {{ $announcement->priority === 'urgent' ? 'border-danger' : ($announcement->priority === 'high' ? 'border-warning' : 'border-primary') }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge {{ $announcement->priority_badge_class }} rounded-pill small">
                                {{ ucfirst($announcement->priority) }} Priority
                            </span>
                            <span class="badge bg-light text-secondary border rounded-pill small">
                                {{ $announcement->category }}
                            </span>
                        </div>
                        <span class="text-muted small">
                            <i class="bi bi-clock me-1"></i> {{ $announcement->created_at->format('M d, Y') }}
                        </span>
                    </div>

                    <h4 class="fw-bold text-dark mb-2 fs-5">
                        <a href="{{ route('announcements.show', $announcement->id) }}" class="text-dark text-decoration-none">
                            {{ $announcement->title }}
                        </a>
                    </h4>

                    <p class="text-muted small mb-4 flex-grow-1" style="white-space: pre-line;">
                        {{ Str::limit($announcement->content, 180) }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                        <span class="text-muted small">
                            <i class="bi bi-building me-1"></i> AdU Health Services Office
                        </span>
                        <a href="{{ route('announcements.show', $announcement->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                            Full Details <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-megaphone-fill fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">No announcements match your search criteria.</h5>
                <a href="{{ route('announcements.index') }}" class="btn btn-outline-primary rounded-pill btn-sm mt-2">View All Announcements</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $announcements->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
