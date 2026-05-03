<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    stripe_publishable_key: props.settings.stripe_publishable_key ?? '',
    stripe_secret_key: props.settings.stripe_secret_key ?? '',
    stripe_webhook_secret: props.settings.stripe_webhook_secret ?? '',
    stripe_mode: props.settings.stripe_mode ?? 'test',
    stripe_enabled: Boolean(props.settings.stripe_enabled),
    reservation_currency: props.settings.reservation_currency ?? 'USD',
    reservation_deposit_percentage: props.settings.reservation_deposit_percentage ?? 20,
});

function submit() {
    form.put('/admin/settings/payments', { preserveScroll: true });
}
</script>

<template>
    <div>
        <Head title="Payments settings" />
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Payments settings</h1>
            <p class="mt-1 text-sm text-slate-600">Manage Stripe keys, currency, and reservation deposit percentage.</p>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Stripe</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Publishable key</label>
                        <input v-model="form.stripe_publishable_key" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="text">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Secret key</label>
                        <input v-model="form.stripe_secret_key" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="text">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Webhook secret</label>
                        <input v-model="form.stripe_webhook_secret" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" type="text">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Mode</label>
                        <select v-model="form.stripe_mode" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="test">Test</option>
                            <option value="live">Live</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Currency</label>
                        <input v-model="form.reservation_currency" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm uppercase" maxlength="3" type="text">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Deposit percentage</label>
                        <input v-model.number="form.reservation_deposit_percentage" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" min="1" max="100" type="number">
                    </div>
                </div>
                <label class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input v-model="form.stripe_enabled" type="checkbox">
                    Enable online payment
                </label>
            </section>

            <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white" :disabled="form.processing">
                Save settings
            </button>
        </form>
    </div>
</template>
