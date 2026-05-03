<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    packageLabelGroups: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const flash = computed(() => page.props.flash?.status ?? null);
const deleteError = computed(() => page.props.errors?.delete?.[0] ?? null);

function destroyGroup(id, label) {
    if (!window.confirm(`Delete label group “${label}”? This cannot be undone.`)) {
        return;
    }
    router.delete(`/admin/package-label-groups/${id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Package label groups</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Group labels for filters; names are stored per language (same pattern as categories).
                </p>
            </div>
            <Link
                href="/admin/package-label-groups/create"
                class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600"
            >
                Add group
            </Link>
        </div>

        <div
            v-if="flash"
            class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            role="status"
        >
            {{ flash }}
        </div>

        <div
            v-if="deleteError"
            class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
            role="alert"
        >
            {{ deleteError }}
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Name (default language)</th>
                            <th class="px-4 py-3">Slug</th>
                            <th class="px-4 py-3">Labels</th>
                            <th class="px-4 py-3">Created by</th>
                            <th class="px-4 py-3">Updated by</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="row in packageLabelGroups.data" :key="row.id" class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 font-medium text-slate-900">
                                {{ row.label }}
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-600">
                                {{ row.slug }}
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ row.labels_count ?? 0 }}
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ row.creator ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ row.updater ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <Link
                                    :href="`/admin/package-label-groups/${row.id}/edit`"
                                    class="mr-3 font-medium text-sky-700 hover:text-sky-900"
                                >
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    class="font-medium text-red-600 hover:text-red-800"
                                    @click="destroyGroup(row.id, row.label)"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!packageLabelGroups.data?.length">
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                                No label groups yet.
                                <Link
                                    href="/admin/package-label-groups/create"
                                    class="font-medium text-sky-700 hover:underline"
                                >
                                    Create the first one
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                v-if="packageLabelGroups.last_page > 1"
                class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-600"
            >
                <span> Page {{ packageLabelGroups.current_page }} of {{ packageLabelGroups.last_page }} </span>
                <div class="flex gap-2">
                    <Link
                        v-if="packageLabelGroups.prev_page_url"
                        :href="packageLabelGroups.prev_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="packageLabelGroups.next_page_url"
                        :href="packageLabelGroups.next_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
