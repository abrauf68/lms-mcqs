@extends('layouts.master')

@section('title', __('Create Question'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.questions.index') }}">{{ __('Questions') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.questions.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row p-5">
                        <h3>{{ __('Add New Question') }}</h3>
                        <div class="mb-4 col-md-4">
                            <label class="form-label" for="product_id">{{ __('Product') }}</label>
                            <select id="product_id" name="product_id"
                                class="select2 form-select @error('product_id') is-invalid @enderror">
                                <option value="" selected disabled>{{ __('Select Product') }}</option>
                                @if (isset($products) && count($products) > 0)
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ $product->id == old('product_id') ? 'selected' : '' }}>{{ $product->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('product_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label class="form-label" for="domain_id">{{ __('Domain') }}</label>
                            <select id="domain_id" name="domain_id"
                                class="select2 form-select @error('domain_id') is-invalid @enderror">
                                <option value="" selected disabled>{{ __('Select Domain') }}</option>
                                @if (isset($domains) && count($domains) > 0)
                                    @foreach ($domains as $domain)
                                        <option value="{{ $domain->id }}"
                                            {{ $domain->id == old('domain_id') ? 'selected' : '' }}>{{ $domain->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('domain_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label class="form-label" for="process_group_id">{{ __('Process Group') }}</label>
                            <select id="process_group_id" name="process_group_id"
                                class="select2 form-select @error('process_group_id') is-invalid @enderror">
                                <option value="" selected disabled>{{ __('Select Process Group') }}</option>
                                @if (isset($processGroups) && count($processGroups) > 0)
                                    @foreach ($processGroups as $processGroup)
                                        <option value="{{ $processGroup->id }}"
                                            {{ $processGroup->id == old('process_group_id') ? 'selected' : '' }}>{{ $processGroup->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('process_group_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label class="form-label" for="topic_id">{{ __('Topic') }}</label>
                            <select id="topic_id" name="topic_id"
                                class="select2 form-select @error('topic_id') is-invalid @enderror">
                                <option value="" selected disabled>{{ __('Select Topic') }}</option>
                                @if (isset($topics) && count($topics) > 0)
                                    @foreach ($topics as $topic)
                                        <option value="{{ $topic->id }}"
                                            {{ $topic->id == old('topic_id') ? 'selected' : '' }}>{{ $topic->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('topic_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label class="form-label" for="type">{{ __('Type') }}</label>
                            <select id="type" name="type"
                                class="select2 form-select @error('type') is-invalid @enderror">
                                <option value="" selected disabled>{{ __('Select Type') }}</option>
                                <option value="single_choice"
                                    {{ 'single_choice' == old('type') ? 'selected' : '' }}>{{ __('Single Choice') }}
                                </option>
                                <option value="multi_choice"
                                    {{ 'multi_choice' == old('type') ? 'selected' : '' }}>{{ __('Multiple Choice') }}
                                </option>
                                <option value="fill_blank"
                                    {{ 'fill_blank' == old('type') ? 'selected' : '' }}>{{ __('Fill in the Blank') }}
                                </option>
                                <option value="matching"
                                    {{ 'matching' == old('type') ? 'selected' : '' }}>{{ __('Matching') }}
                                </option>
                                <option value="hotspot"
                                    {{ 'hotspot' == old('type') ? 'selected' : '' }}>{{ __('Hotspot') }}
                                </option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label class="form-label" for="approach_id">{{ __('Approach') }}</label>
                            <select id="approach_id" name="approach_id"
                                class="select2 form-select @error('approach_id') is-invalid @enderror">
                                <option value="" selected disabled>{{ __('Select Approach') }}</option>
                                @if (isset($approaches) && count($approaches) > 0)
                                    @foreach ($approaches as $approach)
                                        <option value="{{ $approach->id }}"
                                            {{ $approach->id == old('approach_id') ? 'selected' : '' }}>{{ $approach->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('approach_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                placeholder="{{ __('Enter description') }}" cols="30" rows="10">
                                {{ old('description') }}
                            </textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Add Question') }}</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Generate slug from name
            $('#name').on('keyup change', function() {
                let name = $(this).val();
                let slug = name.toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                $('#slug').val(slug);
            });

        });
    </script>
@endsection
