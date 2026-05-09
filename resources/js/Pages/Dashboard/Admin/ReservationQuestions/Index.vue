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
    reservationQuestions: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyQuestion(id, title) {
    if (!window.confirm(`Delete reservation question "${title}"? This cannot be undone.`)) {
        return;
    }

    router.delete(`/admin/reservation-questions/${id}`, {
        preserveScroll: true,
    });
}

function formatType(type) {
    if (type === 'multi_select') {
        return 'Multi select';
    }

    return type.charAt(0).toUpperCase() + type.slice(1);
}
</script>

<template>
    <div>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-xl font-semibold text-slate-900">Reservation questions</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Dynamic questions for package reservation and reservation page forms.
                </p>
            </div>
            <Link
                href="/admin/reservation-questions/create"
                class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
            >
                Add question
            </Link>
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
                            <th class="px-4 py-3">Title (default language)</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Package reservation</th>
                            <th class="px-4 py-3">Reservation page</th>
                            <th class="px-4 py-3">Options</th>
                            <th class="px-4 py-3">Created by</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr
                            v-for="row in reservationQuestions.data"
                            :key="row.id"
                            class="hover:bg-slate-50/80"
                        >
                            <td class="px-4 py-3 font-medium text-slate-900">{{ row.title }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">
                                    {{ formatType(row.type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ row.is_package_reservation ? 'Yes' : 'No' }}</td>
                            <td class="px-4 py-3">{{ row.is_reservation_page ? 'Yes' : 'No' }}</td>
                            <td class="px-4 py-3">{{ row.options_count }}</td>
                            <td class="px-4 py-3">{{ row.creator ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <Link
                                    :href="`/admin/reservation-questions/${row.id}/edit`"
                                    class="mr-3 font-medium text-sky-700 hover:text-sky-900"
                                >
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    class="font-medium text-red-600 hover:text-red-800"
                                    @click="destroyQuestion(row.id, row.title)"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!reservationQuestions.data?.length">
                            <td colspan="7" class="px-4 py-10 text-center text-slate-500">
                                No reservation questions yet.
                                <Link
                                    href="/admin/reservation-questions/create"
                                    class="font-medium text-sky-700 hover:underline"
                                >
                                    Create one
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
