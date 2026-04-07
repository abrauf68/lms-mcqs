@extends('frontend.layouts.exam.master')

@section('title', 'Try Demo')

@section('css')
    <style>
        .app-wrapper {
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Top bar with timer */
        .top-bar {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            padding: 0.75rem 1.8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            z-index: 100;
        }

        .exam-title {
            font-weight: 700;
            font-size: 1.2rem;
            background: linear-gradient(135deg, #0d6efd, #2c7da0);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .timer-box {
            background: #1e293b;
            padding: 0.4rem 1rem;
            border-radius: 3rem;
            color: white;
            font-family: monospace;
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 1px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .timer-box i {
            margin-right: 6px;
            font-size: 0.9rem;
        }

        .main-scroll-area {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem 1.5rem 1rem 1.5rem;
            scroll-behavior: smooth;
        }

        .card-premium {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 2rem;
            border: none;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .question-card {
            /* border-left: 6px solid #0d6efd; */
            border-radius: 1.75rem;
        }

        .badge-smart {
            font-size: 0.7rem;
            padding: 0.35rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
        }

        .option-item {
            background: #ffffff;
            border: 1.5px solid #e9edf4;
            border-radius: 1.2rem;
            padding: 0.85rem 1.2rem;
            margin-bottom: 0.85rem;
            transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            cursor: pointer;
        }

        .option-item:hover {
            background: #f6fafe;
            border-color: #0d6efd;
            transform: translateX(6px);
            box-shadow: 0 6px 12px -8px rgba(0, 0, 0, 0.1);
        }

        .form-check-input:checked+.form-check-label {
            font-weight: 700;
            color: #0d6efd;
        }

        .user-highlight {
            background-color: #f9f1c0;
            border-radius: 6px;
            padding: 0 2px;
        }

        .user-strikethrough {
            text-decoration: line-through;
            text-decoration-thickness: 2px;
            text-decoration-color: #dc2626;
            background-color: #fff0f0;
            border-radius: 4px;
            padding: 0 2px;
        }

        .question-text-area {
            font-size: 1rem;
            /* font-weight: 500; */
            line-height: 1.5;
            color: #0f2b3d;
            background: #fefefe;
            padding: 0.75rem 0.5rem;
            border-radius: 1rem;
        }

        .bottom-action-bar {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            padding: 0.8rem 1.8rem;
            box-shadow: 0 -6px 20px rgba(0, 0, 0, 0.03);
        }

        .btn-soft-primary {
            background: #eef2ff;
            color: #1e4a76;
            border: none;
            border-radius: 3rem;
            padding: 0.5rem 1.4rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-soft-primary:hover {
            background: #e0e9fc;
            transform: translateY(-2px);
        }

        .annotation-btn {
            border-radius: 2rem;
            padding: 0.45rem 1.1rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            background: white;
            border: 1px solid #ced4da;
        }

        .annotation-active {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(30, 111, 92, 0.3);
        }

        /* Modal Compact Boxes */
        .question-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(40px, 50px));
            gap: 12px;
            justify-content: center;
            padding: 0.5rem 0;
        }

        .q-box {
            background: #f8fafc;
            border-radius: 1rem;
            text-align: center;
            padding: 8px 0 6px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: 0.15s linear;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            position: relative;
        }

        .q-box:hover {
            transform: translateY(-3px);
            background: #eef2ff;
            border-color: #0d6efd;
        }

        .q-num {
            font-size: 1rem;
            font-weight: 700;
            display: block;
            margin-top: 2px;
        }

        .q-status-sup {
            position: absolute;
            top: 2px;
            right: 6px;
            display: flex;
            gap: 3px;
            font-size: 0.65rem;
        }

        .q-status-sup i {
            font-size: 0.7rem;
            text-shadow: 0 0 1px white;
        }

        .modal-tab-btn {
            border-radius: 2rem;
            padding: 0.5rem 1.2rem;
            font-size: 13px;
            margin: 0 0.2rem;
            font-weight: 600;
            background: #f1f5f9;
            border: none;
            transition: 0.2s;
        }

        .modal-tab-btn.active {
            background: #0d6efd;
            color: white;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #e9ecef;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #b9c8e0;
            border-radius: 10px;
        }

        .active-q {
            border: 2px solid #0d6efd;
            background-color: #e7f1ff;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .top-bar {
                padding: 0.5rem 1rem;
            }

            .exam-title {
                font-size: 1rem;
            }

            .timer-box {
                font-size: 0.9rem;
                padding: 0.3rem 0.8rem;
            }

            .main-scroll-area {
                padding: 1rem;
            }

            .question-text-area {
                font-size: 1rem;
            }

            .bottom-action-bar {
                padding: 0.6rem 1rem;
                flex-wrap: wrap;
                gap: 10px;
            }

            .annotation-btn {
                padding: 0.3rem 0.8rem;
                font-size: 0.75rem;
            }

            .btn-soft-primary {
                padding: 0.4rem 1rem;
                font-size: 0.85rem;
            }

            .option-item {
                padding: 0.65rem 1rem;
            }

            .question-grid {
                grid-template-columns: repeat(auto-fill, minmax(55px, 65px));
                gap: 8px;
            }

            .q-box {
                padding: 6px 0 4px;
            }

            .q-num {
                font-size: 0.85rem;
            }
        }

        .drag-item {
            cursor: grab;
            color: #5a5a5a;
        }

        @media (max-width: 576px) {
            .radio-label {
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('content')

    <div class="app-wrapper">
        <!-- TOP BAR with TIMER -->
        <div class="top-bar">
            <div class="exam-title">
                <a class="navbar-brand me-0" href="{{ route('frontend.home') }}">
                    <img class="light-mode-item navbar-brand-item" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}"
                        alt="logo">
                    <img class="dark-mode-item navbar-brand-item" src="{{ asset(\App\Helpers\Helper::getLogoDark()) }}"
                        alt="logo">
                </a>
            </div>
            <div class="timer-box">
                <i class="fas fa-hourglass-half"></i> <span id="examTimer">00:00</span>
            </div>
        </div>

        <div class="main-scroll-area">
            <div class="container-fluid px-0">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 col-xl-9">
                        <div class="card-premium question-card p-3 p-md-4 shadow-lg">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                        <i class="fas fa-clipboard-list me-1"></i> Question
                                        <span>{{ $currentQuestion->question_order ?? 1 }}</span> /
                                        <span>{{ $userExamQuestions->count() }}</span>
                                    </span>
                                </div>
                                <div class="mt-2 mt-sm-0">
                                    @if ($currentQuestion->is_answered == '1')
                                        <span id="currentStatusBadge"
                                            class="badge-smart bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-check-circle me-1"></i> Answered
                                        </span>
                                    @else
                                        <span id="currentStatusBadge"
                                            class="badge-smart bg-secondary bg-opacity-10 text-secondary">
                                            <i class="far fa-circle me-1"></i> Unanswered
                                        </span>
                                    @endif
                                    @if ($currentQuestion->is_marked == '1')
                                        <span id="markBadge"
                                            class="badge-smart bg-warning bg-opacity-15 text-warning ms-2"><i
                                                class="fas fa-flag me-1"></i> Marked for review</span>
                                    @else
                                        <span id="markBadge"
                                            class="badge-smart bg-secondary bg-opacity-15 text-secondary ms-2">
                                            <i class="fas fa-flag me-1"></i> Not marked
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div id="questionTextContainer" class="question-text-area mb-4"
                                data-original="{{ e($currentQuestion->question->question_text) }}">
                            </div>

                            @if ($currentQuestion->question->type == 'single_choice')
                                <form id="singleChoiceForm" method="POST"
                                    action="{{ route('frontend.single-choice.submit') }}">
                                    @csrf

                                    <input type="hidden" name="user_exam_answer_id" value="{{ $currentQuestion->id }}">
                                    <div id="optionsContainer" class="mb-4">
                                        @foreach ($currentQuestion->question->options as $option)
                                            <div class="option-item">
                                                <div class="form-check">
                                                    <input class="form-check-input option-radio" type="radio"
                                                        name="option_id" id="opt_{{ $option->id }}"
                                                        value="{{ $option->id }}"
                                                        {{ $currentQuestion->selected_option_id == $option->id ? 'checked' : '' }}>

                                                    <label class="form-check-label radio-label w-100"
                                                        for="opt_{{ $option->id }}">
                                                        <strong>{{ chr(65 + $loop->index) }}.</strong>
                                                        {{ $option->option_text }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </form>
                            @endif
                            @if ($currentQuestion->question->type == 'multi_choice')
                                <form id="multiChoiceForm" method="POST"
                                    action="{{ route('frontend.multi-choice.submit') }}">
                                    @csrf

                                    <input type="hidden" name="user_exam_answer_id" value="{{ $currentQuestion->id }}">
                                    <div id="optionsContainer" class="mb-4">
                                        @php
                                            $selectedOptions = is_array($currentQuestion->selected_options)
                                                ? $currentQuestion->selected_options
                                                : json_decode($currentQuestion->selected_options, true) ?? [];
                                        @endphp
                                        @foreach ($currentQuestion->question->options as $option)
                                            <div class="option-item">
                                                <div class="form-check">
                                                    <input class="form-check-input option-radio" type="checkbox"
                                                        name="option_id[]" id="opt_{{ $option->id }}"
                                                        value="{{ $option->id }}"
                                                        {{ in_array($option->id, $selectedOptions) ? 'checked' : '' }}>

                                                    <label class="form-check-label radio-label w-100"
                                                        for="opt_{{ $option->id }}">
                                                        <strong>{{ chr(65 + $loop->index) }}.</strong>
                                                        {{ $option->option_text }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </form>
                            @endif
                            @if ($currentQuestion->question->type == 'matching')
                                <form id="matchingForm" method="POST" action="{{ route('frontend.matching.submit') }}">
                                    @csrf

                                    <input type="hidden" name="user_exam_answer_id" value="{{ $currentQuestion->id }}">
                                    <input type="hidden" name="matches" id="matchesInput" value="{}">

                                    @php
                                        $pairs = $currentQuestion->question->matchPairs;

                                        $savedPairs = json_decode($currentQuestion->matched_pairs ?? '{}', true);
                                        if (!is_array($savedPairs)) {
                                            $savedPairs = [];
                                        }

                                        $allLeftItems = $pairs->pluck('left_item')->toArray();
                                        $usedLeftItems = array_keys($savedPairs);

                                        $leftItems = array_diff($allLeftItems, $usedLeftItems);

                                        // 🔥 SHUFFLE HERE
                                        $leftItems = array_values($leftItems); // reset keys
                                        shuffle($leftItems);
                                    @endphp

                                    <div class="row">

                                        {{-- LEFT --}}
                                        <div class="col-md-4" id="leftContainer">
                                            <h5 class="text-center">Valued More</h5>

                                            @foreach ($leftItems as $item)
                                                <div class="drag-item p-2 mb-2 border bg-light text-center" draggable="true"
                                                    data-value="{{ $item }}">
                                                    {{ $item }}
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- DROP ZONES --}}
                                        <div class="col-md-4">

                                            <h5 class="text-center">&nbsp;</h5>

                                            @foreach ($pairs as $pair)
                                                @php
                                                    $matchedLeft = array_search($pair->right_item, $savedPairs);
                                                    $matchedLeft = $matchedLeft !== false ? $matchedLeft : null;
                                                @endphp

                                                <div class="drop-zone mb-2 border text-center {{ $matchedLeft ? 'text-white' : '' }}"
                                                    data-right="{{ $pair->right_item }}">

                                                    @if ($matchedLeft)
                                                        <div class="drag-item p-2 border bg-light text-center"
                                                            draggable="true" data-value="{{ $matchedLeft }}">
                                                            {{ $matchedLeft }}
                                                        </div>
                                                    @else
                                                        <span class="placeholder p-2 text-center"
                                                            style="background: none;">Drop here</span>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- RIGHT --}}
                                        <div class="col-md-4">
                                            <h5 class="text-center">Valued Less</h5>

                                            @foreach ($pairs as $pair)
                                                <div class="p-2 mb-2 border bg-light text-center">
                                                    {{ $pair->right_item }}
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </form>
                            @endif
                            @if ($currentQuestion->question->type == 'fill_blank')
                                <form id="fillBlankForm" method="POST"
                                    action="{{ route('frontend.fill-blank.submit') }}">
                                    @csrf

                                    <input type="hidden" name="user_exam_answer_id" value="{{ $currentQuestion->id }}">

                                    <div class="mb-4">
                                        <input type="text" name="answer_text" id="fillBlankInput"
                                            class="form-control form-control-lg" placeholder="Type your answer here..."
                                            value="{{ $currentQuestion->answer_text ?? '' }}">
                                    </div>

                                    @if ($currentQuestion->question->fillBlank && $currentQuestion->question->fillBlank->image)
                                        <img src="{{ asset($currentQuestion->question->fillBlank->image) }}"
                                            class="img-fluid mb-3">
                                    @endif
                                </form>
                            @endif
                            @if ($currentQuestion->question->type == 'hotspot')
                                <form id="hotspotForm" method="POST" action="{{ route('frontend.hotspot.submit') }}">
                                    @csrf

                                    <input type="hidden" name="user_exam_answer_id" value="{{ $currentQuestion->id }}">
                                    <input type="hidden" name="hotspot" id="hotspotInput">

                                    @php
                                        $savedHotspot = json_decode($currentQuestion->hotspot ?? '{}', true);
                                        $x = $savedHotspot['x'] ?? null;
                                        $y = $savedHotspot['y'] ?? null;
                                    @endphp

                                    <div class="position-relative d-inline-block">

                                        @if ($currentQuestion->question->hotspot && $currentQuestion->question->hotspot->image)
                                            <img id="hotspotImage"
                                                src="{{ asset($currentQuestion->question->hotspot->image) }}"
                                                class="img-fluid mb-3" style="cursor: crosshair;">
                                        @endif

                                        {{-- 🔴 Marker --}}
                                        <div id="hotspotMarker"
                                            style="
                                                position:absolute;
                                                width:15px;
                                                height:15px;
                                                background:red;
                                                border-radius:50%;
                                                transform:translate(-50%, -50%);
                                                display: {{ $x !== null ? 'block' : 'none' }};
                                                left: {{ $x ?? 0 }}%;
                                                top: {{ $y ?? 0 }}%;
                                            ">
                                        </div>

                                    </div>
                                </form>
                            @endif
                            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mt-2">
                                <div></div>
                                @php
                                    $questionsArray = $userExamQuestions->values(); // reset index
                                    $currentIndex = $questionsArray->search(function ($item) use ($currentQuestion) {
                                        return $item->question_id == $currentQuestion->question_id;
                                    });

                                    $prevQuestion = $questionsArray[$currentIndex - 1] ?? null;
                                    $nextQuestion = $questionsArray[$currentIndex + 1] ?? null;
                                @endphp
                                <div class="d-flex gap-3">

                                    {{-- Previous --}}
                                    @if ($prevQuestion)
                                        <a href="{{ route('frontend.exam', [$exam->slug, $userExam->id, $prevQuestion->question_id]) }}"
                                            class="btn btn-outline-primary rounded-pill px-4">
                                            <i class="fas fa-chevron-left me-1"></i> Previous
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary rounded-pill px-4" disabled>
                                            <i class="fas fa-chevron-left me-1"></i> Previous
                                        </button>
                                    @endif


                                    {{-- Next OR Submit --}}
                                    @if ($nextQuestion)
                                        <a href="{{ route('frontend.exam', [$exam->slug, $userExam->id, $nextQuestion->question_id]) }}"
                                            class="btn btn-primary rounded-pill px-4">
                                            Next <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    @else
                                        <form action="{{ route('frontend.score.submit') }}" method="POST"
                                            id="submitExamForm">
                                            @csrf
                                            <input type="hidden" name="user_exam_id"
                                                value="{{ $currentQuestion->user_exam_id }}">
                                            <input type="hidden" name="time_taken" id="timeTakenInput">

                                            <!-- 👇 type button karo (submit nahi) -->
                                            <button type="button" class="btn btn-success rounded-pill px-4"
                                                data-bs-toggle="modal" data-bs-target="#confirmSubmitModal">
                                                Score Exam <i class="fas fa-check ms-1"></i>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="text-muted small text-center"><i class="fas fa-magic"></i> Select text → use
                                Highlight /
                                Strikethrough tools. Mark for review flags questions.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-action-bar d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <button class="btn btn-soft-primary rounded-pill px-4 py-2 fw-semibold" data-bs-toggle="modal"
                    data-bs-target="#reviewModal">
                    <i class="fas fa-chart-simple me-2"></i> Review Progress
                </button>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if ($currentQuestion->is_marked == '1')
                    <a href="{{ route('frontend.mark.for.review', $currentQuestion->id) }}"
                        class="btn bg-warning annotation-btn text-dark">
                        <i class="fas fa-flag-checkered me-1"></i>
                        <span class="d-none d-sm-inline ms-1">Unmark</span>
                    </a>
                @else
                    <a href="{{ route('frontend.mark.for.review', $currentQuestion->id) }}"
                        class="btn btn-outline-warning annotation-btn">
                        <i class="fas fa-flag me-1"></i>
                        <span class="d-none d-sm-inline ms-1">Mark for Review</span>
                    </a>
                @endif
                <button id="highlightModeBtn" class="btn btn-outline-info annotation-btn">
                    <i class="fas fa-highlighter me-1"></i>
                    <span class="d-none d-sm-inline ms-1">Highlight</span>
                </button>
                <button id="strikethroughModeBtn" class="btn btn-outline-danger annotation-btn">
                    <i class="fas fa-strikethrough me-1"></i>
                    <span class="d-none d-sm-inline ms-1">Strikethrough</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: compact boxes with sup icons -->
    @php
        $answered = $userExamQuestions->where('is_answered', 1);
        $unanswered = $userExamQuestions->where('is_answered', 0);
        $marked = $userExamQuestions->where('is_marked', 1);
    @endphp
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-xl">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-chart-pie me-2"></i>Question Navigator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <ul class="nav nav-tabs justify-content-start gap-2 border-0 mt-2 flex-wrap" id="reviewTab"
                        role="tablist">
                        <li class="nav-item"><button class="btn modal-tab-btn active" id="unanswered-tab"
                                data-bs-toggle="tab" data-bs-target="#unansweredTab" type="button" role="tab"><i
                                    class="far fa-circle me-1"></i>
                                Unanswered</button></li>
                        <li class="nav-item"><button class="btn modal-tab-btn" id="marked-tab" data-bs-toggle="tab"
                                data-bs-target="#markedTab" type="button" role="tab"><i
                                    class="fas fa-flag me-1"></i>
                                Marked</button>
                        </li>
                        <li class="nav-item"><button class="btn modal-tab-btn" id="answered-tab" data-bs-toggle="tab"
                                data-bs-target="#answeredTab" type="button" role="tab"><i
                                    class="fas fa-check-circle me-1"></i>
                                Answered</button></li>
                        <li class="nav-item"><button class="btn modal-tab-btn" id="all-tab" data-bs-toggle="tab"
                                data-bs-target="#allTab" type="button" role="tab"><i
                                    class="fas fa-layer-group me-1"></i> All
                                Questions</button></li>
                    </ul>
                    <div class="tab-content mt-4" id="reviewTabContent">
                        <div class="tab-pane fade show active" id="unansweredTab" role="tabpanel">
                            <div id="unansweredGrid" class="question-grid custom-scroll"
                                style="max-height: 450px; overflow-y: auto;">
                                @foreach ($unanswered as $q)
                                    <div class="q-box {{ $currentQuestion->id == $q->id ? 'active-q' : '' }}"
                                        onclick="window.location.href='{{ route('frontend.exam', [$exam->slug, $userExam->id, $q->question_id]) }}'">
                                        <div class="q-status-sup">
                                            @if ($q->is_marked)
                                                <i class="fas fa-flag text-warning" title="Marked"></i>
                                            @endif
                                        </div>
                                        <div class="q-num">{{ $q->question_order }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="markedTab" role="tabpanel">
                            <div id="markedGrid" class="question-grid custom-scroll"
                                style="max-height: 450px; overflow-y: auto;">
                                @foreach ($marked as $q)
                                    <div class="q-box {{ $currentQuestion->id == $q->id ? 'active-q' : '' }}"
                                        onclick="window.location.href='{{ route('frontend.exam', [$exam->slug, $userExam->id, $q->question_id]) }}'">
                                        <div class="q-status-sup">
                                            @if ($q->is_answered)
                                                <i class="fas fa-check-circle text-success" title="Answered"></i>
                                            @endif
                                        </div>
                                        <div class="q-num">{{ $q->question_order }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="answeredTab" role="tabpanel">
                            <div id="answeredGrid" class="question-grid custom-scroll"
                                style="max-height: 450px; overflow-y: auto;">
                                @foreach ($answered as $q)
                                    <div class="q-box {{ $currentQuestion->id == $q->id ? 'active-q' : '' }}"
                                        onclick="window.location.href='{{ route('frontend.exam', [$exam->slug, $userExam->id, $q->question_id]) }}'">
                                        <div class="q-status-sup">
                                            @if ($q->is_marked)
                                                <i class="fas fa-flag text-warning" title="Marked"></i>
                                            @endif
                                        </div>
                                        <div class="q-num">{{ $q->question_order }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="allTab" role="tabpanel">
                            <div id="allGrid" class="question-grid custom-scroll"
                                style="max-height: 450px; overflow-y: auto;">
                                @foreach ($userExamQuestions as $q)
                                    <div class="q-box {{ $currentQuestion->id == $q->id ? 'active-q' : '' }}"
                                        onclick="window.location.href='{{ route('frontend.exam', [$exam->slug, $userExam->id, $q->question_id]) }}'">
                                        <div class="q-status-sup">
                                            @if ($q->is_answered)
                                                <i class="fas fa-check-circle text-success" title="Answered"></i>
                                            @endif
                                            @if ($q->is_marked)
                                                <i class="fas fa-flag text-warning" title="Marked"></i>
                                            @endif
                                        </div>
                                        <div class="q-num">{{ $q->question_order }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmSubmitModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                {{-- <div class="modal-header">
                    <h5 class="modal-title">Confirm Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div> --}}

                <div class="modal-body">
                    <h4>Score Exam ?</h4>
                    By clicking on the [Score Exam] button below, you will complete your current exam and receive your
                    score. You will not be able to change any answers after this point.
                </div>

                <div class="modal-footer" style="border: none; margin: 0px; padding: 5px 10px;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <!-- 👇 Final Confirm -->
                    <button type="button" id="confirmSubmitBtn" class="btn btn-sm btn-success">
                        Yes, Submit
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        document.querySelectorAll('.option-radio').forEach(function(el) {
            el.addEventListener('change', function() {
                document.getElementById('singleChoiceForm').submit();
            });
        });

        document.querySelectorAll('#multiChoiceForm .option-radio').forEach(function(el) {
            el.addEventListener('change', function() {
                document.getElementById('multiChoiceForm').submit();
            });
        });
    </script>
    <script>
        let matches = @json($savedPairs ?? []);
        if (Array.isArray(matches)) matches = {};

        document.addEventListener('dragstart', function(e) {
            if (e.target.classList.contains('drag-item')) {
                e.dataTransfer.setData("text/plain", e.target.dataset.value);
            }
        });

        document.querySelectorAll('.drop-zone').forEach(zone => {

            zone.addEventListener('dragover', e => e.preventDefault());

            zone.addEventListener('drop', function(e) {
                e.preventDefault();

                let newLeft = e.dataTransfer.getData("text/plain");
                if (!newLeft) return;

                let newRight = this.dataset.right;

                // 🔥 STEP 1: remove old mapping of this LEFT
                let oldRight = matches[newLeft];

                if (oldRight) {
                    delete matches[newLeft];
                }

                // 🔥 STEP 2: remove ANY left already using this RIGHT
                Object.keys(matches).forEach(left => {
                    if (matches[left] === newRight) {
                        delete matches[left];

                        // move that item back to left container
                        let el = document.querySelector(`.drag-item[data-value="${left}"]`);
                        if (el) {
                            document.getElementById('leftContainer').appendChild(el);
                        }
                    }
                });

                // 🔥 STEP 3: if drop zone already has item, send back
                let existing = this.querySelector('.drag-item');
                if (existing) {
                    document.getElementById('leftContainer').appendChild(existing);
                }

                // 🔥 STEP 4: move new item
                let dragged = document.querySelector(`.drag-item[data-value="${newLeft}"]`);

                if (dragged) {
                    let placeholder = this.querySelector('.placeholder');
                    if (placeholder) placeholder.remove();

                    this.appendChild(dragged);
                }

                this.classList.add('text-white');

                // 🔥 FINAL STATE UPDATE
                matches[newLeft] = newRight;

                document.getElementById('matchesInput').value = JSON.stringify(matches);

                console.log("FINAL MATCHES:", matches);

                setTimeout(() => {
                    document.getElementById('matchingForm').submit();
                }, 80);
            });
        });
    </script>
    <script>
        let typingTimer;
        let delay = 500; // 0.5 sec

        let input = document.getElementById('fillBlankInput');

        if (input) {
            input.addEventListener('keyup', function() {

                clearTimeout(typingTimer);

                typingTimer = setTimeout(() => {
                    if (input.value.trim() !== '') {
                        document.getElementById('fillBlankForm').submit();
                    }
                }, delay);

            });

            input.addEventListener('keydown', function() {
                clearTimeout(typingTimer);
            });
        }
    </script>
    <script>
        let img = document.getElementById('hotspotImage');
        let marker = document.getElementById('hotspotMarker');
        let hotspotInput = document.getElementById('hotspotInput');

        if (img) {
            img.addEventListener('click', function(e) {

                let rect = img.getBoundingClientRect();

                // 🔥 get relative %
                let x = ((e.clientX - rect.left) / rect.width) * 100;
                let y = ((e.clientY - rect.top) / rect.height) * 100;

                // 🔥 move marker
                marker.style.left = x + "%";
                marker.style.top = y + "%";
                marker.style.display = "block";

                // 🔥 save to input
                let data = {
                    x: x.toFixed(2),
                    y: y.toFixed(2)
                };

                hotspotInput.value = JSON.stringify(data);

                console.log("HOTSPOT:", data);

                // 🔥 auto submit
                setTimeout(() => {
                    document.getElementById('hotspotForm').submit();
                }, 100);
            });
        }
    </script>
    <script>
        let mode = null;
        let annotations = @json(json_decode($currentQuestion->annotations ?? '[]', true) ?? []);

        const container = document.getElementById('questionTextContainer');

        const highlightBtn = document.getElementById('highlightModeBtn');
        const strikeBtn = document.getElementById('strikethroughModeBtn');

        // 🔥 TOGGLE FUNCTION
        function setMode(newMode) {

            if (mode === newMode) {
                // same button clicked again → OFF
                mode = null;
                highlightBtn.classList.remove('active');
                strikeBtn.classList.remove('active');
                return;
            }

            mode = newMode;

            // reset both
            highlightBtn.classList.remove('active');
            strikeBtn.classList.remove('active');

            // activate selected
            if (mode === 'highlight') {
                highlightBtn.classList.add('active');
            } else if (mode === 'strike') {
                strikeBtn.classList.add('active');
            }
        }

        // 🔥 BUTTON EVENTS
        highlightBtn.addEventListener('click', () => setMode('highlight'));
        strikeBtn.addEventListener('click', () => setMode('strike'));

        // 🔥 TEXT SELECTION
        container.addEventListener('mouseup', function() {

            if (!mode) return;

            let selection = window.getSelection();
            let text = selection.toString();

            if (!text.length) return;

            let range = selection.getRangeAt(0);

            let start = getOffset(container, range.startContainer, range.startOffset);
            let end = getOffset(container, range.endContainer, range.endOffset);

            annotations.push({
                type: mode,
                start: start,
                end: end
            });

            applyAnnotations();
            saveAnnotations();

            selection.removeAllRanges();

            // 🔥 AUTO OFF AFTER ONE USE
            mode = null;
            highlightBtn.classList.remove('active');
            strikeBtn.classList.remove('active');
        });

        // 🔥 OFFSET
        function getOffset(container, node, offset) {
            let range = document.createRange();
            range.setStart(container, 0);
            range.setEnd(node, offset);
            return range.toString().length;
        }

        // 🔥 APPLY
        function applyAnnotations() {

            let text = container.dataset.original;
            let chars = text.split('').map(c => ({
                char: c,
                highlight: false,
                strike: false
            }));

            // 🔥 APPLY ALL ANNOTATIONS
            annotations.forEach(a => {
                for (let i = a.start; i < a.end; i++) {
                    if (!chars[i]) continue;

                    if (a.type === 'highlight') {
                        chars[i].highlight = true;
                    }

                    if (a.type === 'strike') {
                        chars[i].strike = true;
                    }
                }
            });

            // 🔥 BUILD FINAL HTML
            let result = '';
            let currentStyle = '';

            chars.forEach(c => {

                let styleKey = (c.highlight ? 'h' : '') + (c.strike ? 's' : '');

                if (styleKey !== currentStyle) {

                    // close previous
                    if (currentStyle) {
                        result += closeTag(currentStyle);
                    }

                    // open new
                    if (styleKey) {
                        result += openTag(styleKey);
                    }

                    currentStyle = styleKey;
                }

                result += escapeHtml(c.char);
            });

            // close last
            if (currentStyle) {
                result += closeTag(currentStyle);
            }

            container.innerHTML = result;
        }

        // 🔥 TAG HELPERS
        function openTag(style) {
            if (style === 'h') return '<mark>';
            if (style === 's') return '<span style="text-decoration: line-through;">';
            if (style === 'hs') return '<mark><span style="text-decoration: line-through;">';
            if (style === 'sh') return '<span style="text-decoration: line-through;"><mark>';
            return '';
        }

        function closeTag(style) {
            if (style === 'h') return '</mark>';
            if (style === 's') return '</span>';
            if (style === 'hs') return '</span></mark>';
            if (style === 'sh') return '</mark></span>';
            return '';
        }

        // 🔥 SAVE
        function saveAnnotations() {
            fetch("{{ route('frontend.annotation.submit') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    user_exam_answer_id: "{{ $currentQuestion->id }}",
                    annotations: annotations
                })
            });
        }

        // 🔥 ESCAPE
        function escapeHtml(text) {
            return text.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }

        // 🔥 LOAD
        document.addEventListener("DOMContentLoaded", function() {
            applyAnnotations();
        });
    </script>
    @if(session('reset_timer'))
    <script>
        // 👇 NEW TEST START → reset everything
        localStorage.removeItem('exam_finished');
        localStorage.removeItem('exam_start_time');
    </script>
    @endif
    <script>
        let timerInterval = null;

        function isExamFinished() {
            return localStorage.getItem('exam_finished') === 'true';
        }

        function getStartTime() {
            if (isExamFinished()) return null; // 👈 STOP

            let storedTime = localStorage.getItem('exam_start_time');

            if (!storedTime) {
                storedTime = Date.now();
                localStorage.setItem('exam_start_time', storedTime);
            }

            return parseInt(storedTime);
        }

        function updateTimerDisplay() {
            const startTime = getStartTime();

            if (!startTime) {
                document.getElementById('examTimer').innerText = "00:00";
                return;
            }

            const elapsedSeconds = Math.floor((Date.now() - startTime) / 1000);

            const minutes = Math.floor(elapsedSeconds / 60);
            const seconds = elapsedSeconds % 60;

            const formatted = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            document.getElementById('examTimer').innerText = formatted;
        }

        function startTimer() {
            if (isExamFinished()) return; // 👈 STOP

            if (timerInterval) clearInterval(timerInterval);

            updateTimerDisplay();
            timerInterval = setInterval(updateTimerDisplay, 1000);
        }

        startTimer();

        document.getElementById('confirmSubmitBtn').addEventListener('click', function() {

            let startTime = localStorage.getItem('exam_start_time');

            if (startTime) {
                let elapsedSeconds = Math.floor((Date.now() - parseInt(startTime)) / 1000);
                document.getElementById('timeTakenInput').value = elapsedSeconds;
            }

            // ✅ mark exam finished
            localStorage.setItem('exam_finished', 'true');

            // ❌ clear timer
            localStorage.removeItem('exam_start_time');

            document.getElementById('submitExamForm').submit();
        });
    </script>
@endsection
