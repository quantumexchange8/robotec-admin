<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from "vue";

</script>

<template>
    <Head title="Withdrawal Request" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xxl text-gray-800 dark:text-gray-200 leading-tight">Network</h2>
        </template>

    </AuthenticatedLayout>
</template>
