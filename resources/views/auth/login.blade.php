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

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email') }}" placeholder="Enter your division email" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot password?</a>
                @endif
            </div>
            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                id="password" name="password" placeholder="Enter your password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label text-muted small" for="remember_me">
                    Remember me on this device
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="text-center mt-4">
            <p class="small text-muted mb-0">
                Don't have an account? <a href="{{ route('register') }}" class="text-primary text-decoration-none">Register
                    here</a>
            </p>
        </div>
    @endif
@endsection
