@extends('layouts.main')

@section('title', 'Manage Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">User Accounts Management</h1>
            <p class="text-muted mb-0">Configure staff user accounts, assigned offices, and roles.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="align-middle me-1" data-feather="user-plus"></i> Add User
        </button>
    </div>

    <div class="row mb-3 align-items-center">
        <!-- Search Form -->
        <div class="col-md-6 col-lg-4">
            <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2">
                <!-- Retain current status filter if selected -->
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i data-feather="search" class="feather-sm text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                        placeholder="Search by name or email..." value="{{ request('search') }}">
                    @if (request('search'))
                        <a href="{{ route('users.index', ['status' => request('status')]) }}"
                            class="btn btn-outline-secondary" title="Clear Search">
                            <i data-feather="x" class="feather-sm"></i>
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>

        <!-- Quick Status Filter Pills -->
        <div class="col-md-6 col-lg-8 text-md-end mt-2 mt-md-0">
            <div class="btn-group" role="group">
                <a href="{{ route('users.index', array_filter(['search' => request('search')])) }}"
                    class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                    All
                </a>
                <a href="{{ route('users.index', array_filter(['search' => request('search'), 'status' => 'pending'])) }}"
                    class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                    Pending
                </a>
                <a href="{{ route('users.index', array_filter(['search' => request('search'), 'status' => 'approved'])) }}"
                    class="btn btn-sm {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                    Approved
                </a>
                <a href="{{ route('users.index', array_filter(['search' => request('search'), 'status' => 'deactivated'])) }}"
                    class="btn btn-sm {{ request('status') == 'deactivated' ? 'btn-danger' : 'btn-outline-danger' }}">
                    Deactivated
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Date Registered</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="ps-3 fw-bold text-dark">
                                    <div class="d-flex align-items-center">
                                        <!-- Avatar Circle -->
                                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm me-2 flex-shrink-0"
                                            style="width: 32px; height: 32px; font-size: 1rem; font-weight: bold;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <!-- User Name & Office Stacked -->
                                        <div class="d-flex flex-column">
                                            <span class="text-dark">{{ $user->name }}</span>
                                            <small class="text-muted fw-normal">
                                                <span class="badge bg-light text-secondary border mt-1">
                                                    {{ $user->office->name ?? 'Unassigned' }}
                                                </span>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start">
                                        <span class="text-dark">{{ $user->email }}</span>
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-success mt-1" style="font-size: 0.7rem;">Verified</span>
                                        @else
                                            <span class="badge bg-warning mt-1" style="font-size: 0.7rem;">Unverified</span>
                                        @endif

                                        <!-- Account Approval / Activation Status Badge -->
                                        @switch($user->status)
                                            @case('approved')
                                                <span class="badge bg-success mt-1" style="font-size: 0.7rem;">Approved</span>
                                            @break

                                            @case('deactivated')
                                                <span class="badge bg-danger mt-1" style="font-size: 0.7rem;">Deactivated</span>
                                            @break

                                            @default
                                                <span class="badge bg-warning mt-1" style="font-size: 0.7rem;">Pending</span>
                                        @endswitch
                                    </div>
                                </td>

                                <td>
                                    @foreach ($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td><small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small></td>
                                <td class="text-end pe-3">
                                    <!-- Edit Button (Triggers Modal & Populates Data) -->
                                    <button type="button" class="btn btn-sm btn-primary me-1 edit-user-btn"
                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}" data-office_id="{{ $user->office_id }}"
                                        data-role="{{ $user->roles->first()->name ?? '' }}">
                                        <i data-feather="edit-2" class="feather-sm"></i>
                                    </button>
                                    @hasanyrole('SuperAdmin|Admin')
                                        <div class="btn-group btn-group-sm me-1" role="group">
                                            @if ($user->status !== 'approved')
                                                <form
                                                    action="{{ route('users.update-status', [$user->id, 'status' => 'approved']) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve User">
                                                        <i data-feather="user-check" class="feather-sm"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($user->status !== 'deactivated')
                                                <form
                                                    action="{{ route('users.update-status', [$user->id, 'status' => 'deactivated']) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        title="Deactivate User">
                                                        <i data-feather="user-x" class="feather-sm"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endhasanyrole

                                    @role('SuperAdmin')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="d-inline delete-user-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-user-btn">
                                                <i data-feather="trash-2" class="feather-sm"></i>
                                            </button>
                                        </form>
                                    @endrole
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                {{ $users->links() }}
            </div>
        </div>

        <!-- Modal: Create User -->
        <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Create User Account</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Jane Doe"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="janedoe@division.gov"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Assigned Office <span
                                        class="text-danger">*</span></label>
                                <select name="office_id" class="form-select" required>
                                    <option value="" disabled selected>Select office...</option>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->name }} ({{ $office->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Assign Role <span
                                        class="text-danger">*</span></label>
                                <select name="role" class="form-select" required>
                                    <option value="" disabled selected>Select role...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Minimum 8 characters" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Edit User -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editUserForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit User Account</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_user_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" id="edit_user_email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Assigned Office <span
                                        class="text-danger">*</span></label>
                                <select name="office_id" id="edit_user_office_id" class="form-select" required>
                                    <option value="" disabled>Select office...</option>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->name }} ({{ $office->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Assign Role <span
                                        class="text-danger">*</span></label>
                                <select name="role" id="edit_user_role" class="form-select" required>
                                    <option value="" disabled>Select role...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">New Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Leave blank to keep current password">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Modal trigger & data populator
                const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));

                document.querySelectorAll('.edit-user-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const name = this.dataset.name;
                        const email = this.dataset.email;
                        const officeId = this.dataset.office_id;
                        const role = this.dataset.role;

                        // Dynamically assign form update route
                        document.getElementById('editUserForm').action = `/users/${id}`;

                        // Populate inputs
                        document.getElementById('edit_user_name').value = name;
                        document.getElementById('edit_user_email').value = email;
                        document.getElementById('edit_user_office_id').value = officeId;
                        document.getElementById('edit_user_role').value = role;

                        editUserModal.show();
                    });
                });

                // 2. SweetAlert2 Delete Confirmation
                document.querySelectorAll('.delete-user-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('.delete-user-form');

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "This action will permanently delete this user account!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Yes, delete user!',
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
