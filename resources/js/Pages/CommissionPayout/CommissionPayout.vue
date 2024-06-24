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
import { SearchIcon, FilterIcon } from '@/Components/Icons/outline';
import Modal from "@/Components/Modal.vue";
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from "@headlessui/vue";
import PendingRequest from "@/Pages/CommissionPayout/PendingRequest.vue";
import CommissionHistory from "@/Pages/CommissionPayout/CommissionHistory.vue"
import Search from '@/Components/Search.vue';

const props = defineProps({
    totalCommissionRequest: [String, Number],
    totalCommissionHistory: [String, Number],
});

const formatter = ref({
    date: 'YYYY-MM-DD',
    month: 'MM'
});

const totalCommissionRequest = ref(props.totalCommissionRequest);
const totalCommissionHistory = ref(props.totalCommissionHistory);
const search = ref('');
const today = new Date();
const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

// Formatting the start and end of the current month
const formattedStartDate = `${firstDayOfMonth.getFullYear()}-${(firstDayOfMonth.getMonth() + 1).toString().padStart(2, '0')}-01`;
const formattedEndDate = `${lastDayOfMonth.getFullYear()}-${(lastDayOfMonth.getMonth() + 1).toString().padStart(2, '0')}-${lastDayOfMonth.getDate().toString().padStart(2, '0')}`;

// Set the initial date range to the current month
const date = ref(`${formattedStartDate} - ${formattedEndDate}`);
const type = ref('Pending');
const updateCommissionType = (commission_type) => {
    type.value = commission_type;
};
// Event handlers to update totals from child components
const updateTotalCommission = (pending, history) => {
    totalCommissionRequest.value = pending;
    totalCommissionHistory.value = history;
};

</script>

<template>
    <Head :title="$t('public.commission_payout')" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-loose mb-3">{{ $t('public.commission_payout') }}</h2>
        </template>

        <div class="rounded-md shadow-md mb-3">
            <div class="w-full">
                <TabGroup>
                    <TabList class="max-w-md flex py-3 sticky top-0 bg-gray-900 z-[5]">
                        <Tab
                            as="template"
                            v-slot="{ selected }"
                            class="px-3 py-2"
                        >
                            <button
                                @click="updateCommissionType('Pending')"
                                :class="[
                                    'w-full py-2.5 text-sm font-semibold text-gray-300',
                                    'ring-white ring-offset-0 focus:outline-none focus:ring-0',
                                    selected
                                        ? 'text-white border-b-2 border-white'
                                        : 'border-b border-gray-700',
                                ]"
                            >
                                {{ $t('public.pending_request') }} ({{ totalCommissionRequest ?? 0 }})
                            </button>
                        </Tab>
                        <Tab
                            as="template"
                            v-slot="{ selected }"
                            class="px-3 py-2"
                        >
                            <button
                                @click="updateCommissionType('History')"
                                :class="[
                                    'w-full py-2.5 text-sm font-semibold text-gray-300',
                                    'ring-white ring-offset-0 focus:outline-none focus:ring-0',
                                    selected
                                        ? 'text-white border-b-2 border-white'
                                        : 'border-b border-gray-700',
                                ]"
                            >
                                {{ $t('public.history') }} ({{ totalCommissionHistory ?? 0 }})
                            </button>
                        </Tab>
                    </TabList>
                    <div class="sticky top-16 bg-gray-900 z-[5]">
                        <div>
                            <Search v-model="search" :placeholder="$t('public.search')" />
                        </div>
                        <div class="mt-3">
                            <vue-tailwind-datepicker
                                :placeholder="$t('public.date_placeholder')"
                                :formatter="formatter"
                                separator=" - "
                                v-model="date"
                                input-classes="py-3 px-4 w-full rounded-lg placeholder:text-gray-500 focus:ring-primary-500 hover:border-primary-500 focus:border-primary-500 bg-gray-800 text-white border border-gray-600"
                            />
                        </div>
                    </div>

                    <TabPanels>
                        <TabPanel>
                            <PendingRequest 
                                :search="search" 
                                :date="date" 
                                :type="type" 
                                @update:totalCommissionRequest="updateTotalCommission"
                                />
                        </TabPanel>
                        <TabPanel>
                            <CommissionHistory 
                                :search="search" 
                                :date="date" 
                                :type="type" 
                                @update:totalCommissionHistory="updateTotalCommission"
                            />
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
