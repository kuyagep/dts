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
                                    <form action="{{ route('departments.destroy', $dept->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this department?')">
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
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
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
