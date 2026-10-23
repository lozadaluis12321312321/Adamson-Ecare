@extends('layouts.admin')

@section('title', 'Create Health Article | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">Health Articles</a></li>
            <li class="breadcrumb-item active" aria-current="page">New Article</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark mb-1">Publish New Health Article</h2>
    <p class="text-muted small">Provide preventive healthcare guides and wellness tips for the Adamson community.</p>
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
    <div class="col-lg-9">
        <div class="card card-adu p-4 p-md-5">
            <form method="POST" action="{{ route('admin.articles.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Article Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Managing Exam Anxiety and Improving Sleep Quality">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Health Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="" disabled selected>Select category...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Author / Publishing Office <span class="text-danger">*</span></label>
                        <input type="text" name="author_name" class="form-control @error('author_name') is-invalid @enderror" value="{{ old('author_name', 'Adamson University Health Services Office') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Brief Summary / Lead Paragraph <span class="text-danger">*</span></label>
                    <textarea name="summary" rows="2" class="form-control @error('summary') is-invalid @enderror" placeholder="1-2 sentences summarizing key takeaways for students and faculty..." required>{{ old('summary') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Article Content <span class="text-danger">*</span></label>
                    <textarea name="content" rows="10" class="form-control @error('content') is-invalid @enderror" placeholder="Write full healthcare guidance, preventive measures, practical steps, or recommendations..." required>{{ old('content') }}</textarea>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold text-dark">Featured Image URL (Optional)</label>
                        <input type="url" name="image_url" class="form-control" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-dark">Publishing Status <span class="text-danger">*</span></label>
                        <select name="is_published" class="form-select" required>
                            <option value="1" {{ old('is_published', '1') == '1' ? 'selected' : '' }}>Publish Immediately</option>
                            <option value="0" {{ old('is_published') == '0' ? 'selected' : '' }}>Save as Draft</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> Save Health Article
                    </button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
