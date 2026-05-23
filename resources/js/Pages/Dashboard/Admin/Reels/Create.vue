<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';
export default { layout: MainLayout };
</script>

<script setup>
import EditableTextArea from '@/Components/Admin/EditableTextArea.vue';
import ImageUploader from '@/Components/Admin/ImageUploader.vue';
import VideoUploader from '@/Components/Admin/VideoUploader.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const page = usePage();
const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    name: {},
    description: {},
    video_url: '',
    snapshot_url: '',
});

watch(languages, (langs) => {
    const name = { ...form.name };
    const description = { ...form.description };
    for (const language of langs) {
        if (name[language.slug] === undefined) {
            name[language.slug] = '';
        }
        if (description[language.slug] === undefined) {
            description[language.slug] = '';
        }
    }
    form.name = name;
    form.description = description;
}, { immediate: true });

function submit() {
    form.post('/admin/reels', { preserveScroll: true });
}
</script>

<template>
    <div>
        <Head title="Add reel" />
        <div class="mb-6">
            <Link href="/admin/reels" class="text-sm font-medium text-sky-700">← Reels</Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Add reel</h1>
        </div>
        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Name</h2>
                <div v-for="lang in languages" :key="`name-${lang.slug}`">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <input v-model="form.name[lang.slug]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Description</h2>
                <div v-for="lang in languages" :key="`desc-${lang.slug}`">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <EditableTextArea v-model="form.description[lang.slug]" />
                </div>
            </div>

            <VideoUploader v-model="form.video_url" label="Reel video" />

            <ImageUploader v-model="form.snapshot_url" label="Snapshot (optional)" />

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white" :disabled="form.processing">Save</button>
                <Link href="/admin/reels" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700">Cancel</Link>
            </div>
        </form>
    </div>
</template>
