<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';
export default { layout: MainLayout };
</script>

<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const page = usePage();
const languages = computed(() => page.props.languages ?? []);
const form = useForm({ name: {} });

watch(languages, (langs) => {
    const name = { ...form.name };
    for (const lang of langs) {
        if (name[lang.slug] === undefined) name[lang.slug] = '';
    }
    form.name = name;
}, { immediate: true });

function submit() {
    form.post('/admin/blog-categories', { preserveScroll: true });
}
</script>

<template>
    <div>
        <Head title="Add blog category" />
        <div class="mb-6"><Link href="/admin/blog-categories" class="text-sm font-medium text-sky-700">← Blog categories</Link><h1 class="mt-2 text-xl font-semibold text-slate-900">Add blog category</h1></div>
        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Name</h2>
                <div v-for="lang in languages" :key="`name-${lang.slug}`">
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label>
                    <input v-model="form.name[lang.slug]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" :class="{ 'border-red-300': form.errors[`name.${lang.slug}`] }" />
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Save</button>
                <Link href="/admin/blog-categories" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700">Cancel</Link>
            </div>
        </form>
    </div>
</template>
