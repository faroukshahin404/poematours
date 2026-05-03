<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    page: {
        type: Object,
        required: true,
    },
    sections: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    meta_title: props.page.meta_title ?? '',
    meta_description: props.page.meta_description ?? '',
    meta_keywords: (props.page.meta_keywords ?? []).join(', '),
    og_title: props.page.og_tags?.title ?? '',
    og_description: props.page.og_tags?.description ?? '',
    og_type: props.page.og_tags?.type ?? 'website',
    og_url: props.page.og_tags?.url ?? '',
    og_image: props.page.og_tags?.image ?? '',
    og_image_file: null,
});

function onOgImageChange(event) {
    const file = event.target?.files?.[0] ?? null;
    form.og_image_file = file;
}

function submit() {
    form.put(`/admin/pages/${props.page.id}`, {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <div>
        <Head :title="`Edit ${page.name} content`" />

        <div class="mb-6">
            <Link href="/admin/pages" class="text-sm font-medium text-sky-700 hover:text-sky-900">
                ← Pages content
            </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Edit {{ page.name }} content</h1>
            <p class="mt-1 text-sm text-slate-600">
                Manage SEO and section content for this page. New pages cannot be added from dashboard.
            </p>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Page SEO</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Meta title</label>
                        <input
                            v-model="form.meta_title"
                            type="text"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            :class="{ 'border-red-300': form.errors.meta_title }"
                        />
                        <p v-if="form.errors.meta_title" class="mt-1 text-sm text-red-600">{{ form.errors.meta_title }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Meta description</label>
                        <textarea
                            v-model="form.meta_description"
                            rows="3"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            :class="{ 'border-red-300': form.errors.meta_description }"
                        />
                        <p v-if="form.errors.meta_description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.meta_description }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Meta keywords</label>
                        <input
                            v-model="form.meta_keywords"
                            type="text"
                            placeholder="keyword one, keyword two, keyword three"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            :class="{ 'border-red-300': form.errors.meta_keywords }"
                        />
                        <p v-if="form.errors.meta_keywords" class="mt-1 text-sm text-red-600">{{ form.errors.meta_keywords }}</p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">OG title</label>
                        <input
                            v-model="form.og_title"
                            type="text"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">OG type</label>
                        <select
                            v-model="form.og_type"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        >
                            <option value="website">website</option>
                            <option value="article">article</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">OG description</label>
                        <textarea
                            v-model="form.og_description"
                            rows="3"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">OG URL</label>
                        <input
                            v-model="form.og_url"
                            type="text"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">OG image path</label>
                        <input
                            v-model="form.og_image"
                            type="text"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">OG image file (optional)</label>
                        <input
                            type="file"
                            accept="image/*"
                            class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            @change="onOgImageChange"
                        />
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Page sections</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Open each section in its own edit page.
                </p>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <Link
                        v-for="section in sections"
                        :key="section.id"
                        :href="`/admin/pages/${page.id}/sections/${section.id}/edit`"
                        class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-800 hover:bg-slate-100"
                    >
                        {{ section.key }}
                    </Link>
                </div>
            </section>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save content
                </button>
                <Link
                    href="/admin/pages"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Back
                </Link>
            </div>
        </form>
    </div>
</template>
