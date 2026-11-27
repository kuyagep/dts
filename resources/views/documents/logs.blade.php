@extends('layouts.main')

@section('title', 'Document Activity Logs')

@section('content')
    <div class="container-fluid p-0">

        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h1 class="h3 mb-0 fw-bold">Document Activity Logs</h1>
                <p class="text-muted small mb-0">
                    System audit trail of document actions, status transitions, and user activity.
                </p>
            </div>
            <a href="{{ route('documents.create') }}" class="btn btn-primary shadow-sm">
                <i data-feather="plus-circle" class="feather-sm me-1"></i> Create Document
            </a>
        </div>

        <!-- Statistics KPI Row -->
        <div class="row g-3 mb-3">
            <!-- Total Logged Actions -->
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold text-uppercase">Total Logs</span>
                            <span class="badge bg-light text-dark border">
                                <i data-feather="activity" class="feather-sm text-primary"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_logs']) }}</h3>
                        <small class="text-muted">
                            <span class="text-primary fw-semibold">{{ number_format($stats['today_logs']) }}</span> recorded
                            today
                        </small>
                    </div>
                </div>
            </div>

            <!-- Received Documents -->
            <div class="col-6 col-md-3">
                <a href="{{ route('documents.logs', array_merge(request()->except('page'), ['action' => 'RECEIVED'])) }}"
                    class="text-decoration-none">
                    <div
                        class="card border-0 shadow-sm h-100 {{ request('action') === 'RECEIVED' ? 'border border-primary' : '' }}">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="text-muted small fw-semibold text-uppercase">Received</span>
                                <span class="badge bg-primary-subtle text-primary">
                                    <i data-feather="inbox" class="feather-sm"></i>
                                </span>
                            </div>
                            <h3 class="fw-bold mb-0 text-primary">{{ number_format($stats['received']) }}</h3>
                            <small class="text-muted">Acknowledged receipts</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Forwarded / Released -->
            <div class="col-6 col-md-3">
                <a href="{{ route('documents.logs', array_merge(request()->except('page'), ['action' => 'FORWARDED'])) }}"
                    class="text-decoration-none">
                    <div
                        class="card border-0 shadow-sm h-100 {{ in_array(request('action'), ['FORWARDED', 'RELEASED']) ? 'border border-warning' : '' }}">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="text-muted small fw-semibold text-uppercase">Forwarded / Released</span>
                                <span class="badge bg-warning-subtle text-warning">
                                    <i data-feather="send" class="feather-sm"></i>
                                </span>
                            </div>
                            <h3 class="fw-bold mb-0 text-warning">{{ number_format($stats['forwarded']) }}</h3>
                            <small class="text-muted">Routed &amp; released out</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Completed / Approved & Rejected -->
            <div class="col-6 col-md-3">
                <a href="{{ route('documents.logs', array_merge(request()->except('page'), ['action' => 'COMPLETED'])) }}"
                    class="text-decoration-none">
                    <div
                        class="card border-0 shadow-sm h-100 {{ in_array(request('action'), ['COMPLETED', 'APPROVED']) ? 'border border-success' : '' }}">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="text-muted small fw-semibold text-uppercase">Completed / Approved</span>
                                <span class="badge bg-success-subtle text-success">
                                    <i data-feather="check-circle" class="feather-sm"></i>
                                </span>
                            </div>
                            <h3 class="fw-bold mb-0 text-success">{{ number_format($stats['completed']) }}</h3>
                            <small class="text-muted">
                                <span class="text-danger fw-semibold">{{ number_format($stats['rejected']) }}</span>
                                rejected &bull;
                                <span class="fw-semibold">{{ number_format($stats['created']) }}</span> created
                            </small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <form action="{{ route('documents.logs') }}" method="GET" class="row g-2 align-items-end">
                    <!-- Search Input -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="search" class="form-label small text-muted mb-1">Keyword</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-feather="search" class="feather-sm text-muted"></i>
                            </span>
                            <input type="text" id="search" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Tracking #, title, user, or remarks..." value="{{ request('search') }}">
                            @if (request('search'))
                                <a href="{{ route(
                                    'documents.logs',
                                    array_filter([
                                        'office_id' => request('office_id'),
                                        'action' => request('action'),
                                        'start_date' => request('start_date'),
                                        'end_date' => request('end_date'),
                                    ]),
                                ) }}"
                                    class="btn btn-outline-secondary" title="Clear Search">
                                    <i data-feather="x" class="feather-sm"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Office Filter -->
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <label for="filter_office_id" class="form-label small text-muted mb-1">Office / Department</label>
                        <select name="office_id" id="filter_office_id" class="form-select select2-office"
                            onchange="this.form.submit()">
                            <option value="">All Offices / Departments</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}"
                                    {{ request('office_id') == $office->id ? 'selected' : '' }}>
                                    {{ $office->name }} ({{ $office->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Filter -->
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                        <label for="filter_action" class="form-label small text-muted mb-1">Action</label>
                        <select name="action" id="filter_action" class="form-select" onchange="this.form.submit()">
                            <option value="">All Actions</option>
                            @foreach (['CREATED', 'RECEIVED', 'FORWARDED', 'RELEASED', 'APPROVED', 'COMPLETED', 'REJECTED'] as $act)
                                <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>
                                    {{ $act }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- From Date -->
                    <div class="col-6 col-md-4 col-lg-1-5" style="flex: 1 1 135px;">
                        <label for="start_date" class="form-label small text-muted mb-1">From Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ request('start_date') }}"
                            max="{{ request('end_date') ?? now()->format('Y-m-d') }}">
                    </div>

                    <!-- To Date -->
                    <div class="col-6 col-md-4 col-lg-1-5" style="flex: 1 1 135px;">
                        <label for="end_date" class="form-label small text-muted mb-1">To Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="{{ request('end_date') }}" min="{{ request('start_date') }}"
                            max="{{ now()->format('Y-m-d') }}">
                    </div>

                    <!-- Submit & Reset Buttons -->
                    <div class="col-12 col-lg-auto d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-3">
                            Filter
                        </button>
                        @if (request('search') || request('office_id') || request('action') || request('start_date') || request('end_date'))
                            <a href="{{ route('documents.logs') }}" class="btn btn-outline-secondary"
                                title="Reset All Filters">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Timestamp</th>
                                <th>Tracking #</th>
                                <th>Document Title</th>
                                <th>Action</th>
                                <th>Office</th>
                                <th>User & Remarks</th>
                                <th class="text-end">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <!-- Timestamp -->
                                    <td class="text-nowrap small">
                                        <div class="fw-semibold text-dark">{{ $log->created_at->format('M d, Y h:i A') }}
                                        </div>
                                        <div class="text-muted">{{ $log->created_at->diffForHumans() }}</div>
                                    </td>

                                    <!-- Tracking Number -->
                                    <td class="font-monospace fw-bold text-primary text-nowrap">
                                        {{ $log->document->tracking_number ?? 'N/A' }}
                                    </td>

                                    <!-- Document Title & Type -->
                                    <td>
                                        <div class="fw-bold text-dark">
                                            {{ Str::limit($log->document->title ?? 'Deleted Document', 40) }}
                                        </div>
                                        <span class="text-muted d-block" style="font-size: 0.78rem;">
                                            {{ $log->document->documentType->name ?? 'Unclassified' }}
                                        </span>
                                    </td>

                                    <!-- Action & From -> To Status -->
                                    <td>
                                        @php
                                            $badgeColor = match (strtoupper($log->action)) {
                                                'APPROVED', 'COMPLETED' => 'bg-success',
                                                'RECEIVED' => 'bg-primary',
                                                'FORWARDED', 'IN TRANSIT' => 'bg-warning text-dark',
                                                'RELEASED' => 'bg-info text-dark',
                                                'REJECTED' => 'bg-danger',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeColor }}">
                                            {{ strtoupper($log->action) }}
                                        </span>

                                        @if ($log->from_status || $log->to_status)
                                            <div class="small text-muted mt-1">
                                                {{ $log->from_status ?? 'New' }} &rarr; <strong
                                                    class="text-dark">{{ $log->to_status ?? 'N/A' }}</strong>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Office -->
                                    <td class="small">
                                        <div class="fw-semibold text-dark">
                                            {{ $log->office->name ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <!-- User, Remarks & IP Address -->
                                    <td class="small">
                                        <div class="fw-semibold text-dark">
                                            {{ $log->user->name ?? 'System' }}
                                            @if ($log->ip_address)
                                                <span class="text-muted fw-normal font-monospace"
                                                    style="font-size: 0.75rem;">
                                                    ({{ $log->ip_address }})
                                                </span>
                                            @endif
                                        </div>
                                        @if ($log->remarks)
                                            <div class="text-muted text-truncate" style="max-width: 240px;"
                                                title="{{ $log->remarks }}">
                                                "{{ $log->remarks }}"
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Track Button -->
                                    <td class="text-end text-nowrap">
                                        @if ($log->document)
                                            <a href="{{ route('documents.show', $log->document->id) }}"
                                                class="btn btn-sm btn-outline-secondary" title="View Full Document Trail">
                                                <i class="fa-solid fa-qrcode me-1"></i> Track
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i data-feather="activity" class="feather-lg mb-2 text-secondary"></i>
                                        <p class="mb-0 fw-semibold">No document logs found.</p>
                                        <small>Document activity entries will appear here as actions occur.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Footer -->
            @if ($logs->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <span class="text-muted small">
                            Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} log
                            entries
                        </span>
                        <div>
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if ($('.select2-office').length) {
                $('.select2-office').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                }).on('change', function() {
                    this.form.submit();
                });
            }
        });
    </script>
@endpush
