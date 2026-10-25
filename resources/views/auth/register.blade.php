@extends('layouts.auth')

@section('title', 'Register Account')

@section('content')
    <div class="text-center mb-4">
        <h3 class="h4 text-muted">Account Registration</h3>
        {{-- <p class="text-muted small">Register as an official division action officer or staff</p> --}}
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Full Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Full Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control  @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name') }}" placeholder="e.g. Juan Dela Cruz" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Official Email<span class="text-danger">*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{ old('email') }}" placeholder="juan.delacruz@deped.gov.ph" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Office Selection -->
        <div class="mb-3">
            <label for="office_id">Assigned Office/School<span class="text-danger mb-1">*</span></label>
            <select class="form-select form-select-sm select2 @error('office_id') is-invalid @enderror" id="office_id"
                name="office_id" required>
                <option value="" disabled {{ old('office_id') ? '' : 'selected' }}>Select your office...</option>
                @foreach (\App\Models\Office::where('is_active', true)->orderBy('name')->get() as $office)
                    <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                        {{ $office->name }} ({{ $office->code . '-' . $office->department->name }})
                    </option>
                @endforeach
            </select>
            @error('office_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
            <input type="password" class="form-control  @error('password') is-invalid @enderror" id="password"
                name="password" placeholder="Minimum 8 characters" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password<span class="text-danger">*</span></label>
            <input type="password" class="form-control " id="password_confirmation" name="password_confirmation"
                placeholder="Re-enter password" required>
        </div>

        <!-- Terms & Conditions Checkbox -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms"
                    id="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                <label class="form-check-label text-secondary" for="terms">
                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal"
                        class="text-primary text-decoration-underline">Terms of Service</a> & <a href="#"
                        data-bs-toggle="modal" data-bs-target="#privacyModal"
                        class="text-primary text-decoration-underline">Privacy Policy</a><span class="text-danger">*</span>
                </label>
                @error('terms')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
        </div>
    </form>

    <div class="text-center mt-4">
        <p class="small text-muted mb-0">
            Already registered? <a href="{{ route('login') }}" class="text-primary text-decoration-none">Sign in here</a>
        </p>
    </div>


    <!-- Terms of Service Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header ">
                    <h5 class="modal-title fw-bold fs-6 fs-sm-5" id="termsModalLabel">
                        <i class="fa-solid fa-file-contract me-2 text-warning"></i>Terms of Service
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-sm-4 text-dark fs-6" style="line-height: 1.6;">
                    <p class="text-muted small"><strong>Last Updated:</strong> {{ date('F Y') }}</p>

                    <h6 class="fw-bold text-primary mt-3">1. Account Authorization & Security</h6>
                    <p class="small text-secondary">
                        Access to the Division Document Tracking System (DTS) is strictly restricted to authorized
                        personnel of the Department of Education (DepEd) Schools Division of Davao del Sur. All accounts
                        require a valid <i>@deped.gov.ph</i> email address and administrator approval prior to system
                        access.
                    </p>

                    <h6 class="fw-bold text-primary mt-3">2. User Responsibilities</h6>
                    <p class="small text-secondary">
                        Users are solely responsible for maintaining the confidentiality of their login credentials. Any
                        document uploaded, routed, received, or released through your account will be logged under your
                        administrative user ID.
                    </p>

                    <h6 class="fw-bold text-primary mt-3">3. Acceptable Use Policy</h6>
                    <ul class="small text-secondary ps-3 mb-3">
                        <li>You must only create, route, or verify official DepEd Davao del Sur communications, orders, and
                            transmittals.</li>
                        <li>Falsification of official document tracking records or unauthorized account sharing is strictly
                            prohibited and subject to administrative disciplinary action.</li>
                        <li>System activity logs are continuously monitored by Division ITO.</li>
                    </ul>

                    <h6 class="fw-bold text-primary mt-3">4. System Availability & Service Modifications</h6>
                    <p class="small text-secondary mb-0">
                        The Division Office reserves the right to modify, suspend, or revoke account access at any time to
                        ensure system integrity, perform server maintenance, or mitigate unauthorized access risks.
                    </p>
                </div>
                <div class="modal-footer bg-light p-2 p-sm-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold fs-6 fs-sm-5" id="privacyModalLabel">
                        <i class="fa-solid fa-user-shield me-2 text-warning"></i>Privacy Policy
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-sm-4 text-dark fs-6" style="line-height: 1.6;">
                    <p class="text-muted small"><strong>Last Updated:</strong> {{ date('F Y') }}</p>

                    <h6 class="fw-bold text-primary mt-3">1. Data Privacy Act Compliance</h6>
                    <p class="small text-secondary">
                        In compliance with Republic Act No. 10173 (Data Privacy Act of 2012 of the Philippines), DepEd Davao
                        del Sur is committed to protecting your personal information collected during system registration.
                    </p>

                    <h6 class="fw-bold text-primary mt-3">2. Information Collected</h6>
                    <p class="small text-secondary mb-2">The system collects and stores the following official information:
                    </p>
                    <ul class="small text-secondary ps-3 mb-3">
                        <li>Full Name and official DepEd Email Address.</li>
                        <li>Assigned Division Office, Section, or School.</li>
                        <li>System usage metrics, IP addresses, and document action timestamps (receives, releases, and
                            updates).</li>
                    </ul>

                    <h6 class="fw-bold text-primary mt-3">3. Purpose of Data Processing</h6>
                    <p class="small text-secondary">
                        Collected data is strictly used for identity verification, role-based access control (RBAC),
                        document accountability audit trails, and administrative tracking across the division.
                    </p>

                    <h6 class="fw-bold text-primary mt-3">4. Data Protection & Retention</h6>
                    <p class="small text-secondary mb-0">
                        Your information is stored on secure division servers with strict database access controls. We do
                        not sell, share, or disclose user data to third parties outside DepEd administrative requirements.
                    </p>
                </div>
                <div class="modal-footer bg-light p-2 p-sm-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
