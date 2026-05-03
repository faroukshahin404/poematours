<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    requests: {
        type: Object,
        required: true,
    },
});

function interestLabel(value) {
    const labels = {
        history: 'Ancient history',
        'nile-cruise': 'Nile cruise',
        'desert-adventure': 'Desert adventure',
        'red-sea': 'Red Sea',
        'family-time': 'Family',
        honeymoon: 'Honeymoon',
        luxury: 'Luxury',
        'culture-food': 'Culture and food',
    };

    return labels[value] ?? value;
}
</script>

<template>
    <div>
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Customize tour requests</h1>
            <p class="mt-1 text-sm text-slate-600">
                Incoming requests from the public customize page for Egypt tours.
            </p>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Requester</th>
                            <th class="px-4 py-3">Contact</th>
                            <th class="px-4 py-3">Travelers</th>
                            <th class="px-4 py-3">Travel window</th>
                            <th class="px-4 py-3">Destinations</th>
                            <th class="px-4 py-3">Interests</th>
                            <th class="px-4 py-3">Budget</th>
                            <th class="px-4 py-3">Notes</th>
                            <th class="px-4 py-3">Submitted</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr v-for="row in requests.data" :key="row.id" class="align-top hover:bg-slate-50/70">
                            <td class="px-4 py-3 font-medium text-slate-900">
                                {{ row.full_name || 'Anonymous' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ row.contact_summary }}
                            </td>
                            <td class="px-4 py-3">{{ row.travelers }}</td>
                            <td class="px-4 py-3">{{ row.travel_window }}</td>
                            <td class="max-w-xs px-4 py-3">
                                <span class="line-clamp-2">{{ row.destinations || '—' }}</span>
                            </td>
                            <td class="max-w-xs px-4 py-3">
                                <div class="flex flex-wrap gap-1.5">
                                    <span
                                        v-for="interest in row.interests"
                                        :key="interest"
                                        class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-700"
                                    >
                                        {{ interestLabel(interest) }}
                                    </span>
                                    <span v-if="!row.interests?.length" class="text-slate-400">—</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ row.budget_range || '—' }}</td>
                            <td class="max-w-xs px-4 py-3 text-slate-600">
                                <span class="line-clamp-2">{{ row.notes_preview || '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600">{{ row.created_at }}</td>
                        </tr>
                        <tr v-if="!requests.data?.length">
                            <td colspan="9" class="px-4 py-10 text-center text-slate-500">
                                No customize requests have been submitted yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                v-if="requests.last_page > 1"
                class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-600"
            >
                <span> Page {{ requests.current_page }} of {{ requests.last_page }} </span>
                <div class="flex gap-2">
                    <Link
                        v-if="requests.prev_page_url"
                        :href="requests.prev_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="requests.next_page_url"
                        :href="requests.next_page_url"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
