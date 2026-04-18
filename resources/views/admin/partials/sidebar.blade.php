<div class="sidebar">
    <div class="brand">
        <h5><i class="fas fa-graduation-cap"></i> Quiz Admin</h5>
    </div>

    <nav class="nav flex-column">
        <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-chart-line me-2"></i> Dashboard
        </a>

        <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
            <i class="fas fa-folder me-2"></i> Categories
        </a>

        <a class="nav-link {{ request()->is('admin/quizzes*') ? 'active' : '' }}" href="{{ route('admin.quizzes.index') }}">
            <i class="fas fa-book me-2"></i> Quizzes
        </a>

        <a class="nav-link {{ request()->is('admin/questions*') ? 'active' : '' }}" href="{{ route('admin.questions.index') }}">
            <i class="fas fa-question-circle me-2"></i> Questions
        </a>
    </nav>
</div>
