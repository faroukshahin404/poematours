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
    boats: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyBoat(id, label) {
    if (!window.confirm(`Delete boat "${label}"?`)) {
        return;
    }
    router.delete(`/admin/boats/${id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Boats</h1>
                <p class="mt-1 text-sm text-slate-600">Manage multilingual boat content and images.</p>
            </div>
            <Link
                href="/admin/boats/create"
                class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
            >
                Add boat
            </Link>
        </div>

        <div v-if="flash" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ flash }}
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Boat</th>
                            <th class="px-4 py-3">Section images</th>
                            <th class="px-4 py-3">Gallery</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="row in boats.data" :key="row.id">
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">{{ row.name }}</div>
                                <div class="font-mono text-xs text-slate-500">{{ row.slug }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600">
                                boat: {{ row.boat_image ? 'yes' : 'no' }} |
                                suite: {{ row.suite_image ? 'yes' : 'no' }} |
                                food: {{ row.food_image ? 'yes' : 'no' }} |
                                wellness: {{ row.wellness_image ? 'yes' : 'no' }}
                            </td>
                            <td class="px-4 py-3">{{ row.gallery_count }}</td>
                            <td class="px-4 py-3">
                                <Link :href="`/admin/boats/${row.id}/edit`" class="mr-3 font-medium text-sky-700 hover:text-sky-900">Edit</Link>
                                <button type="button" class="font-medium text-red-600 hover:text-red-800" @click="destroyBoat(row.id, row.name)">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="!boats.data?.length">
                            <td colspan="4" class="px-4 py-10 text-center text-slate-500">No boats yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
