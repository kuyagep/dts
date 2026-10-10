@extends('layouts.main')

@section('title', 'Pending Documents')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Pending Documents Queue</h1>
            <p class="text-muted mb-0">
                Documents currently held by <strong>{{ auth()->user()->office->name ?? 'Your Office' }}</strong> requiring
                action.
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
                            <th>Current Custodian</th>
                            <th>Urgency</th>
                            <th>Date Received</th>
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

                                <!-- Custodian -->
                                <td>
                                    <small class="text-muted">{{ $doc->currentCustodian->name ?? 'Unassigned' }}</small>
                                </td>

                                <!-- Urgency -->
                                <td>
                                    @if ($doc->urgency === 'Immediate')
                                        <span class="badge bg-danger">Immediate</span>
                                    @elseif($doc->urgency === 'Urgent')
                                        <span class="badge bg-warning text-dark">Urgent</span>
                                    @else
                                        <span class="badge bg-secondary">Normal</span>
                                    @endif
                                </td>

                                <!-- Date Received -->
                                <td>
                                    <small class="text-muted">{{ $doc->updated_at->format('M d, Y h:i A') }}</small>
                                </td>

                                <!-- Action Buttons -->
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <!-- Inspect -->
                                        <a href="{{ route('documents.show', $doc->id) }}"
                                            class="btn btn-sm btn-outline-secondary" title="View Details">
                                            <i data-feather="eye" class="feather-sm"></i>
                                        </a>


                                        <!-- Forward Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#forwardModal-{{ $doc->id }}" title="Forward Document">
                                            <i data-feather="send" class="feather-sm me-1"></i> Forward
                                        </button>
                                        <!-- Complete / File Modal Trigger (NEW) -->
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#completeModal-{{ $doc->id }}"
                                            title="Mark as Completed / Filed">
                                            <i data-feather="check-circle" class="feather-sm me-1"></i> Complete / File
                                        </button>

                                        <!-- Release Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                            data-bs-target="#releaseModal-{{ $doc->id }}" title="Release Document">
                                            <i data-feather="external-link" class="feather-sm me-1"></i> Release
                                        </button>

                                        <!-- Archive Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#archiveModal-{{ $doc->id }}" title="Archive Document">
                                            <i data-feather="archive" class="feather-sm me-1"></i> Archive
                                        </button>
                                    </div>

                                    <!-- Modal: Forward Document -->
                                    <div class="modal fade text-start" id="forwardModal-{{ $doc->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('documents.forward', $doc->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title text-white"><i data-feather="send"
                                                                class="me-1"></i> Forward Document</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label font-weight-bold">Target Office <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="to_office_id" class="form-select" required>
                                                                <option value="" disabled selected>Select destination
                                                                    office...</option>
                                                                @foreach ($offices as $office)
                                                                    @if ($office->id !== auth()->user()->office_id)
                                                                        <option value="{{ $office->id }}">
                                                                            {{ $office->name }} ({{ $office->code }})
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label font-weight-bold">Forwarding
                                                                Remarks</label>
                                                            <textarea name="remarks" class="form-control" rows="3" placeholder="Specify instructions or purpose..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Dispatch
                                                            Document</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal: Release Document -->
                                    <div class="modal fade text-start" id="releaseModal-{{ $doc->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('documents.release', $doc->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title text-white"><i data-feather="external-link"
                                                                class="me-1"></i> Release Document</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Mark this document as <strong>Released</strong> to an external
                                                            party or end-user applicant.</p>
                                                        <div class="mb-3">
                                                            <label class="form-label font-weight-bold">Released To /
                                                                Recipient Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="released_to" class="form-control"
                                                                placeholder="e.g. Juan Dela Cruz / External Client"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label font-weight-bold">Release Notes /
                                                                Instructions</label>
                                                            <textarea name="remarks" class="form-control" rows="3"
                                                                placeholder="e.g. Handed physical copy to claiming party."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-info text-white">Confirm
                                                            Release</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal: Archive Document -->
                                    <div class="modal fade text-start" id="archiveModal-{{ $doc->id }}"
                                        tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('documents.archive', $doc->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-dark text-white">
                                                        <h5 class="modal-title text-white"><i data-feather="archive"
                                                                class="me-1"></i> Archive Document</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Move this completed document to the <strong>Archived
                                                                Vault</strong>.</p>
                                                        <div class="mb-3">
                                                            <label class="form-label font-weight-bold">Archival
                                                                Remarks</label>
                                                            <textarea name="remarks" class="form-control" rows="3"
                                                                placeholder="e.g. Processing completed, filed for records keeping."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-dark">Archive
                                                            Record</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal: Complete / File Document -->
                                    <div class="modal fade text-start" id="completeModal-{{ $doc->id }}"
                                        tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('documents.complete', $doc->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title text-white"><i data-feather="check-circle"
                                                                class="me-1"></i> Complete &
                                                            File Document</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2">Mark this document as <strong>Completed /
                                                                Filed</strong> for your office?</p>
                                                        <div class="p-3 bg-light rounded border mb-3">
                                                            <div class="fw-bold text-primary">{{ $doc->tracking_number }}
                                                            </div>
                                                            <div class="small fw-semibold text-dark">{{ $doc->title }}
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label font-weight-bold">Completion / Filing
                                                                Remarks</label>
                                                            <textarea name="remarks" class="form-control" rows="3"
                                                                placeholder="e.g. Action completed. Document filed for reference in local office records."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Mark as
                                                            Completed</button>
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
                                    <p class="mb-0">No pending documents requiring action in your office!</p>
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
                        {{ $documents->total() }} pending documents
                    </span>
                    <div>
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>



@endsection
