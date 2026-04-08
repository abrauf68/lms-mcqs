@extends('layouts.master')

@section('title', __('Customer Details'))

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.customers.index') }}">{{ __('Customers') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- TOP PROFILE CARD -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">

                <img src="{{ $customer->image ? asset($customer->image) : asset('assets/img/default/user.png') }}"
                     class="rounded-circle me-4"
                     width="90" height="90">

                <div class="flex-grow-1">
                    <h4 class="mb-1">{{ $customer->name }}</h4>
                    <p class="text-muted mb-1">{{ $customer->email }}</p>

                    <span class="badge rounded-pill
                        {{ $customer->is_active == 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($customer->is_active) }}
                    </span>
                </div>

                <div class="text-end">
                    <p class="mb-1"><strong>Username:</strong> {{ $customer->username }}</p>
                    <p class="mb-0"><strong>Joined:</strong> {{ $customer->created_at->format('d M Y') }}</p>
                </div>

            </div>
        </div>
    </div>

    <!-- STATS -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>{{ $customer->userExams->count() }}</h5>
                    <small class="text-muted">Total Exams</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>{{ $customer->transactions->where('status','paid')->count() }}</h5>
                    <small class="text-muted">Paid Transactions</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>{{ $customer->userPricings->where('status','active')->count() }}</h5>
                    <small class="text-muted">Active Plans</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>{{ \App\Helpers\Helper::formatCurrency($customer->transactions->where('status','paid')->sum('amount')) }}</h5>
                    <small class="text-muted">Total Spend</small>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <!-- LEFT SIDE -->
        <div class="col-md-4">

            <!-- CONTACT -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Contact Info</h6>
                </div>
                <div class="card-body">
                    <p><strong>Phone:</strong> {{ $customer->phone ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                </div>
            </div>

            <!-- BILLING -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Billing Info</h6>
                </div>
                <div class="card-body">
                    @php $billing = $customer->userBillings; @endphp

                    @if($billing)
                        <p><strong>Name:</strong> {{ $billing->first_name }} {{ $billing->last_name }}</p>
                        <p><strong>City:</strong> {{ $billing->city }}</p>
                        <p><strong>Country:</strong> {{ $billing->country }}</p>
                        <p><strong>Address:</strong> {{ $billing->address }}</p>
                    @else
                        <p class="text-muted">No billing info</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="col-md-8">

            <!-- SUBSCRIPTIONS -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="mb-0">Subscriptions</h6>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Plan</th>
                                <th>Duration</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->userPricings as $pricing)
                                <tr>
                                    <td>
                                        <strong>{{ $pricing->pricing->name ?? '-' }}</strong><br>
                                        <small class="text-muted">
                                            {{ $pricing->start_date }} → {{ $pricing->end_date }}
                                        </small>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($pricing->start_date)->diffInDays($pricing->end_date) }} days
                                    </td>
                                    <td>
                                        <span class="badge
                                            {{ $pricing->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($pricing->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No plans</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- EXAMS -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Exam Performance</h6>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Exam</th>
                                <th>Score</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->userExams as $exam)
                                <tr>
                                    <td>{{ $exam->exam->name ?? '-' }}</td>
                                    <td>
                                        <strong>{{ $exam->score_percentage ?? 0 }}%</strong>
                                    </td>
                                    <td>
                                        <span class="badge
                                            {{ $exam->result == 'pass' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($exam->result ?? '-') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No exams</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TRANSACTIONS -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Transactions</h6>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->transactions as $trx)
                                <tr>
                                    <td>{{ $trx->transaction_id }}</td>
                                    <td><strong>{{ \App\Helpers\Helper::formatCurrency($trx->amount) }}</strong></td>
                                    <td>
                                        <span class="badge
                                            {{ $trx->status == 'paid' ? 'bg-success' : ($trx->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst($trx->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No transactions</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
