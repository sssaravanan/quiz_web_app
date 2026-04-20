<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import { ref } from 'vue';

defineOptions({
    layout: UserLayout,
});

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const passwordInput = ref(null);

// Form for profile and password update
const form = useForm({
    name: user.name,
    email: user.email,
    password: '',
    password_confirmation: '',
});

const submitForm = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <Head title="Edit Profile" />

    <div class="mb-4">
        <Link href="/profile" class="btn btn-sm btn-secondary mb-3">
            <i class="fas fa-arrow-left me-2"></i> Back to Profile
        </Link>
        <h1 class="h3">Edit Profile</h1>
        <p class="text-muted">Update your profile information and password</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="submitForm" class="needs-validation">
                        <!-- Name Field -->
                        <div class="mb-3">
                            <InputLabel for="name" value="Full Name" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.name }"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Enter your full name"
                            />
                            <InputError :message="form.errors.name" class="invalid-feedback d-block" />
                        </div>

                        <!-- Email Field (Read-only) -->
                        <div class="mb-3">
                            <InputLabel for="email" value="Email Address" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="form-control"
                                readonly
                                disabled
                                placeholder="Your email address"
                            />
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i> Email cannot be changed
                            </small>
                        </div>

                        <!-- Password Section -->
                        <hr class="my-4" />

                        <h5 class="mb-3">Change Password</h5>
                        <p class="text-muted mb-3">Leave blank if you don't want to change your password</p>

                        <!-- New Password -->
                        <div class="mb-3">
                            <InputLabel for="password" value="New Password" />
                            <TextInput
                                id="password"
                                ref="passwordInput"
                                v-model="form.password"
                                type="password"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.password }"
                                autocomplete="new-password"
                                placeholder="Enter new password (optional)"
                            />
                            <InputError :message="form.errors.password" class="invalid-feedback d-block" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <InputLabel for="password_confirmation" value="Confirm Password" />
                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.password_confirmation }"
                                autocomplete="new-password"
                                placeholder="Confirm new password"
                            />
                            <InputError :message="form.errors.password_confirmation" class="invalid-feedback d-block" />
                        </div>

                        <!-- Buttons -->
                        <div class="mt-4 d-flex gap-2">
                            <PrimaryButton :disabled="form.processing" class="btn btn-primary">
                                <i v-if="!form.processing" class="fas fa-save me-2"></i>
                                <i v-else class="fas fa-spinner fa-spin me-2"></i>
                                {{ form.processing ? 'Saving...' : 'Save Changes' }}
                            </PrimaryButton>
                            <Link href="/profile" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </Link>
                        </div>

                        <!-- Success Message -->
                        <transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-if="form.recentlySuccessful" class="text-success mt-3">
                                <i class="fas fa-check-circle me-2"></i> Changes saved successfully!
                            </p>
                        </transition>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="alert alert-info mt-3">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle"></i> Password Requirements
                </h6>
                <small>
                    <ul class="mb-0 text-muted">
                        <li>Minimum 8 characters</li>
                        <li>Mix of uppercase and lowercase letters</li>
                        <li>At least one number</li>
                    </ul>
                </small>
            </div>
        </div>
    </div>
</template>

<style scoped>
.form-control:disabled,
.form-control[readonly] {
    background-color: #e9ecef;
    opacity: 1;
}
</style>
