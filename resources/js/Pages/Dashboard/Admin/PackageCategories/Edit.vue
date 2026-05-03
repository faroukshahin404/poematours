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
    packageCategory: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    slug: props.packageCategory.slug,
    name: { ...props.packageCategory.name_translations },
    title: { ...props.packageCategory.title_translations },
    description: { ...props.packageCategory.description_translations },
    image: null,
});

watch(
    languages,
    (langs) => {
        const next = { ...form.name };
        const nextTitle = { ...form.title };
        const nextDescription = { ...form.description };
        for (const l of langs) {
            if (next[l.slug] === undefined) {
                next[l.slug] = '';
            }
            if (nextTitle[l.slug] === undefined) {
                nextTitle[l.slug] = '';
            }
            if (nextDescription[l.slug] === undefined) {
                nextDescription[l.slug] = '';
            }
        }
        form.name = next;
        form.title = nextTitle;
        form.description = nextDescription;
    },
    { immediate: true },
);

function onFileChange(event) {
    const file = event.target.files?.[0];
    form.image = file ?? null;
}

function submit() {
    form
        .transform((data) => ({ ...data, _method: 'put' }))
        .post(`/admin/package-categories/${props.packageCategory.id}`, {
            preserveScroll: true,
            forceFormData: true,
        });
}
</script>

<template>
    <div>
        <Head title="Edit package category" />

        <div class="mb-6">
            <Link href="/admin/package-categories" class="text-sm font-medium text-sky-700 hover:text-sky-900">
                ← Package categories
            </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Edit package category</h1>
            <p class="mt-1 text-sm text-slate-600">Update localized names, slug, or replace the image.</p>
        </div>

        <form
            class="max-w-2xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
            @submit.prevent="submit"
        >
            <div v-if="packageCategory.image_url" class="flex items-start gap-4">
                <div class="size-24 shrink-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                    <img :src="packageCategory.image_url" alt="" class="size-full object-cover" />
                </div>
                <p class="text-sm text-slate-600">Upload a new file to replace this image.</p>
            </div>

            <div>
                <label for="slug" class="mb-2 block text-sm font-medium text-slate-700">Slug</label>
                <input
                    id="slug"
                    v-model="form.slug"
                    type="text"
                    name="slug"
                    required
                    class="block w-full rounded-lg border border-slate-200 px-3 py-2 font-mono text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                    :class="{ 'border-red-300': form.errors.slug }"
                />
                <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Names</h2>
                <div v-for="lang in languages" :key="lang.slug" class="space-y-1">
                    <label :for="`name-${lang.slug}`" class="block text-sm font-medium text-slate-700">
                        {{ lang.name }}
                        <span class="font-mono text-xs font-normal text-slate-500">({{ lang.slug }})</span>
                    </label>
                    <input
                        :id="`name-${lang.slug}`"
                        v-model="form.name[lang.slug]"
                        type="text"
                        :name="`name[${lang.slug}]`"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        :class="{ 'border-red-300': form.errors[`name.${lang.slug}`] }"
                    />
                    <p v-if="form.errors[`name.${lang.slug}`]" class="text-sm text-red-600">
                        {{ form.errors[`name.${lang.slug}`] }}
                    </p>
                </div>
                <p v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Card Titles (Ways to Explore)</h2>
                <div v-for="lang in languages" :key="`title-${lang.slug}`" class="space-y-1">
                    <label :for="`title-${lang.slug}`" class="block text-sm font-medium text-slate-700">
                        {{ lang.name }}
                        <span class="font-mono text-xs font-normal text-slate-500">({{ lang.slug }})</span>
                    </label>
                    <input
                        :id="`title-${lang.slug}`"
                        v-model="form.title[lang.slug]"
                        type="text"
                        :name="`title[${lang.slug}]`"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        :class="{ 'border-red-300': form.errors[`title.${lang.slug}`] }"
                    />
                    <p v-if="form.errors[`title.${lang.slug}`]" class="text-sm text-red-600">
                        {{ form.errors[`title.${lang.slug}`] }}
                    </p>
                </div>
                <p v-if="form.errors.title" class="text-sm text-red-600">{{ form.errors.title }}</p>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Card Descriptions (Ways to Explore)</h2>
                <div v-for="lang in languages" :key="`description-${lang.slug}`" class="space-y-1">
                    <label :for="`description-${lang.slug}`" class="block text-sm font-medium text-slate-700">
                        {{ lang.name }}
                        <span class="font-mono text-xs font-normal text-slate-500">({{ lang.slug }})</span>
                    </label>
                    <textarea
                        :id="`description-${lang.slug}`"
                        v-model="form.description[lang.slug]"
                        :name="`description[${lang.slug}]`"
                        rows="3"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        :class="{ 'border-red-300': form.errors[`description.${lang.slug}`] }"
                    />
                    <p v-if="form.errors[`description.${lang.slug}`]" class="text-sm text-red-600">
                        {{ form.errors[`description.${lang.slug}`] }}
                    </p>
                </div>
                <p v-if="form.errors.description" class="text-sm text-red-600">{{ form.errors.description }}</p>
            </div>

            <div>
                <label for="image" class="mb-2 block text-sm font-medium text-slate-700">New image (optional)</label>
                <input
                    id="image"
                    type="file"
                    name="image"
                    accept="image/*"
                    class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-sky-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-sky-800 hover:file:bg-sky-100"
                    @change="onFileChange"
                />
                <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">{{ form.errors.image }}</p>
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
                    href="/admin/package-categories"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
