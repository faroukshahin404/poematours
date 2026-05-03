<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    pages: {
        type: Array,
        required: true,
    },
});

const selectedSection = ref({});

function goToSection(pageId) {
    const sectionId = selectedSection.value[pageId];
    if (!sectionId) {
        return;
    }
    window.location.href = `/admin/pages/${pageId}/sections/${sectionId}/edit`;
}
</script>

<template>
    <div>
        <Head title="Pages content" />

        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Pages content</h1>
            <p class="mt-1 text-sm text-slate-600">
                Edit existing pages, SEO metadata, and sections content. Creating pages is disabled.
            </p>
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
                            <th class="px-4 py-3">Last update</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="page in pages" :key="page.id" class="hover:bg-slate-50/80">
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
                                <div class="flex items-center gap-2">
                                    <select
                                        v-model="selectedSection[page.id]"
                                        class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700"
                                    >
                                        <option :value="null">Choose section</option>
                                        <option
                                            v-for="section in page.sections"
                                            :key="section.id"
                                            :value="section.id"
                                        >
                                            {{ section.key }}
                                        </option>
                                    </select>
                                    <button
                                        type="button"
                                        class="rounded-lg border border-slate-200 px-2 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50"
                                        @click="goToSection(page.id)"
                                    >
                                        Open
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ page.updated_at || '—' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <Link
                                    :href="`/admin/pages/${page.id}/edit`"
                                    class="font-medium text-sky-700 hover:text-sky-900"
                                >
                                    Edit SEO
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="!pages.length">
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                                No pages available yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
