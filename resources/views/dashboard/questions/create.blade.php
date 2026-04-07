@extends('layouts.master')

@section('title', __('Create Question'))

@section('css')
<style>
    #preview_image {
        cursor: crosshair;
    }
</style>
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
                            <label class="form-label" for="product_id">{{ __('Product') }}</label><span
                                class="text-danger">*</span>
                            <select id="product_id" name="product_id"
                                class="select2 form-select @error('product_id') is-invalid @enderror" required>
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
                            <label class="form-label" for="domain_id">{{ __('Domain') }}</label><span
                                class="text-danger">*</span>
                            <select id="domain_id" name="domain_id"
                                class="select2 form-select @error('domain_id') is-invalid @enderror" required>
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
                            <label class="form-label" for="process_group_id">{{ __('Process Group') }}</label><span
                                class="text-danger">*</span>
                            <select id="process_group_id" name="process_group_id"
                                class="select2 form-select @error('process_group_id') is-invalid @enderror" required>
                                <option value="" selected disabled>{{ __('Select Process Group') }}</option>
                                @if (isset($processGroups) && count($processGroups) > 0)
                                    @foreach ($processGroups as $processGroup)
                                        <option value="{{ $processGroup->id }}"
                                            {{ $processGroup->id == old('process_group_id') ? 'selected' : '' }}>
                                            {{ $processGroup->name }}
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
                            <label class="form-label" for="topic_id">{{ __('Topic') }}</label><span
                                class="text-danger">*</span>
                            <select id="topic_id" name="topic_id"
                                class="select2 form-select @error('topic_id') is-invalid @enderror" required>
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
                            <label class="form-label" for="approach_id">{{ __('Approach') }}</label><span
                                class="text-danger">*</span>
                            <select id="approach_id" name="approach_id"
                                class="select2 form-select @error('approach_id') is-invalid @enderror" required>
                                <option value="" selected disabled>{{ __('Select Approach') }}</option>
                                @if (isset($approaches) && count($approaches) > 0)
                                    @foreach ($approaches as $approach)
                                        <option value="{{ $approach->id }}"
                                            {{ $approach->id == old('approach_id') ? 'selected' : '' }}>
                                            {{ $approach->name }}
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
                        <div class="mb-4 col-md-4">
                            <label class="form-label" for="type">{{ __('Type') }}</label><span
                                class="text-danger">*</span>
                            <select id="type" name="type"
                                class="select2 form-select @error('type') is-invalid @enderror" required>
                                <option value="" selected disabled>{{ __('Select Type') }}</option>
                                <option value="single_choice" {{ 'single_choice' == old('type') ? 'selected' : '' }}>
                                    {{ __('Single Choice') }}
                                </option>
                                <option value="multi_choice" {{ 'multi_choice' == old('type') ? 'selected' : '' }}>
                                    {{ __('Multiple Choice') }}
                                </option>
                                <option value="fill_blank" {{ 'fill_blank' == old('type') ? 'selected' : '' }}>
                                    {{ __('Fill in the Blank') }}
                                </option>
                                <option value="matching" {{ 'matching' == old('type') ? 'selected' : '' }}>
                                    {{ __('Matching') }}
                                </option>
                                <option value="hotspot" {{ 'hotspot' == old('type') ? 'selected' : '' }}>
                                    {{ __('Hotspot') }}
                                </option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="question_text" class="form-label">{{ __('Question Text') }}</label><span
                                class="text-danger">*</span>
                            <textarea class="form-control @error('question_text') is-invalid @enderror" id="question_text" name="question_text"
                                placeholder="{{ __('Enter question text') }}" rows="5" required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="ans_explanation" class="form-label">{{ __('Answer Explanation') }}</label><span
                                class="text-danger">*</span>
                            <textarea class="form-control @error('ans_explanation') is-invalid @enderror" id="ans_explanation"
                                name="ans_explanation" placeholder="{{ __('Enter answer explanation') }}" rows="5" required>{{ old('ans_explanation') }}</textarea>
                            @error('ans_explanation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- ================= OPTIONS (Single / Multi) ================= -->
                    <div id="choice_fields" class="row p-5 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h5>Options</h5>
                            <button type="button" class="btn btn-primary btn-sm mt-2" id="add_option">Add More</button>
                        </div>

                        <div id="options_wrapper">
                            <div class="row option-item mb-3">
                                <div class="col-md-8">
                                    <input type="text" name="options[]" class="form-control" placeholder="Option">
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="is_correct[]" value="0"> Correct
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-option">X</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- ================= FILL BLANK ================= -->
                    <div id="fill_blank_fields" class="row p-5 d-none">
                        <h5>Fill in the Blank</h5>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <input type="file" name="blank_image" class="form-control" accept="image/*" placeholder="Blank Image">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="correct_answer" class="form-control" placeholder="Correct Answer">
                            </div>
                        </div>
                    </div>


                    <!-- ================= MATCHING ================= -->
                    <div id="matching_fields" class="row p-5 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h5>Matching</h5>
                            <button type="button" class="btn btn-primary btn-sm mt-2" id="add_match">Add More</button>
                        </div>

                        <div id="match_wrapper">
                            <div class="row mb-3 match-item">
                                <div class="col-md-5">
                                    <input type="text" name="left_item[]" class="form-control"
                                        placeholder="Left Item">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="right_item[]" class="form-control"
                                        placeholder="Right Item">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-match">X</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- ================= HOTSPOT ================= -->
                    <div id="hotspot_fields" class="row p-5 d-none">
                        <h5>Hotspot</h5>

                        <div class="mb-3 col-md-6">
                            <input type="file" id="hotspot_image" name="hotspot_image" class="form-control" accept="image/*">
                        </div>

                        <div class="col-md-12">
                            <div style="position: relative; display: inline-block;">
                                <img id="preview_image" src="" style="max-width: 100%; display:none; border:1px solid #ccc;">

                                <!-- Selection Box -->
                                <div id="selection_box"
                                    style="position:absolute; border:2px dashed red; display:none; cursor:move;">
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="x" id="input_x">
                        <input type="hidden" name="y" id="input_y">
                        <input type="hidden" name="width" id="input_width">
                        <input type="hidden" name="height" id="input_height">
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

            function toggleFields(type) {
                $('#choice_fields, #fill_blank_fields, #matching_fields, #hotspot_fields').addClass('d-none');

                if (type === 'single_choice' || type === 'multi_choice') {
                    $('#choice_fields').removeClass('d-none');
                }
                if (type === 'fill_blank') {
                    $('#fill_blank_fields').removeClass('d-none');
                }
                if (type === 'matching') {
                    $('#matching_fields').removeClass('d-none');
                }
                if (type === 'hotspot') {
                    $('#hotspot_fields').removeClass('d-none');
                }
            }

            $('#type').on('change', function() {
                toggleFields($(this).val());
            });

            toggleFields($('#type').val());


            // ================= OPTIONS =================
            let optionIndex = 1;

            $('#add_option').click(function() {
                $('#options_wrapper').append(`
                    <div class="row option-item mb-3">
                        <div class="col-md-8">
                            <input type="text" name="options[]" class="form-control" placeholder="Option">
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" name="is_correct[]" value="${optionIndex}"> Correct
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-option">X</button>
                        </div>
                    </div>
                `);
                optionIndex++;
            });

            $(document).on('click', '.remove-option', function() {
                $(this).closest('.option-item').remove();
            });


            // ================= MATCHING =================
            $('#add_match').click(function() {
                $('#match_wrapper').append(`
                    <div class="row mb-3 match-item">
                        <div class="col-md-5">
                            <input type="text" name="left_item[]" class="form-control" placeholder="Left Item">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="right_item[]" class="form-control" placeholder="Right Item">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-match">X</button>
                        </div>
                    </div>
                `);
            });

            $(document).on('click', '.remove-match', function() {
                $(this).closest('.match-item').remove();
            });

            // HOTSPOT IMAGE PREVIEW
            $('#hotspot_image').on('change', function(e) {

                if (!this.files || !this.files[0]) return;

                let reader = new FileReader();

                reader.onload = function(ev) {
                    console.log("IMAGE LOADED");

                    $('#preview_image')
                        .attr('src', ev.target.result)
                        .css('display', 'block'); // IMPORTANT FIX
                }

                reader.readAsDataURL(this.files[0]);
            });

            // DRAW
            let isDragging = false;
            let startX, startY;

            $(document).on('mousedown', '#preview_image', function(e) {
                let rect = this.getBoundingClientRect();

                startX = e.clientX - rect.left;
                startY = e.clientY - rect.top;

                isDragging = true;

                $('#selection_box').show().css({
                    left: startX,
                    top: startY,
                    width: 0,
                    height: 0
                });
            });

            $(document).on('mousemove', function(e) {
                if (!isDragging) return;

                let rect = $('#preview_image')[0].getBoundingClientRect();

                let currentX = e.clientX - rect.left;
                let currentY = e.clientY - rect.top;

                let width = currentX - startX;
                let height = currentY - startY;

                $('#selection_box').css({
                    width: Math.abs(width),
                    height: Math.abs(height),
                    left: width < 0 ? currentX : startX,
                    top: height < 0 ? currentY : startY
                });
            });


            $(document).on('mouseup', function() {
                if (!isDragging) return;
                isDragging = false;

                let img = $('#preview_image')[0].getBoundingClientRect();
                let box = $('#selection_box')[0].getBoundingClientRect();

                let x = box.left - img.left;
                let y = box.top - img.top;

                let width = box.width;
                let height = box.height;

                // convert to %
                let xPercent = (x / img.width) * 100;
                let yPercent = (y / img.height) * 100;
                let wPercent = (width / img.width) * 100;
                let hPercent = (height / img.height) * 100;

                // save
                $('#input_x').val(xPercent.toFixed(2));
                $('#input_y').val(yPercent.toFixed(2));
                $('#input_width').val(wPercent.toFixed(2));
                $('#input_height').val(hPercent.toFixed(2));

                // 🔥 IMPORTANT: keep box visible (selected state)
                $('#selection_box').css({
                    border: "2px solid green",
                    background: "rgba(0,255,0,0.2)"
                });
            });

        });
    </script>
@endsection
