<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

const form = useForm({
    enabled: Boolean(props.settings.enabled),
    publishable_key: props.settings.publishable_key ?? '',
    secret_key: '',
    webhook_secret: props.settings.webhook_secret ?? '',
    success_url: props.settings.success_url ?? '',
    cancel_url: props.settings.cancel_url ?? '',
    default_currency: props.settings.default_currency ?? 'USD',
});

function submit() {
    form.put('/admin/settings/payment', {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="Payment settings" />

        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Payment settings</h1>
            <p class="mt-1 text-sm text-slate-600">
                Configure secure Stripe checkout configuration used by backend payment services.
            </p>
        </div>

        <div
            v-if="flash"
            class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            role="status"
        >
            {{ flash }}
        </div>

        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <section>
                <h2 class="text-base font-semibold text-slate-900">Gateway mode</h2>
                <label class="mt-3 inline-flex items-center gap-2 text-sm text-slate-700">
                    <input v-model="form.enabled" type="checkbox" class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500" />
                    Enable Stripe gateway
                </label>
            </section>

            <section class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Stripe secret key</label>
                    <input
                        v-model="form.secret_key"
                        type="password"
                        autocomplete="new-password"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        placeholder="sk_live_..."
                    />
                    <p class="mt-1 text-xs text-slate-500">
                        Leave empty to keep existing key. Stored encrypted in database.
                    </p>
                    <p v-if="props.settings.has_secret_key" class="mt-1 text-xs text-emerald-700">
                        A secret key is already saved.
                    </p>
                    <p v-if="form.errors.secret_key" class="mt-1 text-sm text-red-600">{{ form.errors.secret_key }}</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Publishable key</label>
                    <input
                        v-model="form.publishable_key"
                        type="text"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        placeholder="pk_live_..."
                    />
                    <p v-if="form.errors.publishable_key" class="mt-1 text-sm text-red-600">{{ form.errors.publishable_key }}</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Webhook secret</label>
                    <input
                        v-model="form.webhook_secret"
                        type="text"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        placeholder="whsec_..."
                    />
                    <p v-if="form.errors.webhook_secret" class="mt-1 text-sm text-red-600">{{ form.errors.webhook_secret }}</p>
                </div>
            </section>

            <section class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Success URL (HTTPS)</label>
                    <input
                        v-model="form.success_url"
                        type="url"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        placeholder="https://example.com/payment/success"
                    />
                    <p v-if="form.errors.success_url" class="mt-1 text-sm text-red-600">{{ form.errors.success_url }}</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Cancel URL (HTTPS)</label>
                    <input
                        v-model="form.cancel_url"
                        type="url"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        placeholder="https://example.com/payment/cancel"
                    />
                    <p v-if="form.errors.cancel_url" class="mt-1 text-sm text-red-600">{{ form.errors.cancel_url }}</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Default currency</label>
                    <select
                        v-model="form.default_currency"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                    >
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                        <option value="AED">AED</option>
                        <option value="SAR">SAR</option>
                        <option value="EGP">EGP</option>
                    </select>
                    <p v-if="form.errors.default_currency" class="mt-1 text-sm text-red-600">{{ form.errors.default_currency }}</p>
                </div>
            </section>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save settings
                </button>
            </div>
        </form>
    </div>
</template>
