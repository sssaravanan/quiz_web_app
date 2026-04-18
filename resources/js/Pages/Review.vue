<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import UserLayout from '@/Layouts/UserLayout.vue'

const props = defineProps({
    attempt: { type: Object, default: () => ({}) },
    questions: { type: Array, default: () => [] },
})

const expandedQuestion = ref(null)

const toggleExpand = (questionId) => {
    expandedQuestion.value = expandedQuestion.value === questionId ? null : questionId
}

const getUserAnswer = (questionId) => {
    const answer = props.attempt.answers?.find(a => a.question_id === questionId)
    return answer ? answer.selected_option : null
}

const isAnswerCorrect = (question) => {
    const answer = getUserAnswer(question.id)
    return answer && answer.is_correct
}

defineOptions({
    layout: UserLayout,
})
</script>

<template>
    <div>
        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between">
            <div>
                <h1 class="h3 mb-1">Review Answers</h1>
                <p class="text-muted">{{ props.attempt.quiz?.title }}</p>
            </div>
            <div>
                <Link href="/dashboard" class="btn btn-sm btn-outline-secondary ms-3">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </Link>
            </div>
        </div>

        <!-- Score Summary -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h6 class="text-muted">Overall Score</h6>
                        <h3 class="mb-0">{{ props.attempt.score }}%</h3>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Correct</h6>
                        <h3 class="text-success mb-0">
                            {{ props.questions.filter(q => isAnswerCorrect(q)).length }}
                        </h3>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Total</h6>
                        <h3 class="mb-0">{{ props.questions.length }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions List -->
        <div v-for="(question, index) in props.questions" :key="question.id" class="card mb-3">
            <div 
                @click="toggleExpand(question.id)"
                class="card-header bg-light"
                style="cursor: pointer;"
            >
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <span class="badge bg-secondary me-2">Q{{ index + 1 }}</span>
                        {{ question.question_text }}
                    </h6>
                    <i :class="['fas', isAnswerCorrect(question) ? 'fa-check text-success' : 'fa-times text-danger']"></i>
                </div>
            </div>

            <div v-if="expandedQuestion === question.id" class="card-body">
                <p class="mb-3"><strong>Your Answer:</strong></p>
                <div class="mb-3">
                    <div v-for="option in question.options" :key="option.id" class="mb-2">
                        <div :class="['p-3 border rounded',
                            getUserAnswer(question.id)?.id === option.id && option.is_correct ? 'border-success bg-success bg-opacity-10' : '',
                            getUserAnswer(question.id)?.id === option.id && !option.is_correct ? 'border-danger bg-danger bg-opacity-10' : '',
                            getUserAnswer(question.id)?.id !== option.id && option.is_correct ? 'border-success bg-success bg-opacity-10' : '',
                            getUserAnswer(question.id)?.id !== option.id && !option.is_correct ? 'border-secondary' : ''
                        ]">
                            <div class="d-flex justify-content-between">
                                <p class="mb-0">{{ option.option_text }}</p>
                                <span v-if="getUserAnswer(question.id)?.id === option.id && option.is_correct" class="badge bg-success ms-2">Your answer ✓</span>
                                <span v-else-if="getUserAnswer(question.id)?.id === option.id" class="badge bg-danger ms-2">Your answer ✗</span>
                                <span v-else-if="option.is_correct" class="badge bg-success ms-2">Correct</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="question.explanation" class="alert alert-info mt-3">
                    <strong>Explanation:</strong> {{ question.explanation }}
                </div>
            </div>
        </div>
    </div>
</template>
