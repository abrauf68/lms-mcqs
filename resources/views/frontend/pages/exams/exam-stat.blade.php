@extends('frontend.layouts.exam.master')

@section('title', 'Exam Stats')

@section('css')
    <style>
        .section-one .header-bar {
            background: #555;
            color: #fff;
            text-align: center;
            padding: 12px;
            font-weight: bold;
            font-size: 14px;
        }

        .section-one .card-box {
            background: #fff;
            border-radius: 6px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .section-one .score-box {
            text-align: center;
        }

        .section-one .score-box h1 {
            color: #e53935;
            font-size: 75px;
            margin: 10px 0;
        }

        .section-one .btn-review {
            border: 2px solid #4caf50;
            color: #4caf50;
            font-weight: bold;
        }

        .section-one .btn-review:hover {
            background: #4caf50;
            color: #fff;
        }

        .section-one .breakdown-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-radius: 4px;
            overflow: hidden;
        }

        .section-one .breakdown-label {
            background: #f1f1f1;
            padding: 10px;
            flex: 1;
            font-size: 14px;
        }

        .section-one .breakdown-value {
            padding: 10px;
            color: #fff;
            min-width: 60px;
            text-align: center;
            font-size: 14px;
        }

        .section-one .green {
            background: #4caf50;
        }

        .section-one .red {
            background: #e53935;
        }

        .section-one .blue {
            background: #2196f3;
        }

        .section-one .side-box {
            background: #fff;
            padding: 15px;
            border-radius: 6px;
            border-top: 5px solid #ff6f00;
            text-align: center;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .section-one .side-box strong {
            display: block;
            font-size: 18px;
        }

        /* 🔥 MOBILE FIXES */
        @media (max-width: 768px) {

            .section-one .score-box h1 {
                font-size: 40px;
            }

            .section-one .header-bar {
                font-size: 12px;
            }

            .section-one .card-box {
                padding: 15px;
            }

            .section-one .side-box strong {
                font-size: 16px;
            }

            /* chart center */
            .section-one #chart {
                max-width: 160px;
                margin: auto;
            }
        }

        /* EXTRA SMALL DEVICES */
        @media (max-width: 576px) {

            .section-one .score-box h1 {
                font-size: 35px;
            }

            .section-one .btn-review {
                font-size: 14px;
                padding: 8px;
            }

            .section-one .breakdown-label,
            .section-one .breakdown-value {
                font-size: 12px;
                padding: 8px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="section-one">
        <div class="header-bar">
            EXAM | {{ $userExam->created_at->format('m/d/Y') }} | {{ $userExam->total_questions }} QUESTIONS
        </div>

        <div class="container my-4">
            <div class="row g-4">

                <!-- LEFT -->
                <div class="col-lg-3 col-md-4 col-12">
                    <div class="card-box score-box">
                        <p><strong>SCORE</strong></p>
                        <h1 style="color: {{ $userExam->result == 'pass' ? '#4caf50' : '#e53935' }};">{{ $userExam->score_percentage }}%</h1>
                        <p>{{ $userExam->result == 'pass' ? 'PASSED' : 'FAILED' }} THE EXAM</p>

                        <button class="btn btn-review w-100 mt-3">
                            REVIEW TEST
                        </button>
                    </div>
                </div>

                <!-- CENTER -->
                <div class="col-lg-6 col-md-8 col-12">
                    <div class="card-box">
                        <h6 class="text-center mb-4">SCORE BREAKDOWN</h6>

                        <div class="row align-items-center text-center text-md-start">

                            <!-- Breakdown -->
                            <div class="col-md-6 col-12 mb-3 mb-md-0">
                                <div class="breakdown-item">
                                    <div class="breakdown-label">CORRECT</div>
                                    <div class="breakdown-value green">{{ $userExam->correct_answers }}</div>
                                </div>

                                <div class="breakdown-item">
                                    <div class="breakdown-label">INCORRECT</div>
                                    <div class="breakdown-value red">{{ $userExam->wrong_answers }}</div>
                                </div>

                                <div class="breakdown-item">
                                    <div class="breakdown-label">PERCENTAGE</div>
                                    <div class="breakdown-value blue">{{ $userExam->score_percentage }}%</div>
                                </div>
                            </div>

                            <!-- Chart -->
                            <div class="col-md-6 col-12 text-center">
                                <canvas id="chart" height="200"></canvas>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-lg-3 col-md-12 col-12">
                    <div class="side-box">
                        <strong>{{ \App\Helpers\Helper::formatTime($userExam->avg_time) }}</strong>
                        <small>AVERAGE TIME PER QUESTION</small>
                    </div>

                    <div class="side-box">
                        <strong>{{ \App\Helpers\Helper::formatTime($userExam->total_time) }}</strong>
                        <small>TOTAL TIME</small>
                    </div>

                    <div class="side-box">
                        <strong>{{ $userExam->total_questions }} questions</strong>
                        <small>PROJ. ANSWERED IN 180 MINUTES</small>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('chart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [{{ $userExam->correct_answers }}, {{ $userExam->wrong_answers }}],
                    backgroundColor: ['#4caf50', '#e53935'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
