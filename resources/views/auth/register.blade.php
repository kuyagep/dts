@extends('layouts.auth')

@section('title', 'Register Account')

@section('content')
    <div class="text-center mb-4">
        <h3 class="h4">Account Registration</h3>
        {{-- <p class="text-muted small">Register as an official division action officer or staff</p> --}}
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Full Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Full Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
                name="name" value="{{ old('name') }}" placeholder="e.g. Juan Dela Cruz" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Official Email<span class="text-danger">*</span></label>
            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email') }}" placeholder="juan.delacruz@deped.gov.ph" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Office Selection -->
        <div class="form-group mb-3">
            <label for="office_id">Assigned Office/School<span class="text-danger">*</span></label>
            <select class="form-select select2 @error('office_id') is-invalid @enderror" id="office_id" name="office_id"
                required>
                <option value="" disabled {{ old('office_id') ? '' : 'selected' }}>Select your office...</option>
                @foreach (\App\Models\Office::where('is_active', true)->orderBy('name')->get() as $office)
                    <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                        {{ $office->name }} ({{ $office->code . '-' . $office->department->name }})
                    </option>
                @endforeach
            </select>
            @error('office_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                id="password" name="password" placeholder="Minimum 8 characters" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password<span class="text-danger">*</span></label>
            <input type="password" class="form-control form-control-lg" id="password_confirmation"
                name="password_confirmation" placeholder="Re-enter password" required>
        </div>

        <!-- Terms & Conditions Checkbox -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms"
                    id="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                <label class="form-check-label text-secondary" for="terms">
                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal"
                        class="text-primary text-decoration-underline">Terms of Service</a> & <a href="#"
                        data-bs-toggle="modal" data-bs-target="#privacyModal"
                        class="text-primary text-decoration-underline">Privacy Policy</a><span class="text-danger">*</span>
                </label>
                @error('terms')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
        </div>
    </form>

    <div class="text-center mt-4">
        <p class="small text-muted mb-0">
            Already registered? <a href="{{ route('login') }}" class="text-primary text-decoration-none">Sign in here</a>
        </p>
    </div>
@endsection
