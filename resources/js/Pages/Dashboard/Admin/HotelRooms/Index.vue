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
    hotel: { type: Object, required: true },
    rooms: { type: Object, required: true },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyRoom(id, label) {
    if (!window.confirm(`Delete room "${label}"?`)) {
        return;
    }
    router.delete(`/admin/hotels/${props.hotel.id}/rooms/${id}`, { preserveScroll: true });
}
</script>

<template>
    <div>
        <div class="mb-6">
            <Link href="/admin/hotels" class="text-sm font-medium text-sky-700 hover:text-sky-900">← Hotels</Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Rooms for {{ hotel.name }}</h1>
        </div>

        <div class="mb-4 flex justify-end">
            <Link :href="`/admin/hotels/${hotel.id}/rooms/create`" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">Add room</Link>
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
                            <th class="px-4 py-3">Room</th>
                            <th class="px-4 py-3">Capacity</th>
                            <th class="px-4 py-3">Area</th>
                            <th class="px-4 py-3">Base price</th>
                            <th class="px-4 py-3">Single supplement</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="row in rooms.data" :key="row.id">
                            <td class="px-4 py-3">
                                <div class="size-12 overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                                    <img v-if="row.images?.[0]?.url" :src="row.images[0].url" alt="" class="size-full object-cover" />
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">{{ row.name }}</div>
                                <div class="font-mono text-xs text-slate-500">{{ row.slug }}</div>
                            </td>
                            <td class="px-4 py-3">{{ row.capacity }}</td>
                            <td class="px-4 py-3">{{ row.area ?? '—' }}</td>
                            <td class="px-4 py-3">{{ row.base_price }}</td>
                            <td class="px-4 py-3">{{ row.single_supplement }}</td>
                            <td class="px-4 py-3 text-right">
                                <Link :href="`/admin/hotels/${hotel.id}/rooms/${row.id}/edit`" class="mr-3 font-medium text-sky-700 hover:text-sky-900">Edit</Link>
                                <button type="button" class="font-medium text-red-600 hover:text-red-800" @click="destroyRoom(row.id, row.name)">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="!rooms.data?.length">
                            <td colspan="7" class="px-4 py-10 text-center text-slate-500">No rooms yet for this hotel.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
