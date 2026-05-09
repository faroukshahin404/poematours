<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    pages: {
        type: Array,
        required: true,
    },
});

const expandedPageIds = ref({});

function toggleSections(pageId) {
    expandedPageIds.value = {
        ...expandedPageIds.value,
        [pageId]: !expandedPageIds.value[pageId],
    };
}

function sectionEditUrl(pageId, sectionId) {
    return `/admin/pages/${pageId}/sections/${sectionId}/edit`;
}
</script>

<template>
    <div>
        <Head title="Pages content" />

        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Pages content</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Edit pages, SEO metadata, and section content. Create simple pages with optional footer links.
                </p>
            </div>
            <Link
                href="/admin/pages/create"
                class="inline-flex shrink-0 items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
            >
                Create page
            </Link>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Page</th>
                            <th class="px-4 py-3">Slug</th>
                            <th class="px-4 py-3">Meta title</th>
                            <th class="px-4 py-3">Sections</th>
                            <th class="px-4 py-3">Footer</th>
                            <th class="px-4 py-3">Last update</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <template v-for="page in pages" :key="page.id">
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 font-medium text-slate-900">
                                    {{ page.name }}
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600">
                                    {{ page.slug }}
                                </td>
                                <td class="max-w-[20rem] truncate px-4 py-3 text-slate-600">
                                    {{ page.meta_title || '—' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button
                                            type="button"
                                            class="rounded-lg border border-slate-200 px-2 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50"
                                            :aria-expanded="Boolean(expandedPageIds[page.id])"
                                            @click="toggleSections(page.id)"
                                        >
                                            {{
                                                expandedPageIds[page.id]
                                                    ? 'Hide sections'
                                                    : `Sections (${page.sections?.length ?? 0})`
                                            }}
                                        </button>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600">
                                    <span v-if="page.show_in_footer" class="font-medium text-emerald-700">Listed</span>
                                    <span v-else>—</span>
                                    <span v-if="page.footer_label" class="mt-0.5 block text-slate-500">{{
                                        page.footer_label
                                    }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ page.updated_at || '—' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <Link
                                        :href="`/admin/pages/${page.id}/edit`"
                                        class="font-medium text-sky-700 hover:text-sky-900"
                                    >
                                        Edit
                                    </Link>
                                </td>
                            </tr>
                            <tr v-show="expandedPageIds[page.id]" class="bg-slate-50/90">
                                <td colspan="7" class="px-4 py-3">
                                    <ul
                                        v-if="page.sections?.length"
                                        class="space-y-2 border-l-2 border-sky-200 pl-4"
                                    >
                                        <li
                                            v-for="section in page.sections"
                                            :key="section.id"
                                            class="flex flex-wrap items-center justify-between gap-2 text-sm"
                                        >
                                            <span class="font-mono text-xs text-slate-700">{{ section.key }}</span>
                                            <Link
                                                :href="sectionEditUrl(page.id, section.id)"
                                                class="rounded-md bg-white px-3 py-1 text-xs font-semibold text-sky-700 shadow-sm ring-1 ring-slate-200 hover:bg-sky-50"
                                            >
                                                Edit section
                                            </Link>
                                        </li>
                                    </ul>
                                    <p v-else class="text-sm text-slate-500">No sections for this page.</p>
                                </td>
                            </tr>
                        </template>
                        <tr v-if="!pages.length">
                            <td colspan="7" class="px-4 py-10 text-center text-slate-500">
                                No pages available yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
