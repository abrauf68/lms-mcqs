@extends('layouts.master')

@section('title', __('Edit Question'))

@section('css')
<style>
    #preview_image {
        cursor: crosshair;
    }
</style>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.questions.index') }}">{{ __('Questions') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <div class="card-body pt-4">

                <form method="POST" action="{{ route('dashboard.questions.update', $question->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row p-5">
                        <h3>{{ __('Edit Question') }}</h3>

                        {{-- PRODUCT --}}
                        <div class="mb-4 col-md-4">
                            <label class="form-label">{{ __('Product') }}</label>
                            <select name="product_id" class="select2 form-select" required>
                                <option disabled>{{ __('Select Product') }}</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $product->id == old('product_id', $question->product_id) ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DOMAIN --}}
                        <div class="mb-4 col-md-4">
                            <label class="form-label">{{ __('Domain') }}</label>
                            <select name="domain_id" class="select2 form-select" required>
                                @foreach ($domains as $domain)
                                    <option value="{{ $domain->id }}"
                                        {{ $domain->id == old('domain_id', $question->domain_id) ? 'selected' : '' }}>
                                        {{ $domain->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PROCESS --}}
                        <div class="mb-4 col-md-4">
                            <label class="form-label">{{ __('Process Group') }}</label>
                            <select name="process_group_id" class="select2 form-select" required>
                                @foreach ($processGroups as $pg)
                                    <option value="{{ $pg->id }}"
                                        {{ $pg->id == old('process_group_id', $question->process_group_id) ? 'selected' : '' }}>
                                        {{ $pg->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TOPIC --}}
                        <div class="mb-4 col-md-4">
                            <label class="form-label">{{ __('Topic') }}</label>
                            <select name="topic_id" class="select2 form-select" required>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}"
                                        {{ $topic->id == old('topic_id', $question->topic_id) ? 'selected' : '' }}>
                                        {{ $topic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- APPROACH --}}
                        <div class="mb-4 col-md-4">
                            <label class="form-label">{{ __('Approach') }}</label>
                            <select name="approach_id" class="select2 form-select" required>
                                @foreach ($approaches as $approach)
                                    <option value="{{ $approach->id }}"
                                        {{ $approach->id == old('approach_id', $question->approach_id) ? 'selected' : '' }}>
                                        {{ $approach->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TYPE --}}
                        <div class="mb-4 col-md-4">
                            <label class="form-label">{{ __('Type') }}</label>
                            <select id="type" name="type" class="select2 form-select" required>
                                <option value="single_choice"
                                    {{ old('type', $question->type) == 'single_choice' ? 'selected' : '' }}>Single Choice
                                </option>
                                <option value="multi_choice"
                                    {{ old('type', $question->type) == 'multi_choice' ? 'selected' : '' }}>Multiple Choice
                                </option>
                                <option value="fill_blank"
                                    {{ old('type', $question->type) == 'fill_blank' ? 'selected' : '' }}>
                                    Fill Blank</option>
                                <option value="matching"
                                    {{ old('type', $question->type) == 'matching' ? 'selected' : '' }}>
                                    Matching</option>
                                <option value="hotspot" {{ old('type', $question->type) == 'hotspot' ? 'selected' : '' }}>
                                    Hotspot
                                </option>
                            </select>
                        </div>

                        {{-- QUESTION --}}
                        <div class="mb-4 col-md-12">
                            <label>{{ __('Question Text') }}</label>
                            <textarea class="form-control" name="question_text" rows="5">{{ old('question_text', $question->question_text) }}</textarea>
                        </div>

                        {{-- EXPLANATION --}}
                        <div class="mb-4 col-md-12">
                            <label>{{ __('Answer Explanation') }}</label>
                            <textarea class="form-control" name="ans_explanation" rows="5">{{ old('ans_explanation', $question->ans_explanation) }}</textarea>
                        </div>
                    </div>

                    {{-- OPTIONS --}}
                    <div id="choice_fields" class="row p-5 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h5>Options</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="add_option">Add More</button>
                        </div>

                        <div id="options_wrapper">
                            @foreach ($question->options as $key => $option)
                                <div class="row option-item mb-3">
                                    <div class="col-md-8">
                                        <input type="text" name="options[]" class="form-control"
                                            value="{{ $option->option_text }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="checkbox" name="is_correct[]" value="{{ $key }}"
                                            {{ $option->is_correct ? 'checked' : '' }}> Correct
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-option">X</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- FILL BLANK --}}
                    <div id="fill_blank_fields" class="row p-5 d-none">
                        <h5>Fill in the Blank</h5>

                        @if ($question->fillBlank && $question->fillBlank->image)
                            <img src="{{ asset($question->fillBlank->image) }}" width="120" class="mb-2">
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="file" name="blank_image" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="correct_answer" class="form-control"
                                    value="{{ $question->fillBlank->correct_answer ?? '' }}">
                            </div>
                        </div>
                    </div>

                    {{-- MATCHING --}}
                    <div id="matching_fields" class="row p-5 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h5>Matching</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="add_match">Add More</button>
                        </div>

                        <div id="match_wrapper">
                            @foreach ($question->matchPairs as $pair)
                                <div class="row mb-3 match-item">
                                    <div class="col-md-5">
                                        <input type="text" name="left_item[]" class="form-control"
                                            value="{{ $pair->left_item }}">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="right_item[]" class="form-control"
                                            value="{{ $pair->right_item }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-match">X</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- HOTSPOT --}}
                    <div id="hotspot_fields" class="row p-5 d-none">
                        <h5>Hotspot</h5>

                        <input type="file" id="hotspot_image" name="hotspot_image" class="form-control mb-3">

                        <div id="imageWrapper" style="position:relative; display:inline-block;">
                            <img id="preview_image"
                                src="{{ $question->hotspot->image ? asset($question->hotspot->image) : '' }}"
                                style="max-width:100%; {{ $question->hotspot ? 'display:block;' : 'display:none;' }}">

                            <div id="selection_box"
                                style="position:absolute; border:2px dashed red; display:none; pointer-events:none;">
                            </div>
                        </div>

                        <input type="hidden" name="x" id="input_x" value="{{ $question->hotspot->x ?? '' }}">
                        <input type="hidden" name="y" id="input_y" value="{{ $question->hotspot->y ?? '' }}">
                        <input type="hidden" name="width" id="input_width"
                            value="{{ $question->hotspot->width ?? '' }}">
                        <input type="hidden" name="height" id="input_height"
                            value="{{ $question->hotspot->height ?? '' }}">
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">Update Question</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            function toggleFields(type) {
                $('#choice_fields,#fill_blank_fields,#matching_fields,#hotspot_fields').addClass('d-none');

                if (type === 'single_choice' || type === 'multi_choice') $('#choice_fields').removeClass('d-none');
                if (type === 'fill_blank') $('#fill_blank_fields').removeClass('d-none');
                if (type === 'matching') $('#matching_fields').removeClass('d-none');
                if (type === 'hotspot') $('#hotspot_fields').removeClass('d-none');
            }

            toggleFields($('#type').val());

            $('#type').on('change', function() {
                toggleFields($(this).val());
            });

            // OPTIONS
            let optionIndex = 100;

            $('#add_option').click(function() {
                $('#options_wrapper').append(`
            <div class="row option-item mb-3">
                <div class="col-md-8">
                    <input type="text" name="options[]" class="form-control">
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

            // MATCHING
            $('#add_match').click(function() {
                $('#match_wrapper').append(`
                    <div class="row mb-3 match-item">
                        <div class="col-md-5"><input type="text" name="left_item[]" class="form-control"></div>
                        <div class="col-md-5"><input type="text" name="right_item[]" class="form-control"></div>
                        <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-match">X</button></div>
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

            $('#imageWrapper').on('mousedown', function(e) {
                e.preventDefault();
            });
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
