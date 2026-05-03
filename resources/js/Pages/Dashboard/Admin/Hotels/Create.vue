<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
    destinations: { type: Array, default: () => [] },
});

const page = usePage();
const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    destination_id: '',
    stars: 5,
    name: {},
    description: {},
    images: [],
});

watch(
    languages,
    (langs) => {
        const name = { ...form.name };
        const description = { ...form.description };
        for (const l of langs) {
            if (name[l.slug] === undefined) name[l.slug] = '';
            if (description[l.slug] === undefined) description[l.slug] = '';
        }
        form.name = name;
        form.description = description;
    },
    { immediate: true },
);

watch(
    () => props.destinations,
    (destinations) => {
        if (destinations.length > 0 && !form.destination_id) {
            form.destination_id = destinations[0].id;
        }
    },
    { immediate: true },
);

function onImagesChange(event) {
    form.images = Array.from(event.target.files ?? []);
}

function submit() {
    form.post('/admin/hotels', { preserveScroll: true, forceFormData: true });
}
</script>

<template>
    <div>
        <Head title="Add hotel" />
        <div class="mb-6">
            <Link href="/admin/hotels" class="text-sm font-medium text-sky-700 hover:text-sky-900">← Hotels</Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Add hotel</h1>
        </div>

        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Destination</label>
                    <select v-model="form.destination_id" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" :class="{ 'border-red-300': form.errors.destination_id }">
                        <option v-for="d in destinations" :key="d.id" :value="d.id">{{ d.label }} ({{ d.slug }})</option>
                    </select>
                    <p v-if="form.errors.destination_id" class="mt-1 text-sm text-red-600">{{ form.errors.destination_id }}</p>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Stars</label>
                    <input v-model="form.stars" type="number" min="1" max="7" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" :class="{ 'border-red-300': form.errors.stars }" />
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Name</h2>
                <div v-for="lang in languages" :key="`name-${lang.slug}`">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <input v-model="form.name[lang.slug]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" :class="{ 'border-red-300': form.errors[`name.${lang.slug}`] }" />
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Description</h2>
                <div v-for="lang in languages" :key="`description-${lang.slug}`">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <textarea v-model="form.description[lang.slug]" rows="3" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" :class="{ 'border-red-300': form.errors[`description.${lang.slug}`] }" />
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Images</label>
                <input type="file" multiple accept="image/*" class="block w-full text-sm text-slate-600" @change="onImagesChange" />
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700" :disabled="form.processing">Save</button>
                <Link href="/admin/hotels" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</Link>
            </div>
        </form>
    </div>
</template>
