@extends('layouts.main')

@section('title', 'All Documents')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Document Tracking Index</h1>
            <p class="text-muted mb-0">Monitor document movements, custodians, and lifecycle status across all offices.</p>
        </div>

        <a href="{{ route('documents.create') }}" class="btn btn-primary">
            <i class="align-middle me-1" data-feather="plus"></i> New Document
        </a>

    </div>

    <!-- Filters & Search Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('documents.index') }}" class="row g-3">
                <!-- Search Keyword -->
                <div class="col-md-6 col-lg-5">
                    <label for="search" class="form-label small font-weight-bold text-muted">Search Tracking No. or
                        Title</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i data-feather="search"
                                class="feather-sm text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" id="search" name="search"
                            value="{{ request('search') }}" placeholder="e.g. DIV-202610-0042 or Purchase Request...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-4 col-lg-4">
                    <label for="status" class="form-label small font-weight-bold text-muted">Filter Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="In Transit" {{ request('status') == 'In Transit' ? 'selected' : '' }}>In Transit
                        </option>
                        <option value="Received" {{ request('status') == 'Received' ? 'selected' : '' }}>Received</option>
                        <option value="In Review" {{ request('status') == 'In Review' ? 'selected' : '' }}>In Review
                        </option>
                        <option value="Action Taken" {{ request('status') == 'Action Taken' ? 'selected' : '' }}>Action
                            Taken</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Archived" {{ request('status') == 'Archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <!-- Submit & Reset -->
                <div class="col-md-2 col-lg-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    @if (request()->hasAny(['search', 'status']))
                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary" title="Reset Filters">
                            <i data-feather="rotate-ccw"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Documents Datatable Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Tracking Number</th>
                            <th>Document Title & Type</th>
                            <th>Origin Office</th>
                            <th>Current Location</th>
                            <th>Urgency</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            <tr>
                                <!-- Tracking No. -->
                                <td class="ps-3">
                                    <a href="{{ route('documents.show', $doc->id) }}" class="fw-bold text-primary">
                                        {{ $doc->tracking_number }}
                                    </a>
                                </td>

                                <!-- Title & Document Type -->
                                <td>
                                    <div class="fw-bold text-dark">{{ Str::limit($doc->title, 40) }}</div>
                                    <span class="badge bg-light text-dark border small">
                                        {{ $doc->documentType->name ?? 'Unclassified' }}
                                    </span>
                                </td>

                                <!-- Origin Office -->
                                <td>
                                    <span class="small">{{ $doc->originatingOffice->name ?? 'N/A' }}</span>
                                </td>

                                <!-- Current Office & Custodian -->
                                <td>
                                    <div class="small fw-semibold text-dark">
                                        {{ $doc->currentOffice->name ?? 'In Transit' }}
                                    </div>
                                    @if ($doc->currentCustodian)
                                        <small class="text-muted">Custodian: {{ $doc->currentCustodian->name }}</small>
                                    @endif
                                </td>

                                <!-- Urgency Badge -->
                                <td>
                                    @if ($doc->urgency === 'Immediate')
                                        <span class="badge bg-danger">Immediate</span>
                                    @elseif($doc->urgency === 'Urgent')
                                        <span class="badge bg-warning text-dark">Urgent</span>
                                    @else
                                        <span class="badge bg-secondary">Normal</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td>
                                    @switch($doc->status)
                                        @case('In Transit')
                                            <span class="badge bg-info text-dark"><i data-feather="send"
                                                    class="feather-sm me-1"></i> In Transit</span>
                                        @break

                                        @case('Received')
                                            <span class="badge bg-primary"><i data-feather="check-circle"
                                                    class="feather-sm me-1"></i> Received</span>
                                        @break

                                        @case('In Review')
                                            <span class="badge bg-warning text-dark"><i data-feather="eye"
                                                    class="feather-sm me-1"></i> In Review</span>
                                        @break

                                        @case('Approved')
                                            <span class="badge bg-success"><i data-feather="check" class="feather-sm me-1"></i>
                                                Approved</span>
                                        @break

                                        @case('Archived')
                                            <span class="badge bg-dark"><i data-feather="archive" class="feather-sm me-1"></i>
                                                Archived</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">{{ $doc->status }}</span>
                                    @endswitch
                                </td>

                                <!-- Creation Date -->
                                <td>
                                    <span class="small text-muted">{{ $doc->created_at->format('M d, Y h:i A') }}</span>
                                </td>

                                <!-- Actions -->
                                <td class="text-end pe-3">
                                    <a href="{{ route('documents.show', $doc->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i data-feather="eye" class="feather-sm"></i> Track
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i data-feather="folder-off" class="mb-2" style="width: 40px; height: 40px;"></i>
                                        <p class="mb-0">No documents found matching your criteria.</p>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted">
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
    @endsection
