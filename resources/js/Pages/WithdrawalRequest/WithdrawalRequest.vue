<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import {ref, watch} from "vue";
import TanStackTable from '@/Components/TanStackTable.vue';

const formatter = ref({
    date: 'YYYY-MM-DD',
    month: 'MM'
});

const date = ref('');

</script>

<template>
    <Head title="Withdrawal Request" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xxl text-gray-800 dark:text-gray-200 leading-tight">Withdrawal Request</h2>
        </template>

        <div class="w-full">
            <vue-tailwind-datepicker
                :placeholder="$t('public.date_placeholder')"
                :formatter="formatter"
                separator=" - "
                v-model="date"
                input-classes="py-2.5 w-full rounded-lg placeholder:text-gray-500 focus:ring-primary-500 hover:border-primary-500 focus:border-primary-500 bg-gray-900 text-white border"
            />
        </div>

        <div class="w-full py-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Total:</h2>
        </div>
{}
    </AuthenticatedLayout>
</template>
