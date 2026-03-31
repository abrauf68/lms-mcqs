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
            grid-template-columns: repeat(auto-fill, minmax(60px, 70px));
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
                            <div id="questionTextContainer" class="question-text-area mb-4">
                                {{ $currentQuestion->question->question_text }}</div>

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
                                        <a href="{{ route('frontend.exam', [$exam->slug, $prevQuestion->question_id]) }}"
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
                                        <a href="{{ route('frontend.exam', [$exam->slug, $nextQuestion->question_id]) }}"
                                            class="btn btn-primary rounded-pill px-4">
                                            Next <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    @else
                                        <form action="{{ route('frontend.score.submit') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                                Submit Score <i class="fas fa-check ms-1"></i>
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
                        <i class="fas fa-flag-checkered me-1"></i> Unmark
                    </a>
                @else
                    <a href="{{ route('frontend.mark.for.review', $currentQuestion->id) }}"
                        class="btn btn-outline-warning annotation-btn">
                        <i class="fas fa-flag me-1"></i> Mark for Review
                    </a>
                @endif
                <button id="highlightModeBtn" class="btn btn-outline-info annotation-btn"><i
                        class="fas fa-highlighter me-1"></i> Highlight</button>
                <button id="strikethroughModeBtn" class="btn btn-outline-danger annotation-btn"><i
                        class="fas fa-strikethrough me-1"></i> Strikethrough</button>
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
                                        onclick="window.location.href='{{ route('frontend.exam', [$exam->slug, $q->question_id]) }}'">
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
                                        onclick="window.location.href='{{ route('frontend.exam', [$exam->slug, $q->question_id]) }}'">
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
                                        onclick="window.location.href='{{ route('frontend.exam', [$exam->slug, $q->question_id]) }}'">
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
                                        onclick="window.location.href='{{ route('frontend.exam', [$exam->slug, $q->question_id]) }}'">
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
        // ========== TIMER LOGIC ==========
        let startTime = Date.now();
        let timerInterval = null;

        function updateTimerDisplay() {
            const elapsedSeconds = Math.floor((Date.now() - startTime) / 1000);
            const minutes = Math.floor(elapsedSeconds / 60);
            const seconds = elapsedSeconds % 60;
            const formatted = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            document.getElementById('examTimer').innerText = formatted;
        }

        function startTimer() {
            if (timerInterval) clearInterval(timerInterval);
            startTime = Date.now();
            updateTimerDisplay();
            timerInterval = setInterval(updateTimerDisplay, 1000);
        }

        // ========== DATA ==========
        const questions = [{
                id: 1,
                text: `A project manager is finalizing a project that has had repeated problems with cost conformance. The project manager is concerned about what management will say. Senior managers have talked about the problems multiple times in their executive meetings, and it's been reported they feel their concerns have not been addressed. Which of the following types of information would be best for the project manager to use to evaluate performance?`,
                options: [{
                    letter: "A",
                    text: "A list of complaints from senior management."
                }, {
                    letter: "B",
                    text: "The most recent CPI."
                }, {
                    letter: "C",
                    text: "The last bar chart."
                }, {
                    letter: "D",
                    text: "The project budget."
                }],
                correct: "B"
            },
            {
                id: 2,
                text: `An organization wants to proactively reduce the probability and impact of a known risk. Which risk response strategy is most appropriate?`,
                options: [{
                    letter: "A",
                    text: "Accept"
                }, {
                    letter: "B",
                    text: "Transfer"
                }, {
                    letter: "C",
                    text: "Mitigate"
                }, {
                    letter: "D",
                    text: "Exploit"
                }],
                correct: "C"
            },
            {
                id: 3,
                text: `Earned Value Management (EVM) integrates project scope, schedule, and cost. Which of the following indicators shows the budgeted cost of work performed?`,
                options: [{
                    letter: "A",
                    text: "Planned Value (PV)"
                }, {
                    letter: "B",
                    text: "Earned Value (EV)"
                }, {
                    letter: "C",
                    text: "Actual Cost (AC)"
                }, {
                    letter: "D",
                    text: "Cost Performance Index (CPI)"
                }],
                correct: "B"
            }
        ];
        const totalQ = questions.length;
        document.getElementById("totalQCount").innerText = totalQ;

        let userAnswers = {
            1: null,
            2: null,
            3: null
        };
        let markedFlags = {
            1: false,
            2: false,
            3: false
        };
        let annotations = {
            1: {
                highlights: [],
                strikethroughs: []
            },
            2: {
                highlights: [],
                strikethroughs: []
            },
            3: {
                highlights: [],
                strikethroughs: []
            }
        };
        let currentIndex = 0;
        let activeAnnotationMode = null;

        function escapeHtml(str) {
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        function applyAnnotationsToText(plainText, qId) {
            const ann = annotations[qId] || {
                highlights: [],
                strikethroughs: []
            };
            const highlights = ann.highlights || [];
            const strikes = ann.strikethroughs || [];
            const allRanges = [...highlights.map(h => ({
                ...h,
                type: 'highlight'
            })), ...strikes.map(s => ({
                ...s,
                type: 'strike'
            }))];
            allRanges.sort((a, b) => a.start - b.start);
            let lastIdx = 0,
                result = [];
            for (let range of allRanges) {
                if (range.start > lastIdx) result.push(escapeHtml(plainText.substring(lastIdx, range.start)));
                const seg = plainText.substring(range.start, range.end);
                if (range.type === 'highlight') result.push(`<span class="user-highlight">${escapeHtml(seg)}</span>`);
                else result.push(`<span class="user-strikethrough">${escapeHtml(seg)}</span>`);
                lastIdx = range.end;
            }
            if (lastIdx < plainText.length) result.push(escapeHtml(plainText.substring(lastIdx)));
            return result.join('');
        }

        function renderCurrentQuestion() {
            const q = questions[currentIndex];
            if (!q) return;
            document.getElementById("currentQNumber").innerText = currentIndex + 1;
            const plainText = q.text;
            const annotatedHtml = applyAnnotationsToText(plainText, q.id);
            document.getElementById("questionTextContainer").innerHTML = annotatedHtml;

            const selectedLetter = userAnswers[q.id];
            let optsHtml = '';
            q.options.forEach(opt => {
                const isChecked = (selectedLetter === opt.letter);
                const id = `opt_${q.id}_${opt.letter}`;
                optsHtml +=
                    `<div class="option-item" data-letter="${opt.letter}"><div class="form-check"><input class="form-check-input" type="radio" name="questionRadio" id="${id}" value="${opt.letter}" ${isChecked ? 'checked' : ''}><label class="form-check-label radio-label w-100" for="${id}"><strong>${opt.letter}.</strong> ${escapeHtml(opt.text)}</label></div></div>`;
            });
            document.getElementById("optionsContainer").innerHTML = optsHtml;
            document.querySelectorAll('input[name="questionRadio"]').forEach(radio => radio.addEventListener('change', (
                e) => updateCurrentAnswer(e.target.value)));
            document.querySelectorAll('.option-item').forEach(div => {
                const radio = div.querySelector('input');
                if (radio) div.addEventListener('click', (e) => {
                    if (!['INPUT', 'LABEL'].includes(e.target.tagName)) {
                        radio.checked = true;
                        radio.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                });
            });

            const isAnswered = (userAnswers[q.id] !== null);
            const isMarked = markedFlags[q.id];
            const statusSpan = document.getElementById("currentStatusBadge");
            const markSpan = document.getElementById("markBadge");
            if (isAnswered) {
                statusSpan.innerHTML = '<i class="fas fa-check-circle me-1"></i> Answered';
                statusSpan.className = "badge-smart bg-success bg-opacity-10 text-success";
            } else {
                statusSpan.innerHTML = '<i class="far fa-circle me-1"></i> Unanswered';
                statusSpan.className = "badge-smart bg-secondary bg-opacity-10 text-secondary";
            }
            markSpan.innerHTML = isMarked ? '<i class="fas fa-flag me-1"></i> Marked for review' :
                '<i class="far fa-flag me-1"></i> Not marked';
            markSpan.className = isMarked ? "badge-smart bg-warning bg-opacity-15 text-warning ms-2" :
                "badge-smart bg-secondary bg-opacity-10 text-secondary ms-2";
            const markBtn = document.getElementById("globalMarkBtn");
            if (isMarked) {
                markBtn.innerHTML = '<i class="fas fa-flag-checkered me-1"></i> Unmark';
                markBtn.classList.remove("btn-outline-warning");
                markBtn.classList.add("btn-warning", "text-white");
            } else {
                markBtn.innerHTML = '<i class="fas fa-flag me-1"></i> Mark for Review';
                markBtn.classList.remove("btn-warning", "text-white");
                markBtn.classList.add("btn-outline-warning");
            }
        }

        function updateCurrentAnswer(letter) {
            const q = questions[currentIndex];
            if (q) {
                userAnswers[q.id] = letter;
                renderCurrentQuestion();
            }
        }

        function toggleMarkCurrent() {
            const q = questions[currentIndex];
            if (q) {
                markedFlags[q.id] = !markedFlags[q.id];
                renderCurrentQuestion();
            }
        }

        function nextQuestion() {
            currentIndex = (currentIndex + 1) % totalQ;
            renderCurrentQuestion();
        }

        function prevQuestion() {
            currentIndex = (currentIndex - 1 + totalQ) % totalQ;
            renderCurrentQuestion();
        }

        // Annotation logic
        function getPlainTextSelectionOffsets(container) {
            const sel = window.getSelection();
            if (!sel.rangeCount) return null;
            const range = sel.getRangeAt(0);
            if (!container.contains(range.commonAncestorContainer)) return null;
            const fullText = container.textContent;
            const preRange = document.createRange();
            preRange.selectNodeContents(container);
            preRange.setEnd(range.startContainer, range.startOffset);
            const startOffset = preRange.toString().length;
            const endOffset = startOffset + range.toString().length;
            return {
                start: startOffset,
                end: endOffset
            };
        }

        function addAnnotationToCurrentQuestion(type, start, end) {
            if (start === end) return;
            const q = questions[currentIndex];
            if (!q) return;
            const plainLen = q.text.length;
            const safeStart = Math.min(start, plainLen);
            const safeEnd = Math.min(end, plainLen);
            if (safeStart >= safeEnd) return;
            let ann = annotations[q.id];
            const targetArr = type === 'highlight' ? ann.highlights : ann.strikethroughs;
            targetArr.push({
                start: safeStart,
                end: safeEnd
            });
            targetArr.sort((a, b) => a.start - b.start);
            let merged = [];
            for (let seg of targetArr) {
                if (merged.length === 0 || merged[merged.length - 1].end < seg.start) merged.push({
                    ...seg
                });
                else merged[merged.length - 1].end = Math.max(merged[merged.length - 1].end, seg.end);
            }
            if (type === 'highlight') ann.highlights = merged;
            else ann.strikethroughs = merged;
            annotations[q.id] = ann;
            renderCurrentQuestion();
        }

        function handleTextSelection() {
            if (!activeAnnotationMode) return;
            const container = document.getElementById("questionTextContainer");
            if (!container) return;
            const offsets = getPlainTextSelectionOffsets(container);
            if (offsets && offsets.start !== offsets.end) {
                addAnnotationToCurrentQuestion(activeAnnotationMode, offsets.start, offsets.end);
                window.getSelection()?.removeAllRanges();
            }
        }

        const highlightBtn = document.getElementById("highlightModeBtn");
        const strikeBtn = document.getElementById("strikethroughModeBtn");

        function setAnnotationMode(mode) {
            if (activeAnnotationMode === mode) {
                activeAnnotationMode = null;
                highlightBtn.classList.remove("annotation-active");
                strikeBtn.classList.remove("annotation-active");
            } else {
                activeAnnotationMode = mode;
                highlightBtn.classList.remove("annotation-active");
                strikeBtn.classList.remove("annotation-active");
                if (mode === 'highlight') highlightBtn.classList.add("annotation-active");
                else strikeBtn.classList.add("annotation-active");
            }
        }
        highlightBtn.addEventListener("click", () => setAnnotationMode('highlight'));
        strikeBtn.addEventListener("click", () => setAnnotationMode('strikethrough'));
        document.addEventListener("mouseup", (e) => {
            if (activeAnnotationMode && document.getElementById("questionTextContainer")?.contains(e.target))
                handleTextSelection();
        });

        // MODAL: compact boxes with superscript icons
        // const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
        // document.getElementById("reviewProgressBtn").addEventListener("click", () => {
        //     populateModalGrids();
        //     reviewModal.show();
        // });

        // function createCompactBox(q, idx) {
        //     const isAnswered = userAnswers[q.id] !== null;
        //     const isMarked = markedFlags[q.id];
        //     const box = document.createElement('div');
        //     box.className = 'q-box';
        //     let supHtml = '<div class="q-status-sup">';
        //     if (isAnswered) supHtml += '<i class="fas fa-check-circle text-success" title="Answered"></i>';
        //     if (isMarked) supHtml += '<i class="fas fa-flag text-warning" title="Marked"></i>';
        //     supHtml += '</div>';
        //     box.innerHTML = supHtml + `<div class="q-num">${q.id}</div>`;
        //     box.addEventListener('click', () => {
        //         currentIndex = idx;
        //         renderCurrentQuestion();
        //         reviewModal.hide();
        //     });
        //     return box;
        // }

        // function populateModalGrids() {
        //     const unansweredGrid = document.getElementById("unansweredGrid");
        //     const markedGrid = document.getElementById("markedGrid");
        //     const answeredGrid = document.getElementById("answeredGrid");
        //     const allGrid = document.getElementById("allGrid");
        //     unansweredGrid.innerHTML = '';
        //     markedGrid.innerHTML = '';
        //     answeredGrid.innerHTML = '';
        //     allGrid.innerHTML = '';
        //     for (let i = 0; i < totalQ; i++) {
        //         const q = questions[i];
        //         const isAnswered = (userAnswers[q.id] !== null);
        //         const isMarked = markedFlags[q.id];
        //         allGrid.appendChild(createCompactBox(q, i));
        //         if (!isAnswered) unansweredGrid.appendChild(createCompactBox(q, i));
        //         if (isMarked) markedGrid.appendChild(createCompactBox(q, i));
        //         if (isAnswered) answeredGrid.appendChild(createCompactBox(q, i));
        //     }
        //     if (!unansweredGrid.children.length) unansweredGrid.innerHTML =
        //         '<div class="text-muted text-center p-3">✨ No unanswered questions</div>';
        //     if (!markedGrid.children.length) markedGrid.innerHTML =
        //         '<div class="text-muted text-center p-3">🏷 No marked questions</div>';
        //     if (!answeredGrid.children.length) answeredGrid.innerHTML =
        //         '<div class="text-muted text-center p-3">✅ No answered questions</div>';
        //     if (!allGrid.children.length) allGrid.innerHTML =
        //         '<div class="text-muted text-center p-3">📋 No questions</div>';
        // }

        // Anti-copy & screenshot
        // function blockCopy(e) {
        //     e.preventDefault();
        //     return false;
        // }

        // function blockKeys(e) {
        //     if (e.ctrlKey && (e.key === 'c' || e.key === 'C' || e.key === 's' || e.key === 'S' || e.key === 'u' || e.key ===
        //             'U' || e.key === 'i' || e.key === 'I' || e.key === 'p' || e.key === 'P')) {
        //         e.preventDefault();
        //         return false;
        //     }
        //     if (e.key === 'PrintScreen') {
        //         e.preventDefault();
        //         return false;
        //     }
        //     if (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'i')) {
        //         e.preventDefault();
        //         return false;
        //     }
        // }
        // document.addEventListener('contextmenu', blockCopy);
        // document.addEventListener('keydown', blockKeys);
        // window.addEventListener('beforeprint', (e) => {
        //     alert("Printing/screenshots restricted in secure exam.");
        //     e.preventDefault();
        //     return false;
        // });

        document.getElementById("globalMarkBtn").addEventListener("click", toggleMarkCurrent);
        document.getElementById("prevQuestionBtn").addEventListener("click", prevQuestion);
        document.getElementById("nextQuestionBtn").addEventListener("click", nextQuestion);

        // Start the timer and render initial question
        startTimer();
        renderCurrentQuestion();
    </script>
@endsection
