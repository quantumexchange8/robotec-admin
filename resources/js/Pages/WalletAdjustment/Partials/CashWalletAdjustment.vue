<script setup>
import { ref, computed, watch, watchEffect } from "vue";
import NoClientSelected from '@/Components/NoClientSelected.vue';
import { usePage, useForm } from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import InputIconWrapper from '@/Components/InputIconWrapper.vue';
import { transactionFormat } from "@/Composables/index.js";
import Modal from "@/Components/Modal.vue";
import toast from "@/Composables/toast.js";
import { TailwindPagination } from "laravel-vue-pagination";
import { trans } from "laravel-vue-i18n";
import NoHistory from "@/Components/NoHistory.vue";

const props = defineProps({
  client: Object,
  type: String,
});
const { formatDateTime, formatAmount } = transactionFormat();

const wallet = ref(null);
const WalletAdjustmentModal = ref(false);
const isDeduction = ref(true); // Default is deduction modal
const histories = ref([]);

const form = useForm({
  id: '',
  type : props.type,
  amount: '',
  isDeduction: '',
});

const getWalletResults = async (client = props.client, type = props.type) => {
  let url = `/transaction/wallet`;

  if (client) {
    url += `?client=${client}`;
  }

  if (type) {
    url += `&type=${type}`;
  }

  const response = await axios.get(url);
  wallet.value = response.data;
};

const getHistoryResults = async (page = 1, client = props.client, type = props.type) => {
  let url = `/transaction/adjustment_history?page=${page}`;

  if (client) {
    url += `&client=${client}`;
  }

  if (type) {
    url += `&type=${type}`;
  }

  const response = await axios.get(url);
  histories.value = response.data;
};

// Watch for changes in props.type
watchEffect(() => {
  // When props.type changes, update form.type
  form.type = props.type;

  // Call functions to get wallet and history results based on the new type
  if (props.client) {
    getWalletResults(props.client.value, props.type);
    getHistoryResults(1, props.client.value, props.type);
    form.id = props.client.value;
  } else {
    // Handle the case where no client is selected
    wallet.value = null; // Clear the wallet data
    histories.value = []; // Clear the histories data by assigning an empty array
  }
});

watch(() => props.client, (newClient, oldClient) => {
    if (newClient !== oldClient) {
        if (newClient) {
            getWalletResults(newClient.value, props.type);
            getHistoryResults(1, newClient.value, props.type);
            form.id = newClient.value;
        } else {
            // Handle the case where no client is selected
            wallet.value = null; // Clear the wallet data
            histories.value = []; // Clear the histories data by assigning an empty array
        }
    } else if (!newClient) {
        getWalletResults(null, props.type); // Pass null as client ID to rerun the wallet results
        getHistoryResults(1, null, props.type); // Pass null as client ID to rerun the history results
    }
});

const openModal = (isDeductionModal) => {
  if (props.client) {
    WalletAdjustmentModal.value = true;
    isDeduction.value = isDeductionModal;
    form.isDeduction = isDeductionModal;
  } else {
        toast.add({
            title: trans('public.no_client_selected'),
            type: 'warning'
        });
  }
};

const closeModal = () => {
  WalletAdjustmentModal.value = false;
};

const adjustedBalance = computed(() => {
    if (wallet.value && form.amount !== '') {
        const currentBalance = parseFloat(wallet.value.balance);
        const adjustmentAmount = parseFloat(form.amount);
        return isDeduction.value ? currentBalance - adjustmentAmount : currentBalance + adjustmentAmount;
    } else {
        const currentBalance = parseFloat(wallet.value.balance);
        return currentBalance;
    }
});

// Watch for changes in form.amount
watch(() => form.amount, (newAmount, oldAmount) => {
    if (wallet.value && newAmount !== oldAmount) {
        adjustedBalance.value; // Trigger the computed property update
    }
});

const confirm = () => {
  form.post(route('transaction.walletAdjustment'), {
    onSuccess: () => {
        form.amount = '';
        closeModal();
        getWalletResults(form.id, form.type);
        getHistoryResults(1, form.id, form.type);
    },
    onError: (errors) => {
      // Handle any errors
      console.error(errors);
    }
  });
}

const handlePageChange = (newPage) => {
    if (newPage >= 1) {
        getHistoryResults(newPage, form.id, form.type);
    }
};

const paginationClass = [
    'bg-transparent border-0 text-white'
];

const paginationActiveClass = [
    'border-0 bg-primary-500 rounded-full text-white'
];

const isExpanded = ref(false);

const toggleExpanded = (history) => {
  // Toggle the expanded state for the given history item
  history.isExpanded = !history.isExpanded;
};

</script>

<template>
    <div class="w-full">
        <div class="w-full h-[210px] px-5 py-8 bg-gray-800 rounded-2xl flex flex-col justify-center items-center gap-8">
            <div class="flex flex-col justify-center items-center gap-3">
                <div class="text-gray-300 text-base font-semibold">{{ $t('public.current_wallet_balance') }}</div>
                <div class="text-white text-3xl font-semibold">$&nbsp;{{ formatAmount(wallet ? wallet.balance : 0) }}</div>
            </div>
            <div class="flex justify-center items-center gap-3">
                <Button variant="danger" class="w-[138px] font-semibold" @click="openModal(true)">{{ $t('public.deduction') }}</Button>
                <Button variant="success" class="w-[138px] font-semibold" @click="openModal(false)">{{ $t('public.addition') }}</Button>
            </div>
        </div>

        <div class="w-full mt-2 flex flex-col justify-start items-center gap-3">
            <div class="w-full justify-start items-center">
                <div class="text-white text-base font-semibold">{{ $t('public.adjustment_history') }}</div>
            </div>

            <div v-if="!props.client" class="py-5">
                <NoClientSelected class="w-40 h-[120px] relative mb-3" />
                <div class="text-gray-300 text-sm text-center font-normal mt-3">{{ $t('public.no_client_selected_message') }}</div>
            </div>
            <div v-else-if="histories && histories.data == ''" class="py-5">
                <NoHistory class="w-40 h-[120px] relative" />
                <div class="text-gray-300 text-sm text-center font-normal mt-3">{{ $t('public.no_history_message') }}</div>
            </div>
            <div v-else class="w-full justify-start items-center">
                <div class="w-full px-4 py-3 bg-gray-800 rounded-xl flex-col justify-start items-start inline-flex">
                    <table class="w-full text-sm text-left text-gray-500">
                        <tbody>
                            <tr
                                v-for="(history, index) in histories.data"
                                :key="history.id"
                                class="text-xs font-normal text-white border-b border-gray-700"
                                :class="{ 'border-transparent': index === histories.data.length - 1 }"
                            >
                                <td class="w-full py-2 flex justify-between items-center" @click="toggleExpanded(history)">
                                    <div class="w-full flex-row">
                                        <div class="w-full flex justify-between">
                                            <div class="text-gray-300 text-xs font-normal font-sans leading-[18px] gap-3 justify-start">
                                                {{ formatDateTime(history.created_at) }}
                                            </div>
                                            <div class="font-medium font-sans leading-normal text-md justify-end"
                                                :class="{'text-success-500': history.to_wallet_id,'text-error-500': history.from_wallet_id,'text-white': !history.from_wallet_id && !history.to_wallet_id}">
                                                {{ history.to_wallet_id ? '+' + formatAmount(history.transaction_amount) : (history.from_wallet_id ? '-' + formatAmount(history.transaction_amount) : formatAmount(history.transaction_amount))}}
                                            </div>
                                        </div>
                                        <div v-if="history.isExpanded" class="mt-2 w-full flex justify-between">
                                            <div class="text-gray-300 text-xxs font-normal font-sans leading-[18px] gap-3 justify-start">
                                                {{ $t('public.previous_balance') }}: <span class="text-white">$&nbsp;{{ formatAmount(history.old_wallet_amount) }}</span>
                                            </div>
                                            <div class="text-gray-300 text-xxs font-normal font-sans leading-[18px] gap-3 justify-start">
                                                {{ $t('public.current_balance') }}: <span class="text-white">$&nbsp;{{ formatAmount(history.new_wallet_amount) }}</span>
                                            </div>
                                        </div>

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
                        :data="histories"
                        :limit="10"
                        @pagination-change-page="handlePageChange"
                    />
                </div>
            </div>
        </div>
    </div>

    <Modal :show="WalletAdjustmentModal" :title="isDeduction ? $t('public.wallet_adjustment_deduction') : $t('public.wallet_adjustment_addition')" @close="closeModal" max-width="sm">
        <form @submit.prevent="confirm">
            <div class="w-full flex-col justify-start items-start gap-1.5 mb-5 inline-flex">
                <Label for="amount" class="text-xs font-medium font-sans leading-[18px]" :invalid="form.errors.amount">{{ $t('public.adjustment_amount') }}</Label>

                <InputIconWrapper class="w-full">
                    <template #icon>
                        <span class='text-white'>{{ isDeduction ? '-$' : '+$' }}</span>
                    </template>

                    <Input
                        withIcon
                        id="amount"
                        class="block w-full py-3 px-4 bg-transparent text-white"
                        :invalid="form.errors.amount"
                        v-model="form.amount"
                        required
                    />
                </InputIconWrapper>

                <InputError class="mt-1.5" :message="form.errors.amount" />
            </div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.client') }}</div>
                <div class="col-span-1 flex items-center">
                <img class="w-5 h-5 rounded-full mr-2" :src="client.img ? client.img : 'https://img.freepik.com/free-icon/user_318-159711.jpg'" alt="Client upline profile picture" />
                <div class="text-white text-xs font-normal font-sans leading-tight">{{ client ? client.label : '' }}</div>
                </div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.wallet') }}</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ wallet ? $t('public.' + wallet.type) : '' }}</div>
            </div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.current_balance') }}</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ formatAmount(wallet ? wallet.balance : 0) }}</div>
            </div>

            <div class="grid grid-cols-2 items-center mb-5">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.after_adjustment') }}</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ formatAmount(adjustedBalance) }}</div>
            </div>

            <div class="items-center pt-8 flex gap-3">
                <Button variant="outline" class="w-full" @click="closeModal">{{ $t('public.close') }}</Button>
                <Button variant="primary" class="w-full" :disabled="form.processing" @click.prevent="confirm">{{ $t('public.confirm') }}</Button>
            </div>
        </form>
    </Modal>

</template>