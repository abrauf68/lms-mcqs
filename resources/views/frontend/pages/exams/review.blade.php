@extends('frontend.layouts.exam.master')

@section('title', 'Review Exam')

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
            /* border: 1.5px solid #e9edf4; */
            border-radius: 1.2rem;
            padding: 0.85rem 1.2rem;
            margin-bottom: 0.85rem;
            transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            cursor: pointer;
        }

        /* .option-item:hover {
                                background: #f6fafe;
                                border-color: #0d6efd;
                                transform: translateX(6px);
                                box-shadow: 0 6px 12px -8px rgba(0, 0, 0, 0.1);
                            } */

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

        .option-item {
            position: relative;
        }

        .your-answer-badge {
            position: absolute;
            left: -120px;
            background: #ff6a00;
            color: #fff;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 3px;
            white-space: nowrap;
        }

        /* Arrow */
        .your-answer-badge::after {
            content: "";
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
            border-left: 10px solid #ff6a00;
        }

        .correct-answer-badge {
            position: absolute;
            left: -120px;
            background: #69c17d;
            color: #fff;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 3px;
            white-space: nowrap;
        }

        /* Arrow */
        .correct-answer-badge::after {
            content: "";
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
            border-left: 10px solid #69c17d;
        }

        .bg-light-success {
            background-color: #69c17d !important;
            color: #fff;
        }

        .bg-light-danger {
            background-color: #e74c3c !important;
            color: #fff;
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

                                <div id="optionsContainer" class="mb-4">

                                    @foreach ($currentQuestion->question->options as $option)
                                        @php
                                            $isSelected = $currentQuestion->selected_option_id == $option->id;
                                            $isCorrect = $option->is_correct == 1;
                                        @endphp

                                        <div class="option-item d-flex align-items-center mb-2 position-relative">

                                            <!-- YOUR ANSWER FLAG -->
                                            @if ($isSelected)
                                                <div class="your-answer-badge">
                                                    YOUR ANSWER
                                                </div>
                                            @endif

                                            <!-- Left Icon -->
                                            <div class="me-2">
                                                @if ($isSelected && !$isCorrect)
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle"></i>
                                                    </span>
                                                @elseif ($isCorrect)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="far fa-circle"></i>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Option Text -->
                                            <div
                                                class="w-100 p-2
                                                @if ($isCorrect) bg-light-success
                                                @elseif($isSelected && !$isCorrect) bg-light-danger @endif
                                            ">
                                                <strong>{{ chr(65 + $loop->index) }}.</strong>
                                                {{ $option->option_text }}
                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            @endif
                            @if ($currentQuestion->question->type == 'multi_choice')
                                @php
                                    $selectedOptions = is_array($currentQuestion->selected_options)
                                        ? $currentQuestion->selected_options
                                        : json_decode($currentQuestion->selected_options, true) ?? [];
                                @endphp
                                <div id="optionsContainer" class="mb-4">

                                    @foreach ($currentQuestion->question->options as $option)
                                        @php
                                            $isSelected = in_array($option->id, $selectedOptions);
                                            $isCorrect = $option->is_correct == 1;
                                        @endphp

                                        <div class="option-item d-flex align-items-center mb-2 position-relative">

                                            <!-- YOUR ANSWER BADGE -->
                                            @if ($isSelected)
                                                <div class="your-answer-badge">
                                                    YOUR ANSWER
                                                </div>
                                            @endif

                                            <!-- LEFT ICON -->
                                            <div class="me-2">
                                                @if ($isSelected && !$isCorrect)
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle"></i>
                                                    </span>
                                                @elseif ($isCorrect)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="far fa-circle"></i>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- OPTION TEXT -->
                                            <div
                                                class="w-100 p-2
                                                @if ($isCorrect) bg-light-success
                                                @elseif($isSelected && !$isCorrect)
                                                    bg-light-danger @endif
                                            ">
                                                <strong>{{ chr(65 + $loop->index) }}.</strong>
                                                {{ $option->option_text }}
                                            </div>

                                        </div>
                                    @endforeach

                                </div>
                            @endif
                            @if ($currentQuestion->question->type == 'matching')

                                @php
                                    $pairs = $currentQuestion->question->matchPairs;

                                    // user answers (left => right)
                                    $userMatches = json_decode($currentQuestion->matched_pairs ?? '{}', true) ?? [];

                                    // reverse mapping (right => left) for correct answers
                                    $correctRightToLeft = $pairs->pluck('left_item', 'right_item')->toArray();
                                @endphp

                                <div class="row">

                                    {{-- COLUMN 1: USER ANSWER --}}
                                    <div class="col-md-4">
                                        <h5 class="text-center">Your Answer</h5>

                                        @foreach ($pairs as $pair)
                                            @php
                                                $right = $pair->right_item;

                                                // user ne is option ke liye kya select kiya
                                                $userLeft = array_search($right, $userMatches);

                                                $correctLeft = $correctRightToLeft[$right] ?? null;

                                                $isCorrect = $userLeft === $correctLeft;
                                            @endphp

                                            <div class="d-flex align-items-center mb-3 position-relative">

                                                {{-- YOUR ANSWER BADGE --}}
                                                @if ($userLeft)
                                                    <div class="your-answer-badge">YOUR ANSWER</div>
                                                @endif

                                                {{-- ICON --}}
                                                <div class="me-2">
                                                    @if ($userLeft && !$isCorrect)
                                                        <span class="badge bg-danger"><i class="fas fa-times"></i></span>
                                                    @elseif ($isCorrect)
                                                        <span class="badge bg-success"><i class="fas fa-check"></i></span>
                                                    @else
                                                        <span class="badge bg-secondary"><i
                                                                class="far fa-circle"></i></span>
                                                    @endif
                                                </div>

                                                {{-- TEXT --}}
                                                <div
                                                    class="w-100 p-2
                                                    @if ($isCorrect) bg-light-success
                                                    @elseif($userLeft && !$isCorrect)
                                                        bg-light-danger @endif
                                                ">
                                                    {{ $userLeft ?? '—' }}
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>


                                    {{-- COLUMN 2: OPTIONS (RIGHT SIDE FIXED) --}}
                                    <div class="col-md-4">
                                        <h5 class="text-center">Options</h5>

                                        @foreach ($pairs as $pair)
                                            <div class="p-2 mb-3 border bg-light text-center">
                                                {{ $pair->right_item }}
                                            </div>
                                        @endforeach
                                    </div>


                                    {{-- COLUMN 3: CORRECT ANSWER --}}
                                    <div class="col-md-4">
                                        <h5 class="text-center">Correct Answer</h5>

                                        @foreach ($pairs as $pair)
                                            @php
                                                $right = $pair->right_item;
                                                $correctLeft = $correctRightToLeft[$right] ?? null;
                                            @endphp

                                            <div class="p-2 mb-3 bg-light-success text-center">
                                                {{ $correctLeft }}
                                            </div>
                                        @endforeach
                                    </div>

                                </div>

                            @endif
                            @if ($currentQuestion->question->type == 'fill_blank')
                                @php
                                    $userAnswer = trim(strtolower($currentQuestion->answer_text ?? ''));
                                    $correctAnswer = trim(
                                        strtolower($currentQuestion->question->fillBlank->answer ?? ''),
                                    );

                                    $isCorrect = $userAnswer === $correctAnswer;
                                @endphp

                                <div class="mb-4">

                                    <!-- USER ANSWER -->
                                    <div class="option-item d-flex align-items-center mb-3 position-relative">

                                        <!-- YOUR ANSWER BADGE -->
                                        @if ($userAnswer)
                                            <div class="your-answer-badge">
                                                YOUR ANSWER
                                            </div>
                                        @endif

                                        <!-- USER ANSWER TEXT -->
                                        <div
                                            class="px-3 pb-2 pt-2 @if ($isCorrect) bg-light-success
                                            @elseif($userAnswer && !$isCorrect) bg-light-danger @endif
                                        ">
                                            {{ $currentQuestion->answer_text ?? 'Not Attempted' }}
                                        </div>

                                    </div>

                                    <!-- CORRECT ANSWER -->
                                    @if (!$isCorrect)
                                        <div class="option-item d-flex align-items-center mb-2">
                                            <div class="correct-answer-badge">
                                                Correct Answer
                                            </div>

                                            <div class="px-3 pb-2 pt-2 bg-light-success">
                                                {{ $currentQuestion->question->fillBlank->correct_answer }}
                                            </div>

                                        </div>
                                    @endif

                                </div>

                                <!-- IMAGE (if exists) -->
                                @if ($currentQuestion->question->fillBlank && $currentQuestion->question->fillBlank->image)
                                    <img src="{{ asset($currentQuestion->question->fillBlank->image) }}"
                                        class="img-fluid mt-2">
                                @endif

                            @endif
                            @if ($currentQuestion->question->type == 'hotspot')
                                @php
                                    $userHotspot = json_decode($currentQuestion->hotspot ?? '{}', true);

                                    $userX = $userHotspot['x'] ?? null;
                                    $userY = $userHotspot['y'] ?? null;

                                    // correct box
                                    $correct = $currentQuestion->question->hotspot;

                                    $boxX = $correct->x ?? null; // left %
                                    $boxY = $correct->y ?? null; // top %
                                    $boxWidth = $correct->width ?? 10; // %
                                    $boxHeight = $correct->height ?? 10; // %

                                    // check if user click inside box
                                    $isCorrect = false;
                                    if ($userX !== null && $boxX !== null) {
                                        $isCorrect =
                                            $userX >= $boxX &&
                                            $userX <= ($boxX + $boxWidth) &&
                                            $userY >= $boxY &&
                                            $userY <= ($boxY + $boxHeight);
                                    }
                                @endphp

                                <div class="mb-4">

                                    <!-- STATUS -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-2">
                                            @if ($userX === null)
                                                <span class="badge bg-secondary p-2">
                                                    <i class="far fa-circle" style="font-size: 20px;"></i>
                                                </span>
                                            @elseif ($isCorrect)
                                                <span class="badge bg-success p-2">
                                                    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                                                </span>
                                            @else
                                                <span class="badge bg-danger p-2">
                                                    <i class="fas fa-times-circle" style="font-size: 20px;"></i>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="w-100 p-2
                                            @if ($isCorrect) bg-light-success
                                            @elseif($userX !== null && !$isCorrect) bg-light-danger @endif
                                        ">
                                            <strong>
                                                @if ($userX === null)
                                                    Not Attempted
                                                @elseif ($isCorrect)
                                                    Correct Answer
                                                @else
                                                    Wrong Answer
                                                @endif
                                            </strong>
                                        </div>
                                    </div>

                                    <!-- IMAGE WITH MARKERS -->
                                    <div class="position-relative d-inline-block">

                                        <img src="{{ asset($currentQuestion->question->hotspot->image) }}"
                                            class="img-fluid">

                                        <!-- 🟩 CORRECT BOX -->
                                        @if ($boxX !== null)
                                            <div style="
                                                position:absolute;
                                                left: {{ $boxX }}%;
                                                top: {{ $boxY }}%;
                                                width: {{ $boxWidth }}%;
                                                height: {{ $boxHeight }}%;
                                                background: rgba(0,255,0,0.3);
                                                border: 2px solid green;
                                            "></div>
                                        @endif

                                        <!-- 🔴 USER CLICK -->
                                        @if ($userX !== null)
                                            <div style="
                                                position:absolute;
                                                left: {{ $userX }}%;
                                                top: {{ $userY }}%;
                                                width:14px;
                                                height:14px;
                                                background:red;
                                                border-radius:50%;
                                                transform: translate(-50%, -50%);
                                            "></div>

                                            <!-- YOUR ANSWER BADGE (directly above marker) -->
                                            <div class="your-answer-badge" style="
                                                position:absolute;
                                                left: {{ $userX - 10 }}%;
                                                top: calc({{ $userY + 2 }}% - 25px); /* badge upar marker ke */
                                                transform: translateX(-50%);
                                            ">
                                                YOUR ANSWER
                                            </div>
                                        @endif

                                    </div>

                                </div>

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
                                        <a href="{{ route('frontend.exam.review', [$exam->slug, $userExam->id, $prevQuestion->question_id]) }}"
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
                                        <a href="{{ route('frontend.exam.review', [$exam->slug, $userExam->id, $nextQuestion->question_id]) }}"
                                            class="btn btn-primary rounded-pill px-4">
                                            Next <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('frontend.exam.stat', $userExam->id) }}"
                                            class="btn btn-warning rounded-pill px-4">
                                            Finish Review
                                        </a>
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
                <button class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" data-bs-toggle="modal"
                    data-bs-target="#reviewModal">
                    <i class="fas fa-chart-simple me-2"></i> Review Progress
                </button>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#explainModal">
                    <i class="fas fa-info me-1"></i>
                    <span class="d-none d-sm-inline ms-1">Explanation</span>
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
                <div class="modal-header border-0 pb-0 mb-3">
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
                                        onclick="window.location.href='{{ route('frontend.exam.review', [$exam->slug, $userExam->id, $q->question_id]) }}'">
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
                                        onclick="window.location.href='{{ route('frontend.exam.review', [$exam->slug, $userExam->id, $q->question_id]) }}'">
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
                                        onclick="window.location.href='{{ route('frontend.exam.review', [$exam->slug, $userExam->id, $q->question_id]) }}'">
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
                                        onclick="window.location.href='{{ route('frontend.exam.review', [$exam->slug, $userExam->id, $q->question_id]) }}'">
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
            </div>
        </div>
    </div>

    <div class="modal fade" id="explainModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body">
                    <h4>Explanation</h4>
                    <p>{{ $currentQuestion->question->ans_explanation }}</p>
                </div>

                <div class="modal-footer" style="border: none; margin: 0px; padding: 5px 10px;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        let mode = null;
        let annotations = @json(json_decode($currentQuestion->annotations ?? '[]', true) ?? []);

        const container = document.getElementById('questionTextContainer');

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

        // 🔥 ESCAPE
        function escapeHtml(text) {
            return text.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }

        // 🔥 LOAD
        document.addEventListener("DOMContentLoaded", function() {
            applyAnnotations();
        });
    </script>
@endsection
