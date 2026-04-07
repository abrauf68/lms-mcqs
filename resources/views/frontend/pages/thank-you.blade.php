@extends('frontend.layouts.master')

@section('title', 'Thank You')

@section('content')

<div class="container d-flex align-items-center justify-content-center" style="margin: 50px 0px;">
    <div class="text-center w-100">

        @if(isset($userPricing))

            <!-- Success Icon -->
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 70px;"></i>
            </div>

            <!-- Heading -->
            <h1 class="fw-bold mb-3">Payment Successful</h1>

            <!-- Message -->
            <p class="text-muted mb-4">
                Your subscription has been activated successfully. <br>
                Below are your plan details:
            </p>

            <!-- Card -->
            <div class="card shadow-sm border-0 mx-auto mb-4" style="max-width: 500px;">
                <div class="card-body text-start">

                    <h5 class="fw-bold mb-3 text-primary">
                        {{ $userPricing->pricing->name ?? 'N/A' }}
                    </h5>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Price</span>
                            <strong>${{ number_format($userPricing->pricing->price ?? 0, 2) }}</strong>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Billing Type</span>
                            <strong>{{ ucfirst($userPricing->pricing->type ?? '-') }}</strong>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Start Date</span>
                            <strong>{{ $userPricing->start_date }}</strong>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>End Date</span>
                            <strong>{{ $userPricing->end_date }}</strong>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Status</span>
                            <span class="badge bg-success">
                                {{ ucfirst($userPricing->status) }}
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Transaction ID</span>
                            <strong>{{ $userPricing->transaction->transaction_id ?? '-' }}</strong>
                        </li>

                    </ul>

                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('frontend.home') }}" class="btn btn-primary px-4">
                    <i class="bi bi-house-door me-1"></i> Go to Dashboard
                </a>

                <a href="{{ route('frontend.pricings') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-tags me-1"></i> View Plans
                </a>
            </div>

        @else

            <!-- Default Thank You -->

            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 70px;"></i>
            </div>

            <h1 class="fw-bold mb-3">Thank You!</h1>

            <p class="text-muted mb-4">
                Your message has been successfully submitted. <br>
                Our team will get back to you shortly.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('frontend.home') }}" class="btn btn-primary px-4">
                    <i class="bi bi-house-door me-1"></i> Back to Home
                </a>

                <a href="{{ route('frontend.contact') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-envelope me-1"></i> Contact Again
                </a>
            </div>

        @endif

    </div>
</div>

@endsection