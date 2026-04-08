@extends('layouts.master')

@section('title', __('Customers'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Customers') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Customers List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create customer'])
                    <a href="{{route('dashboard.customers.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Customer') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete user', 'update user', 'view user'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $index => $customer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">

                                        <!-- User Image -->
                                        <div class="avatar avatar-sm me-3">
                                            @if ($customer->image)
                                                <img src="{{ asset($customer->image) }}" alt="Avatar"
                                                    class="rounded-circle">
                                            @else
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- User Info -->
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">
                                                {{ $customer->name }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $customer->email }}
                                            </small>
                                        </div>

                                    </div>
                                </td>
                                <td>{{ $customer->phone ?? 'N/A' }}</td>
                                <td>{{ $customer->created_at->format('d M Y') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $customer->is_active == 'active' ? 'success' : 'danger' }}">{{ ucfirst($customer->is_active) }}</span>
                                </td>
                                @canany(['delete user', 'update user', 'view user'])
                                    <td class="d-flex">
                                        @canany(['delete user'])
                                            <form action="{{ route('dashboard.user.destroy', $customer->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Customer') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update user'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.user.status.update', $customer->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $customer->is_active == 'active' ? __('Deactivate Customer') : __('Activate Customer') }}">
                                                    @if ($customer->is_active == 'active')
                                                        <i class="ti ti-toggle-right ti-md text-success"></i>
                                                    @else
                                                        <i class="ti ti-toggle-left ti-md text-danger"></i>
                                                    @endif
                                                </a>
                                            </span>
                                            {{-- <span class="text-nowrap">
                                                <a href="{{ route('dashboard.customers.edit', $customer->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Customer') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span> --}}
                                        @endcan
                                        @canany(['view user'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.customers.show', $customer->id) }}"
                                                    class="btn btn-icon btn-text-warning waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Customer') }}">
                                                    <i class="ti ti-eye ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- <script src="{{asset('assets/js/app-user-list.js')}}"></script> --}}
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
