<script>
import MainLayout from '@/Layouts/Dashboard/Admin/MainLayout.vue';

export default {
    layout: MainLayout,
};
</script>

<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <Head title="Dashboard" />

        <div class="mx-auto max-w-4xl rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Welcome</h2>
            <p class="mt-2 text-sm text-slate-600">
                This is a placeholder shell. Build out admin modules here as you expand the dashboard.
            </p>
        </div>
    </div>
</template>
