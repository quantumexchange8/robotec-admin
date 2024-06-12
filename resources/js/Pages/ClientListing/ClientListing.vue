<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps, watchEffect } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import BaseListbox from "@/Components/BaseListbox.vue"
import Combobox from '@/Components/Combobox.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import InputIconWrapper from '@/Components/InputIconWrapper.vue'
import { SearchIcon, FilterIcon } from '@/Components/Icons/outline'
import ClientTable from '@/Pages/ClientListing/ClientTable.vue';
import Modal from "@/Components/Modal.vue";
import AddNewClient from "@/Pages/ClientListing/Partials/AddNewClient.vue";

const user = usePage().props.auth.user;
const profile_photo = usePage().props.auth.user.profile_photo;

// Initialize the form with default values
const form = useForm({
    upline: null,
});

const sortTypes = [
    { value: 'desc', label: 'Sort by latest' },
    { value: 'asc', label: 'Sort by oldest' },
    { value: 'nameasc', label: 'Name (A to Z)' },
    { value: 'namedesc', label: 'Name (Z to A)' },
    { value: 'commissionasc', label: 'Commission (Low to High)' },
    { value: 'commissiondesc', label: 'Commission (High to Low)' }
];

const search = ref('');
const sortType = ref(sortTypes[0].value);
const totalClient = ref(0);

const parseSortField = (sortTypeValue) => {
    if (sortTypeValue.startsWith('name')) {
        return 'name';
    } else if (sortTypeValue.startsWith('commission')) {
        return 'commission';
    }
    return '';
};

const parseSortDirection = (sortTypeValue) => {
    if (sortTypeValue.endsWith('asc')) {
        return 'asc';
    } else if (sortTypeValue.endsWith('desc')) {
        return 'desc';
    }
    return 'desc';
};

const filterModal = ref(false);

const openFilterModal = () => {
    filterModal.value = true;
};

const closeFilterModal = () => {
    filterModal.value = false;
};
const selectedUpline = ref(null);
const selectedPurchasedEA = ref(null);
const selectedFundedPAMM = ref(null);

// Temporary variables to hold selected filter values
const tempUpline = ref(null);
const tempSelectedPurchasedEA = ref(null);
const tempSelectedFundedPAMM = ref(null);

const applyFilters = () => {
    selectedUpline.value = tempUpline.value;
    selectedPurchasedEA.value = tempSelectedPurchasedEA.value;
    selectedFundedPAMM.value = tempSelectedFundedPAMM.value;
    closeFilterModal(); // Close the modal after applying filters
};

function loadUpline(query, setOptions) {
    fetch('/member/getAllClients?query=' + query)
        .then(response => response.json())
        .then(results => {
            setOptions(
                results.map(user => {
                    return {
                        value: user.id,
                        label: user.name,
                        img: user.profile_photo
                    }
                })
            )
        });
}

const clearFilters = () => {
    tempUpline.value = null;
    tempSelectedPurchasedEA.value = null;
    tempSelectedFundedPAMM.value = null;
    selectedUpline.value = null;
    selectedPurchasedEA.value = null;
    selectedFundedPAMM.value = null;
    sortType.value = sortTypes[0].value;
};

// Calculate the count of applied filters
const filterCount = ref(0);

// Watch for changes in filter variables and update the count
watchEffect(() => {
    filterCount.value = 0;
    if (selectedUpline.value !== null) filterCount.value++;
    if (selectedPurchasedEA.value !== null) filterCount.value++;
    if (selectedFundedPAMM.value !== null) filterCount.value++;
});

</script>

<template>
    <Head :title="$t('public.client_listing')" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xxl text-white leading-loose">{{ $t('public.client_listing') }}</h2>
        </template>

        <div class="sticky top-2 bg-gray-900 z-[5]">
            <div class="pb-3">
                <InputIconWrapper class="col-span-2">
                    <template #icon>
                        <SearchIcon aria-hidden="true" class="w-5 h-5 text-white" />
                    </template>
                    <Input withIcon id="search" variant="search" type="text" class="block w-full rounded-lg" :placeholder="$t('public.search')" v-model="search" />
                </InputIconWrapper>
            </div>
            <div class="pb-3 grid grid-cols-3 gap-3">
                <Button variant="transparent" class="relative w-full border border-gray-600 focus:border-primary-500" @click="openFilterModal()">
                    <span class="inline-flex items-center">
                        <span class="mr-2">
                            <FilterIcon aria-hidden="true" class="w-5 h-5" />
                        </span>
                        <span>{{ $t('public.filter') }}</span>
                        <span v-if="filterCount > 0" class="absolute -top-1 -right-1">
                            <div class="w-5 h-5 px-1 bg-error-500 rounded-full border border-gray-900 flex items-center justify-center">
                                <div class="text-white text-xs font-medium">{{ filterCount }}</div>
                            </div>
                        </span>
                    </span>
            </Button>
                <BaseListbox class="w-full col-span-2" :options="sortTypes" v-model="sortType" />
            </div>
        </div>
        <div class="pb-3 flex items-center justify-between">
            <div class="text-white">{{ totalClient }} {{ $t('public.results') }} </div>
            <AddNewClient />
        </div>

        <ClientTable
            :search="search"
            :sort-field="parseSortField(sortType)"
            :sort-direction="parseSortDirection(sortType)"
            :upline="selectedUpline"
            :purchasedEA="selectedPurchasedEA" 
            :fundedPAMM="selectedFundedPAMM"
            @update:totalClient="totalClient = $event"
        />

        <Modal :show="filterModal" :title="$t('public.filter')" @close="closeFilterModal" max-width="sm">
            <div class="w-full">
                <div class="text-white text-base font-semibold font-sans leading-normal mb-3 mt-5">{{ $t('public.upline') }}</div>
                <div class="flex flex-col mb-1.5">
                    <Combobox
                        :load-options="loadUpline"
                        v-model="tempUpline"
                        :placeholder="$t('public.select_client')"
                        image
                    />
                </div>
                <div class="text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.select_client_message') }}</div>
            </div>

            <div class="w-full h-px bg-gray-700 my-5"></div>

            <div class="w-full flex flex-col px-1">
                <div class="text-white text-base font-semibold font-sans leading-normal">{{ $t('public.purchased_ea') }}</div>
                <div class="flex gap-3">
                    <Label class="text-white py-3 grow shrink self-stretch">
                        <input type="radio" value="yes" v-model="tempSelectedPurchasedEA" class="mr-3"/>
                        {{ $t('public.yes') }}
                    </Label>
                    <Label class="text-white py-3 grow shrink self-stretch">
                        <input type="radio" value="no" v-model="tempSelectedPurchasedEA" class="mr-3"/>
                        {{ $t('public.no') }}
                    </Label>
                    <Label class="text-white py-3 grow shrink self-stretch">
                        <input type="radio" value="" v-model="tempSelectedPurchasedEA" class="mr-3"/>
                        {{ $t('public.both') }}
                    </Label>
                </div>
            </div>

            <div class="w-full h-px bg-gray-700 my-5"></div>

            <div class="w-full flex flex-col mb-5 px-1">
                <div class="text-white text-base font-semibold font-sans leading-normal">{{ $t('public.funded_pamm') }}</div>
                <div class="flex gap-3">
                    <Label class="text-white py-3 grow shrink self-stretch">
                        <input type="radio" value="yes" v-model="tempSelectedFundedPAMM" class="mr-3"/>
                        {{ $t('public.yes') }}
                    </Label>
                    <Label class="text-white py-3 grow shrink self-stretch">
                        <input type="radio" value="no" v-model="tempSelectedFundedPAMM" class="mr-3"/>
                        {{ $t('public.no') }}
                    </Label>
                    <Label class="text-white py-3 grow shrink self-stretch">
                        <input type="radio" value="" v-model="tempSelectedFundedPAMM" class="mr-3"/>
                        {{ $t('public.both') }}
                    </Label>
                </div>
            </div>

            <div class="flex flex-col h-[300px] justify-end mt-5">
                <div class="flex gap-3 pt-8">
                    <Button variant="gray" class="w-full" @click="clearFilters">{{ $t('public.clear_all') }}</Button>
                    <Button variant="primary" class="w-full" @click="applyFilters">{{ $t('public.apply') }}</Button>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
