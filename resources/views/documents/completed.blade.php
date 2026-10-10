@extends('layouts.main')

@section('title', 'Completed Documents')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Completed & Filed Documents</h1>
            <p class="text-muted mb-0">
                Documents where all processing has been concluded by
                <strong>{{ auth()->user()->office->name ?? 'Your Office' }}</strong>.
            </p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Tracking Number</th>
                            <th>Title & Type</th>
                            <th>Origin Office</th>
                            <th>Completed By</th>
                            <th>Status</th>
                            <th>Date Completed</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            <tr>
                                <!-- Tracking Number -->
                                <td class="ps-3">
                                    <a href="{{ route('documents.show', $doc->id) }}" class="fw-bold text-primary">
                                        {{ $doc->tracking_number }}
                                    </a>
                                </td>

                                <!-- Title & Type -->
                                <td>
                                    <div class="fw-bold text-dark">{{ Str::limit($doc->title, 40) }}</div>
                                    <span class="badge bg-light text-dark border small">
                                        {{ $doc->documentType->name ?? 'Unclassified' }}
                                    </span>
                                </td>

                                <!-- Origin Office -->
                                <td>
                                    <span class="small font-weight-bold">{{ $doc->originatingOffice->code ?? 'N/A' }}</span>
                                </td>

                                <!-- Custodian / User Who Completed -->
                                <td>
                                    <small class="text-muted">{{ $doc->currentCustodian->name ?? 'System' }}</small>
                                </td>

                                <!-- Status Badge -->
                                <td>
                                    <span class="badge bg-success">
                                        <i data-feather="check-circle" class="feather-sm me-1"></i> Completed
                                    </span>
                                </td>

                                <!-- Date Completed -->
                                <td>
                                    <small class="text-muted">{{ $doc->updated_at->format('M d, Y h:i A') }}</small>
                                </td>

                                <!-- Action Buttons -->
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <a href="{{ route('documents.show', $doc->id) }}"
                                            class="btn btn-sm btn-outline-secondary" title="View Details & Audit Log">
                                            <i data-feather="eye" class="feather-sm me-1"></i> Details
                                        </a>

                                        <!-- Option to Archive if needed -->
                                        <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"
                                            data-bs-target="#archiveModal-{{ $doc->id }}"
                                            title="Move to Archive Vault">
                                            <i data-feather="archive" class="feather-sm me-1"></i> Archive
                                        </button>
                                    </div>

                                    <!-- Modal: Archive Document -->
                                    <div class="modal fade text-start" id="archiveModal-{{ $doc->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('documents.archive', $doc->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-dark text-white">
                                                        <h5 class="modal-title text-white"><i data-feather="archive"
                                                                class="me-1"></i> Move to Archive Vault</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Move this completed document to the <strong>Archived
                                                                Vault</strong> for long-term record storage.</p>
                                                        <div class="mb-3">
                                                            <label class="form-label font-weight-bold">Archival
                                                                Remarks</label>
                                                            <textarea name="remarks" class="form-control" rows="3"
                                                                placeholder="e.g. Processing complete. Archiving in official records vault."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-dark">Confirm
                                                            Archival</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i data-feather="check-circle" class="mb-2" style="width: 40px; height: 40px;"></i>
                                    <p class="mb-0">No completed or filed documents in your office vault yet.</p>
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
                        {{ $documents->total() }} completed documents
                    </span>
                    <div>
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
