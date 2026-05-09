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
    email: props.settings.email ?? '',
    phone_country_1: props.settings.phone_country_1 ?? 'Egypt',
    phone_number_1: props.settings.phone_number_1 ?? '',
    phone_country_2: props.settings.phone_country_2 ?? 'USA',
    phone_number_2: props.settings.phone_number_2 ?? '',
    facebook_url: props.settings.facebook_url ?? '',
    instagram_url: props.settings.instagram_url ?? '',
    tripadvisor_url: props.settings.tripadvisor_url ?? '',
    tiktok_url: props.settings.tiktok_url ?? '',
    x_url: props.settings.x_url ?? '',
    linkedin_url: props.settings.linkedin_url ?? '',
    social_email: props.settings.social_email ?? '',
});

function submit() {
    form.put('/admin/settings/contact', {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="Contact settings" />

        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Contact settings</h1>
            <p class="mt-1 text-sm text-slate-600">
                Manage contact channels displayed to users across website pages.
            </p>
        </div>

        <div
            v-if="flash"
            class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            role="status"
        >
            {{ flash }}
        </div>

        <form class="max-w-4xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <section class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Main contact email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        placeholder="info@example.com"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>
            </section>

            <section>
                <h2 class="text-base font-semibold text-slate-900">Phone numbers</h2>
                <div class="mt-3 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Country 1</label>
                        <input
                            v-model="form.phone_country_1"
                            type="text"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            placeholder="Egypt"
                        />
                        <p v-if="form.errors.phone_country_1" class="mt-1 text-sm text-red-600">{{ form.errors.phone_country_1 }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Phone 1</label>
                        <input
                            v-model="form.phone_number_1"
                            type="text"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            placeholder="+20 100 000 0000"
                        />
                        <p v-if="form.errors.phone_number_1" class="mt-1 text-sm text-red-600">{{ form.errors.phone_number_1 }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Country 2</label>
                        <input
                            v-model="form.phone_country_2"
                            type="text"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            placeholder="USA"
                        />
                        <p v-if="form.errors.phone_country_2" class="mt-1 text-sm text-red-600">{{ form.errors.phone_country_2 }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Phone 2</label>
                        <input
                            v-model="form.phone_number_2"
                            type="text"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            placeholder="+1 555 000 0000"
                        />
                        <p v-if="form.errors.phone_number_2" class="mt-1 text-sm text-red-600">{{ form.errors.phone_number_2 }}</p>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-base font-semibold text-slate-900">Social links</h2>
                <div class="mt-3 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Facebook</label>
                        <input v-model="form.facebook_url" type="url" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" placeholder="https://facebook.com/your-page" />
                        <p v-if="form.errors.facebook_url" class="mt-1 text-sm text-red-600">{{ form.errors.facebook_url }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Instagram</label>
                        <input v-model="form.instagram_url" type="url" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" placeholder="https://instagram.com/your-page" />
                        <p v-if="form.errors.instagram_url" class="mt-1 text-sm text-red-600">{{ form.errors.instagram_url }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Tripadvisor</label>
                        <input v-model="form.tripadvisor_url" type="url" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" placeholder="https://tripadvisor.com/..." />
                        <p v-if="form.errors.tripadvisor_url" class="mt-1 text-sm text-red-600">{{ form.errors.tripadvisor_url }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">TikTok</label>
                        <input v-model="form.tiktok_url" type="url" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" placeholder="https://tiktok.com/@your-page" />
                        <p v-if="form.errors.tiktok_url" class="mt-1 text-sm text-red-600">{{ form.errors.tiktok_url }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">LinkedIn</label>
                        <input v-model="form.linkedin_url" type="url" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" placeholder="https://linkedin.com/company/your-page" />
                        <p v-if="form.errors.linkedin_url" class="mt-1 text-sm text-red-600">{{ form.errors.linkedin_url }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">X (Twitter)</label>
                        <input v-model="form.x_url" type="url" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20" placeholder="https://x.com/your-page" />
                        <p v-if="form.errors.x_url" class="mt-1 text-sm text-red-600">{{ form.errors.x_url }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Social email</label>
                        <input
                            v-model="form.social_email"
                            type="email"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            placeholder="social@example.com"
                        />
                        <p v-if="form.errors.social_email" class="mt-1 text-sm text-red-600">{{ form.errors.social_email }}</p>
                    </div>
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
