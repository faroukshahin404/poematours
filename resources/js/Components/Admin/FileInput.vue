<script setup>
const props = defineProps({
    label: {
        type: String,
        default: '',
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    accept: {
        type: String,
        default: 'image/*',
    },
    modelValue: {
        type: [File, Array, null],
        default: null,
    },
});

const emit = defineEmits(['update:modelValue', 'change']);

function onChange(event) {
    const files = Array.from(event.target.files ?? []);
    const value = props.multiple ? files : (files[0] ?? null);
    emit('update:modelValue', value);
    emit('change', event);
}
</script>

<template>
    <div class="space-y-2">
        <label v-if="label" class="block text-sm font-medium text-slate-700">{{ label }}</label>
        <div class="rounded-lg border border-slate-200 bg-white p-3">
            <input
                type="file"
                :multiple="multiple"
                :accept="accept"
                class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-sky-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-sky-800 hover:file:bg-sky-100"
                @change="onChange"
            />
        </div>
    </div>
</template>
