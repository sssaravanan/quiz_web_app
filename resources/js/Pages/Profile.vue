<script setup>
import { Link } from '@inertiajs/vue3'
import UserLayout from '@/Layouts/UserLayout.vue'

defineProps({
    user: { type: Object, default: () => ({}) },
    stats: { type: Object, default: () => ({}) },
})

defineOptions({
    layout: UserLayout,
})
</script>

<template>
    <div>
        <!-- Header -->
        <div class="mb-4">
            <h1 class="h3 mb-1">My Profile</h1>
        </div>

        <div class="row">
            <!-- Profile Card -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="bg-primary rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-user" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="card-title">{{ user.name }}</h5>
                        <p class="text-muted">{{ user.email }}</p>
                        <p class="small text-muted">Joined {{ new Date(user.created_at).toLocaleDateString() }}</p>
                        <Link href="/profile/edit" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-edit me-2"></i> Edit Profile
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="col-md-8">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-2">Total Attempts</h6>
                                <h3 class="mb-0">{{ stats.total_attempts }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-2">Average Score</h6>
                                <h3 class="mb-0">{{ stats.average_score }}%</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-2">Best Score</h6>
                                <h3 class="mb-0">{{ stats.best_score }}%</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-2">Completed Quizzes</h6>
                                <h3 class="mb-0">{{ stats.completed_quizzes }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Summary -->
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Performance Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Correct Answers</h6>
                                    <h5 class="text-success mb-0">{{ stats.total_correct || 0 }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <i class="fas fa-times-circle text-danger" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Wrong Answers</h6>
                                    <h5 class="text-danger mb-0">{{ stats.total_wrong || 0 }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <i class="fas fa-hourglass-end text-warning" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">In Progress</h6>
                                    <h5 class="text-warning mb-0">{{ stats.in_progress || 0 }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2 d-md-flex">
                            <Link href="/dashboard" class="btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i> Go to Dashboard
                            </Link>
                            <Link href="/quizzes" class="btn btn-outline-primary">
                                <i class="fas fa-book me-2"></i> Browse Quizzes
                            </Link>
                            <Link href="/leaderboard" class="btn btn-outline-primary">
                                <i class="fas fa-chart-bar me-2"></i> View Leaderboard
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
