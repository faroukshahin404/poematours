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
    types: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const languages = computed(() => page.props.languages ?? []);
const showOptions = computed(() => ['select', 'multi_select'].includes(form.type));

const form = useForm({
    title: {},
    description: {},
    type: props.types[0] ?? 'text',
    is_package_reservation: false,
    is_reservation_page: false,
    options: [],
});

watch(
    languages,
    (langs) => {
        const nextTitle = { ...form.title };
        const nextDescription = { ...form.description };

        for (const lang of langs) {
            if (nextTitle[lang.slug] === undefined) {
                nextTitle[lang.slug] = '';
            }
            if (nextDescription[lang.slug] === undefined) {
                nextDescription[lang.slug] = '';
            }
        }

        form.title = nextTitle;
        form.description = nextDescription;

        form.options = form.options.map((option) => {
            const label = { ...(option.label ?? {}) };
            for (const lang of langs) {
                if (label[lang.slug] === undefined) {
                    label[lang.slug] = '';
                }
            }

            return {
                ...option,
                label,
            };
        });
    },
    { immediate: true },
);

watch(showOptions, (value) => {
    if (!value) {
        form.options = [];
    }
});

function addOption() {
    const label = {};
    for (const lang of languages.value) {
        label[lang.slug] = '';
    }

    form.options.push({
        label,
        added_price: 0,
    });
}

function removeOption(index) {
    form.options.splice(index, 1);
}

function submit() {
    form.post('/admin/reservation-questions', {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="Add reservation question" />

        <div class="mb-6">
            <Link href="/admin/reservation-questions" class="text-sm font-medium text-sky-700 hover:text-sky-900">
                ← Reservation questions
            </Link>
            <h1 class="mt-2 text-xl font-semibold text-slate-900">Add reservation question</h1>
        </div>

        <form class="max-w-3xl space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Title (multi-language)</h2>
                <div v-for="lang in languages" :key="`title-${lang.slug}`" class="space-y-1">
                    <label :for="`title-${lang.slug}`" class="block text-sm font-medium text-slate-700">
                        {{ lang.name }} ({{ lang.slug }})
                    </label>
                    <input
                        :id="`title-${lang.slug}`"
                        v-model="form.title[lang.slug]"
                        type="text"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900"
                        :class="{ 'border-red-300': form.errors[`title.${lang.slug}`] }"
                    />
                    <p v-if="form.errors[`title.${lang.slug}`]" class="text-sm text-red-600">
                        {{ form.errors[`title.${lang.slug}`] }}
                    </p>
                </div>
                <p v-if="form.errors.title" class="text-sm text-red-600">{{ form.errors.title }}</p>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-800">Description (multi-language)</h2>
                <div v-for="lang in languages" :key="`description-${lang.slug}`" class="space-y-1">
                    <label :for="`description-${lang.slug}`" class="block text-sm font-medium text-slate-700">
                        {{ lang.name }} ({{ lang.slug }})
                    </label>
                    <textarea
                        :id="`description-${lang.slug}`"
                        v-model="form.description[lang.slug]"
                        rows="3"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900"
                        :class="{ 'border-red-300': form.errors[`description.${lang.slug}`] }"
                    />
                    <p v-if="form.errors[`description.${lang.slug}`]" class="text-sm text-red-600">
                        {{ form.errors[`description.${lang.slug}`] }}
                    </p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="type" class="mb-2 block text-sm font-medium text-slate-700">Type</label>
                    <select
                        id="type"
                        v-model="form.type"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900"
                        :class="{ 'border-red-300': form.errors.type }"
                    >
                        <option v-for="type in types" :key="type" :value="type">
                            {{ type }}
                        </option>
                    </select>
                    <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</p>
                </div>
            </div>

            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input v-model="form.is_package_reservation" type="checkbox" class="rounded border-slate-300" />
                    is_package_reservation
                </label>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input v-model="form.is_reservation_page" type="checkbox" class="rounded border-slate-300" />
                    is_reservation_page
                </label>
            </div>

            <div v-if="showOptions" class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-800">Options</h2>
                    <button
                        type="button"
                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        @click="addOption"
                    >
                        Add option
                    </button>
                </div>
                <p v-if="form.errors.options" class="text-sm text-red-600">{{ form.errors.options }}</p>

                <div
                    v-for="(option, index) in form.options"
                    :key="`option-${index}`"
                    class="space-y-3 rounded-lg border border-slate-200 p-4"
                >
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-slate-700">Option {{ index + 1 }}</h3>
                        <button
                            type="button"
                            class="text-sm font-medium text-red-600 hover:text-red-800"
                            @click="removeOption(index)"
                        >
                            Remove
                        </button>
                    </div>

                    <div class="grid gap-3">
                        <div v-for="lang in languages" :key="`option-${index}-${lang.slug}`" class="space-y-1">
                            <label class="block text-sm font-medium text-slate-700">
                                Label ({{ lang.slug }})
                            </label>
                            <input
                                v-model="option.label[lang.slug]"
                                type="text"
                                class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900"
                                :class="{ 'border-red-300': form.errors[`options.${index}.label.${lang.slug}`] }"
                            />
                            <p v-if="form.errors[`options.${index}.label.${lang.slug}`]" class="text-sm text-red-600">
                                {{ form.errors[`options.${index}.label.${lang.slug}`] }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-700">Added price</label>
                            <input
                                v-model.number="option.added_price"
                                type="number"
                                min="0"
                                step="0.01"
                                class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900"
                                :class="{ 'border-red-300': form.errors[`options.${index}.added_price`] }"
                            />
                            <p v-if="form.errors[`options.${index}.added_price`]" class="text-sm text-red-600">
                                {{ form.errors[`options.${index}.added_price`] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save
                </button>
                <Link
                    href="/admin/reservation-questions"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
