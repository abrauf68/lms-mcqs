@extends('frontend.layouts.master')

@section('title', 'Sign Up')

@section('content')

<div class="container d-flex align-items-center justify-content-center" style="margin: 50px 0px;">

    <div class="card shadow-sm p-4" style="max-width: 420px; width: 100%; border-radius: 12px;">

        <div class="text-center mb-4">
            <h4 class="mb-1">Create Account</h4>
            <p class="text-muted small">Sign up to get started</p>
        </div>

        {{-- Alerts --}}
        @if (session('error'))
            <div class="alert alert-danger py-2">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.attempt') }}">
            @csrf

            <input type="hidden" name="pricing" value="{{ request()->pricing }}">

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label small">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label small">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label small">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <label class="form-label small">Confirm Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="form-control form-control-sm" required>
            </div>

            {{-- Terms & Conditions --}}
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="terms">
                        I agree to the <a href="#" target="_blank">Terms & Conditions</a>
                    </label>

                    @error('terms')
                        <span class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-dark w-100 rounded-pill">
                Sign Up
            </button>

        </form>

        <div class="text-center my-3">
            <span class="text-muted small">OR</span>
        </div>

        <div class="text-center">
            <p class="small mb-0">
                Already have an account?
                @if (request()->pricing)
                    <a href="{{ route('login') }}?pricing={{ request()->pricing }}" class="text-decoration-none fw-semibold">Sign In</a>
                @else
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Sign In</a>
                @endif
            </p>
        </div>

    </div>

</div>

@endsection
