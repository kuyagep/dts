@extends('errors.error')

@section('title', 'Access Forbidden')

@section('content')
    <div class="text-center py-4">
        <h1 class="display-1 fw-bold text-danger">403</h1>
        <h3 class="h4 mb-2">Access Forbidden</h3>
        <p class="text-muted small mb-4">
            {{ $exception->getMessage() ?: 'You do not have permission to access this resource or perform this action.' }}
        </p>

        <div class="d-grid gap-2 col-8 mx-auto">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
            <a href="{{ url('/') }}" class="btn btn-outline-primary">Back to Dashboard</a>
        </div>
    </div>
@endsection
