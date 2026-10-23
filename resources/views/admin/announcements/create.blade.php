@extends('layouts.admin')

@section('title', 'Post Announcement | AdU eCare+')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
            <li class="breadcrumb-item active" aria-current="page">Post Announcement</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark mb-1">Post Campus Health Advisory</h2>
    <p class="text-muted small">Broadcast official health notices, vaccination drives, or clinic schedule adjustments.</p>
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
            <form method="POST" action="{{ route('admin.announcements.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Advisory Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Schedule of Annual Physical Exam for College of Nursing">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="" disabled selected>Select category...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Priority Level <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low Priority (General Info)</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium Priority (Standard Notice)</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High Priority (Important Action Required)</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent (Immediate Campus Advisory)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Advisory Content <span class="text-danger">*</span></label>
                    <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror" placeholder="Write official text, guidelines, clinic instructions, and relevant contact details..." required>{{ old('content') }}</textarea>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Advisory Expiration Date (Optional)</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}" min="{{ now()->toDateString() }}">
                        <div class="form-text small">Date until which this advisory remains highlighted.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark">Publishing Status <span class="text-danger">*</span></label>
                        <select name="is_published" class="form-select" required>
                            <option value="1" {{ old('is_published', '1') == '1' ? 'selected' : '' }}>Publish Immediately</option>
                            <option value="0" {{ old('is_published') == '0' ? 'selected' : '' }}>Save as Draft</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4 form-check bg-light p-3 rounded-3 border">
                    <input type="checkbox" name="notify_users" value="1" class="form-check-input ms-0 me-2" id="notify_users" checked>
                    <label class="form-check-label small fw-semibold text-dark" for="notify_users">
                        Send in-app notifications to all active students and faculty accounts
                    </label>
                    <div class="form-text small ps-4">Dispatches an alert bell notification on the user dashboard.</div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">
                        <i class="bi bi-broadcast me-1"></i> Broadcast Announcement
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
