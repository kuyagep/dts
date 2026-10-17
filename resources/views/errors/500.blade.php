@extends('errors.error')

@section('title', 'Server Error')

@section('content')
    <div class="text-center py-4">
        <h1 class="display-1 fw-bold text-warning">500</h1>
        <h3 class="h4 mb-2">Internal Server Error</h3>
        <p class="text-muted small mb-4">
            Oops! Something went wrong on our end. Please try again later or contact support if the problem persists.
        </p>

        <div class="d-grid gap-2 col-8 mx-auto">
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Back to Safety</a>
        </div>
    </div>
@endsection
