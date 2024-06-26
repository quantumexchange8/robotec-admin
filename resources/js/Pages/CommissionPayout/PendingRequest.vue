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
import NoRequest from '@/Components/NoRequest.vue';
import Button from "@/Components/Button.vue";
import Checkbox from "@/Components/Checkbox.vue";
import { trans } from "laravel-vue-i18n";
import Loading from "@/Components/Loading.vue";

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
const totalPending = ref();
const totalHistory = ref();
const emit = defineEmits(['update:totalCommissionRequest', 'update:loading']);
const isLoading = ref(props.isLoading);

watchEffect(() => {
    // Emit the totalCommissionRequest value whenever it changes
    emit('update:totalCommissionRequest', totalPending.value, totalHistory.value);
});

watch(
    [() => props.search, () => props.date, () => props.type],
    debounce(([searchValue, dateValue, typeValue]) => {
        getResults(currentPage.value, searchValue, dateValue, typeValue);
    }, 300)
);

const getResults = async (page = 1, search = '', date = '', type = '') => {
    isLoading.value = true;
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
        totalPending.value = response.data.totalPending;
        totalHistory.value = response.data.totalHistory;
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
        emit('update:loading', false);
    }
};

getResults(1, props.search, props.date, props.type);

const handlePageChange = (newPage) => {
    if (newPage >= 1) {
        currentPage.value = newPage;
        if (isAllSelected.value !== false) {
            const checkbox = document.getElementById('selectAllCheckbox');
            checkbox.click(); // Trigger click on the checkbox's native DOM element
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
        isChecked.value = commissionData.map(commission => commission.id);
        totalAmount.value = commissionData.reduce((total, commission) => total + parseFloat(commission.amount), 0);
    } else {
        isChecked.value = [];
        totalAmount.value = 0;
    }
};

const updateChecked = (id, amount) => {
    if (isNaN(amount)) {
        console.error(`Invalid amount: ${amount}`);
        return;
    }

    const index = isChecked.value.indexOf(id);
    if (index !== -1) {
        // Item exists, remove it
        isChecked.value.splice(index, 1);
        totalAmount.value -= parseFloat(amount);
    } else {
        // Item does not exist, add it
        isChecked.value.push(id);
        totalAmount.value += parseFloat(amount);
    }

    // Determine if all items are checked
    const allChecked = isChecked.value.length === commissions.value.data.length;
    isAllSelected.value = allChecked;

    // Manually update the selectAllCheckbox checked state
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    selectAllCheckbox.checked = allChecked;
};

function isItemSelected(id, amount) {
    return isChecked.value.includes(id);
}

const selectAllLabel = computed(() => isAllSelected.value ? trans('public.deselect_all') : trans('public.select_all'));
const isAnyCheckboxChecked = computed(() => isChecked.value.length > 0);

const openModal = (commission) => {
    commissionModal.value = true;
    commissionDetails.value = commission;
};

const closeModal = () => {
    commissionModal.value = false;
};

// Function to approve a specific commission
const approveCommission = () => {
    if (!commissionDetails.value || !commissionDetails.value.id) {
        console.error("No specific commission details available.");
        return;
    }

    const id = commissionDetails.value.id;
    const amount = commissionDetails.value.amount;

    // Send the approval request for the specific commission
    form.ids = [id]; // Set form.ids as an array with only the specific ID
    sendApprovalRequest(() => {
        // Remove the approved commission from isChecked and adjust totalAmount
        const index = isChecked.value.indexOf(id);
        if (index !== -1) {
            isChecked.value.splice(index, 1);
            totalAmount.value = parseFloat(totalAmount.value) - parseFloat(amount);
        }
    });
};

// Function to approve multiple commissions
const approveCommissions = () => {
    let ids = [];

    // Add all checked commissions to the ids array
    ids.push(...isChecked.value);

    // If no commissions are selected, log an error and return
    if (ids.length === 0) {
        console.error("No commissions selected for approval.");
        return;
    }

    // Send the approval request for all commissions in the ids array
    form.ids = ids;
    sendApprovalRequest(() => {
        // Clear checked items and reset total amount
        isChecked.value = [];
        totalAmount.value = 0;
    });
};

// Function to send approval request
const sendApprovalRequest = (onSuccessCallback) => {
    form.post('/commission/approve_commission', {
        onSuccess: () => {
            closeModal();
            getResults(1, props.search, props.date, props.type);
            if (isAllSelected.value !== false) {
                const checkbox = document.getElementById('selectAllCheckbox');
                checkbox.click(); // Trigger click on the checkbox's native DOM element
            }
            form.reset();
            commissionDetails.value = null; // Clear commission details

            if (typeof onSuccessCallback === 'function') {
                onSuccessCallback();
            }
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
        <div class="text-white font-semibold">{{ $t('public.total') }}: $&nbsp;{{ formatAmount(totalAmount) }}</div>
        <Button variant="success" :disabled="!isAnyCheckboxChecked || form.processing" @click="approveCommissions">{{ $t('public.approve') }}</Button>
    </div>

    <div class="relative overflow-x-auto rounded-xl">
        <div v-if="isLoading" class="flex items-center justify-center py-8 px-3 bg-gray-800">
            <Loading />
        </div>

        <div v-else-if="commissions.data.length == 0" >
            <div class="w-full h-[360px] p-3 bg-gray-800 rounded-xl flex-col justify-center items-center inline-flex">
                <div class="self-stretch h-[212px] py-5 flex-col justify-start items-center gap-3 flex">
                    <NoRequest class="w-40 h-[120px] relative" />
                    <div class="self-stretch text-center text-gray-300 text-sm">
                        {{ $t('public.no_commission_request_message') }}
                    </div>
                </div>
            </div>
            <div class="px-4 py-5 flex items-center justify-center">
                <div class="rounded-full bg-primary-500 w-9 h-9 flex items-center justify-center">
                    <div class="text-center text-white text-sm font-medium">1</div>
                </div>
            </div>
        </div>

        <div v-else>
            <div class="w-full px-4 py-3 bg-gray-800 rounded-xl flex-col justify-start items-start inline-flex">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead>
                    <tr class="bg-gray-800 text-xs border-b border-gray-700">
                        <th class="py-2">
                            <Checkbox
                                v-model="isAllSelected"
                                @click.native="handleSelectAll"
                                id="selectAllCheckbox"
                            />
                        </th>
                        <th class="text-white text-sm font-normal">
                            {{ selectAllLabel }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(commission, index) in commissions.data" :key="commission.id" class="py-2 bg-gray-800 text-xs border-b border-gray-700" :class="{ 'border-transparent': index === commissions.data.length - 1 }" @click="openModal(commission)">
                            <td>
                                <Checkbox
                                    :checked="isAllSelected || isItemSelected(commission.id, commission.amount)"
                                    :model-value="isChecked.includes(commission.id)"
                                    @update:model-value="updateChecked(commission.id, commission.amount)"
                                    @click.stop
                                />
                            </td>
                            <td class="py-2 flex items-center justify-between">
                                <div>
                                    <div class="text-gray-300 text-xs">{{ formatDateTime(commission.created_at) }}</div>
                                    <div class="text-white text-sm font-medium break-all">{{ commission.upline.name }}</div>
                                </div>
                                <div class="text-white text-md font-medium flex items-center justify-center">$&nbsp;{{ formatAmount(commission.amount) }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="flex justify-center mt-4" v-if="!isLoading">
        <TailwindPagination
            :item-classes="paginationClass"
            :active-classes="paginationActiveClass"
            :data="commissions"
            :limit="10"
            @pagination-change-page="handlePageChange"
        />
    </div>

    <Modal :show="commissionModal" :title="$t('public.commission_payout_details')" @close="closeModal" max-width="sm">
        <div v-if="commissionDetails">
            <form>
                <div class="w-full justify-start items-center gap-3 my-5 pb-3 border-b border-gray-700 inline-flex">
                    <img class="w-9 h-9 rounded-full" :src="commissionDetails.upline.profile_photo || 'https://img.freepik.com/free-icon/user_318-159711.jpg'" alt="Client profile picture"/>
                    <div class="w-full flex-col justify-start items-start inline-flex">
                        <div class="self-stretch text-white text-md font-medium break-all">{{ commissionDetails.upline.name }}</div>
                        <div class="text-gray-300 text-xs">{{ $t('public.id') }}: {{ commissionDetails.upline.id_number }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 items-center mb-2">
                    <div class="text-gray-300 text-xs">{{ $t('public.referee') }}</div>
                    <div class="flex items-center">
                        <img class="w-5 h-5 rounded-full mr-2" :src="commissionDetails.downline.profile_photo || 'https://img.freepik.com/free-icon/user_318-159711.jpg'" alt="Client downline profile picture"/>
                        <div class="text-white text-sm font-medium break-all">{{ commissionDetails.downline.name }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 items-center mb-2">
                    <div class="text-gray-300 text-xs">{{ $t('public.requested_date') }}</div>
                    <div class="text-white text-sm font-medium">{{ formatDateTime(commissionDetails.created_at) }}</div>
                </div>

                <div class="grid grid-cols-2 items-center mb-5">
                    <div class="text-gray-300 text-xs">{{ $t('public.commission_amount') }}</div>
                    <div class="text-white text-sm font-medium">{{ formatAmount(commissionDetails.amount) }}</div>
                </div>

                <div class="items-center pt-8 flex gap-3">
                    <Button variant="outline" class="w-full" @click.prevent="closeModal">{{ $t('public.cancel') }}</Button>
                    <Button variant="success" class="w-full" :disabled="form.processing" @click.prevent="approveCommission">{{ $t('public.approve') }}</Button>
                </div>
            </form>
        </div>
    </Modal>

</template>
