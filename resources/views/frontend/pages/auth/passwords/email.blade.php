@extends('frontend.layouts.master')

@section('title', 'Login')

@section('content')

    <div class="container d-flex align-items-center justify-content-center" style="margin: 50px 0px;">

        <div class="card shadow-sm p-4" style="max-width: 420px; width: 100%; border-radius: 12px;">

            <div class="text-center mb-4">
                <h4 class="mb-1">{{__('Forgot Password?')}}</h4>
                <p class="text-muted small">{{__("Enter your email to get reset password link")}}</p>
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

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label small">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email') }}"
                        required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-dark w-100 rounded-pill">
                    Send Reset Link
                </button>

            </form>

            <div class="text-center my-3">
                <span class="text-muted small">OR</span>
            </div>

            <div class="text-center">
                <p class="small mb-0">
                    {{__('Back to')}}
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login</a>
                </p>
            </div>

        </div>

    </div>

@endsection
