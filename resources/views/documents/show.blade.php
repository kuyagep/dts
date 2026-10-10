@extends('layouts.main')

@section('title', 'Document Details - ' . $document->tracking_number)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Document Tracking: <span class="text-primary">{{ $document->tracking_number }}</span></h1>
            <p class="text-muted mb-0">Created on {{ $document->created_at->format('F d, Y h:i A') }} by
                {{ $document->creator->name ?? 'System' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                <i data-feather="arrow-left" class="feather-sm"></i> Back to Index
            </a>

            <!-- Document Action Buttons -->
            @can('documents.forward')
                @if ($document->status !== 'Archived' && $document->status !== 'Approved')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#forwardModal">
                        <i data-feather="send" class="feather-sm me-1"></i> Forward Document
                    </button>
                @endif
            @endcan

            @can('documents.approve')
                @if ($document->status !== 'Approved' && $document->status !== 'Archived')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i data-feather="check-circle" class="feather-sm me-1"></i> Approve & Complete
                    </button>
                @endif
            @endcan
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Primary Metadata & Attached File -->
        <div class="col-lg-8">
            <!-- Main Document Details Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Document Details</h5>
                    @switch($document->status)
                        @case('In Transit')
                            <span class="badge bg-info text-dark fs-6"><i data-feather="send" class="feather-sm me-1"></i> In
                                Transit</span>
                        @break

                        @case('Received')
                            <span class="badge bg-primary fs-6"><i data-feather="check-circle" class="feather-sm me-1"></i>
                                Received</span>
                        @break

                        @case('In Review')
                            <span class="badge bg-warning text-dark fs-6"><i data-feather="eye" class="feather-sm me-1"></i> In
                                Review</span>
                        @break

                        @case('Approved')
                            <span class="badge bg-success fs-6"><i data-feather="check" class="feather-sm me-1"></i> Approved</span>
                        @break

                        @case('Archived')
                            <span class="badge bg-dark fs-6"><i data-feather="archive" class="feather-sm me-1"></i> Archived</span>
                        @break

                        @default
                            <span class="badge bg-secondary fs-6">{{ $document->status }}</span>
                    @endswitch
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="text-muted small d-block">Document Title</label>
                            <h4 class="fw-bold text-dark mb-0">{{ $document->title }}</h4>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block">Document Type</label>
                            <span class="fw-semibold text-dark">{{ $document->documentType->name ?? 'Unclassified' }}</span>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block">Urgency Level</label>
                            @if ($document->urgency === 'Immediate')
                                <span class="badge bg-danger">Immediate</span>
                            @elseif($document->urgency === 'Urgent')
                                <span class="badge bg-warning text-dark">Urgent</span>
                            @else
                                <span class="badge bg-secondary">Normal</span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block">Originating Office</label>
                            <span class="fw-semibold text-dark">{{ $document->originatingOffice->name ?? 'N/A' }}</span>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block">Current Location & Custodian</label>
                            <span class="fw-semibold text-dark">{{ $document->currentOffice->name ?? 'In Transit' }}</span>
                            @if ($document->currentCustodian)
                                <small class="text-muted d-block">({{ $document->currentCustodian->name }})</small>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label class="text-muted small d-block">Description / Remarks</label>
                            <p class="text-dark bg-light p-3 rounded border mb-0">
                                {{ $document->description ?? 'No specific description provided.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>



            <!-- History -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0"><i data-feather="list" class="feather-sm me-1 text-primary"></i> Detailed
                        Audit History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Timestamp</th>
                                    <th>Action Performed</th>
                                    <th>Actor</th>
                                    <th>Office</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($document->logs as $log)
                                    <tr>
                                        <td class="ps-3"><small
                                                class="text-muted">{{ $log->created_at->format('M d, Y h:i A') }}</small>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $log->action }}</span></td>
                                        <td><span class="small fw-semibold">{{ $log->user->name ?? 'System' }}</span></td>
                                        <td><span class="small">{{ $log->office->code ?? 'N/A' }}</span>
                                        </td>
                                        <td><small class="text-muted">{{ $log->remarks ?? '-' }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">No audit logs recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Verification QR Code & Routing Sequence Timeline -->
        <div class="col-lg-4">
            <!-- Verification QR Code Card -->
            <div class="card shadow-sm border-0 mb-4 text-center">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">Tracking QR Code</h5>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-white d-inline-block rounded border mb-2">
                        <!-- Inline SVG QR Code Generator via BaconQrCode / Simple-QrCode -->
                        {!! QrCode::size(160)->generate(route('documents.show', $document->id)) !!}
                    </div>
                    <small class="text-muted d-block">Scan to quickly open or verify this document route on mobile
                        devices.</small>
                </div>
            </div>

            <!-- Routing Timeline Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0"><i data-feather="navigation" class="feather-sm me-1 text-primary"></i>
                        Routing Sequence</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline list-unstyled mb-0">
                        @forelse($document->routes as $route)
                            <li class="mb-3 position-relative ps-4 border-start border-2 border-primary">
                                <div class="small fw-bold text-primary">{{ $route->fromOffice->code ?? 'Origin' }} &rarr;
                                    {{ $route->toOffice->code ?? 'Destination' }}</div>
                                <div class="small fw-semibold text-dark">{{ $route->action_taken ?? 'Forwarded' }}</div>
                                @if ($route->remarks)
                                    <div class="small text-muted fst-italic">"{{ $route->remarks }}"</div>
                                @endif
                                <small
                                    class="text-muted d-block fs-7 mt-1">{{ $route->created_at->format('M d, Y h:i A') }}</small>
                            </li>
                        @empty
                            <li class="text-muted small">No route transitions recorded.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Forward Document -->
    <div class="modal fade" id="forwardModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('documents.forward', $document->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Forward Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Destination Office <span
                                    class="text-danger">*</span></label>
                            <select name="to_office_id" class="form-select" required>
                                <option value="" disabled selected>Select destination office...</option>
                                @foreach ($offices as $office)
                                    @if ($office->id !== $document->current_office_id)
                                        <option value="{{ $office->id }}">{{ $office->name }} ({{ $office->code }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Action Required / Routing Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="e.g. For evaluation and endorsement."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Dispatch & Forward</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Approve Document -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('documents.approve', $document->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white">Approve & Finalize Document</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">Are you sure you want to mark this document as <strong>Approved</strong>?</p>
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Final Approval Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3"
                                placeholder="e.g. Document approved by Division Chief."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Approve Document</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
