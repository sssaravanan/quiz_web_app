@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Total Categories</h6>
                <h2>{{ $categoriesCount }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Total Quizzes</h6>
                <h2>{{ $quizzesCount }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Total Questions</h6>
                <h2>{{ $questionsCount }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">Total Attempts</h6>
                <h2>{{ $quizAttemptsCount }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Quizzes</h5>
            </div>
            <div class="card-body">
                <table class="DataTable table table-sm">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentQuizzes as $quiz)
                            <tr>
                                <td>{{ Str::limit($quiz->title, 30) }}</td>
                                <td>{{ $quiz->category->name }}</td>
                                <td>
                                    <span class="badge {{ $quiz->is_published ? 'bg-success' : 'bg-warning' }}">
                                        {{ $quiz->is_published ? 'Published' : 'Unpublished' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No quizzes yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quiz Attempts</h5>
            </div>
            <div class="card-body">
                <table class="DataTable table table-sm">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Quiz</th>
                            <th>Score</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttempts as $attempt)
                            <tr>
                                <td>{{ $attempt->user->name }}</td>
                                <td>{{ Str::limit($attempt->quiz->title, 20) }}</td>
                                <td>{{ $attempt->score ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $attempt->status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($attempt->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No attempts yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
    initDataTable('table.DataTable');
</script>
@endsection