<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import EmptyHistory from '@/Components/EmptyHistory.vue';

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">Dashboard</h2>
        </template>

        <div>
            <div class="text-gray-300 text-sm">Total Net Balance ($):</div>
            <div class="text-xl font-semibold text-white">0.00</div>
        </div>
    </AuthenticatedLayout>
</template>
