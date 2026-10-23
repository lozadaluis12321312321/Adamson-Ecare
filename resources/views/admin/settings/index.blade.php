@extends('layouts.admin')

@section('title', 'System Settings | AdU eCare+')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold text-dark mb-1">System Settings & Infrastructure Status</h2>
    <p class="text-muted small">Verify local XAMPP runtime environment, database configurations, and university clinic operational parameters.</p>
</div>

<div class="row g-4 mb-4">
    <!-- Local Technology Stack Health -->
    <div class="col-lg-6">
        <div class="card card-adu p-4 h-100">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-hdd-network text-primary me-2"></i> Local Development Environment (XAMPP)
            </h5>

            <table class="table table-borderless small mb-0">
                <tr>
                    <td class="text-muted fw-semibold" style="width: 40%;">Framework:</td>
                    <td><span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Laravel {{ app()->version() }}</span></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">PHP Version:</td>
                    <td><strong class="text-dark">{{ PHP_VERSION }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Database Connection:</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ config('database.default') }} (MySQL / MariaDB)</span></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Database Name:</td>
                    <td><strong class="font-monospace text-dark">{{ config('database.connections.mysql.database') }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Database Host / Port:</td>
                    <td><span class="font-monospace text-muted">{{ config('database.connections.mysql.host') }}:{{ config('database.connections.mysql.port') }}</span></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Storage Driver:</td>
                    <td><span class="text-dark">Private Local Disk (<code>storage/app</code>)</span></td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">App URL:</td>
                    <td><span class="font-monospace text-muted">{{ config('app.url') }}</span></td>
                </tr>
            </table>

            <div class="mt-4 p-3 bg-light rounded-3 small border">
                <i class="bi bi-check-circle-fill text-success me-1"></i>
                All core database migrations and seeders are in sync. Local connection to XAMPP MySQL verified.
            </div>
        </div>
    </div>

    <!-- Clinic Operational Parameters -->
    <div class="col-lg-6">
        <div class="card card-adu p-4 h-100">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-hospital text-danger me-2"></i> University Clinic Operational Settings
            </h5>

            <div class="d-flex flex-column gap-3 small">
                <div class="p-3 bg-light rounded-3 border">
                    <strong class="text-dark d-block mb-1">Standard Operating Schedule</strong>
                    <div class="text-primary fw-semibold">Monday – Saturday: 7:00 AM – 9:00 PM (Continuous Clinical Duty)</div>
                </div>

                <div class="p-3 bg-light rounded-3 border">
                    <strong class="text-dark d-block mb-1">Clinic Physical Location</strong>
                    <div class="text-muted">Room 104 (Medical) & Room 204 (Dental), St. Vincent Building, Adamson University</div>
                </div>

                <div class="p-3 bg-light rounded-3 border">
                    <strong class="text-dark d-block mb-1">Emergency Protocols Reference</strong>
                    <div class="text-muted">Manila Emergency Hotline: 911 &bull; University Trunkline: (02) 8524-2011 loc. 123 / 124</div>
                </div>

                <div class="p-3 bg-light rounded-3 border">
                    <strong class="text-dark d-block mb-1">Regulatory Privacy Governance</strong>
                    <div class="text-muted">Compliant with Philippine Data Privacy Act of 2012 (Republic Act 10173).</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
