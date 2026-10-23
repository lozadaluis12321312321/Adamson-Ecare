@extends('layouts.public')

@section('title', $article->title . ' | AdU eCare+')

@section('content')
<div class="container py-5">
    <div class="row g-5 justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Health Information</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $article->category }}</li>
                </ol>
            </nav>

            <article class="card card-adu p-4 p-md-5 mb-5">
                <div class="mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1 rounded-pill fw-semibold">
                        {{ $article->category }}
                    </span>
                </div>

                <h1 class="fw-bold text-dark mb-3 display-6">{{ $article->title }}</h1>

                <div class="d-flex flex-wrap align-items-center gap-3 text-muted small pb-4 border-bottom mb-4">
                    <span><i class="bi bi-person-fill text-primary me-1"></i> Published by: <strong>{{ $article->author_name }}</strong></span>
                    <span><i class="bi bi-calendar3 text-primary me-1"></i> {{ $article->created_at->format('F d, Y') }}</span>
                    <span><i class="bi bi-clock text-primary me-1"></i> 3 min read</span>
                </div>

                <div class="lead text-secondary mb-4 p-3 bg-light rounded-3 border-start border-primary border-4">
                    {{ $article->summary }}
                </div>

                <div class="article-body text-dark" style="line-height: 1.8; font-size: 1.05rem; white-space: pre-line;">
                    {{ $article->content }}
                </div>

                <div class="mt-5 pt-4 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to All Articles
                    </a>
                    <div class="text-muted small">
                        <i class="bi bi-shield-check text-success me-1"></i> Medically reviewed by Adamson University Health Services
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar Related Articles -->
        <div class="col-lg-4">
            <div class="card card-adu p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Related Health Articles</h5>
                @forelse($relatedArticles as $rel)
                    <div class="mb-3 pb-3 border-bottom last-border-0">
                        <span class="badge bg-light text-primary border small mb-1">{{ $rel->category }}</span>
                        <h6 class="fw-bold mb-1">
                            <a href="{{ route('articles.show', $rel->slug) }}" class="text-dark text-decoration-none">
                                {{ $rel->title }}
                            </a>
                        </h6>
                        <span class="text-muted small">{{ $rel->created_at->format('M d, Y') }}</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No related articles found.</p>
                @endforelse
            </div>

            <!-- Health Desk Helper Box -->
            <div class="card bg-primary text-white p-4 rounded-4 shadow-sm">
                <h5 class="fw-bold mb-2">Have a Health Concern?</h5>
                <p class="small text-light opacity-90 mb-3">
                    If you are experiencing symptoms, book a dental checkup or consult with our university clinic physicians.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('dental.create') }}" class="btn btn-light text-primary fw-semibold btn-sm rounded-pill">
                        Book Dental Slot
                    </a>
                    <a href="{{ route('teleconsultation.create') }}" class="btn btn-outline-light fw-semibold btn-sm rounded-pill">
                        Request Teleconsultation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
