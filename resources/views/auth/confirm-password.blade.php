@extends('layouts.auth')

@section('title', 'Confirm Password')

@section('content')
    <div class="text-center mb-4">
        <h3 class="h4">Confirm Password</h3>
        <p class="text-muted small">This is a secure area of the application. Please confirm your password before continuing.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                name="password" required autocomplete="current-password" autofocus placeholder="Enter your password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Confirm</button>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="{{ url()->previous() }}" class="small text-decoration-none">&larr; Back</a>
    </div>
@endsection
