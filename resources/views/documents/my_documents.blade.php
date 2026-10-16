@extends('layouts.main')

@section('title', 'My Documents')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">My Documents</h1>
            <p class="text-muted mb-0">
                All records and transmittals created or authored by <strong>{{ auth()->user()->name }}</strong>.
            </p>
        </div>
        <a href="{{ route('documents.create') }}" class="btn btn-success btn-lg shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> New Document
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-3">
            <form action="{{ route('documents.my-documents') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by tracking number or title..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">-- All Statuses --</option>
                        <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="In Transit" {{ request('status') === 'In Transit' ? 'selected' : '' }}>In Transit
                        </option>
                        <option value="Received" {{ request('status') === 'Received' ? 'selected' : '' }}>Received / Pending
                        </option>
                        <option value="In Review" {{ request('status') === 'In Review' ? 'selected' : '' }}>In Review
                        </option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed /
                            Filed</option>
                        <option value="Released" {{ request('status') === 'Released' ? 'selected' : '' }}>Released</option>
                        <option value="Archived" {{ request('status') === 'Archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary w-100"><i data-feather="filter"
                            class="feather-sm me-1"></i> Filter</button>
                    @if (request()->hasAny(['search', 'status']))
                        <a href="{{ route('documents.my-documents') }}" class="btn btn-light"><i data-feather="x"
                                class="feather-sm"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Tracking Number</th>
                            <th>Title & Type</th>
                            <th>Current Office</th>
                            <th>Status</th>
                            <th>Date Created</th>
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
                                    <span class="text-muted small">
                                        Type: {{ $doc->documentType->name ?? 'Unclassified' }}
                                    </span>
                                </td>

                                <!-- Current Office Location -->
                                <td>
                                    <span class="fw-semibold text-dark">{{ $doc->currentOffice->name ?? 'N/A' }}</span>
                                    <small class="text-muted d-block">({{ $doc->currentOffice->code ?? '-' }})</small>
                                </td>

                                <!-- Status Badge -->
                                <td>
                                    @switch($doc->status)
                                        @case('In Transit')
                                            <span class="badge bg-info "><i data-feather="send" class="feather-sm me-1"></i> In
                                                Transit</span>
                                        @break

                                        @case('Received')
                                            <span class="badge bg-primary"><i data-feather="inbox" class="feather-sm me-1"></i>
                                                Received</span>
                                        @break

                                        @case('In Review')
                                            <span class="badge bg-warning text-dark"><i data-feather="clock"
                                                    class="feather-sm me-1"></i> In Review</span>
                                        @break

                                        @case('Completed')
                                            <span class="badge bg-success"><i data-feather="check-circle"
                                                    class="feather-sm me-1"></i> Completed</span>
                                        @break

                                        @case('Released')
                                            <span class="badge bg-secondary"><i data-feather="external-link"
                                                    class="feather-sm me-1"></i> Released</span>
                                        @break

                                        @case('Archived')
                                            <span class="badge bg-dark"><i data-feather="archive" class="feather-sm me-1"></i>
                                                Archived</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">{{ $doc->status }}</span>
                                    @endswitch
                                </td>

                                <!-- Created Date -->
                                <td>
                                    <small class="text-muted">{{ $doc->created_at->format('M d, Y h:i A') }}</small>
                                </td>

                                <!-- Actions -->
                                <td class="text-end pe-3">
                                    <a href="{{ route('documents.show', $doc->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-qrcode"></i> Track
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i data-feather="file-text" class="mb-2" style="width: 40px; height: 40px;"></i>
                                        <p class="mb-0">You haven't created any documents yet.</p>
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
                            {{ $documents->total() }} authored documents
                        </span>
                        <div>
                            {{ $documents->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endsection
