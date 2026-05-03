<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default { layout: MainLayout };
</script>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    reels: { type: Object, required: true },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyReel(id, label) {
    if (!window.confirm(`Delete reel "${label}"?`)) {
        return;
    }

    router.delete(`/admin/reels/${id}`, { preserveScroll: true });
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-slate-900">Reels</h1>
            <Link href="/admin/reels/create" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Add reel</Link>
        </div>

        <div v-if="flash" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ flash }}</div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Video</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Created by</th>
                        <th class="px-4 py-3">Updated by</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr v-for="row in reels.data" :key="row.id">
                        <td class="px-4 py-3">
                            <video v-if="row.video_url" :src="row.video_url" class="h-16 w-28 rounded object-cover" muted controls />
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ row.name }}</td>
                        <td class="px-4 py-3">{{ row.description }}</td>
                        <td class="px-4 py-3">{{ row.creator ?? '—' }}</td>
                        <td class="px-4 py-3">{{ row.updater ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="`/admin/reels/${row.id}/edit`" class="mr-3 font-medium text-sky-700">Edit</Link>
                            <button class="font-medium text-red-600" @click="destroyReel(row.id, row.name)">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!reels.data?.length">
                        <td colspan="6" class="px-4 py-10 text-center text-slate-500">No reels yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
