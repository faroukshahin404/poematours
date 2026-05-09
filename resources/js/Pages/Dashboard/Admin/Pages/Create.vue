<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import EditableTextArea from '@/Components/Admin/EditableTextArea.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    slug: '',
    meta_title: '',
    meta_description: '',
    meta_keywords: '',
    og_title: '',
    og_description: '',
    og_type: 'website',
    og_url: '',
    og_image: '',
    og_image_file: null,
    body: '',
    show_in_footer: false,
    footer_label: '',
    footer_sort_order: '',
});

function onOgImageChange(event) {
    const file = event.target?.files?.[0] ?? null;
    form.og_image_file = file;
}

function submit() {
    form.post('/admin/pages', {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <div>
        <Head title="Create page" />

        <div class="mb-6">
            <Link href="/admin/pages" class="text-sm font-medium text-sky-700 hover:text-sky-900">
                ← Pages content
            </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Create page</h1>
            <p class="mt-1 text-sm text-slate-600">
                Set the page name, URL slug, SEO, main content, and optional footer link. Slug is generated from the
                name if left empty.
            </p>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Page details</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Page title (name)</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            :class="{ 'border-red-300': form.errors.name }"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Slug (optional)</label>
                        <input
                            v-model="form.slug"
                            type="text"
                            placeholder="e.g. travel-essentials"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 font-mono text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            :class="{ 'border-red-300': form.errors.slug }"
                        />
                        <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
                    </div>
                </div>
            </section>

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
                        />
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
                <h2 class="text-base font-semibold text-slate-900">Page body</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Shown on the public site at <span class="font-mono text-xs">/pages/your-slug</span>. Use the toolbar
                    for formatting; output is stored as HTML.
                </p>
                <div class="mt-4">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Main content</label>
                    <div :class="{ 'rounded-lg p-0.5 ring-2 ring-red-400': form.errors.body }">
                        <EditableTextArea v-model="form.body" placeholder="Main content..." :min-height="280" />
                    </div>
                    <p v-if="form.errors.body" class="mt-1 text-sm text-red-600">{{ form.errors.body }}</p>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Footer</h2>
                <p class="mt-1 text-sm text-slate-600">Requires non-empty body so the public page can be reached.</p>
                <div class="mt-4 space-y-4">
                    <label class="flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-800">
                        <input v-model="form.show_in_footer" type="checkbox" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
                        List in footer Legal links
                    </label>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Footer label</label>
                            <input
                                v-model="form.footer_label"
                                type="text"
                                placeholder="Defaults to page name"
                                class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Footer sort order</label>
                            <input
                                v-model="form.footer_sort_order"
                                type="number"
                                min="0"
                                placeholder="Optional"
                                class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            />
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Create page
                </button>
                <Link
                    href="/admin/pages"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
