@extends('admin.layouts.admin')

@section('title', 'Questions')
@section('page_title', 'Questions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Questions</h3>
    <a href="{{ route('admin.questions.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add Question
    </a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table id="questionsTable" class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Quiz</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $index => $question)
                    <tr>
                        <td>{{ ++$index }}</td>
                        <td>{{ Str::limit($question->question_text, 50) }}</td>
                        <td>{{ $question->quiz->title }}</td>
                        <td>{{ $question->created_at->format(config('app.format.datetime')) }}</td>
                        <td>
                            <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-xs btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-xs btn-danger" onclick="deleteQuestion({{ $question->id }})">
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
    initDataTable('#questionsTable');

    function deleteQuestion(questionId) {
        if (confirm('Are you sure you want to delete this question?')) {
            document.getElementById('deleteForm').action = '/admin/questions/' + questionId;
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection
