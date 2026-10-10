@extends('layouts.main')

@section('title', 'Create New Document')

@section('content')
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Create & Route Document</h1>
        <p class="text-muted">Register a new document and initiate its division tracking route.</p>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0">Document Metadata & Routing Information</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <!-- Title -->
                            <div class="col-12">
                                <label for="title" class="form-label font-weight-bold">Document Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}"
                                    placeholder="e.g. Purchase Request for IT Supplies Q4 2026" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Document Type -->
                            <div class="col-md-6">
                                <label for="document_type_id" class="form-label font-weight-bold">Document Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('document_type_id') is-invalid @enderror"
                                    id="document_type_id" name="document_type_id" required>
                                    <option value="" disabled selected>Choose document classification...</option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }} ({{ $type->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('document_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Urgency Level -->
                            <div class="col-md-6">
                                <label for="urgency" class="form-label font-weight-bold">Urgency Level <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('urgency') is-invalid @enderror" id="urgency"
                                    name="urgency" required>
                                    <option value="Normal" {{ old('urgency', 'Normal') == 'Normal' ? 'selected' : '' }}>
                                        Normal (Standard Processing)</option>
                                    <option value="Urgent" {{ old('urgency') == 'Urgent' ? 'selected' : '' }}>Urgent
                                        (Requires Priority)</option>
                                    <option value="Immediate" {{ old('urgency') == 'Immediate' ? 'selected' : '' }}>
                                        Immediate (Action within 24 Hours)</option>
                                </select>
                                @error('urgency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Routing: Origin & Destination -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Originating Office</label>
                                <input type="text" class="form-control bg-light"
                                    value="{{ auth()->user()->office->name ?? 'Unassigned Office' }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label for="destination_office_id" class="form-label font-weight-bold">Forward To
                                    (Destination Office) <span class="text-danger">*</span></label>
                                <select class="form-select @error('destination_office_id') is-invalid @enderror"
                                    id="destination_office_id" name="destination_office_id" required>
                                    <option value="" disabled selected>Select destination office...</option>
                                    @foreach ($offices as $office)
                                        @if ($office->id !== auth()->user()->office_id)
                                            <option value="{{ $office->id }}"
                                                {{ old('destination_office_id') == $office->id ? 'selected' : '' }}>
                                                {{ $office->name }} ({{ $office->code }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('destination_office_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label font-weight-bold">Description / Subject
                                    Details</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" placeholder="Brief background or purpose of the document...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div class="col-12">
                                <label for="attachment" class="form-label font-weight-bold">Attach Scanned Document / File
                                    <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                    id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.png" required>
                                <div class="form-text">Accepted formats: PDF, DOC, DOCX, JPG, PNG (Max 10MB).</div>
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <label for="remarks" class="form-label font-weight-bold">Dispatch Remarks</label>
                                <input type="text" class="form-control @error('remarks') is-invalid @enderror"
                                    id="remarks" name="remarks" value="{{ old('remarks') }}"
                                    placeholder="e.g. Please review section B and provide approval signature.">
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="align-middle me-1" data-feather="send"></i> Save & Route Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
