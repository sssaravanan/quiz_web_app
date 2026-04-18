@extends('admin.layouts.admin')

@section('title', 'Categories')
@section('page_title', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Categories</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add Category
    </a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table id="categoriesTable" class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $index => $category)
                    <tr>
                        <td>{{ ++$index }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->created_at->format(config('app.format.datetime')) }}</td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-xs btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-xs btn-danger" onclick="deleteCategory({{ $category->id }})">
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
    initDataTable('#categoriesTable');

    function deleteCategory(categoryId) {
        if (confirm('Are you sure you want to delete this category?')) {
            document.getElementById('deleteForm').action = '/admin/categories/' + categoryId;
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection
