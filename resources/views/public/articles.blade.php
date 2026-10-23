@extends('layouts.public')

@section('title', 'Health Information & Wellness | AdU eCare+')

@section('content')
<div class="bg-primary text-white py-5" style="background: linear-gradient(135deg, var(--adu-navy) 0%, var(--adu-blue) 100%) !important;">
    <div class="container text-center">
        <span class="badge bg-info text-dark px-3 py-1 rounded-pill mb-2 fw-semibold">Health Education</span>
        <h1 class="fw-bold mb-2">Health Information & Wellness Articles</h1>
        <p class="lead text-light opacity-90 mx-auto" style="max-width: 650px;">
            Evidence-based healthcare guides, hygiene practices, and preventive wellness advice curated by Adamson University medical professionals.
        </p>
    </div>
</div>

<div class="container py-5">
    <!-- Filter and Search Row -->
    <div class="card card-adu p-4 mb-4">
        <form method="GET" action="{{ route('articles.index') }}" class="row g-3 align-items-center">
            <div class="col-md-5">
                <label class="form-label small fw-semibold text-muted">Search Articles</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search keywords, topics..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-5">
                <label class="form-label small fw-semibold text-muted">Filter by Health Category</label>
                <select name="category" class="form-select">
                    <option value="">All Health Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                    <i class="bi bi-filter"></i> Filter
                </button>
                @if(request()->filled('search') || request()->filled('category'))
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Category Pills Quick Navigation -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('articles.index') }}" class="btn btn-sm {{ !request('category') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
            All Topics
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('articles.index', ['category' => $cat]) }}" class="btn btn-sm {{ request('category') == $cat ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- Articles Grid -->
    <div class="row g-4 mb-5">
        @forelse($articles as $article)
            <div class="col-lg-4 col-md-6">
                <div class="card card-adu h-100 d-flex flex-column">
                    <div class="p-4 d-flex flex-column h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill small">
                                {{ $article->category }}
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-calendar3 me-1"></i> {{ $article->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2 fs-6">
                            <a href="{{ route('articles.show', $article->slug) }}" class="text-dark text-decoration-none">
                                {{ $article->title }}
                            </a>
                        </h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            {{ Str::limit($article->summary, 140) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                            <span class="text-muted small">
                                <i class="bi bi-person-circle me-1"></i> {{ $article->author_name }}
                            </span>
                            <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                Read More <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-journal-x fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">No health articles found matching your criteria.</h5>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-primary rounded-pill btn-sm mt-2">View All Articles</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $articles->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
