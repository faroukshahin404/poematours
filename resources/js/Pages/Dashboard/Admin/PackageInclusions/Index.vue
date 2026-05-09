<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default { layout: MainLayout };
</script>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    packageInclusions: { type: Object, required: true },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyRow(id, label) {
    if (!window.confirm(`Delete inclusion "${label}"?`)) return;
    router.delete(`/admin/package-inclusions/${id}`, { preserveScroll: true });
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-slate-900">Package inclusions</h1>
            <Link
                href="/admin/package-inclusions/create"
                class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white"
            >
                Add inclusion
            </Link>
        </div>
        <div
            v-if="flash"
            class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
        >
            {{ flash }}
        </div>
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Icon</th>
                        <th class="px-4 py-3">Packages</th>
                        <th class="px-4 py-3">Created by</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr v-for="row in packageInclusions.data" :key="row.id">
                        <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ row.id }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ row.name }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ row.icon || '—' }}</td>
                        <td class="px-4 py-3">{{ row.packages_count ?? 0 }}</td>
                        <td class="px-4 py-3">{{ row.created_by ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="`/admin/package-inclusions/${row.id}/edit`" class="mr-3 font-medium text-sky-700">
                                Edit
                            </Link>
                            <button type="button" class="font-medium text-red-600" @click="destroyRow(row.id, row.name)">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
