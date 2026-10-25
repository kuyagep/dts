@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
    <div class="text-center mb-4">
        <h3 class="h4">Verify Your Email</h3>
        <p class="text-muted small">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we
            just emailed to you? If you didn't receive the email, we will gladly send you another.
        </p>
    </div>

    @if (session('status'))


        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i data-feather="check-circle" class="feather-sm me-2 flex-shrink-0"></i>
                <div>
                    @if (session('status') == 'verification-link-sent')
                        A new verification link has been sent to the email address you provided during registration.
                    @else
                        {{ session('status') }}
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex flex-column gap-3 mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Resend Verification Email</button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none text-muted small">
                Log Out
            </button>
        </form>
    </div>
@endsection
