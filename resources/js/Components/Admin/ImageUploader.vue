<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    uploadUrl: {
        type: String,
        default: '/admin/reels/upload-snapshot',
    },
    label: {
        type: String,
        default: 'Image',
    },
    previewUrl: {
        type: String,
        default: '',
    },
    fieldName: {
        type: String,
        default: 'snapshot',
    },
});

const emit = defineEmits(['update:modelValue', 'uploaded']);

const uploading = ref(false);
const progress = ref(0);
const error = ref('');
const localPreviewUrl = ref('');

const displayedPreview = computed(() => localPreviewUrl.value || props.previewUrl);

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

function onChange(event) {
    const file = event.target.files?.[0] ?? null;
    if (!file) {
        return;
    }

    upload(file);
}

function upload(file) {
    const token = csrfToken();
    const formData = new FormData();
    formData.append(props.fieldName, file);

    const request = new XMLHttpRequest();
    uploading.value = true;
    progress.value = 0;
    error.value = '';

    request.upload.onprogress = (event) => {
        if (!event.lengthComputable) {
            return;
        }

        progress.value = Math.round((event.loaded / event.total) * 100);
    };

    request.onreadystatechange = () => {
        if (request.readyState !== XMLHttpRequest.DONE) {
            return;
        }

        uploading.value = false;

        if (request.status < 200 || request.status >= 300) {
            error.value = 'Image upload failed. Please try again.';
            return;
        }

        const response = JSON.parse(request.responseText || '{}');
        const path = typeof response.path === 'string' ? response.path : '';
        const url = typeof response.url === 'string' ? response.url : '';

        if (!path || !url) {
            error.value = 'Upload response is invalid.';
            return;
        }

        localPreviewUrl.value = url;
        emit('update:modelValue', path);
        emit('uploaded', { path, url });
    };

    request.open('POST', props.uploadUrl, true);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    if (token) {
        request.setRequestHeader('X-CSRF-TOKEN', token);
    }
    request.send(formData);
}
</script>

<template>
    <div class="space-y-2">
        <label class="block text-sm font-medium text-slate-700">{{ label }}</label>
        <div class="rounded-lg border border-slate-200 bg-white p-3">
            <input
                type="file"
                accept="image/jpeg,image/png,image/webp,image/gif"
                class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-sky-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-sky-800 hover:file:bg-sky-100"
                :disabled="uploading"
                @change="onChange"
            />
            <p v-if="modelValue" class="mt-2 text-xs text-slate-500">Stored path: {{ modelValue }}</p>
        </div>

        <div v-if="uploading" class="rounded-lg border border-sky-100 bg-sky-50 p-3">
            <div class="mb-2 flex items-center justify-between text-xs font-medium text-sky-700">
                <span>Uploading image...</span>
                <span>{{ progress }}%</span>
            </div>
            <div class="h-2 rounded-full bg-sky-100">
                <div class="h-2 rounded-full bg-sky-600 transition-all" :style="{ width: `${progress}%` }"></div>
            </div>
        </div>

        <p v-if="error" class="text-xs text-red-600">{{ error }}</p>

        <img
            v-if="displayedPreview"
            :src="displayedPreview"
            alt="Preview"
            class="w-full max-w-xs rounded-lg border border-slate-200 object-cover"
        />
    </div>
</template>
