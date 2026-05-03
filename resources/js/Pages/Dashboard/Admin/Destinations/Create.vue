<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const page = usePage();

const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    slug: '',
    name: {},
    lat: '',
    lng: '',
    image: null,
});

watch(
    languages,
    (langs) => {
        const next = { ...form.name };
        for (const l of langs) {
            if (next[l.slug] === undefined) {
                next[l.slug] = '';
            }
        }
        form.name = next;
    },
    { immediate: true },
);

const hasLanguages = computed(() => languages.value.length > 0);

function onFileChange(event) {
    const file = event.target.files?.[0];
    form.image = file ?? null;
}

function submit() {
    form.post('/admin/destinations', {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <div>
        <Head title="Add destination" />

        <div class="mb-6">
            <Link href="/admin/destinations" class="text-sm font-medium text-sky-700 hover:text-sky-900">
                ← Destinations
            </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Add destination</h1>
            <p class="mt-1 text-sm text-slate-600">
                Enter a name per language (keys match language slugs). Slug is generated from the default language name
                if left blank.
            </p>
        </div>

        <div
            v-if="!hasLanguages"
            class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900"
        >
            Add at least one language in Settings before creating destinations.
        </div>

        <form
            v-else
            class="max-w-2xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
            @submit.prevent="submit"
        >
            <div>
                <label for="slug" class="mb-2 block text-sm font-medium text-slate-700">Slug (optional)</label>
                <input
                    id="slug"
                    v-model="form.slug"
                    type="text"
                    name="slug"
                    class="block w-full rounded-lg border border-slate-200 px-3 py-2 font-mono text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                    :class="{ 'border-red-300': form.errors.slug }"
                    placeholder="Leave blank to auto-generate from default language name"
                />
                <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Names</h2>
                <div v-for="lang in languages" :key="lang.slug" class="space-y-1">
                    <label :for="`name-${lang.slug}`" class="block text-sm font-medium text-slate-700">
                        {{ lang.name }}
                        <span class="font-mono text-xs font-normal text-slate-500">({{ lang.slug }})</span>
                    </label>
                    <input
                        :id="`name-${lang.slug}`"
                        v-model="form.name[lang.slug]"
                        type="text"
                        :name="`name[${lang.slug}]`"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        :class="{ 'border-red-300': form.errors[`name.${lang.slug}`] }"
                    />
                    <p v-if="form.errors[`name.${lang.slug}`]" class="text-sm text-red-600">
                        {{ form.errors[`name.${lang.slug}`] }}
                    </p>
                </div>
                <p v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <div>
                <label for="image" class="mb-2 block text-sm font-medium text-slate-700">Image</label>
                <input
                    id="image"
                    type="file"
                    name="image"
                    accept="image/*"
                    class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-sky-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-sky-800 hover:file:bg-sky-100"
                    @change="onFileChange"
                />
                <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">{{ form.errors.image }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="lat" class="mb-2 block text-sm font-medium text-slate-700">Latitude (optional)</label>
                    <input
                        id="lat"
                        v-model="form.lat"
                        type="number"
                        step="any"
                        name="lat"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        :class="{ 'border-red-300': form.errors.lat }"
                        placeholder="e.g. 30.0444"
                    />
                    <p v-if="form.errors.lat" class="mt-1 text-sm text-red-600">{{ form.errors.lat }}</p>
                </div>
                <div>
                    <label for="lng" class="mb-2 block text-sm font-medium text-slate-700">Longitude (optional)</label>
                    <input
                        id="lng"
                        v-model="form.lng"
                        type="number"
                        step="any"
                        name="lng"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        :class="{ 'border-red-300': form.errors.lng }"
                        placeholder="e.g. 31.2357"
                    />
                    <p v-if="form.errors.lng" class="mt-1 text-sm text-red-600">{{ form.errors.lng }}</p>
                </div>
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
                    href="/admin/destinations"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
