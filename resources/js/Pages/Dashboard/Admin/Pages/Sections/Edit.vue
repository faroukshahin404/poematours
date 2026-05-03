<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import EditableTextArea from '@/Components/Admin/EditableTextArea.vue';

const sectionSchemas = {
    home_hero: {
        fields: [
            { key: 'eyebrow', label: 'Eyebrow', type: 'text' },
            { key: 'title', label: 'Title', type: 'text' },
            { key: 'cta_text', label: 'CTA text', type: 'text' },
            { key: 'cta_url', label: 'CTA URL', type: 'select', options: ['/packages', '/destinations', '/contact-us'] },
            { key: 'background_image', label: 'Background image', type: 'image' },
            { key: 'background_image_alt', label: 'Background image alt', type: 'text' },
            { key: 'trust_items', label: 'Trust items', type: 'repeater', fields: [
                { key: 'line_1', label: 'Line 1', type: 'text' },
                { key: 'line_2', label: 'Line 2', type: 'text' },
            ] },
        ],
    },
    home_spirit: {
        fields: [
            { key: 'eyebrow', label: 'Eyebrow', type: 'text' },
            { key: 'title', label: 'Title', type: 'text' },
            { key: 'body', label: 'Body', type: 'textarea' },
        ],
    },
    home_tours_across_egypt: {
        fields: [
            { key: 'eyebrow', label: 'Eyebrow', type: 'text' },
            { key: 'title', label: 'Title', type: 'text' }
        ],
    },
    home_last_minute_packages: {
        fields: [
            { key: 'eyebrow', label: 'Eyebrow', type: 'text' },
            { key: 'title', label: 'Title', type: 'text' },
            { key: 'empty_state', label: 'Empty state', type: 'textarea' },
        ],
    },
    home_stories: {
        fields: [
            { key: 'eyebrow', label: 'Eyebrow', type: 'text' },
            { key: 'title', label: 'Title', type: 'text' },
            { key: 'items', label: 'Stories', type: 'repeater', fields: [
                { key: 'title', label: 'Title', type: 'text' },
                { key: 'description', label: 'Description', type: 'textarea' },
                { key: 'image', label: 'Image', type: 'image' },
                { key: 'link', label: 'Link', type: 'text' },
            ] },
        ],
    },
    home_why_poema: {
        fields: [
            { key: 'eyebrow', label: 'Eyebrow', type: 'text' },
            { key: 'title', label: 'Title', type: 'text' },
            { key: 'description', label: 'Description', type: 'textarea' },
            { key: 'items', label: 'Reasons', type: 'repeater', fields: [
                { key: 'title', label: 'Title', type: 'text' },
                { key: 'description', label: 'Description', type: 'textarea' },
            ] },
        ],
    },
    activities_labels: {
        fields: [
            { key: 'hero_title_suffix', label: 'Hero title suffix', type: 'text' },
            { key: 'hero_view_journeys_label', label: 'Hero view journeys label', type: 'text' },
            { key: 'breadcrumb_home_label', label: 'Breadcrumb home label', type: 'text' },
            { key: 'breadcrumb_packages_label', label: 'Breadcrumb packages label', type: 'text' },
            { key: 'hero_side_title', label: 'Hero side title', type: 'textarea' },
            { key: 'hero_side_description', label: 'Hero side description', type: 'textarea' },
            { key: 'intro_title_template', label: 'Intro title template (:activity)', type: 'text' },
            { key: 'trips_title_template', label: 'Trips title template (:activity)', type: 'text' },
            { key: 'empty_trips_label', label: 'Empty trips label', type: 'text' },
            { key: 'activities_list_title', label: 'Activities list section title', type: 'text' },
        ],
    },
    about_hero: {
        fields: [
            { key: 'breadcrumb_home_label', label: 'Breadcrumb home label', type: 'text' },
            { key: 'breadcrumb_current_label', label: 'Breadcrumb current label', type: 'text' },
            { key: 'title', label: 'Hero title', type: 'text' },
            { key: 'subtitle', label: 'Hero subtitle', type: 'textarea' },
        ],
    },
    about_welcome: {
        fields: [
            { key: 'title', label: 'Section title', type: 'text' },
            { key: 'paragraphs', label: 'Paragraphs', type: 'repeater', fields: [
                { key: 'value', label: 'Paragraph', type: 'textarea' },
            ] },
            { key: 'image', label: 'Welcome image', type: 'image' },
            { key: 'image_alt', label: 'Welcome image alt', type: 'text' },
        ],
    },
    about_services: {
        fields: [
            { key: 'title', label: 'Section title', type: 'text' },
            { key: 'items', label: 'Services', type: 'repeater', fields: [
                { key: 'title', label: 'Service title', type: 'text' },
                { key: 'description', label: 'Service description', type: 'textarea' },
            ] },
        ],
    },
    about_gallery: {
        fields: [
            { key: 'title', label: 'Section title', type: 'text' },
            { key: 'images', label: 'Gallery images', type: 'repeater', fields: [
                { key: 'image', label: 'Image', type: 'image' },
                { key: 'alt', label: 'Image alt', type: 'text' },
            ] },
        ],
    },
    about_latest_blogs: {
        fields: [
            { key: 'title', label: 'Section title', type: 'text' },
            { key: 'items', label: 'Blog cards', type: 'repeater', fields: [
                { key: 'title', label: 'Blog title', type: 'text' },
                { key: 'description', label: 'Blog description', type: 'textarea' },
                { key: 'image', label: 'Blog image', type: 'image' },
            ] },
        ],
    },
    legal_privacy_policy: {
        fields: [
            { key: 'breadcrumb_home_label_translations', label: 'Breadcrumb home label', type: 'translatable_text' },
            { key: 'breadcrumb_current_label_translations', label: 'Breadcrumb current label', type: 'translatable_text' },
            { key: 'title_translations', label: 'Page title', type: 'translatable_text' },
            { key: 'body_translations', label: 'Full legal text', type: 'translatable_textarea', rows: 18 },
            { key: 'contact_email', label: 'Contact email', type: 'text' },
        ],
    },
    legal_terms_of_use: {
        fields: [
            { key: 'breadcrumb_home_label_translations', label: 'Breadcrumb home label', type: 'translatable_text' },
            { key: 'breadcrumb_current_label_translations', label: 'Breadcrumb current label', type: 'translatable_text' },
            { key: 'title_translations', label: 'Page title', type: 'translatable_text' },
            { key: 'body_translations', label: 'Full legal text', type: 'translatable_textarea', rows: 18 },
            { key: 'contact_email', label: 'Contact email', type: 'text' },
        ],
    },
};

const props = defineProps({
    page: { type: Object, required: true },
    section: { type: Object, required: true },
    sections: { type: Array, required: true },
    languages: { type: Array, default: () => [] },
});

function schemaFor(key) {
    return sectionSchemas[key] ?? { fields: [] };
}

function normalizeContent(content, schema) {
    const normalized = { ...(content ?? {}) };

    for (const field of schema.fields) {
        if (field.type === 'repeater') {
            let rows = Array.isArray(normalized[field.key]) ? normalized[field.key] : [];
            if (field.key === 'season_names' || field.key === 'paragraphs') {
                rows = rows.map((value) => (typeof value === 'string' ? { value } : value));
            }
            normalized[field.key] = rows.map((row) => {
                const item = { ...(row ?? {}) };
                for (const child of field.fields) {
                    if (item[child.key] === undefined || item[child.key] === null) {
                        item[child.key] = '';
                    }
                }
                return item;
            });
            continue;
        }
        if (field.type === 'translatable_text' || field.type === 'translatable_textarea') {
            const value = normalized[field.key];
            normalized[field.key] = typeof value === 'object' && value !== null && !Array.isArray(value) ? value : {};
            for (const language of props.languages) {
                if (!Object.prototype.hasOwnProperty.call(normalized[field.key], language.slug)) {
                    normalized[field.key][language.slug] = '';
                }
            }
            continue;
        }
        if (normalized[field.key] === undefined || normalized[field.key] === null) {
            normalized[field.key] = '';
        }
    }

    return normalized;
}

const activeSchema = schemaFor(props.section.key);

const form = useForm({
    order: props.section.order ?? 0,
    is_active: !!props.section.is_active,
    content: normalizeContent(props.section.content ?? {}, activeSchema),
    uploads: {},
});

function addRepeaterItem(field) {
    if (!Array.isArray(form.content[field.key])) {
        form.content[field.key] = [];
    }
    const row = {};
    for (const child of field.fields) {
        row[child.key] = '';
    }
    form.content[field.key].push(row);
}

function removeRepeaterItem(fieldKey, index) {
    if (!Array.isArray(form.content[fieldKey])) {
        return;
    }
    form.content[fieldKey].splice(index, 1);
}

function onImageChange(fieldKey, event) {
    const file = event.target?.files?.[0] ?? null;
    if (file) {
        form.uploads[fieldKey] = file;
    }
}

function onRepeaterImageChange(collectionKey, index, childKey, event) {
    const file = event.target?.files?.[0] ?? null;
    if (file) {
        form.uploads[`${collectionKey}__${index}__${childKey}`] = file;
    }
}

function submit() {
    const payload = { ...form.content };
    if (Array.isArray(payload.season_names)) {
        payload.season_names = payload.season_names
            .map((row) => (typeof row?.value === 'string' ? row.value.trim() : ''))
            .filter((value) => value !== '');
    }
    if (Array.isArray(payload.paragraphs)) {
        payload.paragraphs = payload.paragraphs
            .map((row) => (typeof row?.value === 'string' ? row.value.trim() : ''))
            .filter((value) => value !== '');
    }

    form.content = payload;
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post(`/admin/pages/${props.page.id}/sections/${props.section.id}`, {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <div>
        <Head :title="`Edit ${section.key}`" />

        <div class="mb-6">
            <Link :href="`/admin/pages/${page.id}/edit`" class="text-sm font-medium text-sky-700 hover:text-sky-900">
                ← {{ page.name }} page
            </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Edit section: {{ section.key }}</h1>
            <p class="mt-1 text-sm text-slate-600">Update this section using dedicated field inputs.</p>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Order</label>
                        <input
                            v-model.number="form.order"
                            type="number"
                            min="0"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                        />
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                class="size-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500/30"
                            />
                            <span>Active</span>
                        </label>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Section content</h2>
                <div class="mt-4 space-y-4">
                    <template v-for="field in activeSchema.fields" :key="`field-${field.key}`">
                        <div v-if="field.type === 'text'">
                            <label class="mb-2 block text-sm font-medium text-slate-700">{{ field.label }}</label>
                            <input
                                v-model="form.content[field.key]"
                                type="text"
                                class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            />
                        </div>

                        <div v-else-if="field.type === 'textarea'">
                            <label class="mb-2 block text-sm font-medium text-slate-700">{{ field.label }}</label>
                            <textarea
                                v-model="form.content[field.key]"
                                rows="4"
                                class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            />
                        </div>

                        <div v-else-if="field.type === 'select'">
                            <label class="mb-2 block text-sm font-medium text-slate-700">{{ field.label }}</label>
                            <select
                                v-model="form.content[field.key]"
                                class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                            >
                                <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
                            </select>
                        </div>

                        <div v-else-if="field.type === 'translatable_text'">
                            <label class="mb-2 block text-sm font-medium text-slate-700">{{ field.label }}</label>
                            <div class="space-y-2">
                                <div
                                    v-for="language in languages"
                                    :key="`${field.key}-${language.slug}`"
                                    class="grid gap-2 md:grid-cols-[10rem,1fr]"
                                >
                                    <label class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        {{ language.name }} ({{ language.slug }})
                                    </label>
                                    <input
                                        v-model="form.content[field.key][language.slug]"
                                        type="text"
                                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                                    />
                                </div>
                            </div>
                        </div>

                        <div v-else-if="field.type === 'translatable_textarea'">
                            <label class="mb-2 block text-sm font-medium text-slate-700">{{ field.label }}</label>
                            <div class="space-y-2">
                                <div v-for="language in languages" :key="`${field.key}-${language.slug}`" class="space-y-1">
                                    <label class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        {{ language.name }} ({{ language.slug }})
                                    </label>
                                    <EditableTextArea
                                        v-model="form.content[field.key][language.slug]"
                                        :min-height="(field.rows || 12) * 12"
                                        placeholder="Write legal content..."
                                    />
                                </div>
                            </div>
                        </div>

                        <div v-else-if="field.type === 'image'">
                            <label class="mb-2 block text-sm font-medium text-slate-700">{{ field.label }}</label>
                            <input
                                :value="form.content[field.key] || ''"
                                type="text"
                                readonly
                                class="mb-2 block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600"
                            />
                            <input
                                type="file"
                                accept="image/*"
                                class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                                @change="onImageChange(field.key, $event)"
                            />
                        </div>

                        <div v-else-if="field.type === 'repeater'" class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-800">{{ field.label }}</p>
                                <button
                                    type="button"
                                    class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50"
                                    @click="addRepeaterItem(field)"
                                >
                                    Add item
                                </button>
                            </div>

                            <div class="space-y-3">
                                <div
                                    v-for="(row, rowIndex) in form.content[field.key]"
                                    :key="`${field.key}-${rowIndex}`"
                                    class="rounded-lg border border-slate-200 bg-white p-3"
                                >
                                    <div class="mb-2 flex justify-end">
                                        <button
                                            type="button"
                                            class="text-xs font-medium text-red-600 hover:text-red-800"
                                            @click="removeRepeaterItem(field.key, rowIndex)"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                    <div class="grid gap-3 md:grid-cols-2">
                                        <div
                                            v-for="child in field.fields"
                                            :key="`${field.key}-${rowIndex}-${child.key}`"
                                            :class="{ 'md:col-span-2': child.type === 'textarea' }"
                                        >
                                            <label class="mb-1 block text-xs font-medium text-slate-700">
                                                {{ child.label }}
                                            </label>
                                            <input
                                                v-if="child.type === 'text'"
                                                v-model="row[child.key]"
                                                type="text"
                                                class="block w-full rounded-lg border border-slate-200 px-2.5 py-2 text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                                            />
                                            <textarea
                                                v-else-if="child.type === 'textarea'"
                                                v-model="row[child.key]"
                                                rows="3"
                                                class="block w-full rounded-lg border border-slate-200 px-2.5 py-2 text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                                            />
                                            <template v-else-if="child.type === 'image'">
                                                <input
                                                    :value="row[child.key] || ''"
                                                    type="text"
                                                    readonly
                                                    class="mb-2 block w-full rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2 text-xs text-slate-600"
                                                />
                                                <input
                                                    type="file"
                                                    accept="image/*"
                                                    class="block w-full rounded-lg border border-slate-200 px-2.5 py-2 text-sm text-slate-700 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                                                    @change="onRepeaterImageChange(field.key, rowIndex, child.key, $event)"
                                                />
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save section
                </button>
                <Link
                    href="/admin/pages"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Back
                </Link>
            </div>
        </form>
    </div>
</template>
