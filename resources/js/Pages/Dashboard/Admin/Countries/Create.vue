<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({
    name: '',
});

const slugPreview = computed(() => {
    const raw = form.name
        .toLowerCase()
        .trim()
        .replace(/[^\p{L}\p{N}\s-]/gu, '')
        .replace(/[\s_]+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');

    return raw || '...';
});

function submit() {
    form.post('/admin/countries', {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="Add country" />

        <div class="mb-6">
            <Link href="/admin/countries" class="text-sm font-medium text-sky-700 hover:text-sky-900"> ← Countries </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Add country</h1>
            <p class="mt-1 text-sm text-slate-600">The slug is generated automatically from the name.</p>
        </div>

        <form class="max-w-lg space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    name="name"
                    required
                    class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                    :class="{ 'border-red-300': form.errors.name }"
                />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                <span class="font-medium text-slate-700">Slug preview:</span>
                <code class="ml-2 font-mono text-xs text-slate-800">{{ slugPreview }}</code>
            </div>

            <div class="flex gap-3 pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save
                </button>
                <Link
                    href="/admin/countries"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
