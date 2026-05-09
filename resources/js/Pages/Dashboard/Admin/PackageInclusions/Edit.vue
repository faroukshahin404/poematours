<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default { layout: MainLayout };
</script>

<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
    packageInclusion: { type: Object, required: true },
});

const page = usePage();
const languages = computed(() => page.props.languages ?? []);
const form = useForm({
    name: { ...props.packageInclusion.name_translations },
    icon: props.packageInclusion.icon ?? '',
});

watch(
    languages,
    (langs) => {
        const name = { ...form.name };
        for (const l of langs) {
            if (name[l.slug] === undefined) name[l.slug] = '';
        }
        form.name = name;
    },
    { immediate: true },
);

function submit() {
    form.put(`/admin/package-inclusions/${props.packageInclusion.id}`, { preserveScroll: true });
}
</script>

<template>
    <div>
        <Head title="Edit package inclusion" />
        <div class="mb-6">
            <Link href="/admin/package-inclusions" class="text-sm font-medium text-sky-700">← Package inclusions</Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Edit package inclusion</h1>
            <p v-if="packageInclusion.created_by_name" class="mt-1 text-sm text-slate-500">
                Created by {{ packageInclusion.created_by_name }}
            </p>
        </div>
        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Name</h2>
                <div v-for="lang in languages" :key="`name-${lang.slug}`">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <input
                        v-model="form.name[lang.slug]"
                        type="text"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"
                    />
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Icon (optional)</label>
                <input
                    v-model="form.icon"
                    type="text"
                    placeholder="e.g. heroicon name or CSS class"
                    class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm font-mono"
                />
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Update</button>
                <Link
                    href="/admin/package-inclusions"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
