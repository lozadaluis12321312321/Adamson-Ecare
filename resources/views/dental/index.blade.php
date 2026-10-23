@extends('layouts.dashboard')

@section('title', 'Dental Appointments | AdU eCare+')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dental Appointments</h2>
        <p class="text-muted small mb-0">Manage your dental clinic appointments, track statuses, and view schedules.</p>
    </div>
    <a href="{{ route('dental.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-calendar-plus me-1"></i> Book New Appointment
    </a>
</div>

<!-- Status Filter Pills -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('dental.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
        All Appointments
    </a>
    <a href="{{ route('dental.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill">
        Pending
    </a>
    <a href="{{ route('dental.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill">
        Approved
    </a>
    <a href="{{ route('dental.index', ['status' => 'rescheduled']) }}" class="btn btn-sm {{ request('status') == 'rescheduled' ? 'btn-info text-dark' : 'btn-outline-secondary' }} rounded-pill">
        Rescheduled
    </a>
    <a href="{{ route('dental.index', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') == 'completed' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
        Completed
    </a>
    <a href="{{ route('dental.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') == 'cancelled' ? 'btn-secondary' : 'btn-outline-secondary' }} rounded-pill">
        Cancelled
    </a>
</div>

<div class="card card-adu p-4">
    @if($appointments->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-dark">No dental appointments found</h5>
            <p class="text-muted small mb-3">You don't have any appointments matching this filter.</p>
            <a href="{{ route('dental.create') }}" class="btn btn-primary btn-sm rounded-pill">Book a Dental Appointment</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>Date & Time</th>
                        <th>Reason for Visit</th>
                        <th>Status</th>
                        <th>Admin Remarks</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @foreach($appointments as $app)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $app->appointment_date->format('M d, Y (D)') }}</strong>
                                <div class="text-muted">{{ $app->appointment_time }}</div>
                                @if($app->status === 'rescheduled' && $app->rescheduled_date)
                                    <div class="text-info fw-semibold mt-1">
                                        <i class="bi bi-arrow-repeat me-1"></i> Rescheduled to: {{ $app->rescheduled_date->format('M d, Y') }} ({{ $app->rescheduled_time }})
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $app->reason_for_visit }}</span>
                                @if($app->specific_concerns)
                                    <div class="text-muted small text-truncate" style="max-width: 250px;">
                                        {{ $app->specific_concerns }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $app->status_badge_class }} rounded-pill px-3 py-1">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ $app->admin_notes ? Str::limit($app->admin_notes, 40) : '—' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('dental.show', $app->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        View Details
                                    </a>
                                    @if(in_array($app->status, ['pending', 'approved', 'rescheduled']))
                                        <form method="POST" action="{{ route('dental.cancel', $app->id) }}" onsubmit="return confirm('Are you sure you want to cancel this dental appointment?');" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" title="Cancel Appointment">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $appointments->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
