<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps, watchEffect } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import InputIconWrapper from '@/Components/InputIconWrapper.vue';
import { SearchIcon } from '@/Components/Icons/outline';
import Modal from "@/Components/Modal.vue";
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import WithdrawalTable from "@/Pages/WithdrawalRequest/Partials/WithdrawalTable.vue"

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
    <Head :title="$t('public.withdrawal_request')" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-loose">{{ $t('public.withdrawal_request') }}</h2>
        </template>

        <div class="rounded-md shadow-md pb-3 sticky top-2 bg-gray-900 z-[5]">
            <div class="w-full">
                <div class="mb-3">
                    <div>
                        <InputIconWrapper>
                            <template #icon>
                                <SearchIcon aria-hidden="true" class="w-5 h-5 text-white" />
                            </template>
                            <Input withIcon id="search" variant="search" type="text" class="block w-full rounded-lg" :placeholder="$t('public.search')" v-model="search" />
                        </InputIconWrapper>
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

        <div class="py-3">
            <WithdrawalTable 
                :search="search" 
                :date="date" 
            />
        </div>
    </AuthenticatedLayout>
</template>
