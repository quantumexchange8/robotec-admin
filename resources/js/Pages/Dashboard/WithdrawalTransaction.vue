<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps, watchEffect } from "vue";
import Input from '@/Components/Input.vue';
import InputIconWrapper from '@/Components/InputIconWrapper.vue';
import { SearchIcon } from '@/Components/Icons/outline';
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import WithdrawalTable from "@/Pages/Dashboard/Partials/WithdrawalTable.vue"
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from "@headlessui/vue";
import Search from '@/Components/Search.vue';

const props = defineProps({
    totalApprovedResult: [String, Number],
    totalRejectedResult: [String, Number],
});

const formatter = ref({
    date: 'YYYY-MM-DD',
    month: 'MM'
});

const totalApprovedResult = ref(props.totalApprovedResult);
const totalRejectedResult = ref(props.totalRejectedResult);
const search = ref('');
const today = new Date();
const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

// Formatting the start and end of the current month
const formattedStartDate = `${firstDayOfMonth.getFullYear()}-${(firstDayOfMonth.getMonth() + 1).toString().padStart(2, '0')}-01`;
const formattedEndDate = `${lastDayOfMonth.getFullYear()}-${(lastDayOfMonth.getMonth() + 1).toString().padStart(2, '0')}-${lastDayOfMonth.getDate().toString().padStart(2, '0')}`;

// Set the initial date range to the current month
const date = ref(`${formattedStartDate} - ${formattedEndDate}`);
const status = ref('success');
const updateStatus = (newStatus) => {
    status.value = newStatus;
};

</script>

<template>
    <Head :title="$t('public.withdrawal_transactions')" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-loose mb-3">{{ $t('public.withdrawal_transactions') }}</h2>
        </template>

        <div class="rounded-md shadow-md pb-3 sticky top-2 bg-gray-900 z-[5]">
            <div class="w-full">
                <TabGroup>
                    <TabList class="max-w-md flex mb-3 sticky top-2 bg-gray-900 z-[5]">
                        <Tab
                            as="template"
                            v-slot="{ selected }"
                            class="px-3 py-2"
                        >
                            <button
                                @click="updateStatus('success')"
                                :class="[
                                    'w-full py-2.5 text-sm font-semibold text-gray-300',
                                    'ring-white ring-offset-0 focus:outline-none focus:ring-0',
                                    selected
                                        ? 'text-white border-b-2 border-white'
                                        : 'border-b border-gray-700',
                                ]"
                            >
                                {{ $t('public.approved') }} ({{ totalApprovedResult }})
                            </button>
                        </Tab>
                        <Tab
                            as="template"
                            v-slot="{ selected }"
                            class="px-3 py-2"
                        >
                            <button
                                @click="updateStatus('failed')"
                                :class="[
                                    'w-full py-2.5 text-sm font-semibold text-gray-300',
                                    'ring-white ring-offset-0 focus:outline-none focus:ring-0',
                                    selected
                                        ? 'text-white border-b-2 border-white'
                                        : 'border-b border-gray-700',
                                ]"
                            >
                                {{ $t('public.rejected') }} ({{ totalRejectedResult }})
                            </button>
                        </Tab>
                    </TabList>
                    <div class="mb-3 sticky top-16 bg-gray-900 z-[5]">
                        <div>
                            <Search v-model="search" :placeholder="$t('public.search')" />
                        </div>
                        <div class="mt-3">
                            <vue-tailwind-datepicker
                                :formatter="formatter"
                                separator=" - "
                                v-model="date"
                                input-classes="py-3 px-4 w-full rounded-lg placeholder:text-gray-500 focus:ring-primary-500 hover:border-primary-500 focus:border-primary-500 bg-gray-800 text-white border border-gray-600"
                            />
                        </div>
                    </div>

                    <TabPanels>
                        <TabPanel>
                            <WithdrawalTable 
                                :search="search" 
                                :date="date" 
                                :status="status"
                            />
                        </TabPanel>
                        <TabPanel>
                            <WithdrawalTable 
                                :search="search" 
                                :date="date" 
                                :status="status"
                            />
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>