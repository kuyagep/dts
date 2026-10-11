@extends('layouts.main')

@section('title', 'Edit Document - ' . $document->tracking_number)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold">Edit Document</h1>
            <p class="text-muted small mb-0">
                Tracking Number: <strong class="text-primary font-monospace">{{ $document->tracking_number }}</strong>
            </p>
        </div>
        <a href="{{ route('documents.rejected') }}" class="btn btn-outline-secondary">
            <i data-feather="arrow-left" class="feather-sm me-1"></i> Back to Rejected List
        </a>
    </div>

    @if ($document->status === 'Rejected')
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

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark">Document Information</h5>
                </div>
                <div class="card-body p-4">
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

                        <div class="col-md-12 mb-3">
                            <label for="destination_office_id" class="form-label fw-bold">Forward To (Destination Office)
                                <span class="text-danger">*</span></label>
                            <select class="form-select @error('destination_office_id') is-invalid @enderror"
                                id="destination_office_id" name="destination_office_id" required>
                                <option value="" disabled
                                    {{ old('destination_office_id', $document->current_office_id) ? '' : 'selected' }}>
                                    Select destination office...</option>
                                @foreach ($offices as $office)
                                    @if ($office->id !== auth()->user()->office_id)
                                        <option value="{{ $office->id }}"
                                            {{ old('destination_office_id', $document->current_office_id) == $office->id ? 'selected' : '' }}>
                                            {{ $office->name }} ({{ $office->code }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('destination_office_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <a href="{{ route('documents.show', $document->id) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i data-feather="save" class="feather-sm me-1"></i>
                                {{ $document->status === 'Rejected' ? 'Save & Resubmit Document' : 'Update Document Details' }}
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
                        <span
                            class="badge {{ $document->status === 'Rejected' ? 'bg-danger' : 'bg-primary' }} px-3 py-2 fs-7 fw-semibold">
                            {{ $document->status }}
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
