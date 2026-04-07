@extends('frontend.layouts.master')

@section('title', 'Pricings')

@section('css')
@endsection

@section('content')
    <section class="py-0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bg-light p-4 text-center rounded-3">
                        <h1 class="m-0">Pricings</h1>
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-dots mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pricings</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 price-wrap">
        <div class="container">
            <div class="row g-4 position-relative mb-4">
                <!-- SVG decoration -->
                <figure class="position-absolute top-0 start-0 d-none d-sm-block">
                    <svg width="22px" height="22px" viewBox="0 0 22 22">
                        <polygon class="fill-purple"
                            points="22,8.3 13.7,8.3 13.7,0 8.3,0 8.3,8.3 0,8.3 0,13.7 8.3,13.7 8.3,22 13.7,22 13.7,13.7 22,13.7 ">
                        </polygon>
                    </svg>
                </figure>

                <!-- Title and Search -->
                <div class="col-lg-10 mx-auto text-center position-relative">
                    <!-- SVG decoration -->
                    <figure class="position-absolute top-50 end-0 translate-middle-y d-none d-md-block">
                        <svg width="27px" height="27px">
                            <path class="fill-orange"
                                d="M13.122,5.946 L17.679,-0.001 L17.404,7.528 L24.661,5.946 L19.683,11.533 L26.244,15.056 L18.891,16.089 L21.686,23.068 L15.400,19.062 L13.122,26.232 L10.843,19.062 L4.557,23.068 L7.352,16.089 L-0.000,15.056 L6.561,11.533 L1.582,5.946 L8.839,7.528 L8.565,-0.001 L13.122,5.946 Z">
                            </path>
                        </svg>
                    </figure>
                    <!-- Title -->
                    <h1>Affordable Pricing Packages</h1>
                    <p class="mb-4 pb-1">Perceived end knowledge certainly day sweetness why cordially</p>
                </div>
            </div>
            <!-- Pricing START -->
            <div class="row g-4">

                <!-- Pricing item START -->
                @if (isset($pricings) && count($pricings) > 0)
                    @foreach ($pricings as $pricing)
                        <div class="col-md-6 col-xl-4">
                            <div class="card border rounded-3 p-2 p-sm-4 h-100">
                                <!-- Card Header -->
                                <div class="card-header p-0">
                                    <!-- Price and Info -->
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-2">
                                        <!-- Info -->
                                        <div>
                                            <h5 class="mb-0">{{ $pricing->name }}</h5>
                                            <div class="badge text-bg-dark mb-0 rounded-pill">{{ $pricing->tag }}</div>
                                        </div>
                                        <!-- Price -->
                                        <div>
                                            <h4 class="text-success mb-0 plan-price">{{ \App\Helpers\Helper::formatCurrency($pricing->price) }}</h4>
                                            <p class="small mb-0">/ {{ $pricing->duration }} {{ $pricing->type == 'yearly' ? 'Year' : 'Month' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="position-relative my-3 text-center">
                                    <hr>
                                    <p class="small position-absolute top-50 start-50 translate-middle bg-body px-3">
                                        This plan include
                                    </p>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body pt-0 m-0 p-0">
                                    <ul class="list-unstyled mt-2 mb-0">
                                        @php
                                            $features = json_decode($pricing->features)
                                        @endphp
                                        @foreach ($features as $feature)
                                            <li class="mb-3 h6 fw-light">
                                                <i class="bi bi-patch-check-fill text-success me-2"></i>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- Card Footer -->
                                <div class="card-footer text-center d-grid pb-0">
                                    @if (Auth::check())
                                        <a href="{{ route('frontend.checkout', ['pricing' => $pricing->slug]) }}" class="btn btn-dark mb-0">Get Started</a>
                                    @else
                                        <a href="{{ route('register', ['pricing' => $pricing->slug]) }}" class="btn btn-dark mb-0">Get Started</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                <!-- Pricing item END -->
            </div>
            <!-- Row END -->
            <!-- Pricing END -->
        </div>
    </section>
@endsection

@section('script')
    
@endsection
