<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    contacts: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
    statusOptions: {
        type: Array,
        required: true,
    },
    countries: {
        type: Array,
        required: true,
    },
    services: {
        type: Array,
        required: true,
    },
    users: {
        type: Array,
        required: true,
    },
    sourceOptions: {
        type: Array,
        required: true,
    },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);
const statusColumns = computed(() => props.statusOptions ?? []);
const currentView = computed(() => String(props.filters?.view ?? 'table'));
const filtersOpen = ref(false);
const draggingContactId = ref(null);
const activeQueryString = computed(() => {
    const params = new URLSearchParams();

    for (const [key, value] of Object.entries(props.filters ?? {})) {
        if (value !== null && value !== undefined && String(value).trim() !== '') {
            params.set(key, String(value));
        }
    }

    const query = params.toString();

    return query ? `?${query}` : '';
});

const exportUrl = computed(() => {
    const params = new URLSearchParams();

    for (const [key, value] of Object.entries(props.filters ?? {})) {
        if (key === 'view') {
            continue;
        }

        if (value !== null && value !== undefined && String(value).trim() !== '') {
            params.set(key, String(value));
        }
    }

    const query = params.toString();

    return query ? `/admin/crm-contacts-export?${query}` : '/admin/crm-contacts-export';
});

function destroyContact(id, name) {
    if (!window.confirm(`Delete contact "${name}"? This cannot be undone.`)) {
        return;
    }

    router.delete(`/admin/crm-contacts/${id}`, {
        preserveScroll: true,
    });
}

function updateStatus(contact, status) {
    router.put(`/admin/crm-contacts/${contact.id}/status${activeQueryString.value}`, {
        status,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
}

function applyFilters(event) {
    const form = new FormData(event.target);

    router.get('/admin/crm-contacts', {
        search: form.get('search') || undefined,
        status: form.get('status') || undefined,
        source: form.get('source') || undefined,
        country_id: form.get('country_id') || undefined,
        service_id: form.get('service_id') || undefined,
        created_by: form.get('created_by') || undefined,
        updated_by: form.get('updated_by') || undefined,
        archived: form.get('archived') || undefined,
        created_from: form.get('created_from') || undefined,
        created_to: form.get('created_to') || undefined,
        view: form.get('view') || 'table',
    }, {
        preserveState: true,
        preserveScroll: true,
    });
}

function resetFilters() {
    router.get('/admin/crm-contacts', { view: currentView.value }, {
        preserveState: true,
        preserveScroll: true,
    });
}

function contactsByStatus(status) {
    return (props.contacts.data ?? []).filter((contact) => contact.status === status);
}

function updateArchiveState(contact, archived) {
    router.put(`/admin/crm-contacts/${contact.id}/archive${activeQueryString.value}`, {
        archived,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
}

function onDragStart(contact) {
    draggingContactId.value = contact.id;
}

function onDragEnd() {
    draggingContactId.value = null;
}

function onDropStatus(status) {
    if (draggingContactId.value === null) {
        return;
    }

    const contact = (props.contacts.data ?? []).find((row) => row.id === draggingContactId.value);
    draggingContactId.value = null;

    if (!contact || contact.status === status) {
        return;
    }

    updateStatus(contact, status);
}
</script>

<template>
    <div>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">CRM Contacts</h1>
                <p class="mt-1 text-sm text-slate-600">Manage contacts for the CRM module.</p>
            </div>
            <div class="flex items-center gap-2">
                <a
                    :href="exportUrl"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                >
                    Export Excel
                </a>
                <Link
                    href="/admin/crm-contacts/create"
                    class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700"
                >
                    Add contact
                </Link>
            </div>
        </div>

        <form class="mb-5 rounded-xl border border-slate-200 bg-white p-4 shadow-sm" @submit.prevent="applyFilters">
            <input type="hidden" name="view" :value="currentView" />
            <div class="mb-3 flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-700">Filters</p>
                <button type="button" class="text-sm font-medium text-sky-700 hover:text-sky-900" @click="filtersOpen = !filtersOpen">
                    {{ filtersOpen ? 'Hide filters' : 'Show filters' }}
                </button>
            </div>
            <div v-show="filtersOpen" class="grid gap-3 md:grid-cols-3 lg:grid-cols-4">
                <input
                    name="search"
                    :value="filters.search ?? ''"
                    type="text"
                    placeholder="Search name, phone, email"
                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm"
                />
                <select name="status" :value="filters.status ?? ''" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">All statuses</option>
                    <option v-for="status in statusOptions" :key="`filter-status-${status.value}`" :value="status.value">
                        {{ status.label }}
                    </option>
                </select>
                <select name="source" :value="filters.source ?? ''" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">All sources</option>
                    <option v-for="source in sourceOptions" :key="`filter-source-${source.value}`" :value="source.value">
                        {{ source.label }}
                    </option>
                </select>
                <select name="country_id" :value="filters.country_id ?? ''" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">All countries</option>
                    <option v-for="country in countries" :key="`filter-country-${country.id}`" :value="country.id">
                        {{ country.name }}
                    </option>
                </select>
                <select name="service_id" :value="filters.service_id ?? ''" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">All services</option>
                    <option v-for="service in services" :key="`filter-service-${service.id}`" :value="service.id">
                        {{ service.name }}
                    </option>
                </select>
                <select name="created_by" :value="filters.created_by ?? ''" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">Created by (all)</option>
                    <option v-for="user in users" :key="`filter-created-${user.id}`" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
                <select name="updated_by" :value="filters.updated_by ?? ''" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">Updated by (all)</option>
                    <option v-for="user in users" :key="`filter-updated-${user.id}`" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
                <select name="archived" :value="filters.archived ?? ''" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">Active contacts</option>
                    <option value="archived">Archived contacts</option>
                </select>
                <input
                    name="created_from"
                    :value="filters.created_from ?? ''"
                    type="date"
                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm"
                />
                <input
                    name="created_to"
                    :value="filters.created_to ?? ''"
                    type="date"
                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm"
                />
            </div>
            <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                <div class="inline-flex rounded-lg border border-slate-200 bg-slate-50 p-1 text-sm">
                    <Link
                        href="/admin/crm-contacts"
                        :data="{ ...filters, view: 'table' }"
                        class="rounded-md px-3 py-1.5"
                        :class="currentView === 'table' ? 'bg-white font-semibold text-slate-900 shadow-sm' : 'text-slate-600'"
                    >
                        Table
                    </Link>
                    <Link
                        href="/admin/crm-contacts"
                        :data="{ ...filters, view: 'kanban' }"
                        class="rounded-md px-3 py-1.5"
                        :class="currentView === 'kanban' ? 'bg-white font-semibold text-slate-900 shadow-sm' : 'text-slate-600'"
                    >
                        Kanban
                    </Link>
                </div>
                <div class="flex gap-2">
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
            </div>
        </form>

        <div
            v-if="flash"
            class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            role="status"
        >
            {{ flash }}
        </div>

        <div v-if="currentView === 'table'" class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Phone</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Country</th>
                            <th class="px-4 py-3">Source</th>
                            <th class="px-4 py-3">Services</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Archived</th>
                            <th class="px-4 py-3">Notes</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="row in contacts.data" :key="row.id" class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ row.name }}</td>
                            <td class="px-4 py-3">{{ row.phone }}</td>
                            <td class="px-4 py-3">{{ row.email ?? "—" }}</td>
                            <td class="px-4 py-3">{{ row.country?.name ?? "—" }}</td>
                            <td class="px-4 py-3">{{ row.source_label ?? row.source ?? "—" }}</td>
                            <td class="px-4 py-3">
                                <div v-if="row.services?.length" class="flex flex-wrap gap-1">
                                    <span
                                        v-for="service in row.services"
                                        :key="`service-${row.id}-${service.id}`"
                                        class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-700"
                                    >
                                        {{ service.name }}
                                    </span>
                                </div>
                                <span v-else>—</span>
                            </td>
                            <td class="px-4 py-3">
                                <select
                                    class="rounded-lg border border-slate-200 px-2 py-1 text-xs"
                                    :value="row.status"
                                    @change="updateStatus(row, $event.target.value)"
                                >
                                    <option v-for="status in statusOptions" :key="`row-status-${row.id}-${status.value}`" :value="status.value">
                                        {{ status.label }}
                                    </option>
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    :class="row.archived_at ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'"
                                    class="rounded-full px-2 py-0.5 text-xs font-medium"
                                >
                                    {{ row.archived_at ? "Archived" : "Active" }}
                                </span>
                            </td>
                            <td class="max-w-xs px-4 py-3">
                                <p class="line-clamp-2">{{ row.notes ?? "—" }}</p>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <Link
                                    :href="`/admin/crm-contacts/${row.id}/edit`"
                                    class="mr-3 font-medium text-sky-700 hover:text-sky-900"
                                >
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    class="mr-3 font-medium text-amber-700 hover:text-amber-900"
                                    @click="updateArchiveState(row, !row.archived_at)"
                                >
                                    {{ row.archived_at ? "Restore" : "Archive" }}
                                </button>
                                <button
                                    type="button"
                                    class="font-medium text-red-600 hover:text-red-800"
                                    @click="destroyContact(row.id, row.name)"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!contacts.data?.length">
                            <td colspan="10" class="px-4 py-10 text-center text-slate-500">
                                No contacts yet.
                                <Link href="/admin/crm-contacts/create" class="font-medium text-sky-700 hover:underline">
                                    Create the first one
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                v-if="contacts.last_page > 1"
                class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-600"
            >
                <span>Page {{ contacts.current_page }} of {{ contacts.last_page }}</span>
                <div class="flex gap-2">
                    <Link
                        v-if="contacts.prev_page_url"
                        :href="contacts.prev_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="contacts.next_page_url"
                        :href="contacts.next_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </div>

        <div v-else class="grid gap-4 xl:grid-cols-5">
            <section
                v-for="column in statusColumns"
                :key="`kanban-${column.value}`"
                class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm"
                @dragover.prevent
                @drop="onDropStatus(column.value)"
            >
                <header class="mb-3 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-800">{{ column.label }}</h3>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600">
                        {{ contactsByStatus(column.value).length }}
                    </span>
                </header>
                <div class="space-y-2">
                    <article
                        v-for="contact in contactsByStatus(column.value)"
                        :key="`kanban-card-${contact.id}`"
                        class="rounded-lg border border-slate-200 bg-slate-50 p-3"
                        draggable="true"
                        @dragstart="onDragStart(contact)"
                        @dragend="onDragEnd"
                    >
                        <p class="text-sm font-semibold text-slate-900">{{ contact.name }}</p>
                        <p class="text-xs text-slate-600">{{ contact.phone }}</p>
                        <p class="text-xs text-slate-500">{{ contact.country?.name ?? "—" }}</p>
                        <p class="text-[11px] text-slate-500">Source: {{ contact.source_label ?? contact.source ?? "—" }}</p>
                        <div class="mt-2">
                            <select
                                class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs"
                                :value="contact.status"
                                @change="updateStatus(contact, $event.target.value)"
                            >
                                <option v-for="status in statusOptions" :key="`kanban-status-${contact.id}-${status.value}`" :value="status.value">
                                    {{ status.label }}
                                </option>
                            </select>
                        </div>
                        <div class="mt-2 flex flex-wrap gap-1">
                            <span
                                v-for="service in contact.services ?? []"
                                :key="`kanban-service-${contact.id}-${service.id}`"
                                class="rounded-full bg-sky-100 px-2 py-0.5 text-[11px] text-sky-800"
                            >
                                {{ service.name }}
                            </span>
                        </div>
                        <Link :href="`/admin/crm-contacts/${contact.id}/edit`" class="mt-2 inline-block text-xs font-medium text-sky-700 hover:underline">
                            Edit
                        </Link>
                        <button
                            type="button"
                            class="ml-3 mt-2 inline-block text-xs font-medium text-amber-700 hover:underline"
                            @click="updateArchiveState(contact, !contact.archived_at)"
                        >
                            {{ contact.archived_at ? "Restore" : "Archive" }}
                        </button>
                    </article>
                    <p v-if="contactsByStatus(column.value).length === 0" class="text-xs text-slate-400">No contacts.</p>
                </div>
            </section>
        </div>
    </div>
</template>
