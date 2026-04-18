@extends('admin.layouts.admin')

@section('title', 'Create Quiz')
@section('page_title', 'Create Quiz')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="form-section">
            <form action="{{ route('admin.quizzes.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Quiz Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                        id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-control @error('category_id') is-invalid @enderror" 
                        id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="difficulty" class="form-label">Difficulty <span class="text-danger">*</span></label>
                    <select class="form-control @error('difficulty') is-invalid @enderror" 
                        id="difficulty" name="difficulty" required>
                        <option value="">-- Select Difficulty --</option>
                        <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                    @error('difficulty')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="time_limit" class="form-label">Time Limit (minutes) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('time_limit') is-invalid @enderror" 
                        id="time_limit" name="time_limit" value="{{ old('time_limit', 30) }}" min="1" required>
                    @error('time_limit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" 
                        value="1" {{ old('is_published') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">
                        Published
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Quiz
                    </button>
                    <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
