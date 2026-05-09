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
    services: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyService(id, name) {
    if (!window.confirm(`Delete service "${name}"? This cannot be undone.`)) {
        return;
    }

    router.delete(`/admin/crm-services/${id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">CRM Services</h1>
                <p class="mt-1 text-sm text-slate-600">Manage services assignable to CRM contacts.</p>
            </div>
            <Link href="/admin/crm-services/create" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
                Add service
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
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Contacts</th>
                            <th class="px-4 py-3">Created by</th>
                            <th class="px-4 py-3">Updated by</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="row in services.data" :key="row.id">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ row.name }}</td>
                            <td class="px-4 py-3">{{ row.contacts_count ?? 0 }}</td>
                            <td class="px-4 py-3">{{ row.creator?.name ?? "—" }}</td>
                            <td class="px-4 py-3">{{ row.updater?.name ?? "—" }}</td>
                            <td class="px-4 py-3 text-right">
                                <Link :href="`/admin/crm-services/${row.id}/edit`" class="mr-3 font-medium text-sky-700 hover:text-sky-900">
                                    Edit
                                </Link>
                                <button type="button" class="font-medium text-red-600 hover:text-red-800" @click="destroyService(row.id, row.name)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
