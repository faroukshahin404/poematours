<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';
export default { layout: MainLayout };
</script>

<script setup>
import EditableTextArea from '@/Components/Admin/EditableTextArea.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
    blog: { type: Object, required: true },
    categories: { type: Array, default: () => [] },
});

const page = usePage();
const languages = computed(() => page.props.languages ?? []);
const form = useForm({
    blog_category_id: props.blog.blog_category_id,
    title: { ...props.blog.title_translations },
    details: { ...props.blog.details_translations },
    is_featured: props.blog.is_featured,
    views_count: props.blog.views_count,
    images: [],
    remove_media_ids: [],
});

watch(languages, (langs) => {
    const title = { ...form.title };
    const details = { ...form.details };
    for (const lang of langs) {
        if (title[lang.slug] === undefined) title[lang.slug] = '';
        if (details[lang.slug] === undefined) details[lang.slug] = '';
    }
    form.title = title;
    form.details = details;
}, { immediate: true });

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
    form.transform((data) => ({ ...data, _method: 'put' })).post(`/admin/blogs/${props.blog.id}`, {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <div>
        <Head title="Edit blog" />
        <div class="mb-6"><Link href="/admin/blogs" class="text-sm font-medium text-sky-700">← Blogs</Link><h1 class="mt-2 text-xl font-semibold text-slate-900">Edit blog</h1></div>
        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Category</label>
                <select v-model="form.blog_category_id" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.label }} ({{ category.slug }})</option>
                </select>
            </div>
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Title</h2>
                <div v-for="lang in languages" :key="`title-${lang.slug}`">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <input v-model="form.title[lang.slug]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" :class="{ 'border-red-300': form.errors[`title.${lang.slug}`] }" />
                </div>
            </div>
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Details</h2>
                <div v-for="lang in languages" :key="`details-${lang.slug}`">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <EditableTextArea v-model="form.details[lang.slug]" />
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <label class="flex items-center gap-2 text-sm text-slate-700"><input v-model="form.is_featured" type="checkbox" /> Featured</label>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Views count</label>
                    <input v-model="form.views_count" type="number" min="0" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
                </div>
            </div>
            <div class="space-y-3">
                <h2 class="text-sm font-semibold text-slate-800">Existing images</h2>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    <label v-for="media in blog.images" :key="media.id" class="block rounded-lg border p-2" :class="form.remove_media_ids.includes(media.id) ? 'border-red-300 bg-red-50' : 'border-slate-200'">
                        <img :src="media.url" alt="" class="h-24 w-full rounded object-cover" />
                        <span class="mt-2 flex items-center gap-2 text-xs text-slate-700"><input type="checkbox" :checked="form.remove_media_ids.includes(media.id)" @change="toggleRemoveMedia(media.id)" /> Remove</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Upload more images</label>
                <input type="file" multiple accept="image/*" class="block w-full text-sm text-slate-600" @change="onImagesChange" />
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Update</button>
                <Link href="/admin/blogs" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700">Cancel</Link>
            </div>
        </form>
    </div>
</template>
