<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default { layout: MainLayout };
</script>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({ blogCategories: { type: Object, required: true } });

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyCategory(id, label) {
    if (!window.confirm(`Delete blog category "${label}"?`)) return;
    router.delete(`/admin/blog-categories/${id}`, { preserveScroll: true });
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-slate-900">Blog categories</h1>
            <Link href="/admin/blog-categories/create" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Add category</Link>
        </div>
        <div v-if="flash" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ flash }}</div>
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    <tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Slug</th><th class="px-4 py-3">Blogs</th><th class="px-4 py-3 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr v-for="row in blogCategories.data" :key="row.id">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ row.name }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ row.slug }}</td>
                        <td class="px-4 py-3">{{ row.blogs_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="`/admin/blog-categories/${row.id}/edit`" class="mr-3 font-medium text-sky-700">Edit</Link>
                            <button type="button" class="font-medium text-red-600" @click="destroyCategory(row.id, row.name)">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!blogCategories.data?.length">
                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">No categories yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
