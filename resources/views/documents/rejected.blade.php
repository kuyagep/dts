@extends('layouts.main')

@section('title', 'Rejected Documents')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0 text-danger fw-bold">
                <i data-feather="alert-octagon" class="feather-md me-1"></i> My Rejected / Returned Documents
            </h1>
            <p class="text-muted mb-0">
                Documents created by you that were returned, rejected, or flagged for missing physical copies during batch
                reception.
            </p>
        </div>
        <a href="{{ route('documents.my-documents') }}" class="btn btn-outline-secondary">
            <i data-feather="folder" class="feather-sm me-1"></i> All My Documents
        </a>
    </div>

    <!-- Search Filter Card -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-3">
            <form action="{{ route('documents.rejected') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search rejected documents by tracking number or title..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary w-100"><i data-feather="filter"
                            class="feather-sm me-1"></i> Filter</button>
                    @if (request('search'))
                        <a href="{{ route('documents.rejected') }}" class="btn btn-light"><i data-feather="x"
                                class="feather-sm"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Rejected Documents Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Tracking Number</th>
                            <th>Title & Type</th>
                            <th>Returned Location</th>
                            <th>Rejection Reason / Remarks</th>
                            <th>Rejected Date</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            @php
                                $latestRejectionRoute = $doc->routes->first();
                            @endphp
                            <tr>
                                <!-- Tracking Number -->
                                <td class="ps-3">
                                    <a href="{{ route('documents.show', $doc->id) }}" class="fw-bold text-danger">
                                        {{ $doc->tracking_number }}
                                    </a>
                                </td>

                                <!-- Title & Type -->
                                <td>
                                    <div class="fw-bold text-dark">{{ Str::limit($doc->title, 40) }}</div>
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

                                <!-- Returned Office Location -->
                                <td>
                                    <span class="fw-semibold text-dark">{{ $doc->currentOffice->name ?? 'N/A' }}</span>
                                    <small class="text-muted d-block">({{ $doc->currentOffice->code ?? '-' }})</small>
                                </td>

                                <!-- Rejection Reason -->
                                <td style="max-width: 280px;">
                                    <div class="bg-danger-subtle text-danger p-2 rounded border border-danger-subtle small">
                                        <i data-feather="info" class="feather-xs me-1"></i>
                                        {{ $latestRejectionRoute->remarks ?? 'Flagged or rejected during route transit.' }}
                                    </div>
                                </td>

                                <!-- Date Rejected -->
                                <td>
                                    <small class="text-muted">
                                        {{ $latestRejectionRoute ? $latestRejectionRoute->created_at->format('M d, Y h:i A') : $doc->updated_at->format('M d, Y h:i A') }}
                                    </small>
                                </td>

                                <!-- Actions -->
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <a href="{{ route('documents.show', $doc->id) }}"
                                            class="btn btn-sm btn-outline-secondary" title="View History">
                                            <i class="fa-solid fa-qrcode"></i>
                                        </a>
                                        <a href="{{ route('documents.edit', $doc->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit & Re-submit">
                                            <i data-feather="edit-2" class="feather-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i data-feather="check-circle" class="text-success mb-2"
                                        style="width: 40px; height: 40px;"></i>
                                    <p class="mb-0 fw-semibold">No rejected documents found.</p>
                                    <small>All your created documents are actively moving or completed without
                                        discrepancies.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($documents->hasPages())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">
                        Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of
                        {{ $documents->total() }} rejected records
                    </span>
                    <div>
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
