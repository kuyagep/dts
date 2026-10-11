@extends('layouts.main')

@section('title', 'Receive Batch Transmittal')

@section('content')
    <div class="container-fluid px-0">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 text-white fw-bold">
                            <i data-feather="box" class="me-1"></i> Batch Verification: {{ $batch->batch_number }}
                        </h5>
                        <small class="text-white-50">Origin: {{ $batch->fromOffice->name }} &rarr; Destination:
                            {{ $batch->toOffice->name }}</small>
                    </div>
                    <span class="badge bg-light text-primary px-3 py-2 fw-bold">
                        {{ $batch->routes->count() }} Documents
                    </span>
                </div>
            </div>

            <form action="{{ route('batches.receive.process', $batch->id) }}" method="POST">
                @csrf
                <div class="card-body p-0">
                    <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold text-dark">Physical Presence Verification</span>
                            <p class="text-muted small mb-0">Check each physical document present in the folder. Unchecked
                                items will be marked as <strong>Rejected / Missing</strong>.</p>
                        </div>
                        <div>
                            <button type="button" id="selectAllBtn" class="btn btn-sm btn-outline-secondary">Select
                                All</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 50px;">Verify</th>
                                    <th>Tracking Number</th>
                                    <th>Document Title</th>
                                    <th>Type</th>
                                    <th>Individual Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($batch->routes as $route)
                                    <tr>
                                        <td class="ps-3">
                                            <input type="checkbox" name="received_documents[]"
                                                value="{{ $route->document_id }}" class="form-check-input doc-check-item"
                                                checked style="width: 22px; height: 22px;">
                                        </td>
                                        <td>
                                            <span
                                                class="fw-bold text-primary">{{ $route->document->tracking_number }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $route->document->title }}</div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light text-dark border">{{ $route->document->documentType->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <select name="action_notes[{{ $route->document_id }}]"
                                                class="form-select form-select-sm">
                                                <option value="Received in good order">Received - Normal</option>
                                                <option value="Received with missing attachments">Received - Incomplete
                                                    Attachments</option>
                                                <option value="Rejected - Physical document missing">Reject - Document
                                                    Missing</option>
                                                <option value="Rejected - Wrong destination office">Reject - Wrong Office
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 bg-light border-top">
                        <div class="mb-3">
                            <label for="batch_remarks" class="form-label fw-bold">Overall Batch Remarks / Receiving
                                Notes:</label>
                            <textarea name="batch_remarks" id="batch_remarks" class="form-control" rows="2"
                                placeholder="Optional notes regarding the physical delivery..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                    <a href="{{ route('documents.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-success px-4">
                        <i data-feather="check-circle" class="me-1"></i> Finalize Batch Receipt & Process Items
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllBtn = document.getElementById('selectAllBtn');
            const checkboxes = document.querySelectorAll('.doc-check-item');
            let allChecked = true;

            selectAllBtn.addEventListener('click', function() {
                allChecked = !allChecked;
                checkboxes.forEach(cb => cb.checked = allChecked);
                selectAllBtn.textContent = allChecked ? 'Deselect All' : 'Select All';
            });
        });
    </script>
@endsection
