@extends('layouts.main')

@section('title', 'Edit Document - ' . $document->tracking_number)

@section('content')
    @php
        $statusLower = strtolower($document->status);
        $isRejected = $statusLower === 'rejected';
        $isInTransit = in_array($statusLower, ['in_transit', 'in transit']);
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold">Edit Document</h1>
            <p class="text-muted small mb-0">
                Tracking Number: <strong class="text-primary font-monospace">{{ $document->tracking_number }}</strong>
            </p>
        </div>
        <a href="{{ $isRejected ? route('documents.rejected') : route('documents.show', $document->id) }}"
            class="btn btn-outline-secondary">
            <i data-feather="arrow-left" class="feather-sm me-1"></i>
            {{ $isRejected ? 'Back to Rejected List' : 'Back to Document' }}
        </a>
    </div>

    @if ($isRejected)
        <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-start p-3">
            <i data-feather="alert-octagon" class="feather-md me-2 mt-1 flex-shrink-0"></i>
            <div>
                <h6 class="fw-bold mb-1">Document Flagged / Rejected</h6>
                <p class="small mb-0">
                    This document was returned due to a discrepancy or missing physical copy. Review and fix the details
                    below, then click <strong>"Save & Resubmit"</strong> to update its status to Pending.
                </p>
            </div>
        </div>
    @endif

    @if ($isInTransit)
        <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-start p-3" role="alert">
            <i data-feather="alert-triangle" class="feather-md me-2 mt-1 flex-shrink-0"></i>
            <div>
                <h6 class="fw-bold mb-1">Document Currently In Transit</h6>
                <p class="small mb-0">
                    You may only update basic details (Title, Document Type, and Description). Destination routing is locked
                    while the document is in transit.
                </p>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark">Document Information</h5>
                </div>
                <div class="card-body p-3 p-sm-4">
                    <form action="{{ route('documents.update', $document->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Document Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Document Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $document->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Document Type -->
                        <div class="mb-3">
                            <label for="document_type_id" class="form-label fw-bold">Document Type <span
                                    class="text-danger">*</span></label>
                            <select name="document_type_id" id="document_type_id"
                                class="form-select @error('document_type_id') is-invalid @enderror" required>
                                <option value="">-- Select Type --</option>
                                @foreach ($documentTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('document_type_id', $document->document_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('document_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description / Remarks -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description / Purpose</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                rows="4" placeholder="Brief details about this document request or report...">{{ old('description', $document->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Destination Office -->
                        <div class="mb-4">
                            <label for="destination_office_id" class="form-label fw-bold">
                                Forward To (Destination Office) <span class="text-danger">*</span>
                            </label>

                            <select class="form-select @error('destination_office_id') is-invalid @enderror"
                                id="destination_office_id" name="destination_office_id" required>
                                <option value="" disabled>Select destination office...</option>
                                @foreach ($offices as $office)
                                    @if ($office->id !== auth()->user()->office_id)
                                        <option value="{{ $office->id }}"
                                            {{ old('destination_office_id', $document->current_office_id) == $office->id ? 'selected' : '' }}>
                                            {{ $office->name }} ({{ $office->code }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            @if ($isInTransit)
                                <div class="form-text text-warning small">
                                    <i data-feather="alert-circle" class="feather-xs me-1"></i>
                                    Changing the destination office will immediately reroute this document and notify the
                                    new receiving office.
                                </div>
                            @endif

                            @error('destination_office_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-top pt-3">
                            <a href="{{ route('documents.show', $document->id) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i data-feather="save" class="feather-sm me-1"></i>
                                {{ $isRejected ? 'Save & Resubmit Document' : 'Update Document Details' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Sidebar Metadata Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="card-title mb-0 fw-bold">Status Overview</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Current Status:</span>
                        @php
                            $badgeBg = match (true) {
                                $isRejected => 'bg-danger',
                                $isInTransit => 'bg-warning text-dark',
                                default => 'bg-primary',
                            };
                        @endphp
                        <span class="badge {{ $badgeBg }} px-3 py-2 fw-semibold">
                            {{ ucwords(str_replace('_', ' ', $document->status)) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Originating Office:</span>
                        <strong class="text-dark">{{ $document->originatingOffice->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Current Custodian:</span>
                        <strong class="text-dark">{{ $document->currentOffice->name ?? 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-muted small d-block mb-1">Created At:</span>
                        <span
                            class="font-monospace small text-dark">{{ $document->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
