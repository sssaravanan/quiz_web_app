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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-file-import me-2"></i> Import Questions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importForm">
                    <div class="mb-3">
                        <label for="importFile" class="form-label">Select XLSX File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="importFile" name="file" accept=".xlsx" required>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i> Only .xlsx files are supported (max 5MB)
                        </small>
                        <div id="fileError" class="invalid-feedback d-block mt-2" style="display: none;"></div>
                    </div>

                    <!-- File Info -->
                    <div id="fileInfo" style="display: none;" class="mb-3">
                        <div class="alert alert-info mb-0">
                            <small>
                                <strong>File:</strong> <span id="fileName"></span><br>
                                <strong>Size:</strong> <span id="fileSize"></span>
                            </small>
                        </div>
                    </div>

                    <!-- Template Link -->
                    <div class="mb-3">
                        <a href="{{ route('admin.questions.template') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download me-1"></i> Download Template
                        </a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="submitBtn" onclick="submitImport()">
                    <i class="fas fa-upload me-2"></i> Import
                </button>
            </div>
        </div>
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
<script>

    // File input change handler
    document.getElementById('importFile').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const fileError = document.getElementById('fileError');
        const fileInfo = document.getElementById('fileInfo');

        // Reset error
        fileError.style.display = 'none';
        fileInfo.style.display = 'none';

        if (!file) return;

        // Validate file type
        if (!file.name.endsWith('.xlsx')) {
            fileError.textContent = '❌ Only .xlsx files are allowed';
            fileError.style.display = 'block';
            e.target.value = '';
            return;
        }

        // Validate file size (5MB = 5120KB)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            fileError.textContent = `❌ File size exceeds 5MB limit (Current: ${(file.size / 1024 / 1024).toFixed(2)}MB)`;
            fileError.style.display = 'block';
            e.target.value = '';
            return;
        }

        // Show file info
        const sizeInMB = (file.size / 1024 / 1024).toFixed(2);
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = sizeInMB + ' MB';
        fileInfo.style.display = 'block';
    });

    // Submit import form
    function submitImport() {
        const fileInput = document.getElementById('importFile');
        const submitBtn = document.getElementById('submitBtn');
        const file = fileInput.files[0];

        if (!file) {
            alert('Please select a file');
            return;
        }

        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Uploading...';

        // Create FormData
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        // Submit
        fetch('{{ route("admin.questions.import") }}', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i> Import';

                if (data.success) {
                    // Show success message
                    const message = `✅ Import Successful!\n\nImported: ${data.imported}\nSkipped: ${data.skipped}`;
                    alert(message);

                    // Close modal and refresh page
                    const modal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
                    modal.hide();

                    // Reset form
                    document.getElementById('importForm').reset();
                    fileInput.value = '';
                    document.getElementById('fileInfo').style.display = 'none';

                    // Refresh table
                    setTimeout(() => location.reload(), 500);
                } else {
                    // Show error
                    document.getElementById('fileError').textContent = `❌ ${data.message}`;
                    document.getElementById('fileError').style.display = 'block';
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i> Import';
                document.getElementById('fileError').textContent = `❌ Error: ${error.message}`;
                document.getElementById('fileError').style.display = 'block';
            });
    }

    // Add CSRF token to head if not present
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (!token) {
            console.warn('CSRF token not found in meta tags');
        }
    }
</script>
@endsection
