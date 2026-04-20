@extends('admin.layouts.admin')

@section('title', 'Attempt Review - ' . $attempt->quiz->title)
@section('page_title', 'Attempt Review')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.show', $attempt->user) }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to User
    </a>
</div>

<!-- Summary Card -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title fs-4 text-primary">{{ $attempt->quiz->title }}</h4>
                <p class="text-muted mb-3">
                    <strong>User:</strong> {{ $attempt->user->name }} | 
                    <strong>Date:</strong> {{ $attempt->completed_at->format(config('app.format.datetime')) }} | 
                    <strong>Time Taken:</strong> {{ $attempt->time_taken }}
                </p>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h5 class="card-title text-muted">Overall Score</h5>
                                <h3 class="card-text text-primary font-bold">{{ $attempt->score }}%</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h5 class="card-title text-muted">Correct Answers</h5>
                                <h3 class="card-text text-primary font-bold">{{ $correctCount }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h5 class="card-title text-muted">Total Questions</h5>
                                <h3 class="card-text text-primary font-bold">{{ $attempt->total_questions }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Questions Review -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">Questions Review</h5>
    </div>
    <div class="card-body">
        @foreach($attempt->quiz->questions as $index => $question)
            @php
                $answer = $attempt->answers->firstWhere('question_id', $question->id);
                $isCorrect = $answer ? $answer->is_correct : false;
                $correctOption = $question->options->firstWhere('is_correct', true);
            @endphp
            <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="d-flex align-items-start gap-3">
                    <!-- Question Number -->
                    <div class="flex-shrink-0">
                        <span class="badge bg-secondary">Q{{ $loop->iteration }}</span>
                    </div>

                    <!-- Question Content -->
                    <div class="flex-grow-1">
                        <h6 class="mb-3 fw-semibold">{{ $question->question_text }}</h6>

                        <!-- Options -->
                        <div class="options-list">
                            @foreach($question->options as $option)
                                @php
                                    $isSelected = $answer && $answer->selected_option_id == $option->id;
                                    $isCorrectOption = $option->is_correct;
                                @endphp
                                <div class="card mb-2 {{ $isCorrectOption ? 'border-success bg-success bg-opacity-10' : '' }} {{ $isSelected && !$isCorrect ? 'border-danger bg-danger bg-opacity-10' : '' }}">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="flex-grow-1">
                                                <span>{{ $option->option_text }}</span>
                                            </div>
                                            <div class="flex-shrink-0">
                                                @if($isCorrectOption)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Correct Answer
                                                    </span>
                                                @endif

                                                @if($isSelected)
                                                    @if($isCorrect)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle"></i> Your Answer
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle"></i> Your Answer
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Explanation -->
                        @if($question->explanation)
                            <div class="alert alert-info mt-3 mb-0">
                                <strong><i class="fas fa-lightbulb"></i> Explanation:</strong>
                                <p class="mb-0 mt-2">{{ $question->explanation }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
