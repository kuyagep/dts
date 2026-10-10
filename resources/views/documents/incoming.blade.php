@extends('layouts.main')

@section('title', 'Incoming Queue')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Incoming Document Queue</h1>
            <p class="text-muted mb-0">
                Documents currently in transit to <strong>{{ auth()->user()->office->name ?? 'Your Office' }}</strong>
                awaiting official receipt.
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
                            <th>Document Title & Type</th>
                            <th>Origin Office</th>
                            <th>Dispatched By</th>
                            <th>Urgency</th>
                            <th>Date Forwarded</th>
                            <th class="text-end pe-3">Actions</th>
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

                                <!-- Dispatcher/Creator -->
                                <td>
                                    <small class="text-muted">{{ $doc->creator->name ?? 'System' }}</small>
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

                                <!-- Date Dispatched -->
                                <td>
                                    <small class="text-muted">{{ $doc->updated_at->format('M d, Y h:i A') }}</small>
                                </td>

                                <!-- Actions -->
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <a href="{{ route('documents.show', $doc->id) }}"
                                            class="btn btn-sm btn-outline-secondary" title="View Details">
                                            <i data-feather="eye" class="feather-sm"></i> View
                                        </a>

                                        <!-- Receive Button (Triggers Intake Modal with Remarks) -->
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                            data-bs-target="#receiveModal-{{ $doc->id }}" title="Receive with Remarks">
                                            <i data-feather="inbox" class="feather-sm me-1"></i> Receive...
                                        </button>

                                    </div>

                                    <!-- Receive Confirmation Modal -->
                                    <div class="modal fade text-start" id="receiveModal-{{ $doc->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('documents.receive', $doc->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title text-white">
                                                            <i data-feather="inbox" class="me-1"></i> Confirm Document
                                                            Intake
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2">Are you sure you want to mark this document as
                                                            <strong>Received</strong>?
                                                        </p>
                                                        <div class="p-3 bg-light rounded border mb-3">
                                                            <div class="fw-bold text-primary">{{ $doc->tracking_number }}
                                                            </div>
                                                            <div class="small fw-semibold">{{ $doc->title }}</div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="remarks-{{ $doc->id }}"
                                                                class="form-label font-weight-bold">Intake Remarks /
                                                                Notes</label>
                                                            <input type="text" name="remarks"
                                                                id="remarks-{{ $doc->id }}" class="form-control"
                                                                placeholder="e.g. Received physical copy with complete annexes.">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Confirm
                                                            Receipt</button>
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
                                    <i data-feather="inbox" class="mb-2" style="width: 40px; height: 40px;"></i>
                                    <p class="mb-0">Your incoming queue is completely clear!</p>
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
                        {{ $documents->total() }} incoming documents
                    </span>
                    <div>
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>


@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.accept-doc-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.accept-doc-form');

                    Swal.fire({
                        title: 'Accept Document?',
                        text: "Confirm receipt of this document into your office queue.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Accept',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
