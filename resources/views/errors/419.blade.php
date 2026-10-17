@extends('errors.error')

@section('title', 'Page Expired')

@section('content')
    <div class="text-center py-4">
        <h1 class="display-1 fw-bold text-info">419</h1>
        <h3 class="h4 mb-2">Page Expired</h3>
        <p class="text-muted small mb-4">
            Your session has timed out or expired due to inactivity. Please refresh the page or try logging in again.
        </p>

        <div class="d-grid gap-2 col-8 mx-auto">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Back to Login</a>
        </div>
    </div>
@endsection
