<script setup>
import { computed, ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const page = usePage()
const showDropdown = ref(false)

const isActive = (route) => {
    return page.url.startsWith(route)
}

const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value
}

const closeDropdown = () => {
    showDropdown.value = false
}

const logout = () => {
    router.post('/logout', {}, {
        onFinish: () => router.visit('/'),
    })
}
</script>

<template>
    <div class="d-flex vh-100">
        <!-- Sidebar -->
        <div class="bg-dark text-white" style="width: 250px; overflow-y: auto;">
            <div class="p-4">
                <Link href="/dashboard" class="text-decoration-none text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Quiz App
                    </h5>
                </Link>
            </div>

            <nav class="nav flex-column px-3">
                <Link 
                    href="/dashboard"
                    :class="['nav-link', { active: isActive('/dashboard') }]"
                >
                    <i class="fas fa-home me-2"></i>Dashboard
                </Link>

                <Link 
                    href="/quizzes"
                    :class="['nav-link', { active: isActive('/quizzes') }]"
                >
                    <i class="fas fa-book me-2"></i>Quizzes
                </Link>

                <Link 
                    href="/attempts"
                    :class="['nav-link', { active: isActive('/attempts') }]"
                >
                    <i class="fas fa-history me-2"></i>Attempts
                </Link>

                <Link 
                    href="/leaderboard"
                    :class="['nav-link', { active: isActive('/leaderboard') }]"
                >
                    <i class="fas fa-chart-bar me-2"></i>Leaderboard
                </Link>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 d-flex flex-column">
            <!-- Topbar -->
            <div class="bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-muted">Quiz Platform</h6>
                
                <div class="position-relative" style="z-index: 9999;">
                    <button 
                        @click="toggleDropdown"
                        class="btn btn-sm btn-light dropdown-toggle" 
                        type="button"
                        :aria-expanded="showDropdown"
                    >
                        <i class="fas fa-user me-2"></i>{{ $page.props.auth.user.name }}
                    </button>
                    <ul 
                        v-show="showDropdown"
                        class="dropdown-menu dropdown-menu-end show"
                        style="position: absolute; top: 100%; right: 0; z-index: 1000;"
                    >
                        <li>
                            <Link 
                                href="/profile" 
                                class="dropdown-item"
                                @click="closeDropdown"
                            >
                                <i class="fas fa-user me-2"></i>Profile
                            </Link>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button 
                                @click="() => { logout(); closeDropdown(); }"
                                class="dropdown-item text-danger" 
                                style="border: none; background: none; cursor: pointer; width: 100%; text-align: left;"
                            >
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-grow-1 overflow-auto bg-light">
                <div class="container-fluid p-4">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.nav-link {
    color: #ccc !important;
    padding: 0.75rem 1rem;
    border-left: 3px solid transparent;
    transition: all 0.3s;
}

.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: white !important;
}

.nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    border-left-color: #0d6efd;
    color: white !important;
}

.vh-100 {
    height: 100vh;
}

.flex-grow-1 {
    flex: 1;
}

.d-flex {
    display: flex;
}

.flex-column {
    flex-direction: column;
}
</style>
