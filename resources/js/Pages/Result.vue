<script setup>
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import UserLayout from '@/Layouts/UserLayout.vue'
import axios from 'axios'

const props = defineProps({
    attempt: Object,
})

const stats = computed(() => {
    const total = props.attempt.total_questions
    const correct = props.attempt.score ? Math.round((props.attempt.score / 100) * total) : 0
    const incorrect = total - correct
    const percentage = props.attempt.score || 0

    return {
        correct,
        incorrect,
        total,
        percentage,
    }
})

const retryQuiz = async () => {
    try {
        const response = await axios.post(`/api/quiz/${props.attempt.quiz_id}/start`)
        router.visit(`/attempt/${response.data.attempt_id}`)
    } catch (error) {
        console.error('Error starting new attempt:', error)
    }
}

const getResultColor = () => {
    const percentage = stats.value.percentage
    if (percentage >= 80) return 'success'
    if (percentage >= 60) return 'warning'
    return 'danger'
}

const getResultMessage = () => {
    const percentage = stats.value.percentage
    if (percentage >= 90) return 'Excellent! Outstanding performance!'
    if (percentage >= 80) return 'Great! Well done!'
    if (percentage >= 70) return 'Good! Keep practicing!'
    if (percentage >= 60) return 'Fair. Try again to improve!'
    return 'Needs improvement. Study and retry!'
}

defineOptions({
    layout: UserLayout,
})
</script>

<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                        <Link 
                            href="/dashboard"
                            class="btn btn-outline-secondary"
                        >
                            <i class="fas fa-home me-2"></i> Back to Dashboard
                        </Link>
                        <Link 
                            :href="`/review/${props.attempt.id}`"
                            class="btn btn-outline-primary"
                        >
                            <i class="fas fa-eye me-2"></i> Review Answers
                        </Link>
                        <button 
                            @click="retryQuiz"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-redo me-2"></i> Retry Quiz
                        </button>
                    </div>
                </div>
            </div>

            <!-- Result Card -->
            <div class="card mb-4">
                <div class="card-header bg-grey border-bottom d-flex justify-content-center align-items-center">
                    <span class="mb-0"><strong>Quiz Result</strong></span>
                </div>
                <div class="card-body text-center py-4">
                    <h2 class="mb-3 fs-4">{{ props.attempt.quiz.title }}</h2>
                    <h6 class="text-muted mb-4">Quiz Completed</h6>

                    <!-- Score Circle -->
                    <div :class="`bg-${getResultColor()}`" style="width: 125px; height: 125px; border-radius: 50%; margin: 0 auto 30px; display: flex; align-items: center; justify-content: center;">
                        <div class="text-white text-center">
                            <h1 class="mb-0">{{ stats.percentage }}%</h1>
                            <p class="mb-0">Score</p>
                        </div>
                    </div>

                    <!-- Result Message -->
                    <h4 :class="`text-${getResultColor()}`" class="mb-4">
                        {{ getResultMessage() }}
                    </h4>

                    <!-- Stats -->
                    <div class="row text-center mb-4">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <h5 class="mb-0 text-success">{{ stats.correct }}</h5>
                                <small class="text-success">Correct Answers</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <h5 class="mb-0 text-danger">{{ stats.incorrect }}</h5>
                                <small class="text-danger">Incorrect Answers</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <h5 class="mb-0 text-info">{{ stats.total }}</h5>
                                <small class="text-primary">Total Questions</small>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="alert alert-light mb-4">
                        <p class="mb-1">
                            <strong>Category:</strong>
                            <span class="text-uppercase fw-bold text-primary smaller ls-wide">
                                <i class="bi bi-tag-fill ms-3"></i>
                                {{ props.attempt.quiz.category.name }}
                            </span>
                        </p>
                        <p class="mb-1">
                            <strong>Difficulty:</strong> 
                            <span :class="[
                                'ms-2 badge',
                                props.attempt.quiz.difficulty === 'easy' ? 'bg-success' :
                                props.attempt.quiz.difficulty === 'medium' ? 'bg-warning' : 'bg-danger'
                            ]">
                                {{ props.attempt.quiz.difficulty.charAt(0).toUpperCase() + props.attempt.quiz.difficulty.slice(1) }}
                            </span>
                        </p>
                        <p class="mb-1">
                            <strong>Time Taken:</strong> {{ Math.round(props.attempt.time_taken / 60) }} mins
                        </p>
                        <p class="mb-0">
                            <strong>Completed:</strong> {{ new Date(props.attempt.completed_at).toLocaleString() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
