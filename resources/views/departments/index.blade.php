@extends('layouts.main')

@section('title', 'Manage Departments')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Departments Management</h1>
            <p class="text-muted mb-0">Configure top-level organizational divisions and units.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
            <i class="align-middle me-1" data-feather="plus"></i> Add Department
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Code</th>
                            <th>Department Name</th>
                            <th>Offices Count</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $dept)
                            <tr>
                                <td class="ps-3 fw-bold text-primary">{{ $dept->code }}</td>
                                <td>{{ $dept->name }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $dept->offices_count }} Offices</span>
                                </td>
                                <td>
                                    @if ($dept->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $dept->created_at->format('M d, Y') }}</small></td>
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <!-- Edit Department Button (Triggers Modal) -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#editDepartmentModal-{{ $dept->id }}"
                                            title="Edit Department">
                                            <i data-feather="edit-2" class="feather-sm"></i>
                                        </button>

                                        <!-- Delete Department Form -->
                                        <form action="{{ route('departments.destroy', $dept->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this department?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Delete Department">
                                                <i data-feather="trash-2" class="feather-sm"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal: Edit Department -->
                                    <div class="modal fade text-start" id="editDepartmentModal-{{ $dept->id }}"
                                        tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('departments.update', $dept->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title text-white">
                                                            <i data-feather="edit-2" class="me-1"></i> Edit Department
                                                            Details
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Department Code -->
                                                        <div class="mb-3">
                                                            <label for="code-{{ $dept->id }}"
                                                                class="form-label fw-bold">Department Code <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="code"
                                                                id="code-{{ $dept->id }}" class="form-control"
                                                                value="{{ old('code', $dept->code) }}" required>
                                                        </div>

                                                        <!-- Department Name -->
                                                        <div class="mb-3">
                                                            <label for="name-{{ $dept->id }}"
                                                                class="form-label fw-bold">Department Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="name"
                                                                id="name-{{ $dept->id }}" class="form-control"
                                                                value="{{ old('name', $dept->name) }}" required>
                                                        </div>


                                                        <!-- Active Status Switch -->
                                                        <div class="mb-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="is_active" value="1"
                                                                    id="is_active-{{ $dept->id }}"
                                                                    {{ old('is_active', $dept->is_active ?? true) ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold"
                                                                    for="is_active-{{ $dept->id }}">
                                                                    Active Department
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($departments->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $departments->links() }}
            </div>
        @endif
    </div>

    <!-- Modal: Create Department -->
    <div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Department Name</label>
                            <input type="text" name="name" class="form-control"
                                placeholder="e.g. Finance & Admin Division" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Department Code</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. FAD" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
