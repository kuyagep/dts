@extends('layouts.main')

@section('title', 'Manage Offices')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Offices & Sections</h1>
            <p class="text-muted mb-0">Manage internal routing stations and section offices.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOfficeModal">
            <i class="align-middle me-1" data-feather="plus"></i> Add Office
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Code</th>
                            <th>Office Name</th>
                            <th>Parent Department</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offices as $office)
                            <tr>
                                <td class="ps-3 fw-bold text-primary">{{ $office->code }}</td>
                                <td>{{ $office->name }}</td>
                                <td><span
                                        class="badge bg-light text-dark border">{{ $office->department->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if ($office->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $office->created_at->format('M d, Y') }}</small></td>
                                <td class="text-end pe-3">
                                    <form action="{{ route('offices.destroy', $office->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this office?')">
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
                                <td colspan="6" class="text-center py-4 text-muted">No offices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($offices->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $offices->links() }}
            </div>
        @endif
    </div>

    <!-- Modal: Create Office -->
    <div class="modal fade" id="createOfficeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('offices.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Office</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Parent Department <span
                                    class="text-danger">*</span></label>
                            <select name="department_id" class="form-select" required>
                                <option value="" disabled selected>Select department...</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Office Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Accounting Section"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Office Code <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. ACCT" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Office</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
