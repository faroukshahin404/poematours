<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    package: {
        type: Object,
        required: true,
    },
    reviews: {
        type: Array,
        required: true,
    },
});

const page = usePage();
const flash = computed(() => page.props.flash?.status ?? null);

const baseUrl = computed(() => `/admin/packages/${props.package.id}/package-reviews`);

const createForm = useForm({
    reviewer_name: '',
    reviewer_address: '',
    comment: '',
    rate: 5,
});

const editingId = ref(null);
const editForm = useForm({
    reviewer_name: '',
    reviewer_address: '',
    comment: '',
    rate: 5,
});

function submitCreate() {
    createForm.post(baseUrl.value, {
        preserveScroll: true,
        onSuccess: () => {
            createForm.reset();
            createForm.rate = 5;
        },
    });
}

function startEdit(row) {
    editingId.value = row.id;
    editForm.reviewer_name = row.reviewer_name;
    editForm.reviewer_address = row.reviewer_address ?? '';
    editForm.comment = row.comment;
    editForm.rate = row.rate;
    editForm.clearErrors();
}

function cancelEdit() {
    editingId.value = null;
    editForm.reset();
}

function submitEdit() {
    if (!editingId.value) {
        return;
    }

    editForm.put(`${baseUrl.value}/${editingId.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            cancelEdit();
        },
    });
}

function destroyReview(id, name) {
    if (!window.confirm(`Delete review from "${name}"?`)) {
        return;
    }

    router.delete(`${baseUrl.value}/${id}`, { preserveScroll: true });
}
</script>

<template>
    <div>
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="min-w-0 flex-1">
                <nav class="mb-2 text-sm text-slate-500">
                    <Link href="/admin/packages" class="font-medium text-sky-700 hover:text-sky-900">Packages</Link>
                    <span class="mx-1.5">/</span>
                    <span class="text-slate-700">Reviews</span>
                </nav>
                <h1 class="text-xl font-semibold text-slate-900">Package reviews</h1>
                <p class="mt-1 text-sm text-slate-600">
                    {{ package.title }} — manage guest testimonials shown on the public package page.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <Link
                    :href="`/admin/packages/${package.id}/edit`"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                >
                    Edit package
                </Link>
                <Link
                    href="/admin/packages"
                    class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
                >
                    All packages
                </Link>
            </div>
        </div>

        <div
            v-if="flash"
            class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            role="status"
        >
            {{ flash }}
        </div>

        <div class="mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Add review</h2>
            <p class="mt-1 text-sm text-slate-600">Stored fields: reviewer name, location, comment, and star rating (1–5).</p>
            <form class="mt-4 grid gap-4 sm:grid-cols-2" @submit.prevent="submitCreate">
                <div class="sm:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">Reviewer name</label>
                    <input
                        v-model="createForm.reviewer_name"
                        type="text"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"
                        required
                    >
                    <p v-if="createForm.errors.reviewer_name" class="mt-1 text-xs text-red-600">{{ createForm.errors.reviewer_name }}</p>
                </div>
                <div class="sm:col-span-1">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">Reviewer address</label>
                    <input
                        v-model="createForm.reviewer_address"
                        type="text"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"
                        placeholder="e.g. Nevada, USA"
                    >
                    <p v-if="createForm.errors.reviewer_address" class="mt-1 text-xs text-red-600">{{ createForm.errors.reviewer_address }}</p>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">Comment</label>
                    <textarea
                        v-model="createForm.comment"
                        rows="3"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"
                        required
                    />
                    <p v-if="createForm.errors.comment" class="mt-1 text-xs text-red-600">{{ createForm.errors.comment }}</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">Rate (1–5)</label>
                    <select v-model.number="createForm.rate" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                        <option v-for="n in 5" :key="n" :value="n">{{ n }} stars</option>
                    </select>
                    <p v-if="createForm.errors.rate" class="mt-1 text-xs text-red-600">{{ createForm.errors.rate }}</p>
                </div>
                <div class="flex items-end">
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 disabled:opacity-60 sm:w-auto"
                        :disabled="createForm.processing"
                    >
                        Save review
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-4 py-3">
                <h2 class="text-base font-semibold text-slate-900">Existing reviews ({{ reviews.length }})</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Address</th>
                            <th class="px-4 py-3">Rate</th>
                            <th class="px-4 py-3">Comment</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <template v-for="row in reviews" :key="row.id">
                            <tr v-if="editingId !== row.id" class="align-top hover:bg-slate-50/80">
                                <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ row.id }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ row.reviewer_name }}</td>
                                <td class="px-4 py-3">{{ row.reviewer_address || '—' }}</td>
                                <td class="px-4 py-3">{{ row.rate }}</td>
                                <td class="max-w-md px-4 py-3 text-slate-600">{{ row.comment }}</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <button type="button" class="mr-3 font-medium text-sky-700 hover:text-sky-900" @click="startEdit(row)">
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="font-medium text-red-600 hover:text-red-800"
                                        @click="destroyReview(row.id, row.reviewer_name)"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-else class="bg-sky-50/40">
                                <td colspan="6" class="px-4 py-4">
                                    <form class="grid gap-3 sm:grid-cols-2" @submit.prevent="submitEdit">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600">Reviewer name</label>
                                            <input v-model="editForm.reviewer_name" type="text" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" required>
                                            <p v-if="editForm.errors.reviewer_name" class="mt-1 text-xs text-red-600">{{ editForm.errors.reviewer_name }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600">Reviewer address</label>
                                            <input v-model="editForm.reviewer_address" type="text" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                                            <p v-if="editForm.errors.reviewer_address" class="mt-1 text-xs text-red-600">{{ editForm.errors.reviewer_address }}</p>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-xs font-semibold text-slate-600">Comment</label>
                                            <textarea v-model="editForm.comment" rows="3" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" required />
                                            <p v-if="editForm.errors.comment" class="mt-1 text-xs text-red-600">{{ editForm.errors.comment }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600">Rate</label>
                                            <select v-model.number="editForm.rate" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                                                <option v-for="n in 5" :key="`e-${n}`" :value="n">{{ n }}</option>
                                            </select>
                                            <p v-if="editForm.errors.rate" class="mt-1 text-xs text-red-600">{{ editForm.errors.rate }}</p>
                                        </div>
                                        <div class="flex flex-wrap items-end gap-2 sm:col-span-2">
                                            <button
                                                type="submit"
                                                class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 disabled:opacity-60"
                                                :disabled="editForm.processing"
                                            >
                                                Save changes
                                            </button>
                                            <button type="button" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white" @click="cancelEdit">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </template>
                        <tr v-if="!reviews.length">
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500">No reviews yet. Add one above.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
