@extends('layouts.main')

@section('title', 'User Profile Settings')

@section('content')
    <div class="mb-3">
        <h1 class="h3 mb-0">Profile & Security Settings</h1>
        <p class="text-muted mb-0">Manage your account information, assigned office, and security credentials.</p>
    </div>

    <div class="row">
        <!-- Left Column: User Summary Card -->
        <div class="col-md-4 col-xl-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">Account Overview</h5>
                </div>
                <div class="card-body text-center">
                    <!-- Avatar / Initials Circle -->
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                        style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <h5 class="card-title mb-0">{{ $user->name }}</h5>
                    <div class="text-muted mb-2">{{ $user->email }}</div>

                    <!-- Roles Badges -->
                    <div class="mb-3">
                        @foreach ($user->roles as $role)
                            <span class="badge bg-primary me-1">{{ $role->name }}</span>
                        @endforeach
                    </div>
                </div>

                <hr class="my-0">

                <div class="card-body">
                    <h6 class="font-weight-bold text-muted small text-uppercase mb-3">Assignment Details</h6>

                    <div class="mb-2">
                        <small class="text-muted d-block">Office / Unit</small>
                        <span class="fw-bold text-dark">{{ $user->office->name ?? 'Unassigned' }}</span>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted d-block">Department / Division</small>
                        <span class="fw-bold text-dark">{{ $user->office->department->name ?? 'N/A' }}</span>
                    </div>

                    <div>
                        <small class="text-muted d-block">Member Since</small>
                        <span class="small text-dark">{{ $user->created_at->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings Forms -->
        <div class="col-md-8 col-xl-9">
            <!-- Update Personal Information & Office Assignment -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0"><i data-feather="user" class="feather-sm me-1 text-primary"></i> Personal
                        Information & Office</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-info') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label font-weight-bold">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6">
                                <label for="email" class="form-label font-weight-bold">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Assigned Office Dropdown -->
                            <div class="col-md-12">
                                <label for="office_id" class="form-label font-weight-bold">Assigned Office / Station <span
                                        class="text-danger">*</span></label>
                                <select name="office_id" id="office_id"
                                    class="form-select @error('office_id') is-invalid @enderror" required>
                                    <option value="" disabled>Select office...</option>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->id }}"
                                            {{ old('office_id', $user->office_id) == $office->id ? 'selected' : '' }}>
                                            {{ $office->name }} ({{ $office->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('office_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save" class="feather-sm me-1"></i> Save Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0"><i data-feather="lock" class="feather-sm me-1 text-primary"></i> Change
                        Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="current_password" class="form-label font-weight-bold">Current Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label font-weight-bold">New Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label font-weight-bold">Confirm New Password
                                    <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="key" class="feather-sm me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
