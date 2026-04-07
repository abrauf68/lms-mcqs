@extends('layouts.master')

@section('title', __('Questions'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Questions') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Questions List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create question'])
                    <a href="{{route('dashboard.questions.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Question') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-json-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Question') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete question', 'update question'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @foreach ($questions as $index => $question)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $question->type)) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($question->question_text, 30, '...') }}</td>
                                <td>{{ $question->created_at->format('d M Y') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $question->is_active == 'active' ? 'success' : 'danger' }}">{{ ucfirst($question->is_active) }}</span>
                                </td>
                                @canany(['delete question', 'update question'])
                                    <td class="d-flex">
                                        @canany(['delete question'])
                                            <form action="{{ route('dashboard.questions.destroy', $question->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Question') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update question'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.questions.edit', $question->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Question') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.questions.status.update', $question->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $question->is_active == 'active' ? __('Deactivate Question') : __('Activate Question') }}">
                                                    @if ($question->is_active == 'active')
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
                    </tbody> --}}
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- <script src="{{asset('assets/js/app-user-list.js')}}"></script> --}}
    <script>
        $(document).ready(function() {
            $(function () {
                $('.custom-json-datatables').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('questions.json') }}",
                        type: 'GET',
                        xhrFields: {
                            withCredentials: true
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'q_type', name: 'q_type' },
                        { data: 'question_text', name: 'question_text' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'status', name: 'status' },
                        { data: 'action', name: 'action', orderable: false, searchable: false , className: 'col-20'},
                    ],
                    language: {
                        searchPlaceholder: 'Search...',
                        paginate: {
                            next: '<i class="ti ti-chevron-right ti-sm"></i>',
                            previous: '<i class="ti ti-chevron-left ti-sm"></i>'
                        }
                    },
                    dom: 'Bfrtip',
                    dom: '<"row"' +
                        '<"col-md-2"<l>>' +
                        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0"fB>>' +
                        '>t' +
                        '<"row"' +
                        '<"col-sm-12 col-md-6"i>' +
                        '<"col-sm-12 col-md-6"p>' +
                        '>',
                    buttons: [{
                        extend: 'collection',
                        className: 'btn btn-label-secondary dropdown-toggle me-4 waves-effect waves-light border-left-0 border-right-0 rounded',
                        text: '<i class="ti ti-upload ti-xs me-sm-1 align-text-bottom"></i> <span class="d-none d-sm-inline-block">{{__("Export")}}</span>',
                        buttons: [{
                                extend: 'print',
                                text: '<i class="ti ti-printer me-1"></i>{{__("Print")}}',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':not(:last-child)' // Exclude the last column (Actions)
                                }
                            },
                            {
                                extend: 'csv',
                                text: '<i class="ti ti-file-text me-1"></i>{{__("Csv")}}',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':not(:last-child)' // Exclude the last column (Actions)
                                }
                            },
                            {
                                extend: 'excel',
                                text: '<i class="ti ti-file-spreadsheet me-1"></i>{{__("Excel")}}',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':not(:last-child)' // Exclude the last column (Actions)
                                }
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="ti ti-file-description me-1"></i>{{__("Pdf")}}',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':not(:last-child)' // Exclude the last column (Actions)
                                }
                            },
                            {
                                extend: 'copy',
                                text: '<i class="ti ti-copy me-1"></i>{{__("Copy")}}',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: ':not(:last-child)' // Exclude the last column (Actions)
                                }
                            }
                        ]
                    }],
                });
            });
        });
    </script>
@endsection
