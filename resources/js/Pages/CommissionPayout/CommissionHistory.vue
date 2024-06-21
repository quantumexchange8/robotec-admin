<script setup>
import { TailwindPagination } from "laravel-vue-pagination";
import { computed, defineProps, ref, watch, watchEffect } from "vue";
import debounce from "lodash/debounce.js";
import { transactionFormat } from "@/Composables/index.js";
import { usePage, useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import NoHistory from "@/Components/NoHistory.vue";

const props = defineProps({
    search: String,
    date: String,
    type: String,
});

const form = useForm({
    ids: [],
});

const { formatDateTime, formatAmount } = transactionFormat();

const commissions = ref({ data: [] });
const totalAmount = ref(0);
const currentPage = ref(1);
const commissionModal = ref(false);
const commissionDetails = ref(null);
const totalPending = ref();
const totalHistory = ref();
const emit = defineEmits(['update:totalCommissionHistory']);

watchEffect(() => {
    // Emit the totalCommissionHistory value whenever it changes
    emit('update:totalCommissionHistory', totalPending.value, totalHistory.value);
});

watch(
    [() => props.search, () => props.date, () => props.type],
    debounce(([searchValue, dateValue, typeValue]) => {
        getResults(currentPage.value, searchValue, dateValue, typeValue);
    }, 300)
);

const getResults = async (page = 1, search = '', date = '', type = '') => {
    try {
        let url = `/commission/commission_request_data?page=${page}`;

        if (search) {
            url += `&search=${search}`;
        }

        if (date) {
            url += `&date=${date}`;
        }

        if (type) {
            url += `&type=${type}`;
        }

        const response = await axios.get(url);
        commissions.value = response.data.commissions;
        totalAmount.value = response.data.totalAmount;
        totalPending.value = response.data.totalPending;
        totalHistory.value = response.data.totalHistory;
    } catch (error) {
        console.error(error);
    }
};

getResults(1, props.search, props.date, props.type);

const handlePageChange = (newPage) => {
    if (newPage >= 1) {
        currentPage.value = newPage;
        getResults(currentPage.value, props.search, props.date, props.type);
    }
};

const paginationClass = [
    'bg-transparent border-0 text-white'
];

const paginationActiveClass = [
    'border-0 bg-primary-500 rounded-full text-white'
];

const openModal = (commission) => {
    commissionModal.value = true;
    commissionDetails.value = commission;
};

const closeModal = () => {
    commissionModal.value = false;
};

</script>

<template>
    <div class="w-full py-3 justify-between items-center inline-flex">
        <div class="text-white text-base font-semibold font-sans leading-normal">{{ $t('public.total') }}: $&nbsp;{{ formatAmount(totalAmount) }}</div>
    </div>

    <div v-if="commissions.data.length == 0" >
        <div class="w-full h-[360px] p-3 bg-gray-800 rounded-xl flex-col justify-center items-center inline-flex">
            <div class="self-stretch h-[212px] py-5 flex-col justify-start items-center gap-3 flex">
                <NoHistory class="w-40 h-[120px] relative" />
                <div class="self-stretch text-center text-gray-300 text-sm font-normal font-sans leading-tight">
                    {{ $t('public.no_history_message') }}
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
                    <tr v-for="(commission, index) in commissions.data" :key="commission.id" class="py-2 bg-gray-800 text-xs font-normal border-b border-gray-700" :class="{ 'border-transparent': index === commissions.data.length - 1 }" @click="openModal(commission)">
                        <td>
                            <div class="flex justify-between items-center gap-3">
                                <div>
                                    <div class="text-gray-300 text-xs font-normal font-sans leading-[24px]">{{ formatDateTime(commission.created_at) }}</div>
                                    <div class="text-white text-sm font-medium font-sans leading-tight break-all">{{ commission.upline.name }}</div>
                                </div>
                                <div class="text-white text-right text-md font-medium font-sans leading-normal">$&nbsp;{{ formatAmount(commission.amount) }}</div>
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
                :data="commissions"
                :limit="10"
                @pagination-change-page="handlePageChange"
            />
        </div>
    </div>

    <Modal :show="commissionModal" :title="$t('public.commission_payout_details')" @close="closeModal" max-width="sm">
        <div v-if="commissionDetails">
            <div class="w-full justify-start items-center gap-3 my-5 pb-3 border-b border-gray-700 inline-flex">
                <img class="w-9 h-9 rounded-full" :src="commissionDetails.upline.profile_photo || 'https://img.freepik.com/free-icon/user_318-159711.jpg'" alt="Client profile picture"/>
                <div class="w-full flex-col justify-start items-start inline-flex">
                    <div class="self-stretch text-white text-base font-medium font-sans leading-normal break-all">{{ commissionDetails.upline.name }}</div>
                    <div class="text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.id') }}: {{ commissionDetails.upline.id_number }}</div>
                </div>
            </div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.referee') }}</div>
                <div class="col-span-1 flex items-center">
                    <img class="w-5 h-5 rounded-full mr-2" :src="commissionDetails.downline.profile_photo || 'https://img.freepik.com/free-icon/user_318-159711.jpg'" alt="Client downline profile picture"/>
                    <div class="text-white text-xs font-normal font-sans leading-tight break-all">{{ commissionDetails.downline.name }}</div>
                </div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.requested_date') }}</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ formatDateTime(commissionDetails.created_at) }}</div>
            </div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.approved_date') }}</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ formatDateTime(commissionDetails.approved_at) }}</div>
            </div>

            <div class="grid grid-cols-2 items-center mb-5">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.commission_amount') }}</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ formatAmount(commissionDetails.amount) }}</div>
            </div>

        </div>
    </Modal>

</template>
