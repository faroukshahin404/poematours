<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

const form = useForm({
    notification_emails: props.settings.notification_emails ?? [],
});

const emailInput = ref('');

function addEmail() {
    const value = emailInput.value.trim().toLowerCase();
    if (value === '') {
        return;
    }

    if (!form.notification_emails.includes(value)) {
        form.notification_emails.push(value);
    }

    emailInput.value = '';
}

function removeEmail(index) {
    form.notification_emails.splice(index, 1);
}

function onEmailInputKeydown(event) {
    if (event.key === 'Enter' || event.key === ',' || event.key === 'Tab') {
        event.preventDefault();
        addEmail();
    }
}

function submit() {
    addEmail();

    form.put('/admin/settings/general', {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="General settings" />

        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">General settings</h1>
            <p class="mt-1 text-sm text-slate-600">
                Configure recipients for system notification emails.
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
                <h2 class="text-base font-semibold text-slate-900">Notification recipients</h2>
                <p class="mt-1 text-sm text-slate-600">
                    Add one or more email addresses. Press Enter, comma, or Tab to add.
                </p>

                <div class="mt-4">
                    <label for="notification-email-input" class="mb-2 block text-sm font-medium text-slate-700">
                        Recipient email
                    </label>
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                        <input
                            id="notification-email-input"
                            v-model="emailInput"
                            type="email"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            placeholder="name@example.com"
                            @keydown="onEmailInputKeydown"
                        />
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                            @click="addEmail"
                        >
                            Add
                        </button>
                    </div>

                    <p v-if="form.errors.notification_emails" class="mt-2 text-sm text-red-600">
                        {{ form.errors.notification_emails }}
                    </p>
                </div>

                <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-3">
                    <p class="text-sm font-medium text-slate-700">Current recipients</p>

                    <ul v-if="form.notification_emails.length" class="mt-2 flex flex-wrap gap-2">
                        <li
                            v-for="(email, index) in form.notification_emails"
                            :key="email"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-sm text-slate-800"
                        >
                            <span>{{ email }}</span>
                            <button
                                type="button"
                                class="text-slate-500 transition hover:text-red-600"
                                :aria-label="`Remove ${email}`"
                                @click="removeEmail(index)"
                            >
                                &times;
                            </button>
                        </li>
                    </ul>

                    <p v-else class="mt-2 text-sm text-slate-500">
                        No recipients added yet.
                    </p>
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
