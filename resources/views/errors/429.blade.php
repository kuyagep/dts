@extends('errors.error')

@section('title', 'Too Many Requests')

@section('content')
    <div class="text-center py-4">
        <h1 class="display-1 fw-bold text-secondary">429</h1>
        <h3 class="h4 mb-2">Too Many Requests</h3>
        <p class="text-muted small mb-4">
            You have sent too many requests in a short amount of time. Please wait a moment and try again.
        </p>

        <div class="d-grid gap-2 col-8 mx-auto">
            <a href="{{ url('/') }}" class="btn btn-primary">Refresh Page</a>
        </div>
    </div>
@endsection
