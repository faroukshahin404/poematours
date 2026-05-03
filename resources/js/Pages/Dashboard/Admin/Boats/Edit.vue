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

const props = defineProps({
    boat: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    slug: props.boat.slug,
    name: { ...props.boat.name_translations },
    boat: { ...props.boat.boat_translations },
    suite: { ...props.boat.suite_translations },
    food: { ...props.boat.food_translations },
    wellness: { ...props.boat.wellness_translations },
    boat_image: null,
    suite_image: null,
    food_image: null,
    wellness_image: null,
    remove_boat_image: false,
    remove_suite_image: false,
    remove_food_image: false,
    remove_wellness_image: false,
    images: [],
    remove_media_ids: [],
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

function toggleRemoveMedia(mediaId) {
    if (form.remove_media_ids.includes(mediaId)) {
        form.remove_media_ids = form.remove_media_ids.filter((id) => id !== mediaId);
        return;
    }
    form.remove_media_ids.push(mediaId);
}

function submit() {
    form.transform((data) => ({ ...data, _method: 'put' })).post(`/admin/boats/${props.boat.id}`, {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <div>
        <Head title="Edit boat" />
        <div class="mb-6">
            <Link href="/admin/boats" class="text-sm font-medium text-sky-700 hover:text-sky-900">← Boats</Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Edit boat</h1>
        </div>

        <form class="max-w-4xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Slug</label>
                <input v-model="form.slug" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 font-mono text-sm" />
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Name</h2>
                <div v-for="lang in languages" :key="`name-${lang.slug}`">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <input v-model="form.name[lang.slug]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-800">Boat section</h2>
                    <div v-for="lang in languages" :key="`boat-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.boat[lang.slug]" />
                    </div>
                    <img v-if="boat.boat_image" :src="boat.boat_image" alt="" class="h-28 w-full rounded border object-cover" />
                    <label class="flex items-center gap-2 text-sm text-slate-700"><input v-model="form.remove_boat_image" type="checkbox" /> Remove current image</label>
                    <FileInput label="Upload boat image" @update:model-value="onImage($event, 'boat_image')" />
                </div>

                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-800">Suite section</h2>
                    <div v-for="lang in languages" :key="`suite-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.suite[lang.slug]" />
                    </div>
                    <img v-if="boat.suite_image" :src="boat.suite_image" alt="" class="h-28 w-full rounded border object-cover" />
                    <label class="flex items-center gap-2 text-sm text-slate-700"><input v-model="form.remove_suite_image" type="checkbox" /> Remove current image</label>
                    <FileInput label="Upload suite image" @update:model-value="onImage($event, 'suite_image')" />
                </div>

                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-800">Food section</h2>
                    <div v-for="lang in languages" :key="`food-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.food[lang.slug]" />
                    </div>
                    <img v-if="boat.food_image" :src="boat.food_image" alt="" class="h-28 w-full rounded border object-cover" />
                    <label class="flex items-center gap-2 text-sm text-slate-700"><input v-model="form.remove_food_image" type="checkbox" /> Remove current image</label>
                    <FileInput label="Upload food image" @update:model-value="onImage($event, 'food_image')" />
                </div>

                <div class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-800">Wellness section</h2>
                    <div v-for="lang in languages" :key="`wellness-${lang.slug}`">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                        <EditableTextArea v-model="form.wellness[lang.slug]" />
                    </div>
                    <img v-if="boat.wellness_image" :src="boat.wellness_image" alt="" class="h-28 w-full rounded border object-cover" />
                    <label class="flex items-center gap-2 text-sm text-slate-700"><input v-model="form.remove_wellness_image" type="checkbox" /> Remove current image</label>
                    <FileInput label="Upload wellness image" @update:model-value="onImage($event, 'wellness_image')" />
                </div>
            </div>

            <div class="space-y-3">
                <h2 class="text-sm font-semibold text-slate-800">Gallery images</h2>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    <label v-for="media in boat.images" :key="media.id" class="block rounded-lg border p-2" :class="form.remove_media_ids.includes(media.id) ? 'border-red-300 bg-red-50' : 'border-slate-200'">
                        <img :src="media.url" alt="" class="h-24 w-full rounded object-cover" />
                        <span class="mt-2 flex items-center gap-2 text-xs text-slate-700">
                            <input type="checkbox" :checked="form.remove_media_ids.includes(media.id)" @change="toggleRemoveMedia(media.id)" />
                            Remove
                        </span>
                    </label>
                </div>
                <FileInput label="Upload gallery images" multiple @update:model-value="onGallery" />
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700" :disabled="form.processing">Update</button>
                <Link href="/admin/boats" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</Link>
            </div>
        </form>
    </div>
</template>
