@extends('frontend.layouts.master')

@section('title', 'Login')

@section('content')

    <div class="container d-flex align-items-center justify-content-center" style="margin: 50px 0px;">

        <div class="card shadow-sm p-4" style="max-width: 420px; width: 100%; border-radius: 12px;">

            <div class="text-center mb-4">
                <h4 class="mb-1">Reset Password</h4>
                <p class="text-muted small">Your new password must be different from previously used passwords</p>
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

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ request()->route('token') }}">
                <input type="hidden" name="email" value="{{ request()->email }}">

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
                    <input type="password" name="password_confirmation" class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror" required>
                    @error('password_confirmation')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-dark w-100 rounded-pill">
                    Set new password
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
