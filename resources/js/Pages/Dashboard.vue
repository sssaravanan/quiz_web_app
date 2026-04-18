<script setup>
import { ref, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import UserLayout from '@/Layouts/UserLayout.vue'

defineProps({
    quizzes: {
        type: Array,
        default: () => []
    },
    recentAttempts: {
        type: Array,
        default: () => []
    },
    leaderboard: {
        type: Array,
        default: () => []
    },
})

const stats = ref({
    totalAttempts: 0,
    averageScore: 0,
    totalQuizzes: 0,
})

onMounted(() => {
    // Calculate stats from recentAttempts
    if (Array.isArray(recentAttempts) && recentAttempts.length > 0) {
        stats.value.totalAttempts = recentAttempts.length
        const completedAttempts = recentAttempts.filter(a => a.status === 'completed')
        if (completedAttempts.length > 0) {
            stats.value.averageScore = (completedAttempts.reduce((sum, a) => sum + (a.score || 0), 0) / completedAttempts.length).toFixed(2)
        }
    }
})

const isResuming = ref(null)

const resumeQuizAttempt = (quizId) => {
    isResuming.value = quizId

    // Use Inertia router.visit() since controller returns Inertia::render()
    router.visit(`/attempt/${quizId}`, {
        method: 'get',
        onError: () => {
            alert('Failed to resume quiz. Please try again.')
        },
        onFinish: () => {
            isResuming.value = null
        },
    })
}

defineOptions({
    layout: UserLayout,
})
</script>

<template>
    <div>
        <!-- Page Title -->
        <div class="mb-4">
            <h1 class="h3 mb-1">Welcome to Quiz App</h1>
            <p class="text-muted">Test your knowledge with our interactive quizzes</p>
        </div>

        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Total Attempts</h6>
                        <h3 class="mb-0">{{ stats.totalAttempts }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Average Score</h6>
                        <h3 class="mb-0">{{ stats.averageScore }}%</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Available Quizzes</h6>
                        <h3 class="mb-0">{{ quizzes.length }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Your Profile</h6>
                        <Link href="/profile" class="btn btn-sm btn-light">
                            <i class="fas fa-user me-1"></i> View
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left: Available Quizzes -->
            <div class="col-lg-7 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Available Quizzes</h5>
                            <Link href="/quizzes" class="btn btn-sm btn-outline-primary">View All</Link>
                        </div>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div v-if="quizzes.length > 0" class="row">
                            <div v-for="quiz in quizzes.slice(0, 5)" :key="quiz.id" class="col-md-6 mb-3">
                                <div class="card border-1 bg-light h-100">
                                    <div class="card h-100 border-0 shadow-sm transition-hover">
                                        <div class="card-body d-flex flex-column p-4">
                                            <div class="mb-2">
                                                <span class="text-uppercase fw-bold text-primary smaller ls-wide">
                                                    <i class="bi bi-tag-fill me-1"></i> {{ quiz.category.name }}
                                                </span>
                                                <h5 class="card-title fw-bold mt-1 mb-3 text-dark">{{ quiz.title }}</h5>
                                            </div>

                                            <div class="mt-auto">
                                                <div class="mb-4 mt-auto">
                                                    <div class="d-flex align-items-center justify-content-between text-muted small bg-light p-2 rounded-3">
                                                        <span><i class="bi bi-card-list text-primary me-1"></i> {{ quiz.questions_count || 0 }} Qs</span>
                                                        <span :class="[
                                                            'badge px-3 py-2 rounded-pill border', 
                                                            quiz.difficulty === 'easy' ? 'bg-success-subtle text-success border-success' : 
                                                            quiz.difficulty === 'medium' ? 'bg-warning-subtle text-dark border-warning' : 
                                                            'bg-danger-subtle text-danger border-danger'
                                                        ]">
                                                            <i class="bi bi-bar-chart-fill me-1"></i>
                                                            {{ quiz.difficulty.charAt(0).toUpperCase() + quiz.difficulty.slice(1) }}
                                                        </span>
                                                        <span><i class="bi bi-clock text-primary me-1"></i> {{ quiz.time_limit }} Mins</span>
                                                    </div>
                                                </div>

                                                <Link 
                                                    :href="`/quiz/${quiz.id}/start`" 
                                                    class="btn btn-md btn-primary w-100 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-2">
                                                    Start Quiz
                                                    <i class="bi bi-play-fill fs-5"></i>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center text-muted py-5">
                            <i class="fas fa-book" style="font-size: 2.5rem;"></i>
                            <p class="mt-3">No quizzes available yet</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Recent Attempts & Leaderboard -->
            <div class="col-lg-5">
                <!-- Recent Attempts -->
                <div class="card mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Recent Attempts</h5>
                    </div>
                    <div class="card-body p-0">
                        <div v-if="recentAttempts.length > 0" class="list-group list-group-flush">
                            <div v-for="attempt in recentAttempts.slice(0, 5)" :key="attempt.id" class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ attempt.quiz.title }}</h6>
                                    <small class="text-muted">
                                        <span v-if="attempt.status === 'completed'" class="badge bg-success">
                                            {{ attempt.score }}%
                                        </span>
                                        <span v-else class="badge bg-warning text-dark">In Progress</span>
                                        <span class="ms-2">{{ new Date(attempt.created_at).toLocaleString() }}</span>
                                    </small>
                                </div>
                                <Link 
                                    v-if="attempt.status === 'completed'"
                                    :href="`/result/${attempt.id}`"
                                    class="btn btn-sm btn-outline-primary ms-2">
                                    View
                                </Link>
                                <button 
                                    v-else
                                    @click="() => resumeQuizAttempt(attempt.quiz.id)"
                                    :disabled="isResuming === attempt.quiz.id"
                                    class="btn btn-sm btn-outline-warning ms-2">
                                    <span v-if="isResuming === attempt.quiz.id">
                                        <span class="spinner-border spinner-border-sm me-1"></span>
                                        Resuming...
                                    </span>
                                    <span v-else>Resume</span>
                                </button>
                            </div>
                        </div>
                        <div v-else class="text-center text-muted p-4">
                            <i class="fas fa-history" style="font-size: 2rem;"></i>
                            <p class="mt-3 mb-0">No attempts yet</p>
                        </div>
                    </div>
                </div>

                <!-- Leaderboard Preview -->
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Top Scorers</h5>
                            <Link href="/leaderboard" class="btn btn-sm btn-outline-primary">View All</Link>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div v-if="leaderboard.length > 0" class="list-group list-group-flush">
                            <div v-for="(user, index) in leaderboard.slice(0, 5)" :key="user.id" class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <span class="badge bg-warning text-dark me-2">{{ index + 1 }}</span>
                                    <h6 class="mb-0">{{ user.name }}</h6>
                                </div>
                                <span class="badge bg-success">{{ user.avg_score || 0 }}%</span>
                            </div>
                        </div>
                        <div v-else class="text-center text-muted p-4">
                            <i class="fas fa-trophy" style="font-size: 2rem;"></i>
                            <p class="mt-3 mb-0">No leaderboard data yet</p>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</template>