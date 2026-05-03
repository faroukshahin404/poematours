<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
    groupOptions: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();

const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    slug: '',
    name: {},
    package_label_group_id: '',
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

watch(
    () => props.groupOptions,
    (opts) => {
        if (opts.length && (form.package_label_group_id === '' || form.package_label_group_id === null)) {
            form.package_label_group_id = opts[0].id;
        }
    },
    { immediate: true },
);

const hasLanguages = computed(() => languages.value.length > 0);
const hasGroups = computed(() => props.groupOptions.length > 0);

function submit() {
    form.post('/admin/package-labels', {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="Add package label" />

        <div class="mb-6">
            <Link href="/admin/package-labels" class="text-sm font-medium text-sky-700 hover:text-sky-900">
                ← Package labels
            </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Add package label</h1>
            <p class="mt-1 text-sm text-slate-600">
                Choose a label group, then enter names per language. Slug is generated from the default language name if
                left blank.
            </p>
        </div>

        <div
            v-if="!hasLanguages"
            class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900"
        >
            Add at least one language in Settings before creating labels.
        </div>

        <div
            v-else-if="!hasGroups"
            class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900"
        >
            Create at least one label group before adding labels.
            <Link href="/admin/package-label-groups/create" class="font-medium text-sky-800 underline">Add a group</Link>
        </div>

        <form
            v-else
            class="max-w-2xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
            @submit.prevent="submit"
        >
            <div>
                <label for="package_label_group_id" class="mb-2 block text-sm font-medium text-slate-700">Label group</label>
                <select
                    id="package_label_group_id"
                    v-model="form.package_label_group_id"
                    name="package_label_group_id"
                    required
                    class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                    :class="{ 'border-red-300': form.errors.package_label_group_id }"
                >
                    <option v-for="g in groupOptions" :key="g.id" :value="g.id">
                        {{ g.label }} ({{ g.slug }})
                    </option>
                </select>
                <p v-if="form.errors.package_label_group_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.package_label_group_id }}
                </p>
            </div>

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

            <div class="flex gap-3 pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save
                </button>
                <Link
                    href="/admin/package-labels"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
