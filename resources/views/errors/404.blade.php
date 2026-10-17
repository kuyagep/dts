@extends('errors.error')

@section('title', 'Page Not Found')

@section('content')
    <div class="text-center py-4">
        <h1 class="display-1 fw-bold text-primary">404</h1>
        <h3 class="h4 mb-2">Page Not Found</h3>
        <p class="text-muted small mb-4">
            The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
        </p>

        <div class="d-grid gap-2 col-8 mx-auto">
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Back to Dashboard</a>
        </div>
    </div>
@endsection
