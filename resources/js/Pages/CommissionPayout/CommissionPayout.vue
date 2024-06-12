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

</script>

<template>
    <Head :title="$t('public.commission_payout')" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xxl text-white leading-loose">{{ $t('public.commission_payout') }}</h2>
        </template>

        <div class="rounded-md shadow-md mb-3">
            <div class="w-full">
                <TabGroup>
                    <TabList class="max-w-md flex mb-3 sticky top-2 bg-gray-900 z-[5]">
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
                    <div class="mb-3 sticky top-16 bg-gray-900 z-[5]">
                        <div>
                            <InputIconWrapper>
                                <template #icon>
                                    <SearchIcon aria-hidden="true" class="w-5 h-5 text-white" />
                                </template>
                                <Input withIcon id="search" variant="search" type="text" class="block w-full rounded-lg" :placeholder="$t('public.search')" v-model="search" />
                            </InputIconWrapper>
                        </div>
                    </div>

                    <div class="mb-3 sticky top-32 bg-gray-900 z-[5]">
                        <vue-tailwind-datepicker
                            :placeholder="$t('public.date_placeholder')"
                            :formatter="formatter"
                            separator=" - "
                            v-model="date"
                            input-classes="py-3 px-4 w-full rounded-lg placeholder:text-gray-500 focus:ring-primary-500 hover:border-primary-500 focus:border-primary-500 bg-gray-800 text-white border border-gray-600"
                        />
                    </div>

                    <TabPanels>
                        <TabPanel>
                            <PendingRequest 
                                :search="search" 
                                :date="date" 
                                :type="type" 
                                @update:totalCommissionRequest="totalCommissionRequest = $event"
                            />
                        </TabPanel>
                        <TabPanel>
                            <CommissionHistory 
                                :search="search" 
                                :date="date" 
                                :type="type" 
                                @update:totalCommissionHistory="totalCommissionHistory = $event"
                            />
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
