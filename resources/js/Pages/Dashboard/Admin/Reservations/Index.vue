<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    reservations: {
        type: Object,
        required: true,
    },
});

function labelize(value) {
    return String(value || '')
        .replaceAll('_', ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());
}
</script>

<template>
    <div>
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Reservations</h1>
            <p class="mt-1 text-sm text-slate-600">Manage booking pipeline, payment progress, and reservation details.</p>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Guest</th>
                            <th class="px-4 py-3">Contact</th>
                            <th class="px-4 py-3">Travellers</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="row in reservations.data" :key="row.id" class="hover:bg-slate-50/70">
                            <td class="px-4 py-3">
                                <p class="font-medium text-slate-900">{{ row.guest_name }}</p>
                                <p class="text-xs text-slate-500">{{ row.created_at }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p>{{ row.email || '—' }}</p>
                                <p class="text-xs text-slate-500">{{ row.contact_phone_number || '—' }}</p>
                            </td>
                            <td class="px-4 py-3">{{ row.travellers_count }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded bg-slate-100 px-2 py-1 text-xs font-medium">
                                    {{ labelize(row.booking_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <p>{{ labelize(row.payment_status) }}</p>
                                <p class="text-xs text-slate-500">
                                    Paid: {{ Number(row.paid_amount || 0).toFixed(2) }}
                                    <template v-if="row.total_amount !== null">
                                        / {{ Number(row.total_amount).toFixed(2) }}
                                    </template>
                                </p>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <Link :href="`/admin/reservations/${row.id}`" class="font-medium text-sky-700 hover:text-sky-900">
                                    View details
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="!reservations.data?.length">
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500">No reservations submitted yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
