<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    reservation_addon_groups: Array.isArray(props.settings.reservation_addon_groups) ? props.settings.reservation_addon_groups : [],
});

function addGroup() {
    form.reservation_addon_groups.push({
        code: '',
        title: '',
        selection_type: 'multiple',
        is_required: false,
        sort_order: form.reservation_addon_groups.length,
        options: [],
    });
}

function removeGroup(index) {
    form.reservation_addon_groups.splice(index, 1);
}

function addOption(group) {
    group.options.push({
        code: '',
        label: '',
        price: 0,
        price_type: 'flat',
        is_active: true,
        sort_order: group.options.length,
    });
}

function removeOption(group, optionIndex) {
    group.options.splice(optionIndex, 1);
}

function submit() {
    form.put('/admin/settings/reservation-addons', { preserveScroll: true });
}
</script>

<template>
    <div>
        <Head title="Reservation add-ons settings" />
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Reservation add-ons settings</h1>
            <p class="mt-1 text-sm text-slate-600">Manage add-on groups, options, and prices for the reservation page.</p>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Add-on groups</h2>
                    <button type="button" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium" @click="addGroup">Add group</button>
                </div>

                <div v-if="form.reservation_addon_groups.length === 0" class="rounded-lg border border-dashed border-slate-300 p-4 text-sm text-slate-500">
                    No add-on groups yet.
                </div>

                <div v-for="(group, groupIndex) in form.reservation_addon_groups" :key="groupIndex" class="mb-4 rounded-lg border border-slate-200 p-4">
                    <div class="mb-3 grid grid-cols-1 gap-3 md:grid-cols-5">
                        <input v-model="group.code" class="rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="Group code">
                        <input v-model="group.title" class="rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="Group title">
                        <select v-model="group.selection_type" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="multiple">Multiple</option>
                            <option value="single">Single</option>
                        </select>
                        <input v-model.number="group.sort_order" class="rounded-lg border border-slate-200 px-3 py-2 text-sm" type="number" min="0" placeholder="Sort">
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input v-model="group.is_required" type="checkbox"> Required
                        </label>
                    </div>

                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-700">Options</p>
                        <div class="flex gap-2">
                            <button type="button" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm" @click="addOption(group)">Add option</button>
                            <button type="button" class="rounded-lg border border-red-200 px-3 py-1.5 text-sm text-red-600" @click="removeGroup(groupIndex)">Remove group</button>
                        </div>
                    </div>

                    <div v-for="(option, optionIndex) in group.options" :key="optionIndex" class="mb-2 grid grid-cols-1 gap-2 md:grid-cols-7">
                        <input v-model="option.code" class="rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="Option code">
                        <input v-model="option.label" class="rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="Label">
                        <input v-model.number="option.price" class="rounded-lg border border-slate-200 px-3 py-2 text-sm" min="0" step="0.01" type="number" placeholder="Price">
                        <select v-model="option.price_type" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="flat">Flat</option>
                            <option value="per_person">Per person</option>
                        </select>
                        <input v-model.number="option.sort_order" class="rounded-lg border border-slate-200 px-3 py-2 text-sm" type="number" min="0" placeholder="Sort">
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input v-model="option.is_active" type="checkbox"> Active
                        </label>
                        <button type="button" class="rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600" @click="removeOption(group, optionIndex)">Remove</button>
                    </div>
                </div>
            </section>

            <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white" :disabled="form.processing">
                Save add-ons
            </button>
        </form>
    </div>
</template>
