<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps, watchEffect } from "vue";
import Input from '@/Components/Input.vue';
import InputIconWrapper from '@/Components/InputIconWrapper.vue';
import { SearchIcon } from '@/Components/Icons/outline';
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import PammTable from "@/Pages/Dashboard/Partials/PammTable.vue"

const formatter = ref({
    date: 'YYYY-MM-DD',
    month: 'MM'
});

const search = ref('');
const date = ref('');

</script>

<template>
    <Head title="PAMM Fund In" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-loose">PAMM Fund In</h2>
        </template>

        <div class="rounded-md shadow-md pb-3 sticky top-2 bg-gray-900 z-[5]">
            <div class="w-full">
                <div class="mb-3">
                    <div>
                        <InputIconWrapper>
                            <template #icon>
                                <SearchIcon aria-hidden="true" class="w-5 h-5 text-white" />
                            </template>
                            <Input withIcon id="search" variant="search" type="text" class="block w-full rounded-lg" placeholder="Search" v-model="search" />
                        </InputIconWrapper>
                    </div>
                </div>

                <div>
                    <vue-tailwind-datepicker
                        :placeholder="$t('public.date_placeholder')"
                        :formatter="formatter"
                        separator=" - "
                        v-model="date"
                        input-classes="py-3 px-4 w-full rounded-lg placeholder:text-gray-500 focus:ring-primary-500 hover:border-primary-500 focus:border-primary-500 bg-gray-800 text-white border border-gray-600"
                    />
                </div>
            </div>
        </div>

        <div class="p-3">
            <PammTable 
                :search="search" 
                :date="date" 
            />
        </div>
    </AuthenticatedLayout>
</template>
e