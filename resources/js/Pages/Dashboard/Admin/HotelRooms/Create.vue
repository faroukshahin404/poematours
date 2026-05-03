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
    hotel: { type: Object, required: true },
});

const page = usePage();
const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    hotel_id: props.hotel.id,
    name: {},
    capacity: 1,
    area: '',
    base_price: '',
    single_supplement: 0,
    images: [],
});

watch(
    languages,
    (langs) => {
        const next = { ...form.name };
        for (const l of langs) {
            if (next[l.slug] === undefined) next[l.slug] = '';
        }
        form.name = next;
    },
    { immediate: true },
);

function onImagesChange(event) {
    form.images = Array.from(event.target.files ?? []);
}

function submit() {
    form.post(`/admin/hotels/${props.hotel.id}/rooms`, { preserveScroll: true, forceFormData: true });
}
</script>

<template>
    <div>
        <Head title="Add room" />
        <div class="mb-6">
            <Link :href="`/admin/hotels/${hotel.id}/rooms`" class="text-sm font-medium text-sky-700 hover:text-sky-900">← Rooms</Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Add room for {{ hotel.name }}</h1>
        </div>

        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Capacity</label>
                    <input v-model="form.capacity" type="number" min="1" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Area</label>
                    <input v-model="form.area" type="number" step="0.01" min="0" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Base price</label>
                    <input v-model="form.base_price" type="number" step="0.01" min="0" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Single supplement</label>
                    <input v-model="form.single_supplement" type="number" step="0.01" min="0" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Name</h2>
                <div v-for="lang in languages" :key="lang.slug">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <input v-model="form.name[lang.slug]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Images</label>
                <input type="file" multiple accept="image/*" class="block w-full text-sm text-slate-600" @change="onImagesChange" />
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700" :disabled="form.processing">Save</button>
                <Link :href="`/admin/hotels/${hotel.id}/rooms`" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</Link>
            </div>
        </form>
    </div>
</template>
