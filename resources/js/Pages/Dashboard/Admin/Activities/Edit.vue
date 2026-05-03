<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';
export default { layout: MainLayout };
</script>

<script setup>
import EditableTextArea from '@/Components/Admin/EditableTextArea.vue';
import FileInput from '@/Components/Admin/FileInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
    activity: { type: Object, required: true },
    destinations: { type: Array, default: () => [] },
});
const page = usePage();
const languages = computed(() => page.props.languages ?? []);
const form = useForm({
    destination_id: props.activity.destination_id ?? '',
    name: { ...props.activity.name_translations },
    description: { ...props.activity.description_translations },
    image: null,
});

watch(languages, (langs) => {
    const name = { ...form.name };
    const description = { ...form.description };
    for (const l of langs) {
        if (name[l.slug] === undefined) name[l.slug] = '';
        if (description[l.slug] === undefined) description[l.slug] = '';
    }
    form.name = name;
    form.description = description;
}, { immediate: true });

watch(() => props.destinations, (destinations) => {
    if (destinations.length > 0 && !form.destination_id) {
        form.destination_id = destinations[0].id;
    }
}, { immediate: true });

function submit() {
    form.transform((d) => ({ ...d, _method: 'put' })).post(`/admin/activities/${props.activity.id}`, {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <div>
        <Head title="Edit activity" />
        <div class="mb-6"><Link href="/admin/activities" class="text-sm font-medium text-sky-700">← Activities</Link><h1 class="mt-2 text-xl font-semibold text-slate-900">Edit activity</h1></div>
        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Destination</label>
                <select v-model="form.destination_id" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" :class="{ 'border-red-300': form.errors.destination_id }">
                    <option v-for="d in destinations" :key="d.id" :value="d.id">{{ d.label }} ({{ d.slug }})</option>
                </select>
                <p v-if="form.errors.destination_id" class="mt-1 text-sm text-red-600">{{ form.errors.destination_id }}</p>
            </div>
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Name</h2>
                <div v-for="lang in languages" :key="`name-${lang.slug}`"><label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label><input v-model="form.name[lang.slug]" type="text" class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" /></div>
            </div>
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Description</h2>
                <div v-for="lang in languages" :key="`desc-${lang.slug}`"><label class="mb-1 block text-sm font-medium text-slate-700">{{ lang.name }} ({{ lang.slug }})</label><EditableTextArea v-model="form.description[lang.slug]" /></div>
            </div>
            <img v-if="activity.image_url" :src="activity.image_url" alt="" class="h-24 w-24 rounded object-cover" />
            <FileInput label="Replace image" @update:model-value="form.image = $event" />
            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Update</button>
                <Link href="/admin/activities" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700">Cancel</Link>
            </div>
        </form>
    </div>
</template>
