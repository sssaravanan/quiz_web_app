<script setup>
import { Link } from '@inertiajs/vue3'
import UserLayout from '@/Layouts/UserLayout.vue'

defineProps({
    leaderboard: { type: Array, default: () => [] },
})

defineOptions({
    layout: UserLayout,
})
</script>

<template>
    <div>
        <!-- Header -->
        <div class="mb-4">
            <h1 class="h3 mb-1">Leaderboard</h1>
            <p class="text-muted">Top performers on our platform</p>
        </div>

        <!-- Leaderboard Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Rank</th>
                            <th>User</th>
                            <th>Average Score</th>
                            <th>Total Attempts</th>
                            <th>Best Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in leaderboard" :key="user.id">
                            <td>
                                <span class="badge bg-primary" style="font-size: 1.1rem; padding: 0.2rem 0.5rem;">
                                    {{ index + 1 }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ user.name }}</h6>
                                        <small class="text-muted">{{ user.email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h5 class="mb-0">{{ user.avg_score || 0 }}%</h5>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ user.total_attempts || 0 }}</span>
                            </td>
                            <td>
                                <h5 class="mb-0 text-success">{{ user.best_score || 0 }}%</h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="leaderboard.length === 0" class="card-body text-center text-muted py-5">
                <i class="fas fa-chart-bar" style="font-size: 3rem;"></i>
                <p class="mt-3">No leaderboard data yet</p>
            </div>
        </div>
    </div>
</template>
