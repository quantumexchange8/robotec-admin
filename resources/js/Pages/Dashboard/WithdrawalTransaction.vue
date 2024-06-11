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
const date = ref('');
const status = ref('Approved');
const updateStatus = (newStatus) => {
    status.value = newStatus;
};

</script>

<template>
    <Head title="Withdrawal Transactions" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-loose">Withdrawal Transactions</h2>
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
                                @click="updateStatus('Approved')"
                                :class="[
                                    'w-full py-2.5 text-sm font-semibold text-gray-300',
                                    'ring-white ring-offset-0 focus:outline-none focus:ring-0',
                                    selected
                                        ? 'text-white border-b-2 border-white'
                                        : 'border-b border-gray-700',
                                ]"
                            >
                                Approved ({{ totalApprovedResult }})
                            </button>
                        </Tab>
                        <Tab
                            as="template"
                            v-slot="{ selected }"
                            class="px-3 py-2"
                        >
                            <button
                                @click="updateStatus('Rejected')"
                                :class="[
                                    'w-full py-2.5 text-sm font-semibold text-gray-300',
                                    'ring-white ring-offset-0 focus:outline-none focus:ring-0',
                                    selected
                                        ? 'text-white border-b-2 border-white'
                                        : 'border-b border-gray-700',
                                ]"
                            >
                                Rejected ({{ totalRejectedResult }})
                            </button>
                        </Tab>
                    </TabList>
                    <div class="mb-3 sticky top-16 bg-gray-900 z-[5]">
                        <div>
                            <InputIconWrapper>
                                <template #icon>
                                    <SearchIcon aria-hidden="true" class="w-5 h-5 text-white" />
                                </template>
                                <Input withIcon id="search" variant="search" type="text" class="block w-full rounded-lg" placeholder="Search" v-model="search" />
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
                            <WithdrawalTable 
                                :search="search" 
                                :date="date" 
                                :status="status"
                                @update:totalResult="totalApprovedResult = $event"
                            />
                        </TabPanel>
                        <TabPanel>
                            <WithdrawalTable 
                                :search="search" 
                                :date="date" 
                                :status="status"
                                @update:totalResult="totalRejectedResult = $event"
                            />
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>