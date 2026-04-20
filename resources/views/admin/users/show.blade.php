@extends('admin.layouts.admin')

@section('title', $user->name)
@section('page_title', 'User Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>{{ $user->name }}</h3>
        <small class="text-muted">User ID: #{{ $user->id }}</small>
    </div>
    <div>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i> Edit User
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>
</div>

<!-- Tabs -->
<div class="card">
    <ul class="nav nav-tabs" role="tablist" style="border-bottom: 1px solid #dee2e6;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">
                <i class="fas fa-user me-2"></i> Basic Details
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="attempts-tab" data-bs-toggle="tab" data-bs-target="#attempts" type="button" role="tab" aria-controls="attempts" aria-selected="false">
                <i class="fas fa-list me-2"></i> Quiz Attempts ({{ $attempts->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content p-4">
        <!-- Tab 1: Basic Details -->
        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 35%;"><strong>Name:</strong></td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Member Since:</strong></td>
                            <td>{{ $user->created_at->format(config('app.format.datetime')) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 35%;"><strong>Total Attempts:</strong></td>
                            <td><span class="badge bg-primary">{{ $attempts->count() }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Average Score:</strong></td>
                            <td><span class="badge bg-info">{{ number_format($avgScore, 2) }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Top Score:</strong></td>
                            <td><span class="badge bg-success">{{ number_format($maxScore, 2) }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Quiz Attempts -->
        <div class="tab-pane fade" id="attempts" role="tabpanel" aria-labelledby="attempts-tab">
            @if($attempts->count() > 0)
                <div class="table-responsive">
                    <table class="DataTable table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Quiz Name</th>
                                <th>Total Questions</th>
                                <th>Correct Answers</th>
                                <th>Score</th>
                                <th>Time Taken</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempts as $attempt)
                                <tr>
                                    <td>{{ Str::limit($attempt->quiz->title, 40) }}</td>
                                    <td>{{ $attempt->total_questions }}</td>
                                    <td>{{ $attempt->answers->where('is_correct', true)->count() }}</td>
                                    <td>{{ $attempt->score }}%</td>
                                    <td>{{ $attempt->time_taken }}</td>
                                    <td>{{ $attempt->completed_at->format(config('app.format.datetime')) }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.attempt.review', $attempt) }}" class="btn btn-xs btn-primary" title="Review">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No quiz attempts found.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
    initDataTable('table.DataTable');
</script>
@endsection