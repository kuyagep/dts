@extends('layouts.main')

@section('title', 'Document Tracking Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Document Tracking Overview</h1>
            <p class="text-muted small mb-0">
                Welcome back, <strong>{{ auth()->user()->name }}</strong>
                ({{ auth()->user()->office->code ?? 'Division Office' }})
            </p>
        </div>

    </div>


    <div class="d-flex gap-2 mb-3">
        <!-- Main Camera Scan Button -->
        <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#qrScanModal">
            <i class="fa-solid fa-qrcode me-1"></i> Scan QR Code
        </button>
        <a href="{{ route('documents.create') }}" class="btn btn-success btn-lg shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> New Document
        </a>
        <a href="{{ route('documents.transit') }}" class="btn btn-info btn-lg">
            <i class="fa-regular fa-paper-plane me-1"></i> In Transit
        </a>
    </div>
    <!-- Main Content Grid -->
    <div class="row g-3">
        <!-- Recent Documents Table -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0">Document Information</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">


                            <!-- Document Type -->
                            <div class="col-md-8">
                                <label for="document_type_id" class="form-label font-weight-bold">Document Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('document_type_id') is-invalid @enderror"
                                    id="document_type_id" name="document_type_id" required>
                                    <option value="" disabled selected>Choose document classification...</option>
                                    @foreach ($documentTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('document_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Urgency Level -->
                            <div class="col-md-4">
                                <label for="priority" class="form-label font-weight-bold">Priority Level <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="urgency"
                                    name="priority" required>
                                    <option value="Normal" {{ old('priority', 'Normal') == 'Normal' ? 'selected' : '' }}>
                                        Normal (Standard Processing)</option>
                                    <option value="Urgent" {{ old('priority') == 'Urgent' ? 'selected' : '' }}>Urgent
                                        (Requires Priority)</option>
                                    <option value="Immediate" {{ old('priority') == 'Immediate' ? 'selected' : '' }}>
                                        Immediate (Action within 24 Hours)</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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


                            <div class="col-md-12">
                                <label for="destination_office_id" class="form-label font-weight-bold">
                                    Forward To (Destination Office) <span class="text-danger">*</span>
                                </label>

                                <select
                                    class="form-select select2-select @error('destination_office_id') is-invalid @enderror"
                                    id="destination_office_id" name="destination_office_id" required>
                                    <option value="" disabled {{ old('destination_office_id') ? '' : 'selected' }}>
                                        Select destination office...</option>
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
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Remarks -->
                            <div class="col-12">
                                <label for="remarks" class="form-label font-weight-bold">Remarks</label>
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
                                <i class="align-middle me-1" data-feather="send"></i> Route Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Search & Recent Activity Stream -->
        <div class="col-lg-4">
            <!-- Quick Search Box -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">Quick Search</h6>
                    <form action="{{ route('documents.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-lg"
                                placeholder="Enter Tracking No..." required>
                            <button class="btn btn-primary" type="submit">
                                <i data-feather="search" class="feather-sm"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stat Cards Row -->
            <div class="row g-3 mb-4">
                <!-- Incoming / Pending -->
                <div class="col-xl-6 col-md-12">
                    <div class="card border-0 shadow-sm border-start border-4 border-warning h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted text-uppercase fw-bold fs-7">Incoming</span>
                                    <h2 class="mt-2 mb-0 fw-bold text-dark">{{ $pendingCount ?? 12 }}</h2>
                                </div>
                                <div class="p-3 bg-warning-subtle rounded-circle text-warning">
                                    <i data-feather="inbox" style="width: 24px; height: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Transit -->
                <div class="col-xl-6 col-md-12">
                    <div class="card border-0 shadow-sm border-start border-4 border-info h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted text-uppercase fw-bold fs-7">In Transit</span>
                                    <h2 class="mt-2 mb-0 fw-bold text-dark">{{ $transitCount ?? 8 }}</h2>
                                </div>
                                <div class="p-3 bg-info-subtle rounded-circle text-info">
                                    <i data-feather="send" style="width: 24px; height: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Review -->
                <div class="col-xl-6 col-md-12">
                    <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted text-uppercase fw-bold fs-7">In Review</span>
                                    <h2 class="mt-2 mb-0 fw-bold text-dark">{{ $inReviewCount ?? 5 }}</h2>
                                </div>
                                <div class="p-3 bg-primary-subtle rounded-circle text-primary">
                                    <i data-feather="clock" style="width: 24px; height: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed / Filed -->
                <div class="col-xl-6 col-md-12">
                    <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted text-uppercase fw-bold fs-7">Completed</span>
                                    <h2 class="mt-2 mb-0 fw-bold text-dark">{{ $completedCount ?? 42 }}</h2>
                                </div>
                                <div class="p-3 bg-success-subtle rounded-circle text-success">
                                    <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
