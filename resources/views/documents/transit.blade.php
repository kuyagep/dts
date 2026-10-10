@extends('layouts.main')

@section('title', 'In Transit Documents')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-0">In Transit Documents</h1>
                <p class="text-muted mb-0">
                    Documents dispatched from <strong>{{ auth()->user()->office->name ?? 'Your Office' }}</strong> currently
                    awaiting receipt at destination offices.
                </p>
            </div>
            <div>
                <!-- Print Transmittal Slip Button -->
                <button type="button" id="printTransmittalBtn" class="btn btn-primary" disabled>
                    <i data-feather="printer" class="feather-sm me-1"></i> Print Transmittal (<span
                        id="selectedCount">0</span>)
                </button>
                <iframe id="printFrame" name="printFrame" style="display: none; width: 0; height: 0; border: 0;"></iframe>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 40px;">
                                <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                            </th>
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
                                <!-- Row Checkbox -->
                                <td class="ps-3">
                                    <input type="checkbox" name="document_ids[]" value="{{ $doc->id }}"
                                        class="form-check-input doc-checkbox">
                                </td>
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
                                    <span class="fw-semibold text-dark">{{ $doc->destinationOffice->name ?? 'N/A' }}</span>
                                    <small class="text-muted d-block">({{ $doc->destinationOffice->code ?? '-' }})</small>
                                </td>

                                <!-- Dispatched By -->
                                <td>
                                    <small class="text-muted">{{ $doc->creator->name ?? 'System' }}</small>
                                </td>

                                <!-- Status Badge -->
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <i data-feather="send" class="feather-sm me-1"></i> In Transit
                                    </span>
                                </td>

                                <!-- Date Dispatched -->
                                <td>
                                    <small class="text-muted">{{ $doc->updated_at->format('M d, Y h:i A') }}</small>
                                </td>

                                <!-- Actions -->
                                <td class="text-end pe-3">
                                    <a href="{{ route('documents.show', $doc->id) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i data-feather="eye" class="feather-sm me-1"></i> Track & Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i data-feather="send" class="mb-2" style="width: 40px; height: 40px;"></i>
                                    <p class="mb-0">No documents currently in transit from your office.</p>
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
                        {{ $documents->total() }} in-transit documents
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
    <!-- JS Script for Checkbox Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAllCheckbox');
            const checkboxes = document.querySelectorAll('.doc-checkbox');
            const printBtn = document.getElementById('printTransmittalBtn');
            const selectedCountSpan = document.getElementById('selectedCount');

            function updateButtonState() {
                const checkedCount = document.querySelectorAll('.doc-checkbox:checked').length;
                selectedCountSpan.textContent = checkedCount;
                printBtn.disabled = checkedCount === 0;
            }

            // Select All Toggle
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateButtonState();
                });
            }

            // Individual Checkbox Toggle
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (!this.checked && selectAll) {
                        selectAll.checked = false;
                    } else if (document.querySelectorAll('.doc-checkbox:checked').length ===
                        checkboxes.length) {
                        selectAll.checked = true;
                    }
                    updateButtonState();
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAllCheckbox');
            const checkboxes = document.querySelectorAll('.doc-checkbox');
            const printBtn = document.getElementById('printTransmittalBtn');
            const selectedCountSpan = document.getElementById('selectedCount');
            const printFrame = document.getElementById('printFrame');

            // Update button enable/disable state and selected badge counter
            function updateButtonState() {
                const checkedCount = document.querySelectorAll('.doc-checkbox:checked').length;
                if (selectedCountSpan) selectedCountSpan.textContent = checkedCount;
                if (printBtn) printBtn.disabled = checkedCount === 0;
            }

            // Select All Checkbox logic
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateButtonState();
                });
            }

            // Individual Checkbox logic
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (!this.checked && selectAll) {
                        selectAll.checked = false;
                    } else if (document.querySelectorAll('.doc-checkbox:checked').length ===
                        checkboxes.length) {
                        selectAll.checked = true;
                    }
                    updateButtonState();
                });
            });

            // AJAX Print Button Click Handler
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    const selectedIds = Array.from(document.querySelectorAll('.doc-checkbox:checked'))
                        .map(cb => cb.value);

                    if (selectedIds.length === 0) return;

                    // UI Feedback during request
                    const originalText = printBtn.innerHTML;
                    printBtn.disabled = true;
                    printBtn.innerHTML =
                        `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Preparing...`;

                    // Make AJAX POST request to fetch transmittal HTML
                    fetch("{{ route('documents.transmittal') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'text/html'
                            },
                            body: JSON.stringify({
                                document_ids: selectedIds
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Failed to generate transmittal slip.');
                            }
                            return response.text();
                        })
                        .then(htmlContent => {
                            // Restore button state
                            printBtn.innerHTML = originalText;
                            updateButtonState();

                            // Inject HTML response into hidden iframe and trigger print
                            const frameDoc = printFrame.contentWindow.document;
                            frameDoc.open();
                            frameDoc.write(htmlContent);
                            frameDoc.close();

                            // Wait briefly for CSS/styles in the iframe to register, then trigger print
                            setTimeout(() => {
                                printFrame.contentWindow.focus();
                                printFrame.contentWindow.print();
                            }, 300);
                        })
                        .catch(error => {
                            console.error('Print Error:', error);
                            alert(
                                'An error occurred while generating the transmittal slip. Please try again.');
                            printBtn.innerHTML = originalText;
                            updateButtonState();
                        });
                });
            }
        });
    </script>
@endpush
