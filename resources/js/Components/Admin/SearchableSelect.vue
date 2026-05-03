<script setup>
import { computed, ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number, null],
        default: null,
    },
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Search...',
    },
    emptyText: {
        type: String,
        default: 'No results.',
    },
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);
const query = ref('');

const selectedOption = computed(() =>
    (props.options ?? []).find((option) => String(option.id) === String(props.modelValue)) ?? null,
);

watch(
    selectedOption,
    (option) => {
        query.value = option?.label ?? '';
    },
    { immediate: true },
);

const filteredOptions = computed(() => {
    const q = query.value.trim().toLowerCase();
    if (q === '') {
        return props.options ?? [];
    }

    return (props.options ?? []).filter((option) => {
        const label = String(option.label ?? '').toLowerCase();
        const slug = String(option.slug ?? '').toLowerCase();

        return label.includes(q) || slug.includes(q);
    });
});

function selectOption(option) {
    emit('update:modelValue', option.id);
    query.value = option.label ?? '';
    open.value = false;
}

function clearSelection() {
    emit('update:modelValue', '');
    query.value = '';
}

function onFocus() {
    open.value = true;
}

function onBlur() {
    window.setTimeout(() => {
        if (selectedOption.value) {
            query.value = selectedOption.value.label ?? '';
        }
        open.value = false;
    }, 120);
}
</script>

<template>
    <div class="relative">
        <div class="relative">
            <input
                v-model="query"
                type="text"
                class="block w-full rounded-lg border border-slate-200 px-3 py-2 pr-10 text-sm"
                :placeholder="placeholder"
                @focus="onFocus"
                @blur="onBlur"
            />
            <button
                v-if="modelValue !== null && modelValue !== ''"
                type="button"
                class="absolute right-2 top-1/2 -translate-y-1/2 rounded px-1 text-xs text-slate-500 hover:bg-slate-100"
                @mousedown.prevent
                @click="clearSelection"
            >
                ×
            </button>
        </div>
        <div
            v-if="open"
            class="absolute z-20 mt-1 max-h-52 w-full overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-sm"
        >
            <button
                v-for="option in filteredOptions"
                :key="option.id"
                type="button"
                class="block w-full border-b border-slate-100 px-3 py-2 text-left text-sm text-slate-700 last:border-b-0 hover:bg-slate-50"
                @mousedown.prevent
                @click="selectOption(option)"
            >
                {{ option.label }}
            </button>
            <p v-if="filteredOptions.length === 0" class="px-3 py-2 text-sm text-slate-500">
                {{ emptyText }}
            </p>
        </div>
    </div>
</template>
