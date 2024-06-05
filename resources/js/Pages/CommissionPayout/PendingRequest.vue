<script setup>
import { TailwindPagination } from "laravel-vue-pagination";
import { computed, defineProps, ref, watch, watchEffect } from "vue";
import debounce from "lodash/debounce.js";
import { transactionFormat } from "@/Composables/index.js";
import { usePage, useForm } from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Input from '@/Components/Input.vue';
import Modal from "@/Components/Modal.vue";
import NoClient from '@/Components/NoClient.vue';
import Button from "@/Components/Button.vue";
import Checkbox from "@/Components/Checkbox.vue";

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
const isAllSelected = ref(false);
const isChecked = ref([]);
const totalAmount = ref(0);
const currentPage = ref(1);
const commissionModal = ref(false);
const commissionDetails = ref(null);

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
        commissions.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

getResults(1, props.search, props.date, props.type);

const handlePageChange = (newPage) => {
    if (newPage >= 1) {
        currentPage.value = newPage;
        if (isAllSelected.value !== false) {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            if (selectAllCheckbox) {
                selectAllCheckbox.click(); // Trigger click event to reset "Select All" checkbox
            }
        }
        getResults(currentPage.value, props.search, props.date, props.type);
    }
};

const paginationClass = [
    'bg-transparent border-0 text-white'
];

const paginationActiveClass = [
    'border-0 bg-primary-500 rounded-full text-white'
];

const handleSelectAll = () => {
    isAllSelected.value = !isAllSelected.value;
    if (isAllSelected.value) {
        const commissionData = commissions.value.data;

        for (const commission of commissionData) {
            const id = commission.id;
            if (!isChecked.value.includes(id)) {
                isChecked.value.push(id);
                totalAmount.value += parseFloat(commission.transaction_amount);
            }
        }
    } else {
        isChecked.value = [];
        totalAmount.value = 0;
    }
};

const updateChecked = (id, transaction_amount) => {
    if (isNaN(transaction_amount)) {
        console.error(`Invalid amount: ${transaction_amount}`);
        return;
    }

    const index = isChecked.value.indexOf(id);
    if (index !== -1) {
        isChecked.value.splice(index, 1);
        totalAmount.value = parseFloat(totalAmount.value) - parseFloat(transaction_amount);
    } else {
        isChecked.value.push(id);
        totalAmount.value = parseFloat(totalAmount.value) + parseFloat(transaction_amount);
    }
};

function isItemSelected(id, transaction_amount) {
    return isChecked.value.some(commission =>
        commission.id === id &&
        commission.transaction_amount === transaction_amount
    );
}

const selectAllLabel = computed(() => isAllSelected.value ? 'Deselect All' : 'Select All');
const isAnyCheckboxChecked = computed(() => isChecked.value.length > 0);

const openModal = (commission) => {
    commissionModal.value = true;
    commissionDetails.value = commission;
};

const closeModal = () => {
    commissionModal.value = false;
};

const approveCommission = () => {
    let ids = [];

    // If commissionDetails is available, add its ID to the ids array
    if (commissionDetails.value) {
        ids.push(commissionDetails.value.id);
    }

    // Add all checked commissions to the ids array
    ids.push(...isChecked.value);

    // If no commissions are selected, log an error and return
    if (ids.length === 0) {
        console.error("No commissions selected for approval.");
        return;
    }

    // Send the approval request for all commissions in the ids array
    form.ids = ids;
    form.post(route('commission.approve_commission'), {
        onSuccess: () => {
            closeModal();
            getResults(1, props.search, props.date, props.type);
        },
        onError: (errors) => {
            // Handle any errors
            console.error(errors);
        }
    });
};
</script>

<template>
    <div class="w-full py-3 justify-between items-center inline-flex">
        <div class="text-white text-base font-semibold font-sans leading-normal">Total: $ {{ formatAmount(totalAmount) }}</div>
        <Button variant="success" :disabled="!isAnyCheckboxChecked" @click="approveCommission">Approve</Button>
    </div>

    <div v-if="commissions.data.length == 0" >
        <div class="w-full h-[360px] p-3 bg-gray-800 rounded-xl flex-col justify-center items-center inline-flex">
            <div class="self-stretch h-[212px] py-5 flex-col justify-start items-center gap-3 flex">
                <NoHistory class="w-40 h-[120px] relative" />
                <div class="self-stretch text-center text-gray-300 text-sm font-normal font-sans leading-tight">
                    It seems there are no commission payouts available at the moment.
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
                <thead>
                <tr class="bg-gray-800 text-xs font-normal border-b border-gray-700">
                    <th class="py-2">
                    <Checkbox
                        v-model="isAllSelected"
                        @click="handleSelectAll"
                        id="selectAllCheckbox"
                    />
                    </th>
                    <th class="text-white text-sm font-normal font-sans leading-tight">
                        {{ selectAllLabel }}
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="commission in commissions.data" :key="commission.id" class="bg-gray-800 text-xs font-normal border-b border-gray-700" @click="openModal(commission)">
                        <td class="py-2">
                            <Checkbox
                                :checked="isAllSelected || isItemSelected(commission.id, commission.transaction_amount)"
                                :model-value="isChecked.includes(commission.id)"
                                @update:model-value="updateChecked(commission.id, commission.transaction_amount)"
                                @click.stop
                            />
                        </td>
                        <td>
                            <div class="text-gray-300 text-xs font-normal font-sans leading-[24px]">{{ formatDateTime(commission.created_at) }}</div>
                            <div class="text-white text-sm font-medium font-sans leading-tight">{{ commission.user.name }}</div>
                        </td>
                        <td class="text-white flex items-center justify-center">$ {{ commission.transaction_amount }}</td>
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

    <Modal :show="commissionModal" title="Commission Payout Details" @close="closeModal" max-width="sm">
        <div v-if="commissionDetails">
            <div class="w-full justify-start items-center gap-3 my-5 pb-3 border-b border-gray-700 inline-flex">
                <img class="w-9 h-9 rounded-full" :src="commissionDetails.user.profile_photo || 'https://via.placeholder.com/32x32'" alt="Client profile picture"/>
                <div class="w-full flex-col justify-start items-start inline-flex">
                    <div class="self-stretch text-white text-base font-medium font-sans leading-normal">{{ commissionDetails.user.name }}</div>
                    <div class="text-gray-300 text-xs font-normal font-sans leading-[18px]">ID: {{ commissionDetails.user.id }}</div>
                </div>
            </div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">Referee</div>
                <div class="col-span-1 flex items-center">
                    <!-- <img class="w-5 h-5 rounded-full mr-2" :src="commissionDetails.user.upline.profile_photo || 'https://via.placeholder.com/32x32'" alt="Client upline profile picture"/>
                    <div class="text-white text-xs font-normal font-sans leading-tight">{{ commissionDetails.user.upline.name }}</div> -->
                </div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">Requested Date</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ formatDateTime(commissionDetails.created_at) }}</div>
            </div>

            <div class="grid grid-cols-2 items-center mb-5">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">Commission Amount</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ commissionDetails.transaction_amount }}</div>
            </div>

            <div class="items-center pt-8 flex gap-3">
                <Button variant="outline" class="w-full" @click="closeModal">Close</Button>
                <Button variant="success" class="w-full" @click="approveCommission">Approve</Button>
            </div>

        </div>
    </Modal>

</template>
