@extends('layouts.admin')

@section('title', 'Manage Health Articles | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Health Articles Management</h2>
        <p class="text-muted small mb-0">Publish and manage preventive health information and wellness guides for the Adamson community.</p>
    </div>
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-journal-plus me-1"></i> New Health Article
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card card-adu p-4 mb-4">
    <form method="GET" action="{{ route('admin.articles.index') }}" class="row g-3 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by article title or summary..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">All Health Categories</option>
                <option value="General Wellness" {{ request('category') == 'General Wellness' ? 'selected' : '' }}>General Wellness</option>
                <option value="Mental Well-Being" {{ request('category') == 'Mental Well-Being' ? 'selected' : '' }}>Mental Well-Being</option>
                <option value="Dental Health" {{ request('category') == 'Dental Health' ? 'selected' : '' }}>Dental Health</option>
                <option value="Hygiene and Prevention" {{ request('category') == 'Hygiene and Prevention' ? 'selected' : '' }}>Hygiene and Prevention</option>
                <option value="Healthy Lifestyle" {{ request('category') == 'Healthy Lifestyle' ? 'selected' : '' }}>Healthy Lifestyle</option>
                <option value="Preventive Healthcare" {{ request('category') == 'Preventive Healthcare' ? 'selected' : '' }}>Preventive Healthcare</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                Filter
            </button>
            @if(request()->filled('search') || request()->filled('category'))
                <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Articles Table -->
<div class="card card-adu p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Article Title</th>
                    <th>Category</th>
                    <th>Author / Desk</th>
                    <th>Status</th>
                    <th>Date Published</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $art)
                    <tr>
                        <td>
                            <strong class="text-dark">{{ $art->title }}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($art->summary, 60) }}</div>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill">
                                {{ $art->category }}
                            </span>
                        </td>
                        <td>{{ $art->author_name }}</td>
                        <td>
                            @if($art->is_published)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill">Published</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">Draft</span>
                            @endif
                        </td>
                        <td>{{ $art->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill" title="Preview Public View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.articles.edit', $art->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.articles.destroy', $art->id) }}" onsubmit="return confirm('Are you sure you want to delete this article?');" class="d-inline">
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
                        <td colspan="6" class="text-center py-4 text-muted">
                            No health articles found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $articles->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
