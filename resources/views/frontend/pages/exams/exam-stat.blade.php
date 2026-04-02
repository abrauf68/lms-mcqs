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

        /* Tabs */
        .section-two h2 {
            font-size: 25px;
            margin-bottom: 10px;
        }

        .section-two .custom-tabs {
            background: #555;
            padding: 10px 15px 0;
            border-radius: 6px 6px 0 0;
            overflow-x: auto;
            flex-wrap: nowrap;
        }

        .section-two .custom-tabs .nav-link {
            color: #fff;
            border: none;
            margin-right: 10px;
            padding: 10px 15px;
            border-radius: 6px 6px 0 0;
            font-weight: 600;
            white-space: nowrap;
            font-size: 12px;
        }

        .section-two .custom-tabs .nav-link.active {
            background: #fff;
            color: #000;
        }

        /* Card */
        .section-two .card-box {
            background: #f7f7f7;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        /* Row */
        .section-two .progress-row {
            margin-bottom: 25px;
        }

        .section-two .label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .section-two .percent-red {
            color: #d63333;
            font-weight: 600;
            font-size: 14px;
        }

        .section-two .count {
            font-style: italic;
            font-size: 14px;
        }

        /* Progress bar */
        .section-two .progress-custom {
            background: #e5e5e5;
            height: 15px;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }

        .section-two .progress-fill {
            background: #d63333;
            height: 100%;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            transition: width 0.5s ease;
        }

        /* ✅ Responsive Fixes */
        @media (max-width: 768px) {

            .section-two .card-box {
                padding: 20px;
            }

            .section-two .progress-row {
                text-align: center;
            }

            .section-two .progress-row>div {
                margin-bottom: 8px;
            }

            .section-two .label,
            .section-two .percent-red,
            .section-two .count {
                text-align: center;
            }
        }


        /* Main Box */
        .section-three .main-box {
            background: #dcdcdc;
            margin: 30px auto;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .section-three .header {
            background: #8c8c8c;
            color: #fff;
            text-align: center;
            font-weight: 600;
            padding: 12px;
            border-radius: 6px 6px 0 0;
        }

        /* Tabs */
        .section-three .custom-tabs {
            background: #9a9a9a;
            padding: 10px 15px 0;
            overflow-x: auto;
            flex-wrap: nowrap;
        }

        .section-three .custom-tabs .nav-link {
            background: transparent;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 6px 6px 0 0;
            white-space: nowrap;
            font-size: 14px;
        }

        .section-three .custom-tabs .nav-link.active {
            background: #efefef;
            color: #333;
        }

        /* Content */
        .section-three .content-box {
            background: #efefef;
            margin: 15px;
            border-radius: 6px;
            padding: 20px;
        }

        /* Left Cards */
        .section-three .left-card {
            background: #dcdcdc;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            margin-bottom: 15px;
        }

        .section-three .big-text {
            font-size: 50px;
            color: #d32f2f;
            /* font-weight: 600; */
        }

        .section-three .small-text {
            color: #d32f2f;
            /* font-weight: 600; */
            font-size: 20px
        }

        /* Progress */
        .section-three .progress-row {
            margin-bottom: 40px;
        }

        .section-three .progress-label {
            font-weight: 500;
        }

        .section-three .progress-custom {
            background: #ddd;
            border-radius: 20px;
            height: 15px;
            overflow: hidden;
        }

        .section-three .bar {
            height: 100%;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 12px;
        }

        /* Colors */
        .section-three .green {
            background: #5cb85c;
        }

        .section-three .red {
            background: #d32f2f;
        }

        .section-three .blue {
            background: #5b84b1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-three .big-text {
                font-size: 30px;
            }

            .section-three .progress-label {
                margin-bottom: 5px;
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
                        <h1 style="color: {{ $userExam->result == 'pass' ? '#4caf50' : '#e53935' }};">
                            {{ $userExam->score_percentage }}%</h1>
                        <p>{{ $userExam->result == 'pass' ? 'PASSED' : 'FAILED' }} THE EXAM</p>

                        <a href="{{ route('frontend.exam.review', [$userExam->exam->slug, $userExam->id, $firstAns->question_id]) }}" class="btn btn-review w-100 mt-3">
                            REVIEW TEST
                        </a>
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

    <div class="container section-two">

        <h2>Results Analysis</h2>

        @php
            $tabs = [
                'domain' => ['label' => 'DOMAIN', 'data' => $domainStats],
                'process' => ['label' => 'PROCESS GROUP', 'data' => $processStats],
                'topic' => ['label' => 'TOPIC', 'data' => $topicStats],
                'approach' => ['label' => 'APPROACH', 'data' => $approachStats],
            ];
        @endphp

        <!-- Tabs -->
        <ul class="nav custom-tabs" role="tablist">
            @foreach ($tabs as $key => $tab)
                <li class="nav-item">
                    <button class="nav-link @if ($loop->first) active @endif" data-bs-toggle="tab"
                        data-bs-target="#{{ $key }}">
                        {{ $tab['label'] }}
                    </button>
                </li>
            @endforeach
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">

            @foreach ($tabs as $key => $tab)
                <div class="tab-pane fade @if ($loop->first) show active @endif" id="{{ $key }}">

                    <div class="card-box">

                        @foreach ($tab['data']['items'] as $item)
                            <div class="row align-items-center progress-row text-md-start text-center mb-3">

                                <!-- NAME -->
                                <div class="col-12 col-md-2 label">
                                    {{ $item['name'] }}
                                </div>

                                <!-- PERCENT -->
                                <div class="col-6 col-md-1 percent-red">
                                    {{ $item['percent'] }}%
                                </div>

                                <!-- COUNT -->
                                <div class="col-6 col-md-1 count">
                                    {{ $item['correct'] }}/{{ $item['total'] }}
                                </div>

                                <!-- PROGRESS -->
                                @php
                                    $percent = (int) $item['percent'];

                                    if ($percent < 50) {
                                        $color = '#E53935';
                                    } elseif ($percent <= 80) {
                                        $color = '#2196F3';
                                    } else {
                                        $color = '#4CAF50';
                                    }
                                @endphp

                                <div class="col-12 col-md-8">
                                    <div class="progress-custom">
                                        <div class="progress-fill"
                                            style="width: {{ $percent > 0 ? $percent : 8 }}%; background-color: {{ $color }};">
                                            {{ $percent }}%
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>
            @endforeach

        </div>
    </div>

    @php
        $sectionMap = [
            'domain' => $domainStats,
            'process' => $processStats,
            'topic' => $topicStats,
            'approach' => $approachStats,
        ];

        // default
        $activeKey = request('type', 'domain');
        $activeData = $sectionMap[$activeKey];
    @endphp

    <div class="container section-three" id="sectionThree">
        <div class="main-box">

            <!-- Header -->
            <div class="header">
                {{ strtoupper($activeKey) }}
            </div>

            <!-- ITEM TABS (IMPORTANT CHANGE) -->
            <ul class="nav custom-tabs" role="tablist">

                @foreach ($activeData['items'] as $index => $item)
                    <li class="nav-item">
                        <button class="nav-link @if ($loop->first) active @endif" data-bs-toggle="tab"
                            data-bs-target="#item-{{ $index }}">

                            {{ $item['name'] }} | {{ $item['percent'] }}%

                        </button>
                    </li>
                @endforeach

            </ul>

            <!-- TAB CONTENT -->
            <div class="tab-content">

                @foreach ($activeData['items'] as $index => $item)
                    @php
                        $correct = $item['correct'];
                        $wrong = $item['wrong'];
                        $total = $item['total'];
                        $percent = $item['percent'];
                    @endphp

                    <div class="tab-pane fade @if ($loop->first) show active @endif"
                        id="item-{{ $index }}">

                        <div class="content-box">
                            <div class="row">

                                <!-- LEFT -->
                                <div class="col-md-3">

                                    <div class="left-card">
                                        <div class="big-text">{{ $percent }}%</div>
                                    </div>

                                    <div class="left-card">
                                        <div class="big-text" style="font-size:24px;">
                                            {{ $correct }} / {{ $total }}
                                        </div>
                                        <div class="small-text">CORRECT</div>
                                    </div>

                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-9" style="margin-top: 40px;">

                                    <!-- Correct -->
                                    <div class="progress-row">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 progress-label">Correct</div>
                                            <div class="col-md-10">
                                                <div class="progress-custom">
                                                    <div class="bar green" style="width: {{ $percent > 0 ? $percent : 8 }}%">
                                                        {{ $correct }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Wrong -->
                                    <div class="progress-row">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 progress-label">Incorrect</div>
                                            <div class="col-md-10">
                                                <div class="progress-custom">
                                                    <div class="bar red" style="width: {{ ($wrong != 0 ? 100 - $percent : 8) }}%">
                                                        {{ $wrong }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Percentage -->
                                    <div class="progress-row">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 progress-label">Percentage</div>
                                            <div class="col-md-10">
                                                <div class="progress-custom">
                                                    <div class="bar blue" style="width: {{ $percent > 0 ? $percent : 8 }}%">
                                                        {{ $percent }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        const sectionData = @json($sectionMap);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const tabs = document.querySelectorAll('.section-two .nav-link');

            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(e) {

                    let targetId = e.target.getAttribute('data-bs-target').replace('#', '');
                    let data = sectionData[targetId];

                    if (!data) return;

                    renderSectionThree(targetId, data);
                });
            });

            function renderSectionThree(type, data) {

                let html = `
        <div class="main-box">

            <div class="header">${type.toUpperCase()}</div>

            <ul class="nav custom-tabs" role="tablist">
        `;

                data.items.forEach((item, index) => {
                    html += `
                <li class="nav-item">
                    <button class="nav-link ${index === 0 ? 'active' : ''}"
                        data-bs-toggle="tab"
                        data-bs-target="#item-${index}">
                        ${item.name} | ${item.percent}%
                    </button>
                </li>
            `;
                });

                html += `</ul><div class="tab-content">`;

                data.items.forEach((item, index) => {

                    let percent = item.percent;
                    let correct = item.correct;
                    let wrong = item.wrong;
                    let total = item.total;

                    html += `
            <div class="tab-pane fade ${index === 0 ? 'show active' : ''}" id="item-${index}">
                <div class="content-box">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="left-card">
                                <div class="big-text">${percent}%</div>
                            </div>

                            <div class="left-card">
                                <div class="big-text" style="font-size:24px;">
                                    ${correct} / ${total}
                                </div>
                                <div class="small-text">CORRECT</div>
                            </div>
                        </div>

                        <div class="col-md-9" style="margin-top: 40px;">

                            <div class="progress-row">
                                <div class="row align-items-center">
                                    <div class="col-md-2 progress-label">Correct</div>
                                    <div class="col-md-10">
                                        <div class="progress-custom">
                                            <div class="bar green" style="width: ${percent > 0 ? percent : 8}%">
                                                ${correct}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-row">
                                <div class="row align-items-center">
                                    <div class="col-md-2 progress-label">Incorrect</div>
                                    <div class="col-md-10">
                                        <div class="progress-custom">
                                            <div class="bar red" style="width: ${wrong != 0 ? 100 - percent : 8}%">
                                                ${wrong}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-row">
                                <div class="row align-items-center">
                                    <div class="col-md-2 progress-label">Percentage</div>
                                    <div class="col-md-10">
                                        <div class="progress-custom">
                                            <div class="bar blue" style="width: ${percent > 0 ? percent : 8}%">
                                                ${percent}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            `;
                });

                html += `</div></div>`;

                document.getElementById('sectionThree').innerHTML = html;
            }

        });
    </script>
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
