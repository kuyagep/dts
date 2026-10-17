@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
    <div class="text-center mb-4">
        <h3 class="h4">Welcome Back</h3>
        <p class="text-muted small">Sign in to access your document dashboard</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email') }}" placeholder="Enter your registered email" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot password?</a>
                @endif
            </div>

            <div class="input-group">
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                    id="password" name="password" placeholder="Enter your password" required>

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Remember Me -->
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="show_password">
                <label class="form-check-label text-muted " for="show_password">
                    Show Password
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                <span id="btnText">Sign In</span>
                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"
                    aria-hidden="true"></span>
            </button>
        </div>
    </form>


@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showPasswordCheckbox = document.getElementById('show_password');
            const passwordInput = document.getElementById(
                'password'); // Make sure your password input has id="password"

            if (showPasswordCheckbox && passwordInput) {
                showPasswordCheckbox.addEventListener('change', function() {
                    passwordInput.type = this.checked ? 'text' : 'password';
                });
            }

            // 2. Loading State on Form Submit
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            if (loginForm && submitBtn) {
                loginForm.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    btnText.textContent = 'Signing in...';
                    btnSpinner.classList.remove('d-none');
                });
            }
        });
    </script>
@endpush
