@extends('layouts.admin')

@section('title', 'Edit Announcement | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Advisory</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark mb-1">Edit Campus Health Advisory</h2>
    <p class="text-muted small">Update advisory text, priority flag, or publication status.</p>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> Please check form inputs:
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-adu p-4 p-md-5">
            <form method="POST" action="{{ route('admin.announcements.update', $announcement->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Advisory Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $announcement->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Priority Level <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select" required>
                            <option value="low" {{ old('priority', $announcement->priority) == 'low' ? 'selected' : '' }}>Low Priority</option>
                            <option value="medium" {{ old('priority', $announcement->priority) == 'medium' ? 'selected' : '' }}>Medium Priority</option>
                            <option value="high" {{ old('priority', $announcement->priority) == 'high' ? 'selected' : '' }}>High Priority</option>
                            <option value="urgent" {{ old('priority', $announcement->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Advisory Content <span class="text-danger">*</span></label>
                    <textarea name="content" rows="6" class="form-control" required>{{ old('content', $announcement->content) }}</textarea>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Expiration Date (Optional)</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $announcement->expires_at?->toDateString()) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Publishing Status <span class="text-danger">*</span></label>
                        <select name="is_published" class="form-select" required>
                            <option value="1" {{ old('is_published', $announcement->is_published ? '1' : '0') == '1' ? 'selected' : '' }}>Published</option>
                            <option value="0" {{ old('is_published', $announcement->is_published ? '1' : '0') == '0' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                        <i class="bi bi-save me-1"></i> Update Advisory
                    </button>
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
