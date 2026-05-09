<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    filters: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
    top_packages: {
        type: Array,
        required: true,
    },
    top_pages: {
        type: Array,
        required: true,
    },
    visits: {
        type: Object,
        required: true,
    },
});

function applyFilters(event) {
    const form = new FormData(event.target);

    router.get('/admin/page-visits', {
        search: form.get('search') || undefined,
        country_code: form.get('country_code') || undefined,
        from: form.get('from') || undefined,
        to: form.get('to') || undefined,
        only_packages: form.get('only_packages') ? 1 : undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
}

function resetFilters() {
    router.get('/admin/page-visits', {}, {
        preserveState: true,
        preserveScroll: true,
    });
}

function routeParametersSummary(parameters) {
    if (!parameters || typeof parameters !== 'object') {
        return '—';
    }

    const entries = Object.entries(parameters);

    if (!entries.length) {
        return '—';
    }

    return entries
        .map(([key, value]) => `${key}: ${value ?? ''}`)
        .join(', ');
}
</script>

<template>
    <div class="space-y-6">
        <Head title="Page Visits Analytics" />

        <section class="rounded-2xl bg-gradient-to-r from-indigo-600 via-sky-600 to-cyan-600 p-5 text-white shadow-lg">
            <h1 class="text-xl font-semibold tracking-tight">Page Visits Analytics</h1>
            <p class="mt-1 text-sm text-indigo-100">
                Detailed traffic monitoring for pages, countries, and package slugs.
            </p>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Visits</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ stats.total_visits }}</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Unique Pages</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ stats.unique_pages }}</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Unique Countries</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ stats.unique_countries }}</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Package Detail Visits</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ stats.package_page_visits }}</p>
            </article>
        </section>

        <form class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm" @submit.prevent="applyFilters">
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-5">
                <input
                    name="search"
                    :value="filters.search ?? ''"
                    type="text"
                    placeholder="Search route, path, package slug"
                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm"
                />
                <input
                    name="country_code"
                    :value="filters.country_code ?? ''"
                    type="text"
                    maxlength="2"
                    placeholder="Country code (EG, US...)"
                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase"
                />
                <input
                    name="from"
                    :value="filters.from ?? ''"
                    type="date"
                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm"
                />
                <input
                    name="to"
                    :value="filters.to ?? ''"
                    type="date"
                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm"
                />
                <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700">
                    <input
                        name="only_packages"
                        type="checkbox"
                        value="1"
                        :checked="Boolean(filters.only_packages)"
                    >
                    Only package visits
                </label>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
                    Apply filters
                </button>
                <button
                    type="button"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    @click="resetFilters"
                >
                    Reset
                </button>
            </div>
        </form>

        <section class="grid grid-cols-1 gap-4 xl:grid-cols-2">
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="mb-3 text-sm font-semibold text-slate-800">Top Package Slugs</h2>
                <ul v-if="top_packages.length" class="space-y-2">
                    <li
                        v-for="item in top_packages"
                        :key="item.slug"
                        class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 text-sm"
                    >
                        <span class="font-medium text-slate-700">{{ item.slug }}</span>
                        <span class="rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-700">{{ item.visits_count }}</span>
                    </li>
                </ul>
                <p v-else class="text-sm text-slate-500">No package slug visits in selected range.</p>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="mb-3 text-sm font-semibold text-slate-800">Top Pages</h2>
                <ul v-if="top_pages.length" class="space-y-2">
                    <li
                        v-for="item in top_pages"
                        :key="item.page"
                        class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 text-sm"
                    >
                        <span class="font-medium text-slate-700">{{ item.page }}</span>
                        <span class="rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-semibold text-sky-700">{{ item.visits_count }}</span>
                    </li>
                </ul>
                <p v-else class="text-sm text-slate-500">No page traffic in selected range.</p>
            </article>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Visited At</th>
                            <th class="px-4 py-3">Route</th>
                            <th class="px-4 py-3">Path</th>
                            <th class="px-4 py-3">Package Slug</th>
                            <th class="px-4 py-3">Route Params</th>
                            <th class="px-4 py-3">Country</th>
                            <th class="px-4 py-3">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="visit in visits.data" :key="visit.id" class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 text-xs">{{ visit.visited_at ?? '—' }}</td>
                            <td class="px-4 py-3">{{ visit.route_name ?? '—' }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ visit.path ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    v-if="visit.package_slug"
                                    class="rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-700"
                                >
                                    {{ visit.package_slug }}
                                </span>
                                <span v-else>—</span>
                            </td>
                            <td class="max-w-sm px-4 py-3 text-xs text-slate-600">{{ routeParametersSummary(visit.route_parameters) }}</td>
                            <td class="px-4 py-3 text-xs">
                                {{ visit.country_name || visit.country_code || 'Unknown' }}
                            </td>
                            <td class="px-4 py-3 font-mono text-xs">{{ visit.ip_address || '—' }}</td>
                        </tr>
                        <tr v-if="!visits.data?.length">
                            <td colspan="7" class="px-4 py-10 text-center text-slate-500">No visits found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                v-if="visits.last_page > 1"
                class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-600"
            >
                <span>Page {{ visits.current_page }} of {{ visits.last_page }}</span>
                <div class="flex gap-2">
                    <Link
                        v-if="visits.prev_page_url"
                        :href="visits.prev_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="visits.next_page_url"
                        :href="visits.next_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>
