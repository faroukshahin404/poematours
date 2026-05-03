<script setup>
import { computed, nextTick, ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Write here...',
    },
    minHeight: {
        type: Number,
        default: 140,
    },
});

const emit = defineEmits(['update:modelValue', 'blur']);
const editor = ref(null);

const content = computed(() => props.modelValue ?? '');

watch(
    content,
    async (value) => {
        await nextTick();
        if (!editor.value) {
            return;
        }

        if (editor.value.innerHTML !== value) {
            editor.value.innerHTML = value || '';
        }
    },
    { immediate: true },
);

function onInput(event) {
    emit('update:modelValue', event.target.innerHTML);
}

function command(name, value = null) {
    if (!editor.value) {
        return;
    }
    editor.value.focus();
    document.execCommand(name, false, value);
    emit('update:modelValue', editor.value.innerHTML);
}

function stripDirectionWrapper(html) {
    const trimmed = (html ?? '').trim();
    const match = trimmed.match(
        /^<div[^>]*data-editor-direction="(rtl|ltr)"[^>]*>([\s\S]*)<\/div>$/i,
    );

    if (!match) {
        return trimmed;
    }

    return match[2] ?? '';
}

function setDirection(direction) {
    if (!editor.value) {
        return;
    }
    editor.value.focus();

    const content = stripDirectionWrapper(editor.value.innerHTML);
    const align = direction === 'rtl' ? 'right' : 'left';
    editor.value.innerHTML = `<div data-editor-direction="${direction}" dir="${direction}" style="text-align:${align};">${content}</div>`;

    emit('update:modelValue', editor.value.innerHTML);
}
</script>

<template>
    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        <div class="flex flex-wrap items-center gap-1 border-b border-slate-200 bg-slate-50 p-2">
            <button type="button" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100" @click="command('bold')">B</button>
            <button type="button" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs italic text-slate-700 hover:bg-slate-100" @click="command('italic')">I</button>
            <button type="button" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 hover:bg-slate-100" @click="command('justifyLeft')">Left</button>
            <button type="button" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 hover:bg-slate-100" @click="command('justifyCenter')">Center</button>
            <button type="button" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 hover:bg-slate-100" @click="command('justifyRight')">Right</button>
            <button type="button" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 hover:bg-slate-100" @click="command('insertUnorderedList')">• List</button>
            <button type="button" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 hover:bg-slate-100" @click="command('insertOrderedList')">1. List</button>
            <button type="button" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 hover:bg-slate-100" @click="setDirection('ltr')">LTR</button>
            <button type="button" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 hover:bg-slate-100" @click="setDirection('rtl')">RTL</button>
            <button type="button" class="rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 hover:bg-slate-100" @click="command('removeFormat')">Clear</button>
        </div>
        <div
            ref="editor"
            contenteditable="true"
            class="w-full px-3 py-2 text-sm text-slate-900 focus:outline-none"
            :style="{ minHeight: `${minHeight}px` }"
            :data-placeholder="placeholder"
            @input="onInput"
            @blur="$emit('blur')"
        />
    </div>
</template>

<style scoped>
[contenteditable][data-placeholder]:empty::before {
    content: attr(data-placeholder);
    color: #94a3b8;
    pointer-events: none;
}
</style>
