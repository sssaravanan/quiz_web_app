<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import UserLayout from '@/Layouts/UserLayout.vue'

const props = defineProps({
    quizzes: {
        type: Array,
        default: () => []
    },
    categories: {
        type: Array,
        default: () => []
    },
})

const searchQuery = ref('')
const selectedCategory = ref('')
const selectedDifficulty = ref('')

const filteredQuizzes = computed(() => {
    return props.quizzes.filter(quiz => {
        const matchSearch = quiz.title.toLowerCase().includes(searchQuery.value.toLowerCase())
        const matchCategory = !selectedCategory.value || quiz.category_id === parseInt(selectedCategory.value)
        const matchDifficulty = !selectedDifficulty.value || quiz.difficulty === selectedDifficulty.value
        return matchSearch && matchCategory && matchDifficulty
    })
})

defineOptions({
    layout: UserLayout,
})
</script>

<template>
    <div>
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 mb-1 fw-bold text-dark">Explore Quizzes</h1>
                <p class="text-muted mb-0">Test your knowledge across various topics</p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                    {{ filteredQuizzes.length }} Quizzes Available
                </span>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-5 rounded-4">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-2">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                class="form-control border-start-0 ps-0" 
                                placeholder="Quiz title..."
                            >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-2">Category</label>
                        <select v-model="selectedCategory" class="form-select shadow-none">
                            <option value="">All Categories</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-2">Difficulty</label>
                        <select v-model="selectedDifficulty" class="form-select shadow-none">
                            <option value="">All Difficulties</option>
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="filteredQuizzes.length > 0" class="row g-4">
            <div v-for="quiz in filteredQuizzes" :key="quiz.id" class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm transition-hover rounded-4 overflow-hidden">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-3">
                            <span class="text-primary smaller fw-bold text-uppercase ls-wide">
                                <i class="bi bi-bookmark-fill me-1"></i> {{ quiz.category.name }}
                            </span>
                            <h5 class="card-title fw-bold mt-1 text-dark">{{ quiz.title }}</h5>
                        </div>
                        
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
                            class="btn btn-primary w-100 py-2 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                            Start Quiz
                            <i class="bi bi-play-fill fs-5"></i>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-5 bg-white rounded-4 shadow-sm">
            <div class="mb-3">
                <i class="bi bi-emoji-frown text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="fw-bold">No matches found</h4>
            <p class="text-muted">Adjust your search or filters to find what you're looking for.</p>
            <button @click="searchQuery = ''; selectedCategory = ''; selectedDifficulty = ''" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                Clear All Filters
            </button>
        </div>
    </div>
</template>
