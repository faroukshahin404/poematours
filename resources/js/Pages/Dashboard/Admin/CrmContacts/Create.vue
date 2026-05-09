<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import MultiSelectBadges from '@/Components/Admin/MultiSelectBadges.vue';

const props = defineProps({
    countries: {
        type: Array,
        required: true,
    },
    services: {
        type: Array,
        required: true,
    },
    statusOptions: {
        type: Array,
        required: true,
    },
    sourceOptions: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: '',
    phone: '',
    email: '',
    country_id: '',
    status: 'pending',
    source: 'manual',
    service_ids: [],
    notes: '',
});

function submit() {
    form.post('/admin/crm-contacts', {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="Add contact" />

        <div class="mb-6">
            <Link href="/admin/crm-contacts" class="text-sm font-medium text-sky-700 hover:text-sky-900">
                ← CRM Contacts
            </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Add contact</h1>
        </div>

        <form class="max-w-2xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2"
                        :class="{ 'border-red-300': form.errors.name }"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label for="phone" class="mb-2 block text-sm font-medium text-slate-700">Phone</label>
                    <input
                        id="phone"
                        v-model="form.phone"
                        type="text"
                        required
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2"
                        :class="{ 'border-red-300': form.errors.phone }"
                    />
                    <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2"
                        :class="{ 'border-red-300': form.errors.email }"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="country_id" class="mb-2 block text-sm font-medium text-slate-700">Country</label>
                    <select
                        id="country_id"
                        v-model="form.country_id"
                        required
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2"
                        :class="{ 'border-red-300': form.errors.country_id }"
                    >
                        <option disabled value="">Select country</option>
                        <option v-for="country in countries" :key="country.id" :value="country.id">
                            {{ country.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.country_id" class="mt-1 text-sm text-red-600">{{ form.errors.country_id }}</p>
                </div>

                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                    <select
                        id="status"
                        v-model="form.status"
                        required
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2"
                        :class="{ 'border-red-300': form.errors.status }"
                    >
                        <option v-for="statusOption in props.statusOptions" :key="statusOption.value" :value="statusOption.value">
                            {{ statusOption.label }}
                        </option>
                    </select>
                    <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
                </div>

                <div>
                    <label for="source" class="mb-2 block text-sm font-medium text-slate-700">Source</label>
                    <select
                        id="source"
                        v-model="form.source"
                        required
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2"
                        :class="{ 'border-red-300': form.errors.source }"
                    >
                        <option v-for="sourceOption in props.sourceOptions" :key="sourceOption.value" :value="sourceOption.value">
                            {{ sourceOption.label }}
                        </option>
                    </select>
                    <p v-if="form.errors.source" class="mt-1 text-sm text-red-600">{{ form.errors.source }}</p>
                </div>
            </div>

            <MultiSelectBadges
                v-model="form.service_ids"
                :options="props.services.map((service) => ({ id: service.id, label: service.name }))"
                label="Services"
                placeholder="Search services..."
            />
            <p v-if="form.errors.service_ids" class="-mt-3 text-sm text-red-600">{{ form.errors.service_ids }}</p>

            <div>
                <label for="notes" class="mb-2 block text-sm font-medium text-slate-700">Notes</label>
                <textarea
                    id="notes"
                    v-model="form.notes"
                    rows="5"
                    class="block w-full rounded-lg border border-slate-200 px-3 py-2"
                    :class="{ 'border-red-300': form.errors.notes }"
                />
                <p v-if="form.errors.notes" class="mt-1 text-sm text-red-600">{{ form.errors.notes }}</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save
                </button>
                <Link
                    href="/admin/crm-contacts"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
