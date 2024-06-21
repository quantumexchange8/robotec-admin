<script setup>
import { TailwindPagination } from "laravel-vue-pagination";
import { computed, defineProps, ref, watch, watchEffect } from "vue";
import debounce from "lodash/debounce.js";
import { transactionFormat } from "@/Composables/index.js";
import { usePage, useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import Input from '@/Components/Input.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import NoHistory from "@/Components/NoHistory.vue";
import { DuplicateIcon } from "@/Components/Icons/outline";
import Tooltip from "@/Components/Tooltip.vue";

const props = defineProps({
    search: String,
    date: String,
});

const { formatDateTime, formatAmount } = transactionFormat();

const transactions = ref({ data: [] });
const transaction_type = 'pamm_funding'
const totalAmount = ref(0);
const currentPage = ref(1);
const transactionModal = ref(false);
const transactionDetails = ref(null);
const tooltipContent = ref('copy');

watch(
    [() => props.search, () => props.date],
    debounce(([searchValue, dateValue]) => {
        getResults(currentPage.value, searchValue, dateValue);
    }, 300)
);

const getResults = async (page = 1, search = '', date = '') => {
    try {
        let url = `/transaction/transasction_data?page=${page}&transaction_type=${transaction_type}`;

        if (search) {
            url += `&search=${search}`;
        }

        if (date) {
            url += `&date=${date}`;
        }

        const response = await axios.get(url);
        transactions.value = response.data.transactions;
        totalAmount.value = response.data.totalAmount;
    } catch (error) {
        console.error(error);
    }
};

getResults(1, props.search, props.date);

const handlePageChange = (newPage) => {
    if (newPage >= 1) {
        currentPage.value = newPage;
        getResults(currentPage.value, props.search, props.date);
    }
};

const paginationClass = [
    'bg-transparent border-0 text-white'
];

const paginationActiveClass = [
    'border-0 bg-primary-500 rounded-full text-white'
];

const openModal = (request) => {
    transactionModal.value = true;
    transactionDetails.value = request;
};

const closeModal = () => {
    transactionModal.value = false;
};

</script>

<template>
    <!-- <div class="w-full py-3 justify-between items-center inline-flex">
        <div class="text-white text-base font-semibold font-sans leading-normal">Total: $&nbsp;{{ formatAmount(totalAmount) }}</div>
    </div> -->

    <div v-if="transactions.data.length == 0" >
        <div class="w-full h-[360px] p-3 bg-gray-800 rounded-xl flex-col justify-center items-center inline-flex">
            <div class="self-stretch h-[212px] py-5 flex-col justify-start items-center gap-3 flex">
                <NoHistory class="w-40 h-[120px] relative" />
                <div class="self-stretch text-center text-gray-300 text-sm font-normal font-sans leading-tight">
                    {{ $t('public.no_transaction_history_message') }}
                </div>
            </div>
        </div>
        <div class="px-4 py-5 flex items-center justify-center">
            <div class="rounded-full bg-primary-500 w-9 h-9 flex items-center justify-center">
                <div class="text-center text-white text-sm font-medium font-sans leading-tight">1</div>
            </div>
        </div>
    </div>


    <div v-else>
        <div class="w-full px-4 py-3 bg-gray-800 rounded-xl flex-col justify-start items-start inline-flex">
            <table class="w-full text-sm text-left text-gray-500">
                <tbody>
                    <tr v-for="(transaction, index) in transactions.data" :key="transaction.id" class="py-2 bg-gray-800 text-xs font-normal border-b border-gray-700" :class="{ 'border-transparent': index === transactions.data.length - 1 }" @click="openModal(transaction)">
                        <td>
                            <div class="flex justify-between items-center gap-3">
                                <div>
                                    <div class="text-gray-300 text-xs font-normal font-sans leading-[24px]">{{ formatDateTime(transaction.created_at) }}</div>
                                    <div class="text-white text-sm font-medium font-sans leading-tight break-all">{{ transaction.user.name }}</div>
                                </div>
                                <div class="text-success-500 text-right text-md font-medium font-sans leading-normal">$&nbsp;{{ formatAmount(transaction.transaction_amount) }}</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex justify-center mt-4">
            <TailwindPagination
                :item-classes="paginationClass"
                :active-classes="paginationActiveClass"
                :data="transactions"
                :limit="10"
                @pagination-change-page="handlePageChange"
            />
        </div>
    </div>

    <Modal :show="transactionModal" :title="$t('public.fund_in_details')" @close="closeModal" max-width="sm">
        <div v-if="transactionDetails">
            <div class="w-full justify-start items-center gap-3 border-b border-gray-700 inline-flex">
                <img class="w-9 h-9 rounded-full" :src="transactionDetails.user.profile_photo || 'https://img.freepik.com/free-icon/user_318-159711.jpg'" alt="Client profile picture"/>
                <div class="w-full flex-col justify-start items-start inline-flex">
                    <div class="self-stretch text-white text-base font-medium font-sans leading-normal break-all">{{ transactionDetails.user.name }}</div>
                    <div class="text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.id') }}: {{ transactionDetails.user.id_number }}</div>
                </div>
            </div>
            <div class="w-full h-px bg-gray-700 my-4"></div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.transaction_id') }}</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ transactionDetails.transaction_number }}</div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.transaction_date') }}</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ formatDateTime(transactionDetails.created_at) }}</div>
            </div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.from') }}</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ transactionDetails.from_wallet.name }}</div>
            </div>

            <div class="grid grid-cols-2 items-center">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.fund_in_amount') }}</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">$&nbsp;{{ formatAmount(transactionDetails.transaction_amount) }}</div>
            </div>

        </div>
    </Modal>

</template>
