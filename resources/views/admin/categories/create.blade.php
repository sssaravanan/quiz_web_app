@extends('admin.layouts.admin')

@section('title', 'Create Category')
@section('page_title', 'Create Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="form-section">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                        id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
