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
import NoRequest from "@/Components/NoRequest.vue";
import { DuplicateIcon } from "@/Components/Icons/outline";
import Tooltip from "@/Components/Tooltip.vue";

const props = defineProps({
    search: String,
    date: String,
});

const form = useForm({
    id: '',
    from_wallet_address: '',
    txn_hash: '',
    remarks: '',
});

const { formatDateTime, formatAmount } = transactionFormat();

const requests = ref({ data: [] });
const totalAmount = ref(0);
const currentPage = ref(1);
const withdrawalRequestModal = ref(false);
const approveRequestModal = ref(false);
const rejectRequestModal = ref(false);
const requestDetails = ref(null);
const tooltipContent = ref('copy');

function copyTestingCode(walletAddress) {
    const textField = document.createElement('textarea');
    textField.innerText = walletAddress;
    document.body.appendChild(textField);
    textField.select();

    try {
        const successful = document.execCommand('copy');
        if (successful) {
            tooltipContent.value = 'copied';
            setTimeout(() => {
                tooltipContent.value = 'copy'; // Reset tooltip content to 'Copy' after 2 seconds
            }, 1000);
        } else {
            tooltipContent.value = 'try_again_later';
        }
    } catch (err) {
        console.error('Copy error:', err);
        alert('Copy error. Please try again.');
    }

    document.body.removeChild(textField);
}

watch(
    [() => props.search, () => props.date],
    debounce(([searchValue, dateValue]) => {
        getResults(currentPage.value, searchValue, dateValue);
    }, 300)
);

const getResults = async (page = 1, search = '', date = '') => {
    try {
        let url = `/transaction/withdrawal_request_data?page=${page}`;

        if (search) {
            url += `&search=${search}`;
        }

        if (date) {
            url += `&date=${date}`;
        }

        const response = await axios.get(url);
        requests.value = response.data.transactions;
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
    withdrawalRequestModal.value = true;
    requestDetails.value = request;
};

const closeModal = () => {
    withdrawalRequestModal.value = false;
};

const openApproveModal = (request) => {
    approveRequestModal.value = true;
    requestDetails.value = request;
};

const closeApproveModal = () => {
    approveRequestModal.value = false;
};

const openRejectModal = (request) => {
    rejectRequestModal.value = true;
    requestDetails.value = request;
};

const closeRejectModal = () => {
    rejectRequestModal.value = false;
};

const approveRequest = (requestDetails) => {
    form.id = requestDetails.id;
    form.post(route('transaction.approve_withdrawal_request'), {
        onSuccess: () => {
            closeApproveModal();
            closeModal();
            getResults(1, props.search, props.date);
        },
        onError: (errors) => {
        // Handle any errors
        console.error(errors);
        }
    });
}

const rejectRequest = (requestDetails) => {
    form.id = requestDetails.id;
    form.post(route('transaction.reject_withdrawal_request'), {
        onSuccess: () => {
            closeRejectModal();
            closeModal();
            getResults(1, props.search, props.date);
        },
        onError: (errors) => {
        // Handle any errors
        console.error(errors);
        }
    });
}

</script>

<template>
    <div class="w-full py-3 justify-between items-center inline-flex">
        <div class="text-white font-semibold">{{ $t('public.total') }}: $&nbsp;{{ formatAmount(totalAmount) }}</div>
    </div>

    <div v-if="requests.data.length == 0" >
        <div class="w-full h-[360px] p-3 bg-gray-800 rounded-xl flex-col justify-center items-center inline-flex">
            <div class="self-stretch h-[212px] py-5 flex-col justify-start items-center gap-3 flex">
                <NoRequest class="w-40 h-[120px] relative" />
                <div class="self-stretch text-center text-gray-300 text-sm">
                    {{ $t('public.no_withdrawal_request_message') }}
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
                <tbody>
                    <tr v-for="(request, index) in requests.data" :key="request.id" class="bg-gray-800 text-xs border-b border-gray-700" :class="{ 'border-transparent': index === requests.data.length - 1 }" @click="openModal(request)">
                        <td>
                            <div class="flex justify-between items-center gap-3 py-2">
                                <div>
                                    <div class="text-gray-300 text-xs">{{ formatDateTime(request.created_at) }}</div>
                                    <div class="text-white text-sm font-medium break-all">{{ request.user.name }}</div>
                                </div>
                                <div class="text-white text-right text-md font-medium">$&nbsp;{{ formatAmount(request.transaction_amount) }}</div>
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
                :data="requests"
                :limit="10"
                @pagination-change-page="handlePageChange"
            />
        </div>
    </div>

    <Modal :show="withdrawalRequestModal" :title="$t('public.withdrawal_request')" @close="closeModal" max-width="sm">
        <div v-if="requestDetails">
            <div class="w-full justify-start items-center gap-3 my-5 pb-3 border-b border-gray-700 inline-flex">
                <img class="w-9 h-9 rounded-full" :src="requestDetails.user.profile_photo || '/data/profile_photo.svg'" alt="Client profile picture"/>
                <div class="w-full flex-col justify-start items-start inline-flex">
                    <div class="self-stretch text-white text-md font-medium break-all">{{ requestDetails.user.name }}</div>
                    <div class="text-gray-300 text-xs">{{ $t('public.id') }}: {{ requestDetails.user.id_number }}</div>
                </div>
            </div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="text-gray-300 text-xs">{{ $t('public.transaction_id') }}</div>
                <div class="text-white text-sm font-medium">{{ requestDetails.transaction_number }}</div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="text-gray-300 text-xs">{{ $t('public.requested_date') }}</div>
                <div class="text-white text-sm font-medium">{{ formatDateTime(requestDetails.created_at) }}</div>
            </div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="text-gray-300 text-xs">{{ $t('public.withdrawal_amount') }}</div>
                <div class="text-white text-sm font-medium">{{ formatAmount(requestDetails.transaction_amount) }}</div>
            </div>

            <div class="grid grid-cols-2 items-center mb-5">
                <div class="text-gray-300 text-xs">{{ $t('public.usdt_address') }}</div>
                <div class="flex items-start">
                    <div class="text-white text-sm font-medium break-all">
                        {{ requestDetails.to_wallet_address }}
                        <Tooltip :content="$t('public.' + tooltipContent)" placement="bottom">
                            <DuplicateIcon aria-hidden="true" :class="['w-4 h-4 text-gray-200']" @click.stop.prevent="copyTestingCode(requestDetails.to_wallet_address)" style="cursor: pointer" />
                        </Tooltip>
                    </div>
                </div>
            </div>

            <div class="items-center pt-8 flex gap-3">
                <Button variant="danger" class="w-full" @click.prevent="openRejectModal(requestDetails)">{{ $t('public.reject') }}</Button>
                <Button variant="success" class="w-full" @click.prevent="openApproveModal(requestDetails)">{{ $t('public.approve') }}</Button>
            </div>
        </div>
    </Modal>

    <Modal :show="approveRequestModal" :title="$t('public.approve_withdrawal_request')" @close="closeApproveModal" max-width="sm">
        <form>
            <div class="my-5 px-1">
                <div>
                    <Label for="from_wallet_address" :value="$t('public.from_usdt_address')" class="mb-1.5" :invalid="form.errors.from_wallet_address" />
                    <Input
                        id="from_wallet_address"
                        class="block w-full mb-5 bg-transparent text-white"
                        :invalid="form.errors.from_wallet_address"
                        v-model="form.from_wallet_address"
                        required
                    />

                    <InputError class="mt-1.5" :message="form.errors.from_wallet_address" />
                </div>

                <div>
                    <Label for="txn_hash" :value="$t('public.txn_hash')" class="mb-1.5" :invalid="form.errors.txn_hash" />

                    <Input
                        id="txn_hash"
                        class="block w-full mb-5 bg-transparent text-white"
                        :invalid="form.errors.txn_hash"
                        v-model="form.txn_hash"
                        required
                    />

                    <InputError class="mt-1.5" :message="form.errors.txn_hash" />
                </div>

                <div>
                    <Label for="remarks" class="mb-1.5" :invalid="form.errors.remarks">{{ $t('public.description_optional') }}</Label>

                    <Input
                        id="remarks"
                        class="block w-full bg-transparent text-white"
                        :invalid="form.errors.remarks"
                        v-model="form.remarks"
                        :placeholder="$t('public.description_optional_placeholder')"
                    />

                    <InputError class="mt-1.5" :message="form.errors.remarks" />
                </div>
            </div>
            <InputError :message="form.errors.transaction_amount" />

            <div class="w-full flex justify-end pt-8 gap-3">
                <Button variant="outline" class="w-full border border-gray-600" @click.prevent="closeApproveModal">Cancel</Button>
                <Button variant="success" class="w-full" :disabled="form.processing" @click.prevent="approveRequest(requestDetails)">Approve</Button>
            </div>
        </form>
    </Modal>

    <Modal :show="rejectRequestModal" :title="$t('public.reject_withdrawal_request')" @close="closeRejectModal" max-width="sm">
        <form>
            <div class="my-5 px-1">
                <div>
                    <Label for="remarks" class="mb-1.5" :invalid="form.errors.remarks">{{ $t('public.description_optional') }}</Label>

                    <Input
                        id="remarks"
                        class="block w-full bg-transparent text-white"
                        :invalid="form.errors.remarks"
                        v-model="form.remarks"
                        :placeholder="$t('public.description_optional_placeholder')"
                    />

                    <InputError class="mt-1.5" :message="form.errors.remarks" />
                </div>
            </div>

            <div class="w-full flex justify-end pt-8 gap-3">
                <Button variant="outline" class="w-full border border-gray-600" @click.prevent="closeRejectModal">{{ $t('public.cancel') }}</Button>
                <Button variant="danger" class="w-full" :disabled="form.processing" @click.prevent="rejectRequest(requestDetails)">{{ $t('public.reject') }}</Button>
            </div>
        </form>
    </Modal>

</template>
