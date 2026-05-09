<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';
export default { layout: MainLayout };
</script>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({ packages: { type: Object, required: true } });
const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyPackage(id, label) {
    if (!window.confirm(`Delete package "${label}"?`)) return;
    router.delete(`/admin/packages/${id}`, { preserveScroll: true });
}

function duplicatePackage(id, label) {
    if (!window.confirm(`Copy package "${label}" with all its data?`)) return;
    router.post(`/admin/packages/${id}/duplicate`, {}, { preserveScroll: true });
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-slate-900">Packages</h1>
            <Link href="/admin/packages/create" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Add package</Link>
        </div>
        <div v-if="flash" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ flash }}</div>
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    <tr><th class="px-4 py-3">Package</th><th class="px-4 py-3">Categories</th><th class="px-4 py-3">Itinerary items</th><th class="px-4 py-3 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr v-for="row in packages.data" :key="row.id">
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-900">{{ row.title }}</div>
                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                <span
                                    v-if="row.is_featured"
                                    class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-amber-800"
                                >
                                    Featured
                                </span>
                                <span
                                    v-if="row.is_recommended"
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-emerald-800"
                                >
                                    Recommended
                                </span>
                            </div>
                            <div class="mt-1 font-mono text-xs text-slate-500">{{ row.slug }}</div>
                        </td>
                        <td class="px-4 py-3">{{ (row.categories || []).join(', ') }}</td>
                        <td class="px-4 py-3">{{ row.itineraries_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="`/admin/packages/${row.id}/edit`" class="mr-3 font-medium text-sky-700">Edit</Link>
                            <Link :href="`/admin/packages/${row.id}/package-reviews`" class="mr-3 font-medium text-violet-700">Reviews</Link>
                            <button class="mr-3 font-medium text-emerald-700" @click="duplicatePackage(row.id, row.title)">Copy</button>
                            <button class="font-medium text-red-600" @click="destroyPackage(row.id, row.title)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
