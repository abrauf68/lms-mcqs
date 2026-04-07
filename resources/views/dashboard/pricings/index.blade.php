@extends('layouts.master')

@section('title', __('Pricings'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Pricings') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Pricings List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create pricing'])
                    <a href="{{route('dashboard.pricings.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Pricing') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Duration') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete pricing', 'update pricing'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pricings as $index => $pricing)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($pricing->name, 30, '...') }}</td>
                                <td>{{ \App\Helpers\Helper::formatCurrency($pricing->price) }}</td>
                                <td>{{ $pricing->type == 'monthly' ? $pricing->duration . ' ' . __('Months') : $pricing->duration . ' ' . __('Years') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $pricing->is_active == 'active' ? 'success' : 'danger' }}">{{ ucfirst($pricing->is_active) }}</span>
                                </td>
                                @canany(['delete pricing', 'update pricing'])
                                    <td class="d-flex">
                                        @canany(['delete pricing'])
                                            <form action="{{ route('dashboard.pricings.destroy', $pricing->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Pricing') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update pricing'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.pricings.edit', $pricing->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Pricing') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.pricings.status.update', $pricing->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $pricing->is_active == 'active' ? __('Deactivate Pricing') : __('Activate Pricing') }}">
                                                    @if ($pricing->is_active == 'active')
                                                        <i class="ti ti-toggle-right ti-md text-success"></i>
                                                    @else
                                                        <i class="ti ti-toggle-left ti-md text-danger"></i>
                                                    @endif
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
