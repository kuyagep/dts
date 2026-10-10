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
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
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
                                    <form action="{{ route('document-types.destroy', $type->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this document type?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
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
        </div>
        @if ($documentTypes->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $documentTypes->links() }}
            </div>
        @endif
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
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Brief details about this document classification..."></textarea>
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
@endsection
