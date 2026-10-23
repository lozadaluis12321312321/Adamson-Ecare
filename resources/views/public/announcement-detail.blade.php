@extends('layouts.public')

@section('title', $announcement->title . ' | AdU eCare+')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">Announcements</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Advisory Details</li>
                </ol>
            </nav>

            <div class="card card-adu p-4 p-md-5 mb-5 border-start border-4 {{ $announcement->priority === 'urgent' ? 'border-danger' : ($announcement->priority === 'high' ? 'border-warning' : 'border-primary') }}">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge {{ $announcement->priority_badge_class }} rounded-pill px-3 py-1 fw-semibold">
                        {{ ucfirst($announcement->priority) }} Priority
                    </span>
                    <span class="badge bg-light text-secondary border rounded-pill px-3 py-1">
                        {{ $announcement->category }}
                    </span>
                    @if($announcement->expires_at)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border ms-auto small">
                            <i class="bi bi-hourglass-split me-1"></i> Valid until {{ $announcement->expires_at->format('M d, Y') }}
                        </span>
                    @endif
                </div>

                <h1 class="fw-bold text-dark mb-3 display-6">{{ $announcement->title }}</h1>

                <div class="d-flex flex-wrap align-items-center gap-3 text-muted small pb-4 border-bottom mb-4">
                    <span><i class="bi bi-building text-primary me-1"></i> Health Services Office</span>
                    <span><i class="bi bi-calendar3 text-primary me-1"></i> Posted on: {{ $announcement->created_at->format('F d, Y - h:i A') }}</span>
                </div>

                <div class="announcement-content text-dark mb-5" style="line-height: 1.8; font-size: 1.05rem; white-space: pre-line;">
                    {{ $announcement->content }}
                </div>

                <div class="bg-light p-3 rounded-3 border d-flex align-items-center justify-content-between mb-4">
                    <div class="small text-muted">
                        <i class="bi bi-info-circle text-primary me-1"></i> For questions regarding this advisory, submit a query via the Health Inquiries desk.
                    </div>
                    <a href="{{ route('inquiries.create') }}" class="btn btn-sm btn-primary rounded-pill">
                        Submit Inquiry
                    </a>
                </div>

                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to Announcements
                    </a>
                    <span class="text-muted small">Adamson University Official Communication</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
