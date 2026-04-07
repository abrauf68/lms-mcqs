@extends('frontend.layouts.master')

@section('title', 'Login')

@section('content')

    <div class="container d-flex align-items-center justify-content-center" style="margin: 50px 0px;">

        <div class="card shadow-sm p-4" style="max-width: 420px; width: 100%; border-radius: 12px;">

            <div class="text-center mb-4">
                <h4 class="mb-1">Customer Sign In</h4>
                <p class="text-muted small">Sign in to your account</p>
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

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <input type="hidden" name="pricing" value="{{ request()->pricing }}">
                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label small">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email_username" class="form-control form-control-sm @error('email_username') is-invalid @enderror" value="{{ old('email_username') }}"
                        required>
                    @error('email_username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label class="form-label small">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">
                            Remember me
                        </label>
                    </div>

                    <a href="#" class="small text-decoration-none">Forgot?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-dark w-100 rounded-pill">
                    Sign In
                </button>

            </form>

            <div class="text-center my-3">
                <span class="text-muted small">OR</span>
            </div>

            <div class="text-center">
                <p class="small mb-0">
                    Don't have an account?
                    @if (request()->pricing)
                        <a href="{{ route('register') }}?pricing={{ request()->pricing }}" class="text-decoration-none fw-semibold">Sign Up</a>
                    @else
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Sign Up</a>
                    @endif
                </p>
            </div>

        </div>

    </div>

@endsection
