<script setup>
import { TailwindPagination } from "laravel-vue-pagination";
import { computed, ref, watch, watchEffect } from "vue";
import debounce from "lodash/debounce.js";
import { transactionFormat } from "@/Composables/index.js";
import { usePage, useForm } from "@inertiajs/vue3";
import Button from "@/Components/Button.vue";
import Modal from "@/Components/Modal.vue";
import { WarningIcon } from '@/Components/Icons/solid';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Input from '@/Components/Input.vue';
import Combobox from "@/Components/Combobox.vue";
import toast from "@/Composables/toast.js";
import NoClient from "@/Components/NoClient.vue";
import CountryLists from '/public/data/countries.json'
import Loading from "@/Components/Loading.vue";

const props = defineProps({
    // refresh: Boolean,
    // isLoading: Boolean,
    search: String,
    sortField: String,
    sortDirection: String,
    upline: Object,
    purchasedEA: String,
    fundedPAMM: String,
});

const form = useForm({
    id: '',
    name: '',
    email: '',
    dial_code:'',
    phone: '',
    usdt_address: '',
});

const members = ref({ data: [] });
const totalClient = ref(0);
const currentPage = ref(1);
const isLoading = ref(props.isLoading);
const emit = defineEmits(['update:loading', 'update:refresh', 'update:export', 'update:totalClient']);
const { formatAmount } = transactionFormat();
const clientDetailModal = ref(false);
const editModal = ref(false);
const deleteModal = ref(false);
const clientDetails = ref(null);

watchEffect(() => {
    // Emit the totalClient value whenever it changes
    emit('update:totalClient', totalClient.value);
});

watch(
    [() => props.search, () => props.sortField, () => props.sortDirection, () => props.upline, () => props.purchasedEA, () => props.fundedPAMM],
    debounce(([searchValue, sortField, sortDirection, upline, purchasedEA, fundedPAMM]) => {
        getResults(1, searchValue, sortField, sortDirection, upline, purchasedEA, fundedPAMM);
    }, 300)
);

const getResults = async (page = 1, search = props.search, sortField = props.sortField, sortDirection = props.sortDirection, upline = props.upline, purchasedEA = props.purchasedEA, fundedPAMM = props.fundedPAMM) => {
    isLoading.value = true;
    try {
        let url = `/member/client_data?page=${page}`;

        if (search) {
            url += `&search=${search}`;
        }

        if (sortField) {
            url += `&sortField=${sortField}`;
        }

        if (sortDirection) {
            url += `&sortDirection=${sortDirection}`;
        }

        if (upline) {
            url += `&upline=${upline.value}`;
        }

        if (purchasedEA) {
            url += `&purchasedEA=${purchasedEA}`;
        }

        if (fundedPAMM) {
            url += `&fundedPAMM=${fundedPAMM}`;
        }

        const response = await axios.get(url);
        members.value = response.data.clients;
        totalClient.value = response.data.totalClient;
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
        emit('update:loading', false);
    }
};

getResults();

const handlePageChange = (newPage) => {
    if (newPage >= 1) {
        currentPage.value = newPage;
        getResults(newPage);
    }
};

watchEffect(() => {
    if (usePage().props.title !== null) {
        getResults();
    }
});

const paginationClass = [
    'bg-transparent border-0 text-white'
];

const paginationActiveClass = [
    'border-0 bg-primary-500 rounded-full text-white'
];

const openClientModal = (member) => {
    clientDetailModal.value = true;
    clientDetails.value = member;
};

const closeClientModal = () => {
    clientDetailModal.value = false;
};

const openEditModal = (clientDetails) => {
    editModal.value = true;
    form.name = clientDetails.name;
    form.email = clientDetails.email;
    form.usdt_address = clientDetails.usdt_address;
    // Set the dial code in the form
    form.dial_code = { value: clientDetails.dial_code };
    // Remove the dial code from the phone number
    const dialCode = clientDetails.dial_code.replace(/\D/g, ''); // Extract only numeric characters from the dial code
    const phoneWithoutDialCode = clientDetails.phone.replace(/\D/g, ''); // Extract only numeric characters from the phone number

    const dialCodeLength = dialCode.length;
    form.phone = phoneWithoutDialCode.substring(dialCodeLength);
};

const closeEditModal = () => {
    editModal.value = false;
};

const openDeleteModal = (clientDetails) => {
    deleteModal.value = true;
    clientDetails.value = clientDetails;
};

const closeDeleteModal = () => {
    deleteModal.value = false;
};

function loadDialCodes(query, setOptions) {
    fetch('/member/getDialCodes?query=' + query)
        .then(response => response.json())
        .then(results => {
            setOptions(
                results.map(country => {
                    return {
                        value: country.phone_code,
                        label: country.name,
                    }
                })
            )
        });
}

const deleteClient = (clientDetails) => {
    form.id = clientDetails.id;
    form.delete(route('member.delete_client'), {
        onSuccess: () => {
            closeDeleteModal();
            closeClientModal();
            getResults();
        },
        onError: (errors) => {
            // Handle any errors
            console.error(errors);
        }
    });
};

const updateClient = (clientDetails) => {
    form.id = clientDetails.id;
    form.post(route('member.update_client'), {
        onSuccess: () => {
            closeEditModal();
            closeClientModal();
            getResults();
        },
        onError: (errors) => {
            // Handle any errors
            console.error(errors);
        }
    });
};

</script>

<template>
    <div class="relative overflow-x-auto rounded-xl p-3 bg-gray-800">
        <div v-if="isLoading" class="flex items-center justify-center py-8">
            <Loading />
        </div>
        <div v-else-if="members.data.length === 0" class="w-full flex justify-center">
            <div class="p-3 flex flex-col items-center justify-center bg-gray-800 w-full h-[360px]">
                <div class="flex flex-col items-center mb-3 p-5">
                    <NoClient class="w-40 h-[120px]" />
                    <div class="w-full text-center text-gray-300 text-sm font-normal font-sans leading-tight mt-3">
                        {{ $t('public.no_client_message_1') }}
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="flex justify-center items-center">
            <table class="w-full text-sm text-left text-gray-500">
                <tbody>
                <tr
                    v-for="(member, index) in members.data"
                    :key="member.id"
                    class="text-xs font-normal text-white border-b border-gray-700"
                    :class="{
                            'border-transparent': index === members.data.length - 1
                        }"
                    @click="openClientModal(member)"
                >
                    <td class="py-2 flex gap-3 items-center self-stretch">
                        <img :src="member.profile_photo ? member.profile_photo : '/data/profile_photo.svg'" class="w-8 h-8 rounded-full" alt="">
                        <div class="flex flex-col">
                            <div class="font-medium text-sm">
                                {{ member.name }}
                            </div>
                            <div class="flex gap-2 items-center self-stretch">
                                <div class="text-gray-300 text-xs">{{ $t('public.id') }}: {{ member.id_number }}</div>
                                <div class="flex items-center text-xs">
                                    <div class="text-gray-300">{{ $t('public.commission') }}:&nbsp;</div>
                                    <div class="text-success-500 font-medium">$ {{ formatAmount(member.commission) }}</div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-center py-5 px-4" v-if="!isLoading">
        <TailwindPagination
            :item-classes="paginationClass"
            :active-classes="paginationActiveClass"
            :data="members"
            :limit="2"
            @pagination-change-page="handlePageChange"
        />
    </div>

    <Modal :show="clientDetailModal" :title="$t('public.client_details')" @close="closeClientModal" max-width="sm">
        <div v-if="clientDetails">
            <div class="w-full justify-start items-center gap-3 inline-flex">
                <img class="w-9 h-9 rounded-full" :src="clientDetails.profile_photo || '/data/profile_photo.svg'" alt="Client profile picture"/>
                <div class="w-full flex-col justify-start items-start inline-flex">
                    <div class="self-stretch text-white text-md font-medium font-sans leading-normal break-all">{{ clientDetails.name }}</div>
                    <div class="text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.id') }}: {{ clientDetails.id_number }}</div>
                </div>
            </div>

            <div class="w-full h-px bg-gray-700 my-4"></div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="text-gray-300 text-xs leading-[18px] break-all">{{ $t('public.email') }}</div>
                <div class="text-white text-sm leading-tight break-all">{{ clientDetails.email }}</div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="text-gray-300 text-xs leading-[18px]">{{ $t('public.phone_number') }}</div>
                <div class="text-white text-sm leading-tight break-all">{{ clientDetails.phone }}</div>
            </div>
            <div class="grid grid-cols-2 items-center">
                <div class="text-gray-300 text-xs leading-[18px]">{{ $t('public.upline') }}</div>
                <div v-if="clientDetails.upline" class="flex items-center">
                    <img class="w-5 h-5 rounded-full mr-2" :src="clientDetails.upline.profile_photo || '/data/profile_photo.svg'" alt="Client upline profile picture"/>
                    <div class="text-white text-sm leading-tight break-all">{{ clientDetails.upline.name }}</div>
                </div>
            </div>

            <div class="w-full h-px bg-gray-700 my-4"></div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="text-gray-300 text-xs font-normal leading-[18px]">{{ $t('public.total_deposit') }}</div>
                <div class="text-white text-sm font-medium leading-tight">$ {{ formatAmount(clientDetails.total_deposit ? clientDetails.total_deposit : 0) }}</div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="text-gray-300 text-xs font-normal leading-[18px]">{{ $t('public.total_withdrawal') }}</div>
                <div class="text-white text-sm font-medium leading-tight">$ {{ formatAmount(clientDetails.total_withdrawal ? clientDetails.total_withdrawal : 0) }}</div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="text-gray-300 text-xs font-normal leading-[18px]">{{ $t('public.referee') }}</div>
                <div class="text-white text-sm font-medium leading-tight">{{ clientDetails.total_referee ? clientDetails.total_referee : 0 }}</div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="text-gray-300 text-xs font-normal leading-[18px]">{{ $t('public.total_commission') }}</div>
                <div class="text-white text-sm font-medium leading-tight">$ {{ formatAmount(clientDetails.total_commission ? clientDetails.total_commission : 0) }}</div>
            </div>
            <div class="grid grid-cols-2 items-center">
                <div class="text-gray-300 text-xs font-normal leading-[18px]">{{ $t('public.pamm_fund_in_amount') }}</div>
                <div class="text-white text-sm font-medium leading-tight">$ {{ formatAmount(clientDetails.pamm_fund_in_amount ? clientDetails.pamm_fund_in_amount : 0) }}</div>
            </div>

            <div class="w-full h-px bg-gray-700 my-4"></div>

            <div class="items-center mb-5">
                <div class="text-gray-300 text-xs font-normal font-sans leading-[18px] mb-1">{{ $t('public.usdt_address') }}</div>
                <div class="text-white text-xs font-normal font-sans leading-tight overflow-x-auto">{{ clientDetails.usdt_address ?? $t('public.no_usdt_address') }}</div>
            </div>

            <div class="items-center pt-8 flex gap-3">
                <Button variant="gray" class="w-full" @click.prevent="openEditModal(clientDetails)">{{ $t('public.edit') }}</Button>
                <Button variant="danger" class="w-full" @click.prevent="openDeleteModal(clientDetails)">{{ $t('public.delete') }}</Button>
            </div>

        </div>
    </Modal>

    <Modal :show="editModal" :title="$t('public.edit_client')" @close="closeEditModal" max-width="sm">
        <form>
            <div class="flex flex-col gap-5">
                <div class="flex flex-col gap-1.5">
                    <Label for="name" :value="$t('public.client_name')" :invalid="form.errors.name" />
                    <Input
                        id="name"
                        class="block w-full bg-transparent text-white"
                        :invalid="form.errors.name"
                        v-model="form.name"
                        required
                    />

                    <InputError :message="form.errors.name" />
                </div>

                <div class="flex flex-col gap-1.5">
                    <Label for="email" :value="$t('public.email')" :invalid="form.errors.email" />

                    <Input
                        id="email"
                        type="email"
                        class="block w-full bg-transparent text-white"
                        :invalid="form.errors.email"
                        v-model="form.email"
                        required
                    />

                    <InputError :message="form.errors.email" />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label for="phone_number" :value="$t('public.phone_number')" :invalid="form.errors.phone || form.errors.dial_code" />

                    <div class="grid grid-cols-5">
                        <div class="col-span-2">
                            <div class="mr-1.5">
                                <Combobox
                                    :load-options="loadDialCodes"
                                    id="dial_code"
                                    class="block w-full"
                                    :invalid="form.errors.phone"
                                    v-model="form.dial_code"
                                    required
                                    isPhoneCode
                                />
                            </div>
                        </div>

                        <div class="col-span-3">
                            <Input
                                id="phone"
                                class="block w-full bg-transparent text-white"
                                :invalid="form.errors.phone"
                                placeholder="Phone Number"
                                v-model="form.phone"
                                required
                            />
                        </div>
                    </div>

                <InputError :message="form.errors.phone" />
                </div>
                <div class="flex flex-col gap-1.5">
                    <Label for="usdt_address" :invalid="form.errors.usdt_address">{{ $t('public.usdt_address') }}</Label>

                    <Input
                        id="usdt_address"
                        class="block w-full bg-transparent text-white"
                        :invalid="form.errors.usdt_address"
                        v-model="form.usdt_address"
                        required
                    />

                    <InputError :message="form.errors.usdt_address" />
                </div>

                <div class="w-full flex justify-end pt-8 gap-3">
                    <Button variant="transparent" class="w-full border border-gray-600" @click.prevent="closeEditModal">{{ $t('public.cancel') }}</Button>
                    <Button variant="primary" class="w-full" :disabled="form.processing" @click.prevent="updateClient(clientDetails)">{{ $t('public.save_changes') }}</Button>
                </div>
            </div>
        </form>
    </Modal>

    <Modal :show="deleteModal" @close="closeDeleteModal" max-width="sm">
        <form>
            <div class="items-center flex justify-center">
                <WarningIcon  class="w-12 h-12" />
            </div>
            <div class="my-8">
                <div class="mb-1 text-center text-white text-sm font-semibold font-sans leading-tight ">{{ $t('public.delete_warning_message') }}</div>
                <div class="text-center text-gray-300 text-xs font-normal font-sans leading-[18px] ">{{ $t('public.delete_warning_message_1') }}</div>
            </div>
            <div class="w-full flex justify-center gap-3">
                <Button variant="transparent" class="w-full border border-gray-600" @click.prevent="closeDeleteModal">{{ $t('public.cancel') }}</Button>
                <Button variant="danger" class="w-full" :disabled="form.processing" @click.prevent="deleteClient(clientDetails)">{{ $t('public.delete') }}</Button>
            </div>
        </form>
    </Modal>
</template>
