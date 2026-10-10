@extends('layouts.main')

@section('title', 'Archived Vault')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Archived Document Vault</h1>
            <p class="text-muted mb-0">Completed and filed division documents retained for historical reference and
                compliance auditing.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Tracking Number</th>
                            <th>Document Title & Type</th>
                            <th>Origin Office</th>
                            <th>Final Custodian</th>
                            <th>Status</th>
                            <th>Archived Date</th>
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

                                <!-- Title & Type -->
                                <td>
                                    <div class="fw-bold text-dark">{{ Str::limit($doc->title, 45) }}</div>
                                    <span class="badge bg-light text-dark border small">
                                        {{ $doc->documentType->name ?? 'Unclassified' }}
                                    </span>
                                </td>

                                <!-- Origin Office -->
                                <td>
                                    <span class="small font-weight-bold">{{ $doc->originatingOffice->name ?? 'N/A' }}</span>
                                </td>

                                <!-- Final Custodian -->
                                <td>
                                    <small
                                        class="text-muted">{{ $doc->currentCustodian->name ?? 'Records Section' }}</small>
                                </td>

                                <!-- Status Badge -->
                                <td>
                                    <span class="badge bg-dark">
                                        <i data-feather="archive" class="feather-sm me-1"></i> Archived
                                    </span>
                                </td>

                                <!-- Date Archived -->
                                <td>
                                    <small class="text-muted">{{ $doc->updated_at->format('M d, Y h:i A') }}</small>
                                </td>

                                <!-- Action -->
                                <td class="text-end pe-3">
                                    <a href="{{ route('documents.show', $doc->id) }}" class="btn btn-sm btn-outline-primary"
                                        title="View Document File & Logs">
                                        <i data-feather="eye" class="feather-sm"></i> Inspect
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i data-feather="archive" class="mb-2" style="width: 40px; height: 40px;"></i>
                                    <p class="mb-0">No archived documents found in the vault.</p>
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
                        Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }}
                        archived records
                    </span>
                    <div>
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
