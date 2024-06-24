<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps, watchEffect } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import InputIconWrapper from '@/Components/InputIconWrapper.vue';
import Combobox from '@/Components/Combobox.vue';
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from "@headlessui/vue";
import CashWalletAdjustment from "@/Pages/WalletAdjustment/Partials/CashWalletAdjustment.vue";
import CommissionWalletAdjustment from "@/Pages/WalletAdjustment/Partials/CommissionWalletAdjustment.vue";

const form = useForm({
    client: null,
});

const client = ref(null);

function loadClient(query, setOptions) {
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

const type = ref('cash_wallet');
const updateWalletType = (wallet_type) => {
    type.value = wallet_type;
};

</script>

<template>
    <Head :title="$t('public.wallet_adjustment')" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-loose mb-3">{{ $t('public.wallet_adjustment') }}</h2>
        </template>

        <div class="rounded-md shadow-md mb-3">
            <div class="w-full py-3 sticky -top-1 bg-gray-900 z-[5]">
                <div class="flex flex-col bg-gray-900">
                    <Combobox
                        :load-options="loadClient"
                        v-model="client"
                        :placeholder="$t('public.select_client')"
                        image
                    />
                </div>
                <div class="text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.select_client_message') }}</div>
            </div>
            <div class="w-full">
                <TabGroup>
                    <TabList class="max-w-md flex p-3 sticky top-[82px] bg-gray-900">
                        <Tab
                            as="template"
                            v-slot="{ selected }"
                            class="px-3 py-2"
                        >
                            <button
                                @click="updateWalletType('cash_wallet')"
                                :class="[
                                    'w-full py-2.5 text-sm font-semibold text-gray-300',
                                    'ring-white ring-offset-0 focus:outline-none focus:ring-0',
                                    selected
                                        ? 'text-white border-b-2 border-white'
                                        : 'border-b border-gray-700',
                                ]"
                            >
                                {{ $t('public.cash_wallet') }}
                            </button>
                        </Tab>
                        <Tab
                            as="template"
                            v-slot="{ selected }"
                            class="px-3 py-2"
                        >
                            <button
                                @click="updateWalletType('commission_wallet')"
                                :class="[
                                    'w-full py-2.5 text-sm font-semibold text-gray-300',
                                    'ring-white ring-offset-0 focus:outline-none focus:ring-0',
                                    selected
                                        ? 'text-white border-b-2 border-white'
                                        : 'border-b border-gray-700',
                                ]"
                            >
                                {{ $t('public.commission_wallet') }}
                            </button>
                        </Tab>
                    </TabList>
                    <TabPanels class="py-3">
                        <TabPanel>
                            <CashWalletAdjustment
                                :type="type"
                                :client="client"
                            />
                        </TabPanel>
                        <TabPanel>
                            <CommissionWalletAdjustment
                                :type="type"
                                :client="client"
                            />
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>