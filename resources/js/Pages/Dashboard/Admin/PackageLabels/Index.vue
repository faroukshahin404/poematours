<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    packageLabels: {
        type: Object,
        required: true,
    },
    groupOptions: {
        type: Array,
        default: () => [],
    },
    filterGroupId: {
        type: [Number, String, null],
        default: null,
    },
});

const hasActiveGroupFilter = computed(
    () => props.filterGroupId != null && props.filterGroupId !== '',
);

const page = usePage();

const flash = computed(() => page.props.flash?.status ?? null);

function applyFilter(value) {
    const params = value && value !== '' ? { package_label_group_id: value } : {};
    router.get('/admin/package-labels', params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function destroyLabel(id, label) {
    if (!window.confirm(`Delete package label “${label}”? This cannot be undone.`)) {
        return;
    }
    router.delete(`/admin/package-labels/${id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-xl font-semibold text-slate-900">Package labels</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Labels for package filters; each label belongs to one group. Slugs are unique within a group.
                </p>
            </div>
            <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                <div class="flex flex-col gap-1">
                    <label for="filter-group" class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Group filter
                    </label>
                    <select
                        id="filter-group"
                        class="min-w-[12rem] rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        :value="hasActiveGroupFilter ? String(props.filterGroupId) : ''"
                        aria-label="Filter labels by group"
                        @change="applyFilter($event.target.value)"
                    >
                        <option value="">All groups</option>
                        <option v-for="g in groupOptions" :key="g.id" :value="String(g.id)">
                            {{ g.label }} ({{ g.slug }})
                        </option>
                    </select>
                </div>
                <Link
                    href="/admin/package-labels/create"
                    class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 sm:self-end"
                >
                    Add label
                </Link>
            </div>
        </div>

        <div
            v-if="flash"
            class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            role="status"
        >
            {{ flash }}
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Name (default language)</th>
                            <th class="px-4 py-3">Group</th>
                            <th class="px-4 py-3">Slug</th>
                            <th class="px-4 py-3">Created by</th>
                            <th class="px-4 py-3">Updated by</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="row in packageLabels.data" :key="row.id" class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 font-medium text-slate-900">
                                {{ row.label }}
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                <span class="font-medium text-slate-800">{{ row.group_label ?? '—' }}</span>
                                <span v-if="row.group_slug" class="ml-1 font-mono text-xs text-slate-500"
                                    >({{ row.group_slug }})</span
                                >
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-600">
                                {{ row.slug }}
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ row.creator ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ row.updater ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <Link
                                    :href="`/admin/package-labels/${row.id}/edit`"
                                    class="mr-3 font-medium text-sky-700 hover:text-sky-900"
                                >
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    class="font-medium text-red-600 hover:text-red-800"
                                    @click="destroyLabel(row.id, row.label)"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!packageLabels.data?.length">
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                                <template v-if="hasActiveGroupFilter"> No labels in this group. </template>
                                <template v-else> No package labels yet. </template>
                                <Link
                                    href="/admin/package-labels/create"
                                    class="font-medium text-sky-700 hover:underline"
                                >
                                    Create a label
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                v-if="packageLabels.last_page > 1"
                class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-600"
            >
                <span> Page {{ packageLabels.current_page }} of {{ packageLabels.last_page }} </span>
                <div class="flex gap-2">
                    <Link
                        v-if="packageLabels.prev_page_url"
                        :href="packageLabels.prev_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="packageLabels.next_page_url"
                        :href="packageLabels.next_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
