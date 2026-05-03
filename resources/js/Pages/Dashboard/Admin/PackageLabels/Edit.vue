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
    packageLabel: {
        type: Object,
        required: true,
    },
    groupOptions: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();

const languages = computed(() => page.props.languages ?? []);

const form = useForm({
    slug: props.packageLabel.slug,
    name: { ...props.packageLabel.name_translations },
    package_label_group_id: props.packageLabel.package_label_group_id,
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

function submit() {
    form
        .transform((data) => ({ ...data, _method: 'put' }))
        .post(`/admin/package-labels/${props.packageLabel.id}`, {
            preserveScroll: true,
        });
}
</script>

<template>
    <div>
        <Head title="Edit package label" />

        <div class="mb-6">
            <Link href="/admin/package-labels" class="text-sm font-medium text-sky-700 hover:text-sky-900">
                ← Package labels
            </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Edit package label</h1>
            <p class="mt-1 text-sm text-slate-600">Update group assignment, localized names, or slug.</p>
        </div>

        <form
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
                <label for="slug" class="mb-2 block text-sm font-medium text-slate-700">Slug</label>
                <input
                    id="slug"
                    v-model="form.slug"
                    type="text"
                    name="slug"
                    required
                    class="block w-full rounded-lg border border-slate-200 px-3 py-2 font-mono text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                    :class="{ 'border-red-300': form.errors.slug }"
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
                    Update
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
