@extends('admin.layouts.admin')

@section('title', 'Users')
@section('page_title', 'Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Users</h3>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table id="usersTable" class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Avg Score</th>
                    <th>Top Score</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                    <tr>
                        <td>{{ ++$index }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ number_format($user->avg_score, 2) }}%
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success">
                                {{ number_format($user->max_score, 2) }}%
                            </span>
                        </td>
                        <td>{{ $user->created_at->format(config('app.format.datetime')) }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-xs btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-xs btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-xs btn-danger" onclick="deleteUser({{ $user->id }})" title="Delete">
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
    initDataTable('#usersTable');

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user? All associated quiz attempts will also be deleted.')) {
            document.getElementById('deleteForm').action = '/admin/users/' + userId;
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection
