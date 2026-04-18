<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import UserLayout from '@/Layouts/UserLayout.vue'

defineProps({
    attempts: {
        type: Object,
        default: () => ({})
    },
})

const isResuming = ref(null)

const resumeQuizAttempt = (attemptId) => {
    isResuming.value = attemptId

    router.visit(`/attempt/${attemptId}`, {
        method: 'get',
        onError: () => {
            alert('Failed to resume quiz. Please try again.')
        },
        onFinish: () => {
            isResuming.value = null
        },
    })
}

const formatDate = (date) => {
    if (!date) return '-'
    return new Date(date).toLocaleString()
}

const goToPage = (page) => {
    router.visit(`/attempts?page=${page}`, {
        method: 'get',
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
            <h1 class="h3 mb-1">My Attempts</h1>
            <p class="text-muted">View all your quiz attempts and track your progress</p>
        </div>

        <!-- Attempts Table -->
        <div class="card">
            <div class="card-body">
                <div v-if="attempts.data && attempts.data.length > 0" class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Quiz Name</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Started At</th>
                                <th>Completed At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="attempt in attempts.data" :key="attempt.id">
                                <td class="fw-semibold">{{ attempt.quiz.title }}</td>
                                <td>
                                    <span v-if="attempt.status === 'completed'" class="badge bg-success">
                                        {{ attempt.score }}%
                                    </span>
                                    <span v-else class="badge bg-secondary">
                                        -
                                    </span>
                                </td>
                                <td>
                                    <span v-if="attempt.status === 'completed'" class="badge bg-success">
                                        Completed
                                    </span>
                                    <span v-else class="badge bg-warning text-dark">
                                        In Progress
                                    </span>
                                </td>
                                <td>
                                    <small>{{ formatDate(attempt.started_at) }}</small>
                                </td>
                                <td>
                                    <small>{{ formatDate(attempt.completed_at) }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-xs" role="group">
                                        <Link 
                                            v-if="attempt.status === 'completed'"
                                            :href="`/result/${attempt.id}`"
                                            class="btn btn-xs btn-outline-primary"
                                        >
                                            <i class="fas fa-eye me-1"></i>View
                                        </Link>
                                        <button 
                                            v-else
                                            @click="() => resumeQuizAttempt(attempt.id)"
                                            :disabled="isResuming === attempt.id"
                                            class="btn btn-xs btn-outline-warning"
                                        >
                                            <span v-if="isResuming === attempt.id">
                                                <span class="spinner-border spinner-border-sm me-1"></span>
                                                Resuming...
                                            </span>
                                            <span v-else>
                                                <i class="fas fa-play me-1"></i>Resume
                                            </span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-5">
                    <i class="fas fa-history" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">No attempts yet. Start a quiz to see your attempts here.</p>
                    <Link href="/quizzes" class="btn btn-primary mt-2">
                        <i class="fas fa-book me-1"></i>Browse Quizzes
                    </Link>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="attempts.data && attempts.data.length > 0" class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <!-- Previous Button -->
                    <li :class="['page-item', { disabled: !attempts.prev_page_url }]">
                        <button 
                            v-if="attempts.prev_page_url"
                            @click="() => goToPage(attempts.current_page - 1)"
                            class="page-link"
                        >
                            <i class="fas fa-chevron-left me-1"></i>Previous
                        </button>
                        <span v-else class="page-link">
                            <i class="fas fa-chevron-left me-1"></i>Previous
                        </span>
                    </li>

                    <!-- Page Numbers -->
                    <li 
                        v-for="page in attempts.last_page" 
                        :key="page"
                        :class="['page-item', { active: page === attempts.current_page }]"
                    >
                        <button 
                            v-if="page !== attempts.current_page"
                            @click="() => goToPage(page)"
                            class="page-link"
                        >
                            {{ page }}
                        </button>
                        <span v-else class="page-link">
                            {{ page }}
                        </span>
                    </li>

                    <!-- Next Button -->
                    <li :class="['page-item', { disabled: !attempts.next_page_url }]">
                        <button 
                            v-if="attempts.next_page_url"
                            @click="() => goToPage(attempts.current_page + 1)"
                            class="page-link"
                        >
                            Next<i class="fas fa-chevron-right ms-1"></i>
                        </button>
                        <span v-else class="page-link">
                            Next<i class="fas fa-chevron-right ms-1"></i>
                        </span>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<style scoped>
.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
