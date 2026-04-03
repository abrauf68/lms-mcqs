@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="row g-4">

    {{-- 🔹 STAT CARDS --}}
    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted">Total Students</h6>
            <h3 class="fw-bold">1,245</h3>
            <small class="text-success">+12% this month</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted">Total Courses</h6>
            <h3 class="fw-bold">58</h3>
            <small class="text-primary">+5 new added</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted">Exams Taken</h6>
            <h3 class="fw-bold">3,420</h3>
            <small class="text-warning">+18% growth</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted">Revenue</h6>
            <h3 class="fw-bold">$12,450</h3>
            <small class="text-success">+8% increase</small>
        </div>
    </div>

    {{-- 🔹 CHARTS --}}
    <div class="col-md-8">
        <div class="card shadow-sm p-4">
            <h6 class="mb-3">Monthly Students Growth</h6>
            <canvas id="studentsChart"></canvas>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-4">
            <h6 class="mb-3">Course Categories</h6>
            <canvas id="courseChart"></canvas>
        </div>
    </div>

    {{-- 🔹 ALERTS --}}
    <div class="col-md-6">
        <div class="card shadow-sm p-4">
            <h6 class="mb-3">System Alerts</h6>

            <div class="alert alert-warning py-2 mb-2">
                ⚠️ 5 students failed PMP mock exam
            </div>

            <div class="alert alert-danger py-2 mb-2">
                🚫 Server load high (85%)
            </div>

            <div class="alert alert-info py-2">
                ℹ️ New course "Agile Mastery" added
            </div>
        </div>
    </div>

    {{-- 🔹 RECENT ACTIVITY --}}
    <div class="col-md-6">
        <div class="card shadow-sm p-4">
            <h6 class="mb-3">Recent Activity</h6>

            <ul class="list-group list-group-flush">
                <li class="list-group-item">Ali enrolled in PMP Course</li>
                <li class="list-group-item">Sara completed Agile Exam</li>
                <li class="list-group-item">Admin added new questions</li>
                <li class="list-group-item">John failed mock test</li>
                <li class="list-group-item">New user registered</li>
            </ul>
        </div>
    </div>

</div>

@endsection


@section('script')

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    // 🔹 Line Chart (Students Growth)
    new Chart(document.getElementById('studentsChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Students',
                data: [200, 400, 650, 800, 950, 1245],
                borderWidth: 2,
                tension: 0.4
            }]
        }
    });

    // 🔹 Doughnut Chart (Courses)
    new Chart(document.getElementById('courseChart'), {
        type: 'doughnut',
        data: {
            labels: ['PMP', 'Agile', 'Scrum', 'IT'],
            datasets: [{
                data: [20, 15, 10, 13],
                borderWidth: 1
            }]
        }
    });

</script>

@endsection
