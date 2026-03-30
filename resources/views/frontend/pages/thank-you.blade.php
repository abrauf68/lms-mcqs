@extends('frontend.layouts.master')

@section('title', 'Thank You')

@section('content')

<div class="container d-flex align-items-center justify-content-center" style="margin: 50px 0px;">
    <div class="text-center">

        <!-- Icon -->
        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 70px;"></i>
        </div>

        <!-- Heading -->
        <h1 class="fw-bold mb-3">Thank You!</h1>

        <!-- Message -->
        <p class="text-muted mb-4">
            Your message has been successfully submitted. <br>
            Our team will get back to you shortly.
        </p>

        <!-- Buttons -->
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('frontend.home') }}" class="btn btn-primary px-4">
                <i class="bi bi-house-door me-1"></i> Back to Home
            </a>

            <a href="{{ route('frontend.contact') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-envelope me-1"></i> Contact Again
            </a>
        </div>

    </div>
</div>

@endsection
