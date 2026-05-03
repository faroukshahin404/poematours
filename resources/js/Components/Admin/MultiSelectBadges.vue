<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
    options: {
        type: Array,
        default: () => [],
    },
    label: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Search and select...',
    },
});

const emit = defineEmits(['update:modelValue']);
const query = ref('');

const selectedIds = computed(() => (props.modelValue ?? []).map((v) => Number(v)));

const selectedOptions = computed(() =>
    (props.options ?? []).filter((option) => selectedIds.value.includes(Number(option.id))),
);

const filteredOptions = computed(() => {
    const q = query.value.trim().toLowerCase();
    return (props.options ?? []).filter((option) => {
        if (selectedIds.value.includes(Number(option.id))) {
            return false;
        }

        if (q === '') {
            return true;
        }

        const label = String(option.label ?? '').toLowerCase();
        const slug = String(option.slug ?? '').toLowerCase();

        return label.includes(q) || slug.includes(q);
    });
});

function selectOption(optionId) {
    const next = [...selectedIds.value, Number(optionId)];
    emit('update:modelValue', Array.from(new Set(next)));
    query.value = '';
}

function removeOption(optionId) {
    emit(
        'update:modelValue',
        selectedIds.value.filter((id) => id !== Number(optionId)),
    );
}
</script>

<template>
    <div class="space-y-2">
        <label v-if="label" class="block text-sm font-medium text-slate-700">{{ label }}</label>
        <div class="rounded-lg border border-slate-200 bg-white p-3">
            <div class="mb-2 flex flex-wrap gap-2">
                <span
                    v-for="option in selectedOptions"
                    :key="`selected-${option.id}`"
                    class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-2.5 py-1 text-xs font-medium text-sky-800"
                >
                    {{ option.label }}
                    <button
                        type="button"
                        class="rounded-full px-1 text-sky-700 hover:bg-sky-200"
                        @click="removeOption(option.id)"
                    >
                        ×
                    </button>
                </span>
            </div>

            <input
                v-model="query"
                type="text"
                class="mb-2 block w-full rounded-md border border-slate-200 px-3 py-2 text-sm"
                :placeholder="placeholder"
            />

            <div class="max-h-40 overflow-y-auto rounded-md border border-slate-200">
                <button
                    v-for="option in filteredOptions"
                    :key="`option-${option.id}`"
                    type="button"
                    class="block w-full border-b border-slate-100 px-3 py-2 text-left text-sm text-slate-700 last:border-b-0 hover:bg-slate-50"
                    @click="selectOption(option.id)"
                >
                    <span class="font-medium">{{ option.label }}</span>
                    <span v-if="option.slug" class="ml-1 text-xs text-slate-500">({{ option.slug }})</span>
                </button>
                <p v-if="filteredOptions.length === 0" class="px-3 py-2 text-sm text-slate-500">
                    No more matches.
                </p>
            </div>
        </div>
    </div>
</template>
