@extends('frontend.layouts.master')

@section('title', 'Checkout')

@section('css')
@endsection

@section('content')
    <!-- =======================
                                                    Page Banner START -->
    <section class="py-0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bg-light p-4 text-center rounded-3">
                        <h1 class="m-0">Checkout</h1>
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-dots mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =======================
                                                    Page Banner END -->

    <!-- =======================
                                                    Page content START -->
    <section class="pt-5">
        <div class="container">
            <form action="{{ route('frontend.checkout.process') }}" method="POST" id="payment-form">
                @csrf
                <input type="hidden" name="pricing_id" value="{{ $pricing->id }}">
                <input type="hidden" name="amount" value="{{ $pricing->price }}">
                <div class="row g-4 g-sm-5">
                    <!-- Main content START -->
                    <div class="col-xl-8 mb-4 mb-sm-0">

                        <!-- Personal info START -->
                        <div class="card card-body shadow p-4">
                            <!-- Title -->
                            <h5 class="mb-0">Billing details</h5>
                            <div class="row g-3 mt-0">
                                <!-- Name -->
                                <div class="col-md-6 bg-light-input">
                                    <label for="firstName" class="form-label">First name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First name"
                                        value="{{ old('firstName', $billing ? $billing->first_name : '') }}" required>
                                    @error('firstName')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- Last Name -->
                                <div class="col-md-6 bg-light-input">
                                    <label for="lastName" class="form-label">Last name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last name"
                                        value="{{ old('lastName', $billing ? $billing->last_name : '') }}" required>
                                    @error('lastName')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- Email -->
                                <div class="col-md-6 bg-light-input">
                                    <label for="email" class="form-label">Email address <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                        value="{{ old('email', $billing ? $billing->email : Auth::user()->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- Number -->
                                <div class="col-md-6 bg-light-input">
                                    <label for="phone" class="form-label">Phone (optional)</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Mobile number"
                                        value="{{ old('phone', $billing ? $billing->phone : '') }}">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- Country option -->
                                <div class="col-md-6 bg-light-input">
                                    <label for="country" class="form-label">Select country <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select js-choice" id="country" required>
                                        <option value="" selected disabled>Select country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" data-name="{{ $country->id }}"
                                                {{ old('country') == $country->id || ( $billing && $billing->country == $country->name ) ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- State option -->
                                <div class="col-md-6 bg-light-input">
                                    <label for="mobileNumber" class="form-label">Select state <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select js-choice" name="state" id="state" required>
                                        <option value="" selected disabled>Select state</option>
                                    </select>
                                    @error('state')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- Town -->
                                <div class="col-md-6 bg-light-input">
                                    <label for="city" class="form-label">Town / City <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Town"
                                        value="{{ old('city', $billing ? $billing->city : '') }}" required>
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- Postal code -->
                                <div class="col-md-6 bg-light-input">
                                    <label for="zip" class="form-label">Postal code / ZIP <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="zip" name="zip" placeholder="PIN code"
                                        value="{{ old('zip', $billing ? $billing->zip : '') }}" required>
                                    @error('zip')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- Address -->
                                <div class="col-md-12 bg-light-input">
                                    <label for="address" class="form-label">Street Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address"
                                        placeholder="Street Address" name="address" value="{{ old('address', $billing ? $billing->address : '') }}" required>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Personal info END -->


                        <!-- Checkout START -->
                        <div class="card card-body shadow p-4 mt-4">
                            <!-- Title -->

                            <div class="col-12 d-flex justify-content-between align-items-center">

                                <!-- Left Side Text -->
                                <h4 class="mb-0">Credit/Debit Card</h4>

                                <!-- Right Side Cards -->
                                <div class="d-flex gap-2">
                                    <div class="border rounded p-1">
                                        <img src="{{ asset('frontAssets/images/client/mastercard.svg') }}" alt=""
                                            width="40">
                                    </div>
                                    <div class="border rounded p-1">
                                        <img src="{{ asset('frontAssets/images/client/visa.svg') }}" alt=""
                                            width="40">
                                    </div>
                                    <div class="border rounded p-1">
                                        <img src="{{ asset('frontAssets/images/client/ae-card.svg') }}" alt=""
                                            width="40">
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <!-- Coupon END -->

                            <!-- Course item START -->
                            <div class="row g-3">
                                <!-- Stripe Card Element -->
                                <div class="col-12">
                                    <label class="form-label">Card Details <span class="text-danger">*</span></label>
                                    <div id="card-element" class="form-control p-3"></div>
                                    <div id="card-errors" class="text-danger mt-2"></div>
                                </div>
                            </div>
                            <!-- Course item END -->

                            <!-- Button -->
                            <div class="d-grid mt-3">
                                <button class="btn btn-lg btn-success" type="submit" id="card-button">Place Order</button>
                            </div>

                            <!-- Content -->
                            <p class="small mb-0 mt-2 text-center">By completing your purchase, you agree to these
                                <a href="#"><strong>Terms of Service</strong></a>
                            </p>

                        </div>
                        <!-- Checkout END -->
                    </div>
                    <!-- Main content END -->

                    <!-- Right sidebar START -->
                    <div class="col-xl-4">
                        <div class="row mb-0">
                            <div class="col-md-6 col-xl-12">
                                <!-- Order summary START -->
                                <div class="card card-body shadow p-4 mb-4">
                                    <!-- Title -->
                                    <h4 class="mb-4">Order Summary</h4>

                                    <!-- Coupon START -->
                                    {{-- <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Transaction code</span>
                                            <p class="mb-0 h6 fw-light">AB12365E</p>
                                        </div>
                                        <div class="input-group mt-2">
                                            <input class="form-control form-control" placeholder="COUPON CODE">
                                            <button type="button" class="btn btn-primary">Apply</button>
                                        </div>

                                    </div> --}}
                                    <hr>
                                    <!-- Coupon END -->

                                    <!-- Course item START -->
                                    <div class="row g-3">
                                        <!-- Info -->
                                        <div class="col-sm-12">
                                            <h6 class="mb-0 d-flex justify-content-between align-items-center">
                                                <a href="#">
                                                    {{ $pricing->name }} -
                                                    {{ $pricing->duration }}
                                                    {{ $pricing->type == 'yearly' ? 'Year' : 'Month' }}
                                                </a>
                                                <span  class="text-success">{{ \App\Helpers\Helper::formatCurrency($pricing->price) }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <!-- Course item END -->

                                    <hr> <!-- Divider -->

                                    <!-- Price and detail -->
                                    <ul class="list-group list-group-borderless mb-2">
                                        <li class="list-group-item px-0 d-flex justify-content-between">
                                            <span class="h6 fw-light mb-0">Original Price</span>
                                            <span
                                                class="h6 fw-light mb-0 fw-bold">{{ \App\Helpers\Helper::formatCurrency($pricing->price) }}</span>
                                        </li>
                                        <li class="list-group-item px-0 d-flex justify-content-between">
                                            <span class="h6 fw-light mb-0">Tax</span>
                                            <span
                                                class="text-primary">+{{ \App\Helpers\Helper::formatCurrency(0) }}</span>
                                        </li>
                                        <li class="list-group-item px-0 d-flex justify-content-between">
                                            <span class="h6 fw-light mb-0">Coupon Discount</span>
                                            <span class="text-danger">-{{ \App\Helpers\Helper::formatCurrency(0) }}</span>
                                        </li>
                                        <li class="list-group-item px-0 d-flex justify-content-between">
                                            <span class="h5 mb-0">Total</span>
                                            <span
                                                class="h5 mb-0">{{ \App\Helpers\Helper::formatCurrency($pricing->price) }}</span>
                                        </li>
                                    </ul>

                                </div>
                                <!-- Order summary END -->
                            </div>
                        </div><!-- Row End -->
                    </div>
                    <!-- Right sidebar END -->

                </div>
                <input type="hidden" name="country" id="countryName">
                <input type="hidden" name="payment_method" id="payment_method">
            </form>
        </div>
    </section>
    <!-- =======================
                                                    Page content END -->
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $(document).ready(function() {

            const stateChoice = new Choices('#state', {
                shouldSort: false,
                allowHTML: false
            });

            $('#country').on('change', function() {

                let countryId = $(this).val(); // ✅ FIX
                let countryName = $(this).find('option:selected').text();
                $('#countryName').val(countryName); // ✅ FIX
                $.ajax({
                    url: '/get-states/' + countryId,
                    type: 'GET',
                    success: function(response) {

                        const options = [{
                            value: '',
                            label: 'Select state',
                            disabled: true,
                            selected: true
                        }];

                        response.forEach(state => {
                            options.push({
                                value: state.name,
                                label: state.name
                            });
                        });

                        stateChoice.clearChoices(); // better than clearStore
                        stateChoice.setChoices(options, 'value', 'label', true);
                    }
                });

            });

        });
    </script>
    <script>
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const elements = stripe.elements();

        const card = elements.create('card');
        card.mount('#card-element');

        card.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            displayError.textContent = event.error ? event.error.message : '';
        });

        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: card
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                return;
            }

            // ✅ hidden field set
            document.getElementById('payment_method').value = paymentMethod.id;

            // ✅ form submit
            form.submit();
        });
    </script>
@endsection
