@extends('layouts.master')

@section('title', __('Exam Questions'))

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.exams.index') }}">Exams</a></li>
    <li class="breadcrumb-item active">Questions</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-6">
        <div class="card-body pt-4">

            <form method="POST" action="{{ route('dashboard.exams.questions.update', $exam->id) }}">
                @csrf
                @method('PUT')

                <div class="row p-5">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Add Questions to Exam</h3>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#randomQuestionModal">
                            Assign Random Questions
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Questions</h5>
                        <button type="button" class="btn btn-primary btn-sm" id="add_row">Add New</button>
                    </div>

                    <div id="question_wrapper">

                        {{-- EXISTING QUESTIONS --}}
                        @foreach($examQuestions as $index => $eq)
                        <div class="row mb-3 question-item">

                            <div class="col-md-8">
                                <select name="question_id[]" class="form-select select2" required>
                                    <option value="">Select Question</option>
                                    @foreach($questions as $q)
                                        <option value="{{ $q->id }}"
                                            {{ $q->id == $eq->question_id ? 'selected' : '' }}>
                                            {{ Str::limit($q->question_text, 80) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <input type="number" name="question_order[]" class="form-control"
                                    value="{{ $eq->question_order }}" placeholder="Order" required>
                            </div>

                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                            </div>

                        </div>
                        @endforeach

                    </div>
                </div>

                <div class="mt-2 p-5">
                    <button type="submit" class="btn btn-primary">Save Questions</button>
                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="randomQuestionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('dashboard.exams.questions.random', $exam->id) }}">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Assign Random Questions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="form-label">How many questions you want to assign to this exam?</label>
                    <input type="number"
                           name="limit"
                           class="form-control"
                           min="1"
                           required
                           placeholder="e.g. 10">

                    <small class="text-muted">System will randomly assign questions to this exam.</small>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Assign</button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function(){

    // ADD NEW ROW
    $('#add_row').click(function(){

        let row = `
        <div class="row mb-3 question-item">

            <div class="col-md-8">
                <select name="question_id[]" class="form-select select2" required>
                    <option value="">Select Question</option>
                    @foreach($questions as $q)
                        <option value="{{ $q->id }}">
                            {{ Str::limit($q->question_text, 80) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <input type="number" name="question_order[]" class="form-control" placeholder="Order" required>
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
            </div>

        </div>`;

        $('#question_wrapper').append(row);
    });

    // REMOVE ROW
    $(document).on('click', '.remove-row', function(){
        $(this).closest('.question-item').remove();
    });

});
</script>
@endsection
