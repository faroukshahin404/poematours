<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default { layout: MainLayout };
</script>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({ activities: { type: Object, required: true } });
const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyActivity(id, label) {
    if (!window.confirm(`Delete activity "${label}"?`)) return;
    router.delete(`/admin/activities/${id}`, { preserveScroll: true });
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-slate-900">Activities</h1>
            <Link href="/admin/activities/create" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Add activity</Link>
        </div>
        <div v-if="flash" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ flash }}</div>
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    <tr><th class="px-4 py-3">Image</th><th class="px-4 py-3">Name</th><th class="px-4 py-3">Destination</th><th class="px-4 py-3">Packages</th><th class="px-4 py-3 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr v-for="row in activities.data" :key="row.id">
                        <td class="px-4 py-3"><img v-if="row.image_url" :src="row.image_url" alt="" class="h-12 w-12 rounded object-cover" /></td>
                        <td class="px-4 py-3"><div class="font-medium text-slate-900">{{ row.name }}</div><div class="font-mono text-xs text-slate-500">{{ row.slug }}</div></td>
                        <td class="px-4 py-3">{{ row.destination ?? '-' }}</td>
                        <td class="px-4 py-3">{{ row.packages_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="`/admin/activities/${row.id}/edit`" class="mr-3 font-medium text-sky-700">Edit</Link>
                            <button class="font-medium text-red-600" @click="destroyActivity(row.id, row.name)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
