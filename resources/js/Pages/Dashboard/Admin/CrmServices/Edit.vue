<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    service: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.service.name,
});

function submit() {
    form.put(`/admin/crm-services/${props.service.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="Edit CRM service" />
        <div class="mb-6">
            <Link href="/admin/crm-services" class="text-sm font-medium text-sky-700 hover:text-sky-900">← CRM Services</Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Edit CRM service</h1>
        </div>
        <form class="max-w-lg space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                <input id="name" v-model="form.name" type="text" required class="block w-full rounded-lg border border-slate-200 px-3 py-2" :class="{ 'border-red-300': form.errors.name }">
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700" :disabled="form.processing">Update</button>
                <Link href="/admin/crm-services" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</Link>
            </div>
        </form>
    </div>
</template>
