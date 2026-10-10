@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="text-center mb-4">
        <h3 class="h4">Reset Password</h3>
        <p class="text-muted small">Enter your account email to receive a password reset link.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{ old('email') }}" placeholder="Enter your registered email" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Send Password Reset Link</button>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('login') }}" class="small text-decoration-none">&larr; Back to Login</a>
    </div>
@endsection
