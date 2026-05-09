<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';
export default { layout: MainLayout };
</script>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({ blogs: { type: Object, required: true } });
const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

function destroyBlog(id, label) {
    if (!window.confirm(`Delete blog "${label}"?`)) return;
    router.delete(`/admin/blogs/${id}`, { preserveScroll: true });
}
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-slate-900">Blogs</h1>
            <Link href="/admin/blogs/create" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Add blog</Link>
        </div>
        <div v-if="flash" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ flash }}</div>
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    <tr><th class="px-4 py-3">Image</th><th class="px-4 py-3">Title</th><th class="px-4 py-3">Category</th><th class="px-4 py-3">Featured</th><th class="px-4 py-3">Views</th><th class="px-4 py-3 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr v-for="row in blogs.data" :key="row.id">
                        <td class="px-4 py-3"><img v-if="row.images?.[0]?.url" :src="row.images[0].url" alt="" class="h-12 w-12 rounded object-cover" /></td>
                        <td class="px-4 py-3"><div class="font-medium text-slate-900">{{ row.title }}</div><div class="line-clamp-2 text-xs text-slate-500">{{ row.details }}</div></td>
                        <td class="px-4 py-3">{{ row.category ?? '-' }}</td>
                        <td class="px-4 py-3">{{ row.is_featured ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">{{ row.views_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="`/admin/blogs/${row.id}/edit`" class="mr-3 font-medium text-sky-700">Edit</Link>
                            <button type="button" class="font-medium text-red-600" @click="destroyBlog(row.id, row.title)">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!blogs.data?.length"><td colspan="6" class="px-4 py-8 text-center text-slate-500">No blogs yet.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
