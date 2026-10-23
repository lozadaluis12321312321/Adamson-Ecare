@extends('layouts.admin')

@section('title', 'Activity Audit Logs | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">System Activity Audit Trail</h2>
        <p class="text-muted small mb-0">Track administrative operations, patient bookings, status changes, and authentication events.</p>
    </div>
</div>

<!-- Search & Module Filter Card -->
<div class="card card-adu p-4 mb-4">
    <form method="GET" action="{{ route('admin.logs.index') }}" class="row g-3 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search description, action, or user name..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-4">
            <select name="module" class="form-select">
                <option value="">All Functional Modules</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                Filter
            </button>
            @if(request()->filled('search') || request()->filled('module'))
                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Audit Logs Table -->
<div class="card card-adu p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Timestamp</th>
                    <th>Module</th>
                    <th>Action</th>
                    <th>User / Originator</th>
                    <th>Event Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-muted">
                            <span class="fw-semibold text-dark">{{ $log->created_at->format('M d, Y') }}</span>
                            <div style="font-size: 0.72rem;">{{ $log->created_at->format('h:i:s A') }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border rounded-pill">{{ $log->module }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $log->action }}</span>
                        </td>
                        <td>
                            @if($log->user)
                                <strong class="text-dark">{{ $log->user->name }}</strong>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $log->user->role_label }} ({{ $log->user->id_number }})</div>
                            @else
                                <span class="text-muted italic">System / Visitor</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-dark">{{ $log->description }}</span>
                        </td>
                        <td>
                            <span class="font-monospace text-muted" style="font-size: 0.75rem;">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No activity audit logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
