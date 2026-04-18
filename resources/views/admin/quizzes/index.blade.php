@extends('admin.layouts.admin')

@section('title', 'Quizzes')
@section('page_title', 'Quizzes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Quizzes</h3>
    <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add Quiz
    </a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table id="quizzesTable" class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Difficulty</th>
                    <th>Time Limit</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $index => $quiz)
                    <tr>
                        <td>{{ ++$index }}</td>
                        <td>{{ Str::limit($quiz->title, 40) }}</td>
                        <td>{{ $quiz->category->name }}</td>
                        <td>
                            @if($quiz->difficulty == 'easy')
                                <span class="badge bg-success">Easy</span>
                            @elseif($quiz->difficulty == 'medium')
                                <span class="badge bg-warning">Medium</span>
                            @else
                                <span class="badge bg-danger">Hard</span>
                            @endif
                        </td>
                        <td>{{ $quiz->time_limit }} min</td>
                        <td>
                            <span class="badge {{ $quiz->is_published ? 'bg-success' : 'bg-warning' }}">
                                {{ $quiz->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td>{{ $quiz->created_at->format(config('app.format.datetime')) }}</td>
                        <td>
                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-xs btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-xs btn-danger" onclick="deleteQuiz({{ $quiz->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('extra_js')
<script>
    initDataTable('#quizzesTable');

    function deleteQuiz(quizId) {
        if (confirm('Are you sure you want to delete this quiz and all its questions?')) {
            document.getElementById('deleteForm').action = '/admin/quizzes/' + quizId;
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection
