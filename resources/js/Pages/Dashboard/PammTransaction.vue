<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps, watchEffect } from "vue";
import Input from '@/Components/Input.vue';
import InputIconWrapper from '@/Components/InputIconWrapper.vue';
import { SearchIcon } from '@/Components/Icons/outline';
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import PammTable from "@/Pages/Dashboard/Partials/PammTable.vue"
import Search from '@/Components/Search.vue';

const formatter = ref({
    date: 'YYYY-MM-DD',
    month: 'MM'
});

const search = ref('');
const today = new Date();
const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

// Formatting the start and end of the current month
const formattedStartDate = `${firstDayOfMonth.getFullYear()}-${(firstDayOfMonth.getMonth() + 1).toString().padStart(2, '0')}-01`;
const formattedEndDate = `${lastDayOfMonth.getFullYear()}-${(lastDayOfMonth.getMonth() + 1).toString().padStart(2, '0')}-${lastDayOfMonth.getDate().toString().padStart(2, '0')}`;

// Set the initial date range to the current month
const date = ref(`${formattedStartDate} - ${formattedEndDate}`);

</script>

<template>
    <Head :title="$t('public.pamm_fund_in')" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-loose mb-3">{{ $t('public.pamm_fund_in') }}</h2>
        </template>

        <div class="py-3 sticky top-0 bg-gray-900 z-[5]">
            <div class="w-full">
                <div class="mb-3">
                    <div>
                        <Search v-model="search" :placeholder="$t('public.search')" />
                    </div>
                </div>

                <div>
                    <vue-tailwind-datepicker
                        :formatter="formatter"
                        separator=" - "
                        v-model="date"
                        input-classes="py-3 px-4 w-full rounded-lg placeholder:text-gray-500 focus:ring-primary-500 hover:border-primary-500 focus:border-primary-500 bg-gray-800 text-white border border-gray-600"
                    />
                </div>
            </div>
        </div>

        <div>
            <PammTable 
                :search="search" 
                :date="date" 
            />
        </div>
    </AuthenticatedLayout>
</template>
e