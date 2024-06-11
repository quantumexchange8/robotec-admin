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
    wallet_address: '',
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
        // console.log(members);
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
    form.wallet_address = clientDetails.cash_wallet.wallet_address;

    // Find the country object with the matching dial code
    const country = CountryLists.find(country => country.value  === clientDetails.dial_code);
    if (country) {
        // Set the dial code in the form
        form.dial_code = country;
        // Remove the dial code from the phone number
        const dialCode = clientDetails.dial_code.replace(/\D/g, ''); // Extract only numeric characters from the dial code
        const phoneWithoutDialCode = clientDetails.phone.replace(/\D/g, ''); // Extract only numeric characters from the phone number

        const dialCodeLength = dialCode.length;
        form.phone = phoneWithoutDialCode.substring(dialCodeLength);
    } 
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
    <div class="relative overflow-x-auto rounded-lg">
        <div v-if="members.data.length <= 0" class="w-full flex justify-center my-8">
            <div>
                <div class="p-3 flex flex-col items-center justify-center bg-gray-800 w-full h-[360px]">
                    <div class="flex flex-col items-center mb-3 p-5">
                        <NoClient class="w-40 h-[120px]" />
                        <div class="w-full text-center text-gray-300 text-sm font-normal font-sans leading-tight mt-3">
                            It looks like you haven't added any clients yet. Start by creating your first client to get started!
                        </div>
                    </div>
                </div>
                <div class="px-4 py-5 flex items-center justify-center">
                    <div class="rounded-full bg-primary-500 w-9 h-9 flex items-center justify-center">
                        <div class="text-center text-white text-sm font-medium font-sans leading-tight">1</div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <table class="w-full text-sm text-left text-gray-500 mt-5">
                <tbody>
                    <tr
                        v-for="member in members.data"
                        :key="member.id"
                        class="bg-gray-800 text-xs font-normal text-white border-b border-gray-700"
                        @click="openClientModal(member)"
                    >
                        <td class="px-3 py-2.5" colspan="4">
                            <div class="inline-flex items-center gap-2 mr-3">
                                <img :src="member.profile_photo ? member.profile_photo : 'https://img.freepik.com/free-icon/user_318-159711.jpg'" class="w-8 h-8 rounded-full" alt="">
                                <div class="flex flex-col">
                                    <div>
                                        {{ member.name }}
                                    </div>
                                    <div class="flex">
                                        <div class="text-gray-300 text-xs font-normal font-sans leading-[18px] mr-2">ID: {{ member.id }}</div>
                                        <div class="flex items-center text-xs font-normal font-sans leading-[18px]">
                                            <div class="text-gray-300">Commission:&nbsp;</div>
                                            <div class="text-success-300">{{ member.id }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-center mt-4" v-if="!isLoading">
                <TailwindPagination
                    :item-classes="paginationClass"
                    :active-classes="paginationActiveClass"
                    :data="members"
                    :limit="2"
                    @pagination-change-page="handlePageChange"
                />
            </div>
        </div>
    </div>

    <Modal :show="clientDetailModal" title="Client Details" @close="closeClientModal" max-width="sm">
        <div v-if="clientDetails">
            <div class="w-full justify-start items-center gap-3 inline-flex">
                <img class="w-9 h-9 rounded-full" :src="clientDetails.profile_photo || 'https://via.placeholder.com/32x32'" alt="Client profile picture"/>
                <div class="w-full flex-col justify-start items-start inline-flex">
                    <div class="self-stretch text-white text-base font-medium font-sans leading-normal">{{ clientDetails.name }}</div>
                    <div class="text-gray-300 text-xs font-normal font-sans leading-[18px]">ID: {{ clientDetails.id }}</div>
                </div>
            </div>

            <div class="w-full h-px bg-gray-700 my-4"></div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">Email</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ clientDetails.email }}</div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">Phone Number</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ clientDetails.phone }}</div>
            </div>
            <div class="grid grid-cols-2 items-center">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">Upline</div>
                <div v-if="clientDetails.upline" class="col-span-1 flex items-center">
                    <img class="w-5 h-5 rounded-full mr-2" :src="clientDetails.upline.profile_photo || 'https://via.placeholder.com/32x32'" alt="Client upline profile picture"/>
                    <div class="text-white text-xs font-normal font-sans leading-tight">{{ clientDetails.upline.name }}</div>
                </div>
            </div>

            <div class="w-full h-px bg-gray-700 my-4"></div>

            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">Total Deposit</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ clientDetails.totalDeposit }}</div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">Total Withdrawal</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ clientDetails.totalWithdrawal }}</div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">Referee</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ clientDetails.referee }}</div>
            </div>
            <div class="grid grid-cols-2 items-center mb-2">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">Total Commission</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ clientDetails.totalCommission }}</div>
            </div>
            <div class="grid grid-cols-2 items-center">
                <div class="col-span-1 text-gray-300 text-xs font-normal font-sans leading-[18px]">PAMM Fund In Amount</div>
                <div class="col-span-1 text-white text-xs font-normal font-sans leading-tight">{{ clientDetails.totalFundedPAMM }}</div>
            </div>

            <div class="w-full h-px bg-gray-700 my-4"></div>

            <div class="items-center mb-5">
                <div class="text-gray-300 text-xs font-normal font-sans leading-[18px] mb-1">USDT Address</div>
                <div class="text-white text-xs font-normal font-sans leading-tight">{{ clientDetails.cash_wallet.wallet_address }}</div>
            </div>

            <div class="items-center pt-8 flex gap-3">
                <Button variant="gray" class="w-full" @click.prevent="openEditModal(clientDetails)">Edit</Button>
                <Button variant="danger" class="w-full" @click.prevent="openDeleteModal(clientDetails)">Delete</Button>
            </div>

        </div>
    </Modal>

    <Modal :show="editModal" title="Edit Client" @close="closeEditModal" max-width="sm">
        <form>
            <div class="my-5 px-1">
                <div>
                    <Label for="name" value="Client Name" class="text-gray-300 mb-1.5" :invalid="form.errors.name" important />
                    <Input
                        id="name"
                        class="block w-full mb-5 bg-transparent text-white"
                        :invalid="form.errors.name"
                        v-model="form.name"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <Label for="email" value="Email" class="text-gray-300 mb-1.5" :invalid="form.errors.email" important />

                    <Input
                        id="email"
                        type="email"
                        class="block w-full mb-5 bg-transparent text-white"
                        :invalid="form.errors.email"
                        v-model="form.email"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div>
                    <Label for="phone_number" value="Phone Number" class="text-gray-300 mb-1.5" :invalid="form.errors.phone || form.errors.dial_code" important />
                            
                    <div class="grid grid-cols-5">
                        <div class="col-span-2">
                            <div class="mr-1.5">
                                <Combobox 
                                    :options="CountryLists"
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

                <InputError class="mt-2" :message="form.errors.phone" />
                </div>
                <div>
                    <Label for="wallet_address" value="USDT Address" class="text-gray-300 mb-1.5" :invalid="form.errors.wallet_address" />

                    <Input
                        id="wallet_address"
                        class="block w-full bg-transparent text-white"
                        :invalid="form.errors.wallet_address"
                        v-model="form.wallet_address"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.wallet_address" />
                </div>
            </div>
            <div class="w-full flex justify-end pt-8 gap-3">
                <Button variant="transparent" class="w-full border border-gray-600" @click.prevent="closeEditModal">Cancel</Button>
                <Button variant="primary" class="w-full" :disabled="form.processing" @click.prevent="updateClient(clientDetails)">Save Changes</Button>
            </div>
        </form>
    </Modal>

    <Modal :show="deleteModal" @close="closeDeleteModal" max-width="sm">
        <form>
            <div class="items-center flex justify-center">
                <WarningIcon  class="w-12 h-12" />
            </div>
            <div class="my-8">
                <div class="mb-1 text-center text-white text-sm font-semibold font-sans leading-tight ">Are you sure you want to delete this client?</div>
                <div class="text-center text-gray-300 text-xs font-normal font-sans leading-[18px] ">This action cannot be undone and the deleted item will be removed permanently.</div>
            </div>
            <div class="w-full flex justify-center gap-3">
                <Button variant="transparent" class="w-full border border-gray-600" @click.prevent="closeDeleteModal">Cancel</Button>
                <Button variant="danger" class="w-full" :disabled="form.processing" @click.prevent="deleteClient(clientDetails)">Delete</Button>
            </div>
        </form>
    </Modal>
</template>
