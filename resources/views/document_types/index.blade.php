@extends('layouts.main')

@section('title', 'Document Types')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Document Types</h1>
            <p class="text-muted mb-0">Manage classifications and categories for tracked documents.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDocumentTypeModal">
            <i class="align-middle me-1" data-feather="plus"></i> Add Document Type
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Type Name</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documentTypes as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td>
                                    @if ($type->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $type->created_at->format('M d, Y') }}</small></td>
                                <td class="text-end pe-3">
                                    <!-- Edit Button (Triggers Modal & Populates Data via JS) -->
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1 edit-btn"
                                        data-id="{{ $type->id }}" data-name="{{ $type->name }}"
                                        data-is_active="{{ $type->is_active }}">
                                        <i data-feather="edit-2" class="feather-sm"></i>
                                    </button>

                                    <!-- Delete Form with SweetAlert2 Hook -->
                                    <form action="{{ route('document-types.destroy', $type->id) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn">
                                            <i data-feather="trash-2" class="feather-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No document types found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($documentTypes->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    {{ $documentTypes->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Modal: Create Document Type -->
    <div class="modal fade" id="createDocumentTypeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('document-types.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Document Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Type Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Purchase Request"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Code / Abbreviation <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. PR" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Document Type -->
    <div class="modal fade" id="editDocumentTypeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editDocumentTypeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Document Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Type Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Status</label>
                            <select name="is_active" id="edit_is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Document Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Populate Edit Modal Data
            const editModal = new bootstrap.Modal(document.getElementById('editDocumentTypeModal'));

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const isActive = this.dataset.is_active;

                    // Set Form Action URL dynamically
                    document.getElementById('editDocumentTypeForm').action =
                        `/document-types/${id}`;

                    // Fill Form Fields
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_is_active').value = isActive;

                    editModal.show();
                });
            });

            // 2. SweetAlert2 Delete Confirmation
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will delete this document type!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
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
