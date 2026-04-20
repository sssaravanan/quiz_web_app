<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import UserLayout from '@/Layouts/UserLayout.vue'

const props = defineProps({
    attempt: { type: Object, default: () => ({}) },
    questions: { type: Array, default: () => [] },
})

const getUserAnswer = (questionId) => {
    const answer = props.attempt.answers?.find(a => a.question_id === questionId)
    return answer ? answer.selected_option : null
}

const isAnswerCorrect = (question) => {
    const answer = getUserAnswer(question.id)
    return answer && answer.is_correct
}

const correctCount = props.questions.filter(q => isAnswerCorrect(q)).length
const incorrectCount = props.questions.length - correctCount
const percentage = props.attempt.score || 0

const getScoreColor = () => {
    if (percentage >= 80) return 'text-success'
    if (percentage >= 50) return 'text-warning'
    return 'text-danger'
}

const getScoreBg = () => {
    if (percentage >= 80) return 'bg-success'
    if (percentage >= 50) return 'bg-warning'
    return 'bg-danger'
}

defineOptions({
    layout: UserLayout,
})
</script>

<template>
    <div class="pb-5">

        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 fw-bold">Review Answers</h1>
                <p class="text-muted mb-0">{{ props.attempt.quiz?.title }}</p>
            </div>
            <Link href="/dashboard" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </Link>
        </div>

        <!-- Score Summary Card -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0 text-center">

                    <div class="col-md-4 p-4 border-end">
                        <p class="text-muted small mb-1 text-uppercase fw-semibold ls-wide">Overall Score</p>
                        <h2 :class="['fw-bold mb-1', getScoreColor()]">{{ percentage }}%</h2>
                        <div class="progress mt-2" style="height: 6px;">
                            <div
                                :class="['progress-bar rounded-pill', getScoreBg()]"
                                :style="{ width: percentage + '%' }"
                            ></div>
                        </div>
                    </div>

                    <div class="col-md-4 p-4 border-end">
                        <p class="text-muted small mb-1 text-uppercase fw-semibold">Correct</p>
                        <h2 class="fw-bold text-success mb-1">{{ correctCount }}</h2>
                        <p class="text-muted small mb-0">out of {{ props.questions.length }}</p>
                    </div>

                    <div class="col-md-4 p-4">
                        <p class="text-muted small mb-1 text-uppercase fw-semibold">Incorrect</p>
                        <h2 class="fw-bold text-danger mb-1">{{ incorrectCount }}</h2>
                        <p class="text-muted small mb-0">out of {{ props.questions.length }}</p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Questions -->
        <div
            v-for="(question, index) in props.questions"
            :key="question.id"
            class="card border-0 shadow-sm mb-3 rounded-4 overflow-hidden"
        >
            <!-- Question Header -->
            <div :class="['card-header border-0 px-4 py-3', isAnswerCorrect(question) ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10']">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div class="d-flex align-items-start gap-2">
                        <span class="badge bg-secondary mt-1">Q{{ index + 1 }}</span>
                        <p class="mb-0 fw-semibold">{{ question.question_text }}</p>
                    </div>
                    <span :class="['badge rounded-pill flex-shrink-0', isAnswerCorrect(question) ? 'bg-success' : 'bg-danger']">
                        <i :class="['fas', isAnswerCorrect(question) ? 'fa-check' : 'fa-times']"></i>
                        {{ isAnswerCorrect(question) ? 'Correct' : 'Incorrect' }}
                    </span>
                </div>
            </div>

            <!-- Options — always visible -->
            <div class="card-body px-4 pt-3 pb-2">
                <div class="row" v-if="question.attachment">
                    <div class="col-12 h-50 w-50">
                        <div class="rounded-3 p-3 mb-3">
                            <img :src="question.attachment.path_url" :alt="question.attachment.filename" class="img-fluid rounded-3" v-if="question.attachment.type === 'image'" />
                            <video controls class="w-100 rounded-3" v-else-if="question.attachment.type === 'video'">
                                <source :src="question.attachment.path_url" :type="question.attachment.path_url.endsWith('.mp4') ? 'video/mp4' : 'video/webm'">
                            </video>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div
                        v-for="option in question.options"
                        :key="option.id"
                        class="col-md-6"
                    >
                        <div :class="[
                            'p-3 border rounded-3 h-100 d-flex justify-content-between align-items-center gap-2',
                            getUserAnswer(question.id)?.id === option.id && option.is_correct
                                ? 'border-success bg-success bg-opacity-10'
                                : getUserAnswer(question.id)?.id === option.id && !option.is_correct
                                    ? 'border-danger bg-danger bg-opacity-10'
                                    : option.is_correct
                                        ? 'border-success bg-success bg-opacity-10'
                                        : 'border-secondary bg-light opacity-75'
                        ]">
                            <div class="d-flex align-items-center gap-2">
                                <!-- Icon indicator -->
                                <i :class="[
                                    'fas flex-shrink-0',
                                    getUserAnswer(question.id)?.id === option.id && option.is_correct ? 'fa-check-circle text-success' :
                                    getUserAnswer(question.id)?.id === option.id && !option.is_correct ? 'fa-times-circle text-danger' :
                                    option.is_correct ? 'fa-check-circle text-success' :
                                    'fa-circle text-secondary opacity-50'
                                ]"></i>
                                <p class="mb-0 small">{{ option.option_text }}</p>
                            </div>

                            <!-- Badge -->
                            <div class="flex-shrink-0">
                                <span v-if="getUserAnswer(question.id)?.id === option.id && option.is_correct" class="badge bg-success">Your answer ✓</span>
                                <span v-else-if="getUserAnswer(question.id)?.id === option.id" class="badge bg-danger">Your answer ✗</span>
                                <span v-else-if="option.is_correct" class="badge bg-success">Correct</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Explanation -->
                <div v-if="question.explanation" class="alert alert-info border-0 rounded-3 mt-3 mb-2 small">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Explanation:</strong> {{ question.explanation }}
                </div>
            </div>
        </div>

    </div>
</template>