<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    filters: {
        type: Object,
        default: () => ({
            period: 'this_week',
            from: '',
            to: '',
            available_periods: [],
        }),
    },
    stats: {
        type: Object,
        default: () => ({
            reservations_count: 0,
            custom_requests_count: 0,
            total_paid_amount: 0,
            total_reservations_amount: 0,
        }),
    },
    charts: {
        type: Object,
        default: () => ({
            most_visited_pages: [],
            crm_contacts_by_source: [],
        }),
    },
    latest_reservations: {
        type: Array,
        default: () => [],
    },
});

const selectedPeriod = ref(props.filters.period || 'this_week');
const customFrom = ref(props.filters.from || '');
const customTo = ref(props.filters.to || '');

const maxCrmSourceTotal = computed(() => {
    const maxValue = Math.max(...props.charts.crm_contacts_by_source.map((item) => Number(item.total || 0)), 0);

    return maxValue > 0 ? maxValue : 1;
});

const maxPageVisits = computed(() => {
    const maxValue = Math.max(...props.charts.most_visited_pages.map((item) => Number(item.visits_count || 0)), 0);

    return maxValue > 0 ? maxValue : 1;
});

const currencyFormatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
});

function formatMoney(value) {
    return currencyFormatter.format(Number(value || 0));
}

function applyFilters() {
    const query = {
        period: selectedPeriod.value,
    };

    if (selectedPeriod.value === 'custom') {
        query.from = customFrom.value || undefined;
        query.to = customTo.value || undefined;
    }

    router.get('/admin', query, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}
</script>

<template>
    <div class="space-y-6">
        <Head title="Dashboard" />

        <section class="overflow-hidden rounded-2xl bg-gradient-to-r from-sky-600 via-indigo-600 to-violet-600 p-6 text-white shadow-xl">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-sky-100">Business Overview</p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight">Poema Tours Dashboard</h1>
                    <p class="mt-1 text-sm text-sky-100">
                        Monitor reservations, payments, page performance, and lead quality in one place.
                    </p>
                </div>
                <div class="rounded-xl bg-white/15 px-4 py-3 text-sm">
                    <p class="font-semibold">Current Period</p>
                    <p class="text-sky-100">
                        {{ selectedPeriod === 'custom' ? `${customFrom || '...'} to ${customTo || '...'}` : selectedPeriod.replace('_', ' ') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="period" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Period</label>
                    <select
                        id="period"
                        v-model="selectedPeriod"
                        class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-sky-500 focus:outline-none"
                    >
                        <option
                            v-for="periodOption in filters.available_periods"
                            :key="periodOption.value"
                            :value="periodOption.value"
                        >
                            {{ periodOption.label }}
                        </option>
                    </select>
                </div>

                <div v-if="selectedPeriod === 'custom'">
                    <label for="from-date" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">From</label>
                    <input
                        id="from-date"
                        v-model="customFrom"
                        type="date"
                        class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-sky-500 focus:outline-none"
                    >
                </div>

                <div v-if="selectedPeriod === 'custom'">
                    <label for="to-date" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">To</label>
                    <input
                        id="to-date"
                        v-model="customTo"
                        type="date"
                        class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-sky-500 focus:outline-none"
                    >
                </div>

                <button
                    type="button"
                    class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700"
                    @click="applyFilters"
                >
                    Apply Filters
                </button>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-5 shadow-sm">
                <p class="text-sm font-medium text-sky-700">Reservations Count</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.reservations_count }}</p>
            </article>

            <article class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm">
                <p class="text-sm font-medium text-emerald-700">Total Paid Amount</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ formatMoney(stats.total_paid_amount) }}</p>
            </article>

            <article class="rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-white p-5 shadow-sm">
                <p class="text-sm font-medium text-indigo-700">Total Reservations Amount</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ formatMoney(stats.total_reservations_amount) }}</p>
            </article>

            <article class="rounded-2xl border border-violet-100 bg-gradient-to-br from-violet-50 to-white p-5 shadow-sm">
                <p class="text-sm font-medium text-violet-700">Custom Requests Count</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ stats.custom_requests_count }}</p>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-4 xl:grid-cols-2">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Most Visited Pages</h2>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500">Top 6</span>
                        <Link
                            href="/admin/page-visits"
                            class="inline-flex items-center rounded-md border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            View all
                        </Link>
                    </div>
                </div>

                <div v-if="charts.most_visited_pages.length" class="space-y-3">
                    <div
                        v-for="page in charts.most_visited_pages"
                        :key="page.page"
                        class="rounded-xl border border-slate-100 p-3"
                    >
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <p class="truncate text-sm font-medium text-slate-700">{{ page.page }}</p>
                            <p class="text-xs text-slate-500">{{ page.countries_count }} countries</p>
                        </div>
                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-sky-500 to-indigo-500"
                                :style="{ width: `${Math.max(12, Math.round((Number(page.visits_count || 0) / maxPageVisits) * 100))}%` }"
                            />
                        </div>
                        <p class="mt-1 text-xs font-semibold text-slate-600">{{ page.visits_count }} visits</p>
                    </div>
                </div>

                <p v-else class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500">
                    No page visit data in this period yet.
                </p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">CRM Contacts by Source</h2>
                    <span class="text-xs text-slate-500">Vertical chart</span>
                </div>

                <div v-if="charts.crm_contacts_by_source.length">
                    <div class="flex h-64 items-end justify-around gap-4 rounded-xl border border-slate-100 bg-slate-50/60 px-3 pb-3 pt-6">
                        <div
                            v-for="source in charts.crm_contacts_by_source"
                            :key="source.source"
                            class="flex min-w-0 flex-1 flex-col items-center"
                        >
                            <div class="relative flex h-44 w-full items-end justify-center">
                                <div
                                    class="w-12 rounded-t-xl bg-gradient-to-t from-violet-500 via-indigo-500 to-sky-500 shadow-lg"
                                    :style="{ height: `${Math.max(10, Math.round((Number(source.total || 0) / maxCrmSourceTotal) * 100))}%` }"
                                />
                                <span class="absolute -top-5 text-xs font-semibold text-slate-600">{{ source.total }}</span>
                            </div>
                            <p class="mt-2 truncate text-center text-xs font-medium text-slate-600">{{ source.label }}</p>
                        </div>
                    </div>
                </div>

                <p v-else class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500">
                    No CRM contacts recorded in this period.
                </p>
            </article>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-900">Latest 6 Reservations</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">ID</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Guest</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Paid</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Total</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Created At</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="reservation in latest_reservations" :key="reservation.id">
                            <td class="px-3 py-2 text-sm text-slate-700">#{{ reservation.id }}</td>
                            <td class="px-3 py-2">
                                <p class="text-sm font-medium text-slate-800">{{ reservation.guest_name || 'N/A' }}</p>
                                <p class="text-xs text-slate-500">{{ reservation.email || 'No email' }}</p>
                            </td>
                            <td class="px-3 py-2">
                                <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700">
                                    {{ reservation.booking_status }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-sm font-semibold text-emerald-700">{{ formatMoney(reservation.paid_amount) }}</td>
                            <td class="px-3 py-2 text-sm font-semibold text-indigo-700">{{ formatMoney(reservation.total_amount) }}</td>
                            <td class="px-3 py-2 text-xs text-slate-500">{{ reservation.created_at || '—' }}</td>
                            <td class="px-3 py-2">
                                <Link
                                    :href="`/admin/reservations/${reservation.id}`"
                                    class="inline-flex items-center rounded-md bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-700"
                                >
                                    Show details
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="!latest_reservations.length">
                            <td colspan="7" class="px-3 py-8 text-center text-sm text-slate-500">
                                No reservations found in the selected period.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</template>
