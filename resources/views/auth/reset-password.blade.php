@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="text-center mb-4">
        <h3 class="h4">Reset Password</h3>
        <p class="text-muted small">Please enter your email and set your new password below.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{ old('email', $request->email) }}" placeholder="Enter your registered email" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                required autocomplete="new-password" placeholder="Enter new password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation"
                required autocomplete="new-password" placeholder="Confirm new password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Reset Password</button>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('login') }}" class="small text-decoration-none">&larr; Back to Login</a>
    </div>
@endsection
