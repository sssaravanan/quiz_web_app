<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import UserLayout from '@/Layouts/UserLayout.vue'
import axios from 'axios'

const props = defineProps({
    attempt: Object,
    questions: Array,
})

const currentIndex = ref(0)
const answers = ref({})
const flagged = ref(new Set())
const timeRemaining = ref(0)
const timerInterval = ref(null)
const isSubmitting = ref(false)
const isSaving = ref(false)

const currentQuestion = computed(() => {
    return props.questions[currentIndex.value]
})

const progress = computed(() => {
    return {
        answered: Object.keys(answers.value).length,
        flagged: flagged.value.size,
        total: props.questions.length,
    }
})

const canNavigatePrevious = computed(() => currentIndex.value > 0)
const canNavigateNext = computed(() => currentIndex.value < props.questions.length - 1)

onMounted(() => {
    // Load saved answers
    if (props.attempt.answers && props.attempt.answers.length > 0) {
        props.attempt.answers.forEach(answer => {
            answers.value[answer.question_id] = answer.selected_option_id
            if (answer.is_flagged) {
                flagged.value.add(answer.question_id)
            }
        })
    }

    // Calculate time remaining
    const startedAt = new Date(props.attempt.started_at)
    const timeLimit = props.attempt.quiz.time_limit * 60 * 1000 // convert to milliseconds
    const elapsed = Date.now() - startedAt.getTime()
    timeRemaining.value = Math.max(0, Math.floor((timeLimit - elapsed) / 1000))

    // Start timer
    timerInterval.value = setInterval(() => {
        timeRemaining.value--
        if (timeRemaining.value <= 0) {
            clearInterval(timerInterval.value)
            submitQuiz()
        }
    }, 1000)
})

onUnmounted(() => {
    if (timerInterval.value) {
        clearInterval(timerInterval.value)
    }
})

const formatTime = (seconds) => {
    const mins = Math.floor(seconds / 60)
    const secs = seconds % 60
    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`
}

const selectOption = async (optionId) => {
    answers.value[currentQuestion.value.id] = optionId
    await saveAnswer(optionId)
}

const saveAnswer = async (optionId) => {
    isSaving.value = true
    try {
        await axios.post('/api/attempt/save-answer', {
            attempt_id: props.attempt.id,
            question_id: currentQuestion.value.id,
            selected_option_id: optionId,
            is_flagged: flagged.value.has(currentQuestion.value.id),
        })
    } catch (error) {
        console.error('Error saving answer:', error)
    } finally {
        isSaving.value = false
    }
}

const toggleFlag = async () => {
    if (flagged.value.has(currentQuestion.value.id)) {
        flagged.value.delete(currentQuestion.value.id)
    } else {
        flagged.value.add(currentQuestion.value.id)
    }
    
    await saveAnswer(answers.value[currentQuestion.value.id] || null)
}

const nextQuestion = () => {
    if (canNavigateNext.value) {
        currentIndex.value++
    }
}

const previousQuestion = () => {
    if (canNavigatePrevious.value) {
        currentIndex.value--
    }
}

const goToQuestion = (index) => {
    currentIndex.value = index
}

const submitQuiz = async () => {
    if (isSubmitting.value) return

    isSubmitting.value = true
    try {
        const response = await axios.post('/api/attempt/submit', {
            attempt_id: props.attempt.id,
        })

        router.visit(`/result/${response.data.attempt_id}`)
    } catch (error) {
        console.error('Error submitting quiz:', error)
        alert('Error submitting quiz. Please try again.')
        isSubmitting.value = false
    }
}

defineOptions({
    layout: UserLayout,
})
</script>

<template>
    <div>
        <div class="row h-100">
            <!-- Main Question Area -->
            <div class="col-lg-9">
                <div class="card h-100">
                    <!-- Header -->
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">{{ props.attempt.quiz.title }}</h5>
                                <small class="text-muted">Question {{ currentIndex + 1 }} of {{ props.questions.length }}</small>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-0">Time Remaining</h6>
                                <h4 class="mb-0" :class="{ 'text-danger': timeRemaining < 300 }">
                                    {{ formatTime(timeRemaining) }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Question -->
                    <div class="card-body">
                        <!-- Question Text -->
                        <h5 class="mb-4">{{ currentQuestion.question_text }}</h5>

                        <!-- Attachment -->
                        <div v-if="currentQuestion.attachment" class="mb-4">
                            <img 
                                v-if="currentQuestion.attachment.type === 'image'"
                                :src="`/storage/${currentQuestion.attachment.path}`"
                                class="img-fluid rounded"
                                style="max-height: 300px"
                            >
                            <video 
                                v-else
                                controls
                                class="w-100 rounded"
                                style="max-height: 300px"
                            >
                                <source :src="`/storage/${currentQuestion.attachment.path}`">
                            </video>
                        </div>

                        <!-- Options -->
                        <div class="mb-4">
                            <label v-for="option in currentQuestion.options" :key="option.id" class="btn btn-outline-primary w-100 mb-2" style="text-align: left;">
                                <input 
                                    type="radio"
                                    name="option"
                                    :value="option.id"
                                    :checked="answers[currentQuestion.id] === option.id"
                                    @change="selectOption(option.id)"
                                    class="me-3"
                                >
                                {{ option.option_text }}
                            </label>
                        </div>

                        <!-- Flag Button -->
                        <div class="mb-4">
                            <button 
                                @click="toggleFlag"
                                :class="['btn', flagged.has(currentQuestion.id) ? 'btn-warning' : 'btn-outline-warning']"
                            >
                                <i class="fas fa-flag me-2"></i>
                                {{ flagged.has(currentQuestion.id) ? 'Flagged' : 'Flag Question' }}
                            </button>
                            <small class="d-block mt-2 text-muted" v-if="isSaving">Saving...</small>
                        </div>
                    </div>

                    <!-- Footer Navigation -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <button 
                                @click="previousQuestion"
                                :disabled="!canNavigatePrevious"
                                class="btn btn-outline-secondary"
                            >
                                <i class="fas fa-chevron-left me-2"></i> Previous
                            </button>

                            <div class="flex-grow-1 mx-3 text-center">
                                <small class="text-muted">
                                    Answered: {{ progress.answered }} / {{ progress.total }}
                                    <span v-if="progress.flagged > 0" class="ms-3">
                                        Flagged: {{ progress.flagged }}
                                    </span>
                                </small>
                            </div>

                            <button 
                                v-if="canNavigateNext"
                                @click="nextQuestion"
                                class="btn btn-outline-secondary"
                            >
                                Next <i class="fas fa-chevron-right ms-2"></i>
                            </button>
                            <button 
                                v-else
                                @click="submitQuiz"
                                :disabled="isSubmitting"
                                class="btn btn-success"
                            >
                                <i class="fas fa-check me-2"></i> Submit Quiz
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Question Navigation -->
            <div class="col-lg-3">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0">Questions</h6>
                    </div>
                    <div class="card-body p-2">
                        <div class="d-flex flex-wrap gap-2">
                            <button 
                                v-for="(q, index) in props.questions"
                                :key="q.id"
                                @click="goToQuestion(index)"
                                :class="[
                                    'btn btn-sm flex-grow-1',
                                    currentIndex === index ? 'btn-primary' : 'btn-outline-secondary',
                                    answers[q.id] ? 'active' : '',
                                    flagged.has(q.id) ? 'btn-warning' : ''
                                ]"
                                :disabled="isSubmitting"
                                style="padding: 0.4rem; font-size: 0.8rem;"
                            >
                                {{ index + 1 }}
                                <i v-if="flagged.has(q.id)" class="fas fa-flag ms-1" style="font-size: 0.7rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
