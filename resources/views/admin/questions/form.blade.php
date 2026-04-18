@extends('admin.layouts.admin')

@section('title', isset($question) ? 'Edit Question' : 'Create Question')
@section('page_title', isset($question) ? 'Edit Question' : 'Create Question')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="form-section">
            <form action="{{ isset($question) ? route('admin.questions.update', $question) : route('admin.questions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($question))
                    @method('PUT')
                @endif

                <h5 class="mt-3 mb-3"><strong>Basic Details</strong></h5>

                <div class="mb-3">
                    <label for="quiz_id" class="form-label">Quiz <span class="text-danger">*</span></label>
                    <select class="form-control @error('quiz_id') is-invalid @enderror" 
                        id="quiz_id" name="quiz_id" required>
                        <option value="">-- Select Quiz --</option>
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}" 
                                {{ old('quiz_id', $question->quiz_id ?? null) == $quiz->id ? 'selected' : '' }}>
                                {{ $quiz->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('quiz_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="question_text" class="form-label">Question Text <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('question_text') is-invalid @enderror" 
                        id="question_text" name="question_text" rows="3" required>{{ old('question_text', $question->question_text ?? '') }}</textarea>
                    @error('question_text')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="explanation" class="form-label">Explanation (Optional)</label>
                    <textarea class="form-control @error('explanation') is-invalid @enderror" 
                        id="explanation" name="explanation" rows="2">{{ old('explanation', $question->explanation ?? '') }}</textarea>
                    @error('explanation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <h5 class="mt-3 mb-3"><strong>Options</strong></h5>

                @php
                    $savedOptions = $options ?? [];
                    $correctOptionIndex = null;
                    foreach($savedOptions as $index => $opt) {
                        if($opt->is_correct) {
                            $correctOptionIndex = $index;
                            break;
                        }
                    }
                @endphp

                <div class="row mb-4">
                    @for($i = 0; $i < 4; $i++)
                        <div class="col-md-12 mb-3">
                            <label for="option_{{ $i }}" class="form-label">
                                Option {{ chr(65 + $i) }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('option_text.'.$i) is-invalid @enderror" 
                                    id="option_{{ $i }}" name="option_text[]" 
                                    value="{{ old('option_text.'.$i, $savedOptions[$i]->option_text ?? '') }}" required>
                                <div class="input-group-text">
                                    <input type="radio" name="correct_option" value="{{ $i }}" 
                                        class="form-check-input me-2"
                                        {{ old('correct_option') === (string)$i || $correctOptionIndex === $i ? 'checked' : '' }}>
                                    <label class="form-check-label" style="margin: 0; cursor: pointer;">
                                        Correct
                                    </label>
                                </div>
                            </div>
                            @error('option_text.'.$i)
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    @endfor
                </div>

                @error('option_text')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                @error('correct_option')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <hr>

                <h5 class="mt-3 mb-3"><strong>Attachment (Optional)</strong></h5>

                @if(isset($question) && $question->attachment)
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-file"></i> Current attachment: 
                        <a href="{{ asset('storage/' . $question->attachment->path) }}" target="_blank">
                            View File
                        </a>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="attachment" class="form-label">Upload Image or Video (JPG, PNG, GIF, MP4, WebM - Max 10MB)</label>
                    <input type="file" class="form-control @error('attachment') is-invalid @enderror" 
                        id="attachment" name="attachment" accept=".jpg,.jpeg,.png,.gif,.mp4,.webm">
                    @error('attachment')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ isset($question) ? 'Update Question' : 'Create Question' }}
                    </button>
                    <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
