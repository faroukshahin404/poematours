<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

const form = useForm({
    google_seo_script: props.settings.google_seo_script ?? '',
});

function submit() {
    form.put('/admin/settings/seo', {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="SEO settings" />

        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">SEO settings</h1>
            <p class="mt-1 text-sm text-slate-600">
                Paste your Google tag (gtag.js) or Tag Manager snippet. It will be inserted on public pages.
            </p>
        </div>

        <div
            v-if="flash"
            class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            role="status"
        >
            {{ flash }}
        </div>

        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <section>
                <label for="google-seo-script" class="mb-2 block text-sm font-medium text-slate-700">
                    Google SEO / Analytics script
                </label>
                <textarea
                    id="google-seo-script"
                    v-model="form.google_seo_script"
                    rows="12"
                    class="block w-full rounded-lg border border-slate-200 px-3 py-2 font-mono text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                    placeholder="Paste the full script block from Google Analytics or Tag Manager"
                    spellcheck="false"
                />
                <p v-if="form.errors.google_seo_script" class="mt-2 text-sm text-red-600">
                    {{ form.errors.google_seo_script }}
                </p>
                <p class="mt-2 text-xs text-slate-500">
                    Include the full snippet from Google Analytics or Tag Manager (including &lt;script&gt; tags).
                </p>
            </section>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save settings
                </button>
            </div>
        </form>
    </div>
</template>
