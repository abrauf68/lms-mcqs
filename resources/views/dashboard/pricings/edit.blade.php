@extends('layouts.master')

@section('title', __('Edit Pricing'))

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.pricings.index') }}">{{ __('Pricings') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.pricings.update', $pricing->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row p-5">
                        <h3>{{ __('Edit Pricing') }}</h3>
                        <div class="mb-4 col-md-6">
                            <label for="name" class="form-label">{{ __('Pricing Name') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                                name="name" required placeholder="{{ __('Enter pricing name') }}" autofocus
                                value="{{ old('name', $pricing->name) }}" />
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="slug" class="form-label">{{ __('Slug') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('slug') is-invalid @enderror" type="text" id="slug"
                                name="slug" required placeholder="{{ __('Enter slug') }}"
                                value="{{ old('slug', $pricing->slug) }}" />
                            @error('slug')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label class="form-label" for="type">{{ __('Type') }}</label><span
                                class="text-danger">*</span>
                            <select id="type" name="type"
                                class="select2 form-select @error('type') is-invalid @enderror" required>
                                <option value="" selected disabled>{{ __('Select Type') }}</option>
                                <option value="monthly" {{ 'monthly' == old('type', $pricing->type) ? 'selected' : '' }}>
                                    {{ __('Monthly') }}
                                </option>
                                <option value="yearly" {{ 'yearly' == old('type', $pricing->type) ? 'selected' : '' }}>
                                    {{ __('Yearly') }}
                                </option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="price" class="form-label">{{ __('Price') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('price') is-invalid @enderror" type="number" step="any" id="price"
                                name="price" required placeholder="{{ __('Enter price') }}"
                                value="{{ old('price', $pricing->price) }}" />
                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="duration" class="form-label">{{ __('Duration') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('duration') is-invalid @enderror" type="number" step="any" id="duration"
                                name="duration" required placeholder="{{ __('Enter duration') }}"
                                value="{{ old('duration', $pricing->duration) }}" />
                            @error('duration')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="tag" class="form-label">{{ __('Tag') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('tag') is-invalid @enderror" type="text" id="tag"
                                name="tag" required placeholder="{{ __('i.e. Best Value') }}"
                                value="{{ old('tag', $pricing->tag) }}" />
                            @error('tag')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="features" class="form-label">Features</label>
                            <input id="features" name="features"
                                class="form-control @error('features') is-invalid @enderror" placeholder="Select features"
                                value="{{ old('features', $pricing->features) }}" />
                            @error('features')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                placeholder="{{ __('Enter description') }}" cols="30" rows="10">{{ old('description', $pricing->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Update Pricing') }}</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script>
        $(document).ready(function() {
            const tagsEl = document.querySelector('#features');
            const whitelist = @json($uniqueFeatures);
            // Inline
            let tags = new Tagify(tagsEl, {
                whitelist: whitelist,
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: 'tags-inline',
                    enabled: 0,
                    closeOnSelect: false
                }
            });
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
