@extends('layouts.main')

@section('title', 'Released Documents')

@section('content')
    <div class="container-fluid p-0">

        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h1 class="h3 mb-0 fw-bold">Released Documents</h1>
                <p class="text-muted small mb-0">
                    History of documents released and forwarded from your office.
                </p>
            </div>
            <a href="{{ route('documents.create') }}" class="btn btn-primary shadow-sm">
                <i data-feather="plus-circle" class="feather-sm me-1"></i> Create Document
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <!-- Search & Office Filter Form -->
                <form action="{{ route('documents.released') }}" method="GET" class="row g-2 align-items-center">
                    <!-- Search Input -->
                    <div class="col-12 col-md-5 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i data-feather="search" class="feather-sm text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Search tracking #, title, or description..." value="{{ request('search') }}">
                            @if (request('search'))
                                <a href="{{ route('documents.released', array_filter(['office_id' => request('office_id')])) }}"
                                    class="btn btn-outline-secondary" title="Clear Search">
                                    <i data-feather="x" class="feather-sm"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Destination Office Filter -->
                    <div class="col-12 col-sm-7 col-md-4 col-lg-4">
                        <select name="office_id" id="filter_office_id" class="form-select select2-office"
                            onchange="this.form.submit()">
                            <option value="">All Destination Offices / Departments</option>
                            @foreach ($offices as $office)
                                @if ($office->id !== auth()->user()->office_id)
                                    <option value="{{ $office->id }}"
                                        {{ request('office_id') == $office->id ? 'selected' : '' }}>
                                        {{ $office->name }} ({{ $office->code }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit & Reset -->
                    <div class="col-12 col-sm-5 col-md-3 col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Search
                        </button>
                        @if (request('search') || request('office_id'))
                            <a href="{{ route('documents.released') }}" class="btn btn-outline-secondary"
                                title="Reset Filters">
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
                                <th>Tracking #</th>
                                <th>Document Title & Description</th>
                                <th>Released To / Current Office</th>
                                <th>Date Released</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $doc)
                                @php
                                    $latestRelease = $doc->routes->first();
                                    $targetOffice = $latestRelease->toOffice ?? $doc->currentOffice;
                                @endphp
                                <tr>
                                    <!-- Tracking Number -->
                                    <td class="font-monospace fw-bold text-primary text-nowrap">
                                        {{ $doc->tracking_number }}
                                    </td>

                                    <!-- Title, Description & Type -->
                                    <td>
                                        <div class="fw-bold text-dark">{{ Str::limit($doc->title, 45) }}</div>
                                        @if ($doc->description)
                                            <div class="text-muted small text-truncate" style="max-width: 280px;"
                                                title="{{ $doc->description }}">
                                                {{ $doc->description }}
                                            </div>
                                        @endif
                                        <span class="text-muted extra-small d-block mt-1" style="font-size: 0.78rem;">
                                            Type: {{ $doc->documentType->name ?? 'Unclassified' }}
                                        </span>
                                    </td>

                                    <!-- Released To -->
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $targetOffice->name ?? 'External / N/A' }}
                                        </div>
                                        @if ($latestRelease && $latestRelease->remarks)
                                            <small class="text-muted d-block text-truncate" style="max-width: 220px;"
                                                title="{{ $latestRelease->remarks }}">
                                                Remarks: {{ $latestRelease->remarks }}
                                            </small>
                                        @endif
                                    </td>

                                    <!-- Date Released -->
                                    <td class="text-nowrap small text-muted">
                                        {{ ($latestRelease->created_at ?? $doc->updated_at)->format('M d, Y h:i A') }}
                                    </td>

                                    <!-- Current Lifecycle Status -->
                                    <td>
                                        @php
                                            $badgeColor = match ($doc->status) {
                                                'Completed' => 'bg-success',
                                                'Received' => 'bg-primary',
                                                'In Transit' => 'bg-warning text-dark',
                                                'Rejected' => 'bg-danger',
                                                default => 'bg-info text-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeColor }}">
                                            {{ $doc->status }}
                                        </span>
                                    </td>

                                    <!-- Individual Action Buttons -->
                                    <td class="text-end">
                                        <div class="d-flex flex-wrap justify-content-end gap-1">
                                            <a href="{{ route('documents.show', $doc->id) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="Track Document & Audit Trail">
                                                <i class="fa-solid fa-qrcode me-1"></i> Track
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i data-feather="external-link" class="feather-lg mb-2 text-secondary"></i>
                                        <p class="mb-0 fw-semibold">No released documents found.</p>
                                        <small>Documents released from your office will appear here.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Footer -->
            @if ($documents->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <span class="text-muted small">
                            Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of
                            {{ $documents->total() }} documents
                        </span>
                        <div>
                            {{ $documents->links() }}
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
