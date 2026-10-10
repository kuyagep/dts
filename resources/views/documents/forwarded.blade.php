@extends('layouts.main')

@section('title', 'Forwarded Documents')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Dispatched & Forwarded Documents</h1>
            <p class="text-muted mb-0">
                Documents originated or dispatched by <strong>{{ auth()->user()->office->name ?? 'Your Office' }}</strong>
                to other offices.
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
                            <th>Destination Office</th>
                            <th>Dispatched By</th>
                            <th>Current Status</th>
                            <th>Date Dispatched</th>
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

                                <!-- Destination Office -->
                                <td>
                                    <span class="fw-semibold text-dark">{{ $doc->currentOffice->name ?? 'N/A' }}</span>
                                    <small class="text-muted d-block">({{ $doc->currentOffice->code ?? '-' }})</small>
                                </td>

                                <!-- Sender / Dispatched By -->
                                <td>
                                    <small class="text-muted">{{ $doc->creator->name ?? 'System' }}</small>
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
                                                    class="feather-sm me-1"></i> Received by Target</span>
                                        @break

                                        @case('In Review')
                                            <span class="badge bg-warning text-dark"><i data-feather="eye"
                                                    class="feather-sm me-1"></i> Under Review</span>
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

                                <!-- Date Dispatched -->
                                <td>
                                    <small class="text-muted">{{ $doc->updated_at->format('M d, Y h:i A') }}</small>
                                </td>

                                <!-- Action Button -->
                                <td class="text-end pe-3">
                                    <a href="{{ route('documents.show', $doc->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i data-feather="eye" class="feather-sm me-1"></i> Track & Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i data-feather="send" class="mb-2" style="width: 40px; height: 40px;"></i>
                                        <p class="mb-0">No documents have been dispatched or forwarded from your office yet.
                                        </p>
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
                            {{ $documents->total() }} dispatched documents
                        </span>
                        <div>
                            {{ $documents->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endsection
