<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps, watchEffect } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import Modal from "@/Components/Modal.vue";
import NoHistory from "@/Components//NoHistory.vue";
import {transactionFormat} from "@/Composables/index.js";
import { TailwindPagination } from "laravel-vue-pagination";

const props = defineProps({
    pamm: Object,
});
const { formatDateTime, formatAmount } = transactionFormat();

const form = useForm({
    pamm: '',
});

const histories = ref({ data: [] });
const isLoading = ref(props.isLoading);
const emit = defineEmits(['update:loading']);

const getResults = async (page = 1) => {
    isLoading.value = true;
    try {
        const url = `/pamm/pamm_history?page=${page}`;
        const response = await axios.get(url);
        histories.value = response.data;
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

const pammModal = ref(false);

const openModal = () => {
  pammModal.value = true;
};

const closeModal = () => {
  pammModal.value = false;
};

const updatePamm = () => {
    form.post(route('pamm.update_pamm'), {
        onSuccess: () => {
            closeModal();
            form.reset();
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

    <Head :title="$t('public.pamm_return')" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white mb-3">{{ $t('public.pamm_return') }}</h2>
        </template>

        <div class="flex justify-center items-center h-full my-3">
            <div
                class="w-full my-3 px-5 py-8 bg-gray-800 rounded-2xl flex-col justify-center items-center gap-8 inline-flex">
                <div class="flex-col justify-center items-center gap-3 flex mb-8">
                    <div class="flex-col justify-start items-center flex mb-3">
                        <div class="text-center text-white font-semibold">
                            {{ $t('public.current_pamm_return') }}
                        </div>
                        <div class="text-center text-gray-300 text-xs">
                            {{ $t('public.last_updated_at') }} {{ formatDateTime(props.pamm.updated_at, true) }}
                        </div>
                    </div>
                    <div class="text-center text-white text-xxl font-semibold">
                        <span v-if="props.pamm.value > 0">+</span>{{ (props.pamm.value) }} %
                    </div>
                </div>
                <Button variant="primary" class="w-full text-center text-white text-sm font-semibold" @click="openModal">{{ $t('public.update_pamm_return') }}</Button>
            </div>
        </div>

        <span class="text-white font-semibold py-2">
            {{ $t('public.update_history') }}
        </span>

        <div v-if="histories.data.length <= 0">
            <div class="flex justify-center items-center h-full p-3">
                <div
                    class="w-full h-[360px] my-3 px-5 py-8 bg-gray-800 rounded-2xl flex-col justify-center items-center gap-8 inline-flex">
                    <div class="flex flex-col items-center">
                        <NoHistory class="w-40 h-[120px]" />
                        <div
                            class="w-[328px] text-center text-gray-300 text-sm mt-3">
                            {{ $t('public.no_history_message') }}
                        </div>
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
            <div class="p-3 bg-gray-800">
                <table class="w-full text-sm text-left mt-5">
                    <tbody>
                        <tr
                            v-for="(history, index) in histories.data"
                            :key="history.id"
                            class="text-xs text-white border-b border-gray-700"
                            :class="{ 'border-transparent': index === histories.data.length - 1 }"
                        >
                        <td class="py-2 flex justify-between items-center">
                            <div>
                                <div class="text-gray-300 text-xs gap-3">
                                    {{ formatDateTime(history.created_at) }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium text-md"
                                    :class="{ 'text-success-500': history.setting_new_value > 0, 'text-error-500': history.setting_new_value < 0, 'text-white': history.setting_new_value === 0 }">
                                    {{ history.setting_new_value > 0 ? '+' + (history.setting_new_value) : (history.setting_new_value) }}
                                </div>
                            </div>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center mt-4" v-if="!isLoading">
                <TailwindPagination
                    :item-classes="paginationClass"
                    :active-classes="paginationActiveClass"
                    :data="histories"
                    :limit="2"
                    @pagination-change-page="handlePageChange"
                />
            </div>
        </div>

        <Modal :show="pammModal" :title="$t('public.update_pamm_return')" @close="closeModal" max-width="sm">
            <form class="my-5" @submit.prevent="updatePamm">
                <div>
                    <Label for="pamm" :invalid="form.errors.pamm">{{ $t('public.pamm_return') }}</Label>
                    <Input
                        id="pamm"
                        class="block w-full my-1.5 bg-transparent text-white"
                        :invalid="form.errors.pamm"
                        v-model="form.pamm"
                        :placeholder="$t('public.pamm_return_placeholder')"
                        required
                    />

                    <div class="text-gray-300 text-xs">{{ $t('public.pamm_return_rule') }}</div>
                    <InputError :message="form.errors.pamm" />
                </div>
                <div class="w-full flex justify-end pt-8 gap-3">
                    <Button variant="transparent" class="w-full border border-gray-600" @click.prevent="closeModal">{{ $t('public.cancel') }}</Button>
                    <Button variant="primary" class="w-full" :disabled="form.processing" @click.prevent="updatePamm">{{ $t('public.update') }}</Button>
                </div>
            </form>
        </Modal>

    </AuthenticatedLayout>
</template>
