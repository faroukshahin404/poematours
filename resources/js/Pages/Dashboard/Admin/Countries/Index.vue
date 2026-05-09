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
    countries: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const flash = computed(() => page.props.flash?.status ?? null);

function destroyCountry(id, name) {
    if (!window.confirm(`Delete country "${name}"? This cannot be undone.`)) {
        return;
    }
    router.delete(`/admin/countries/${id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Countries</h1>
                <p class="mt-1 text-sm text-slate-600">Manage CRM countries used by contacts.</p>
            </div>
            <Link
                href="/admin/countries/create"
                class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
            >
                Add country
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
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Slug</th>
                            <th class="px-4 py-3">Contacts</th>
                            <th class="px-4 py-3">Created by</th>
                            <th class="px-4 py-3">Updated by</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="row in countries.data" :key="row.id" class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ row.name }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ row.slug }}</td>
                            <td class="px-4 py-3">{{ row.contacts_count ?? 0 }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ row.creator?.name ?? "—" }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ row.updater?.name ?? "—" }}</td>
                            <td class="px-4 py-3 text-right">
                                <Link
                                    :href="`/admin/countries/${row.id}/edit`"
                                    class="mr-3 font-medium text-sky-700 hover:text-sky-900"
                                >
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    class="font-medium text-red-600 hover:text-red-800"
                                    @click="destroyCountry(row.id, row.name)"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!countries.data?.length">
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                                No countries yet.
                                <Link href="/admin/countries/create" class="font-medium text-sky-700 hover:underline">
                                    Create the first one
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                v-if="countries.last_page > 1"
                class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-600"
            >
                <span>Page {{ countries.current_page }} of {{ countries.last_page }}</span>
                <div class="flex gap-2">
                    <Link
                        v-if="countries.prev_page_url"
                        :href="countries.prev_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="countries.next_page_url"
                        :href="countries.next_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
