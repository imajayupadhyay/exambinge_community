<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login" />

        <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-orange text-center mb-6">Welcome Back</h2>

            <!-- Google Login -->
            <a
                href="/auth/google"
                class="w-full text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded mb-6 block"
            >
                Continue with Google
            </a>

            <div class="text-center text-sm text-gray-500 mb-4">or sign in with email</div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between text-sm text-gray-600">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            v-model="form.remember"
                            class="rounded border-gray-300 text-orange shadow-sm focus:ring-orange"
                        />
                        <span class="ml-2">Remember me</span>
                    </label>

                    <Link
                        v-if="route().has('password.request')"
                        :href="route('password.request')"
                        class="text-orange hover:underline"
                    >
                        Forgot your password?
                    </Link>
                </div>

                <PrimaryButton
                    class="w-full bg-orange hover:bg-orange-dark text-white font-bold py-2 px-4 rounded"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log In
                </PrimaryButton>
            </form>

            <div class="mt-4 text-center text-sm text-gray-600">
                Don’t have an account?
                <Link :href="route('register')" class="text-orange hover:underline">Sign up</Link>
            </div>
        </div>
    </GuestLayout>
</template>
