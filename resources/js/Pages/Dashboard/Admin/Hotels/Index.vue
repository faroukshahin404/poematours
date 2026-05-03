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
    hotels: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyHotel(id, label) {
    if (!window.confirm(`Delete hotel "${label}"? This will also delete its rooms and images.`)) {
        return;
    }
    router.delete(`/admin/hotels/${id}`, { preserveScroll: true });
}
</script>

<template>
    <div>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Hotels</h1>
                <p class="mt-1 text-sm text-slate-600">Manage hotels with multilingual name/description and images.</p>
            </div>
            <Link
                href="/admin/hotels/create"
                class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
            >
                Add hotel
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
                            <th class="px-4 py-3">Preview</th>
                            <th class="px-4 py-3">Hotel</th>
                            <th class="px-4 py-3">Destination</th>
                            <th class="px-4 py-3">Stars</th>
                            <th class="px-4 py-3">Rooms</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="row in hotels.data" :key="row.id">
                            <td class="px-4 py-3">
                                <div class="size-12 overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                                    <img v-if="row.images?.[0]?.url" :src="row.images[0].url" alt="" class="size-full object-cover" />
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">{{ row.name }}</div>
                                <div class="font-mono text-xs text-slate-500">{{ row.slug }}</div>
                            </td>
                            <td class="px-4 py-3">{{ row.destination ?? '—' }}</td>
                            <td class="px-4 py-3">{{ row.stars }}</td>
                            <td class="px-4 py-3">{{ row.rooms_count ?? 0 }}</td>
                            <td class="px-4 py-3 text-right">
                                <Link :href="`/admin/hotels/${row.id}/rooms`" class="mr-3 font-medium text-indigo-700 hover:text-indigo-900">Rooms</Link>
                                <Link :href="`/admin/hotels/${row.id}/edit`" class="mr-3 font-medium text-sky-700 hover:text-sky-900">Edit</Link>
                                <button type="button" class="font-medium text-red-600 hover:text-red-800" @click="destroyHotel(row.id, row.name)">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="!hotels.data?.length">
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500">No hotels yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
