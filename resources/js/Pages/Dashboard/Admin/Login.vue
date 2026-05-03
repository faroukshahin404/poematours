<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const status = computed(() => page.props.flash?.status ?? null);

const form = useForm({
email: '',
password: '',
remember: false,
});

const processing = computed(() => form.processing);

function submit() {
form.post('/admin/login', {
    preserveScroll: true,
    onFinish: () => form.reset('password'),
});
}
</script>

<template>
<div
    class="flex min-h-screen flex-col justify-center bg-gradient-to-b from-slate-50 via-white to-slate-100 px-4 py-12 sm:px-6 lg:px-8"
>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="text-center text-2xl font-semibold tracking-tight text-slate-900">
            Poema Tours
        </h1>
        <p class="mt-2 text-center text-sm text-slate-600">Admin sign in</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div
            v-if="status"
            class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-center text-sm text-emerald-800"
            role="status"
        >
            {{ status }}
        </div>

        <form
            class="space-y-6 rounded-2xl border border-slate-200/80 bg-white p-8 shadow-lg shadow-slate-200/50 ring-1 ring-slate-100"
            @submit.prevent="submit"
        >
            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                    Email
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    name="email"
                    autocomplete="username"
                    required
                    class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                    :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500/20': form.errors.email }"
                    placeholder="you@example.com"
                />
                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600" role="alert">
                    {{ form.errors.email }}
                </p>
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
                    Password
                </label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    required
                    class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                />
            </div>

            <div class="flex items-center justify-between gap-4">
                <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                    <input
                        v-model="form.remember"
                        type="checkbox"
                        name="remember"
                        class="size-4 rounded border-slate-300 bg-white text-sky-600 focus:ring-sky-500/30"
                    />
                    Remember me
                </label>
            </div>

            <button
                type="submit"
                class="flex w-full justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="processing"
            >
                <span v-if="processing">Signing in…</span>
                <span v-else>Sign in</span>
            </button>
        </form>

        <p class="mt-8 text-center text-xs text-slate-500">
            <a href="/" class="text-sky-700 underline-offset-4 hover:text-sky-800 hover:underline">
                Back to site
            </a>
        </p>
    </div>
    </div>
</template>
