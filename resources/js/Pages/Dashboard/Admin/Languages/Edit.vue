<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    language: {
        type: Object,
        required: true,
    },
    languageCount: {
        type: Number,
        required: true,
    },
});

const form = useForm({
    name: props.language.name,
    is_default: props.language.is_default,
});

const isOnlyLanguage = computed(() => props.languageCount === 1);

const slugPreview = computed(() => {
    const raw = form.name
        .toLowerCase()
        .trim()
        .replace(/[^\p{L}\p{N}\s-]/gu, '')
        .replace(/[\s_]+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
    return raw || '…';
});

function submit() {
    form.put(`/admin/languages/${props.language.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="Edit language" />

        <div class="mb-6">
            <Link href="/admin/languages" class="text-sm font-medium text-sky-700 hover:text-sky-900">
                ← Languages
            </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Edit language</h1>
            <p class="mt-1 text-sm text-slate-600">Updating the name will regenerate the slug when you save.</p>
        </div>

        <form
            class="max-w-lg space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
            @submit.prevent="submit"
        >
            <div>
                <label for="slug" class="mb-2 block text-sm font-medium text-slate-700">Current slug</label>
                <input
                    id="slug"
                    type="text"
                    :value="language.slug"
                    readonly
                    class="block w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 font-mono text-sm text-slate-600"
                />
            </div>

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
                <span class="font-medium text-slate-700">Slug after save (preview):</span>
                <code class="ml-2 font-mono text-xs text-slate-800">{{ slugPreview }}</code>
            </div>

            <div v-if="isOnlyLanguage" class="rounded-lg border border-sky-100 bg-sky-50 px-3 py-2 text-sm text-sky-900">
                This is the only language; it must remain the default.
            </div>

            <div v-else class="flex items-start gap-3">
                <input
                    id="is_default"
                    v-model="form.is_default"
                    type="checkbox"
                    name="is_default"
                    class="mt-1 size-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500/30"
                    :disabled="isOnlyLanguage"
                />
                <div>
                    <label for="is_default" class="text-sm font-medium text-slate-700">Default language</label>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Only one language can be default. Checking this will unset the current default.
                    </p>
                    <p v-if="form.errors.is_default" class="mt-1 text-sm text-red-600">{{ form.errors.is_default }}</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Update
                </button>
                <Link
                    href="/admin/languages"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
