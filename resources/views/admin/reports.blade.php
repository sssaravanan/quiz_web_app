@extends('admin.layouts.admin')

@section('title', 'Reports & Analytics')
@section('page_title', 'Reports & Analytics')

@section('extra_css')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<style>
    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .stat-card.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-card.success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    
    .stat-card.warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stat-card.info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 10px 0;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ $totalUsers }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="stat-label">Total Quizzes</div>
                <div class="stat-value">{{ $totalQuizzes }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="stat-label">Total Attempts</div>
                <div class="stat-value">{{ $totalAttempts }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="stat-label">Average Score</div>
                <div class="stat-value">{{ $avgScoreOverall }}%</div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <!-- Average Scores Over Time -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Average Scores Over Time</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="avgScoresChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Completion Rate -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Quiz Completion Rate</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="completionRateChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mb-4">
        <!-- Most Attempted Quizzes -->
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Most Attempted Quizzes</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 350px;">
                        <canvas id="mostAttemptedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row mb-4">
        <!-- User Performance -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Top 10 User Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="DataTable table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Attempts</th>
                                    <th>Avg Score</th>
                                    <th>Best Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userPerformance as $user)
                                    <tr>
                                        <td>
                                            <strong>{{ $user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </td>
                                        <td>{{ $user->total_attempts }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $user->avg_score ?? 0 }}%</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $user->best_score ?? 0 }}%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Drop-off Analysis -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Drop-off Analysis by Quiz</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="DataTable table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Quiz</th>
                                    <th>Total</th>
                                    <th>Completed</th>
                                    <th>Completion %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dropoffAnalysis as $item)
                                    <tr>
                                        <td><strong>{{ $item->quiz_name ?? 'Unknown' }}</strong></td>
                                        <td>{{ $item->total_attempts }}</td>
                                        <td>{{ $item->completed_count }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $item->completion_rate ?? 0 }}%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Attempted Quizzes Table -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Quiz Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="DataTable table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Quiz Title</th>
                                    <th>Category</th>
                                    <th>Total Attempts</th>
                                    <th>Completed</th>
                                    <th>Average Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mostAttemptedQuizzes as $quiz)
                                    <tr>
                                        <td><strong>{{ $quiz->title }}</strong></td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $quiz->category ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $quiz->total_attempts }}</td>
                                        <td>{{ $quiz->completed_attempts }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $quiz->avg_score ?? 0 }}%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>

    initDataTable('table.DataTable');

    // Prepare data for charts
    const avgScoresData = {!! json_encode($avgScoresDistribution) !!};
    const mostAttemptedData = {!! json_encode($mostAttemptedQuizzes) !!};
    const dropoffData = {!! json_encode($dropoffAnalysis) !!};

    // 1. Average Scores Over Time Chart
    if (document.getElementById('avgScoresChart')) {
        const ctx1 = document.getElementById('avgScoresChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: avgScoresData.map(d => new Date(d.date).toLocaleDateString()),
                datasets: [{
                    label: 'Average Score',
                    data: avgScoresData.map(d => d.avg_score),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    // 2. Most Attempted Quizzes Chart
    if (document.getElementById('mostAttemptedChart')) {
        const ctx2 = document.getElementById('mostAttemptedChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: mostAttemptedData.map(d => d.title),
                datasets: [{
                    label: 'Total Attempts',
                    data: mostAttemptedData.map(d => d.total_attempts),
                    backgroundColor: '#667eea',
                    borderColor: '#667eea',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // 3. Quiz Completion Rate Chart
    if (document.getElementById('completionRateChart')) {
        const ctx3 = document.getElementById('completionRateChart').getContext('2d');
        new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress'],
                datasets: [{
                    data: [
                        dropoffData.reduce((sum, d) => sum + d.completed_count, 0),
                        dropoffData.reduce((sum, d) => sum + d.in_progress_count, 0)
                    ],
                    backgroundColor: ['#38ef7d', '#f5576c'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }
</script>
@endsection
