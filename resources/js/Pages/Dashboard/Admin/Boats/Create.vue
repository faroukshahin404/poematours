<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import EditableTextArea from '@/Components/Admin/EditableTextArea.vue';
import FileInput from '@/Components/Admin/FileInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const page = usePage();
const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    name: {},
    boat: {},
    suite: {},
    food: {},
    wellness: {},
    boat_image: null,
    suite_image: null,
    food_image: null,
    wellness_image: null,
    images: [],
});

watch(
    languages,
    (langs) => {
        const fields = ['name', 'boat', 'suite', 'food', 'wellness'];
        for (const field of fields) {
            const next = { ...form[field] };
            for (const l of langs) {
                if (next[l.slug] === undefined) {
                    next[l.slug] = '';
                }
            }
            form[field] = next;
        }
    },
    { immediate: true },
);

function onImage(file, key) {
    form[key] = file ?? null;
}

function onGallery(files) {
    form.images = files ?? [];
}

function submit() {
    form.post('/admin/boats', { preserveScroll: true, forceFormData: true });
}
</script>

<template>
    <div>
        <Head title="Add boat" />
        <div class="mb-6">
            <Link href="/admin/boats" class="text-sm font-medium text-sky-700 hover:text-sky-900">← Boats</Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Add boat</h1>
        </div>

        <form class="max-w-4xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Name</h2>
                <div v-for="lang in languages" :key="`name-${lang.slug}`">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <input v-model="form.name[lang.slug]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-800">Boat (text)</h2>
                    <div v-for="lang in languages" :key="`boat-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.boat[lang.slug]" />
                    </div>
                    <FileInput label="Boat image" @update:model-value="onImage($event, 'boat_image')" />
                </div>

                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-800">Suite (text)</h2>
                    <div v-for="lang in languages" :key="`suite-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.suite[lang.slug]" />
                    </div>
                    <FileInput label="Suite image" @update:model-value="onImage($event, 'suite_image')" />
                </div>

                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-800">Food (text)</h2>
                    <div v-for="lang in languages" :key="`food-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.food[lang.slug]" />
                    </div>
                    <FileInput label="Food image" @update:model-value="onImage($event, 'food_image')" />
                </div>

                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-800">Wellness (text)</h2>
                    <div v-for="lang in languages" :key="`wellness-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.wellness[lang.slug]" />
                    </div>
                    <FileInput label="Wellness image" @update:model-value="onImage($event, 'wellness_image')" />
                </div>
            </div>

            <div>
                <FileInput label="Gallery images (multiple)" multiple @update:model-value="onGallery" />
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700" :disabled="form.processing">Save</button>
                <Link href="/admin/boats" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</Link>
            </div>
        </form>
    </div>
</template>
