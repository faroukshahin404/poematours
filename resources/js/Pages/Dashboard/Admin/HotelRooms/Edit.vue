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
    room: { type: Object, required: true },
});

const page = usePage();
const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    hotel_id: props.room.hotel_id,
    slug: props.room.slug,
    name: { ...props.room.name_translations },
    capacity: props.room.capacity,
    area: props.room.area,
    base_price: props.room.base_price,
    single_supplement: props.room.single_supplement,
    images: [],
    remove_media_ids: [],
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

function toggleRemoveMedia(mediaId) {
    if (form.remove_media_ids.includes(mediaId)) {
        form.remove_media_ids = form.remove_media_ids.filter((id) => id !== mediaId);
        return;
    }
    form.remove_media_ids.push(mediaId);
}

function submit() {
    form
        .transform((data) => ({ ...data, _method: 'put' }))
        .post(`/admin/hotels/${props.hotel.id}/rooms/${props.room.id}`, { preserveScroll: true, forceFormData: true });
}
</script>

<template>
    <div>
        <Head title="Edit room" />
        <div class="mb-6">
            <Link :href="`/admin/hotels/${hotel.id}/rooms`" class="text-sm font-medium text-sky-700 hover:text-sky-900">← Rooms</Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Edit room for {{ hotel.name }}</h1>
        </div>

        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Slug</label>
                    <input v-model="form.slug" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 font-mono text-sm" />
                </div>
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

            <div class="space-y-3">
                <h2 class="text-sm font-semibold text-slate-800">Existing images</h2>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    <label v-for="media in room.images" :key="media.id" class="block rounded-lg border p-2" :class="form.remove_media_ids.includes(media.id) ? 'border-red-300 bg-red-50' : 'border-slate-200'">
                        <img :src="media.url" alt="" class="h-24 w-full rounded object-cover" />
                        <span class="mt-2 flex items-center gap-2 text-xs text-slate-700">
                            <input type="checkbox" :checked="form.remove_media_ids.includes(media.id)" @change="toggleRemoveMedia(media.id)" />
                            Remove
                        </span>
                    </label>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Upload more images</label>
                <input type="file" multiple accept="image/*" class="block w-full text-sm text-slate-600" @change="onImagesChange" />
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700" :disabled="form.processing">Update</button>
                <Link :href="`/admin/hotels/${hotel.id}/rooms`" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</Link>
            </div>
        </form>
    </div>
</template>
