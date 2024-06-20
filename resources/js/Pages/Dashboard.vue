<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ExpandIcon } from '@/Components/Icons/outline';
import { ClientIcon, DepositIcon, WithdrawalIcon, PurchaseIcon, PammFundInIcon } from '@/Components/Icons/solid';
import { transactionFormat } from "@/Composables/index.js";

const props = defineProps({
    totalClient: Number,
    totalDeposit: [String, Number],
    totalWithdrawal: [String, Number],
    totalPurchasesEA: [String, Number],
    totalPammFundIn: [String, Number],
});

const { formatDateTime, formatAmount } = transactionFormat();

const handleRedirectTo = (pending) => {
    switch (pending) {
        case 'member':
            window.location.href = route('member.client_listing',);
            break
        case 'deposit':
            window.location.href = route('dashboard.deposit_transactions');
            break
        case 'withdrawal':
            window.location.href = route('dashboard.withdrawal_transactions');
            break
        case 'purchase_robotec':
            window.location.href = route('dashboard.robotec_purchase');
            break
        case 'pamm':
            window.location.href = route('dashboard.pamm_transactions');
            break
        default:
            console.error('Unknown pending status:', pending)
    }
}
</script>

<template>
    <Head :title="$t('public.dashboard')" />

    <AuthenticatedLayout dashboard>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-loose px-4 py-2">{{ $t('public.dashboard') }}</h2>
        </template>

        <div class="mb-3 px-4">
            <div class="text-gray-300 text-sm">{{ $t('public.total_net_balance') }} ($):</div>
            <div class="text-xl font-semibold text-white">{{ formatAmount(props.totalDeposit - props.totalWithdrawal) }}</div>
        </div>

        <div class="w-full flex-col justify-start items-center inline-flex">
            <div class="self-stretch px-4 pt-5 pb-11 bg-gray-400 rounded-tl-3xl rounded-tr-3xl shadow flex-col justify-start items-start gap-2 flex -mb-8" @click.prevent="handleRedirectTo('member')">
                <div class="self-stretch justify-between items-start inline-flex">
                    <div class="w-9 h-9 p-2 bg-gray-900 rounded-[50px] justify-center items-center flex">
                        <ClientIcon class="w-5 h-5 relative flex-col justify-start items-start flex text-white" />
                    </div>
                    <ExpandIcon class="w-4 h-4 relative opacity-50 text-white"/>
                </div>
                <div class="self-stretch justify-between items-center inline-flex">
                    <div class="text-gray-100 text-xs font-medium font-sans leading-[18px]">{{ $t('public.total_clients') }} (pax)</div>
                    <div class="text-white text-xl font-semibold font-sans leading-loose">{{ props.totalClient }}</div>
                </div>
            </div>
            <div class="self-stretch px-4 pt-5 pb-11 bg-gray-500 rounded-tl-3xl rounded-tr-3xl shadow flex-col justify-start items-start gap-2 flex -mb-8" @click.prevent="handleRedirectTo('deposit')">
                <div class="self-stretch justify-between items-start inline-flex">
                    <div class="w-9 h-9 p-2 bg-black rounded-[50px] justify-center items-center flex">
                        <DepositIcon class="w-5 h-5 relative flex-col justify-start items-start flex text-white" />
                    </div>
                    <ExpandIcon class="w-4 h-4 relative opacity-50 text-white" />
                </div>
                <div class="self-stretch justify-between items-center inline-flex">
                    <div class="text-gray-100 text-xs font-medium font-sans leading-[18px]">{{ $t('public.total_deposit') }} ($)</div>
                    <div class="text-success-500 text-xl font-semibold font-sans leading-loose">{{ formatAmount(props.totalDeposit) }}</div>
                </div>
            </div>
            <div class="self-stretch px-4 pt-5 pb-11 bg-gray-600 rounded-tl-3xl rounded-tr-3xl shadow flex-col justify-start items-start gap-2 flex -mb-8" @click.prevent="handleRedirectTo('withdrawal')">
                <div class="self-stretch justify-between items-start inline-flex">
                    <div class="w-9 h-9 p-2 bg-gray-900 rounded-[50px] justify-center items-center flex">
                        <WithdrawalIcon class="w-5 h-5 relative flex-col justify-start items-start flex text-white" />
                    </div>
                    <ExpandIcon class="w-4 h-4 relative opacity-50 text-white" />
                </div>
                <div class="self-stretch justify-between items-center inline-flex">
                    <div class="text-gray-100 text-xs font-medium font-sans leading-[18px]">{{ $t('public.total_withdrawal') }} ($)</div>
                    <div class="text-white text-xl font-semibold font-sans leading-loose">{{ formatAmount(props.totalWithdrawal) }}</div>
                </div>
            </div>
            <div class="self-stretch px-4 pt-5 pb-11 bg-gray-700 rounded-tl-3xl rounded-tr-3xl shadow flex-col justify-start items-start gap-2 flex -mb-8" @click.prevent="handleRedirectTo('purchase_robotec')">
                <div class="self-stretch justify-between items-start inline-flex">
                    <div class="w-9 h-9 p-2 bg-gray-900 rounded-[50px] justify-center items-center flex">
                        <PurchaseIcon class="w-5 h-5 relative flex-col justify-start items-start flex text-white" />
                    </div>
                    <ExpandIcon class="w-4 h-4 relative opacity-50 text-white" />
                </div>
                <div class="self-stretch justify-between items-center inline-flex">
                    <div class="text-gray-100 text-xs font-medium font-sans leading-[18px]">{{ $t('public.purchased_ea') }} ($)</div>
                    <div class="text-success-500 text-xl font-semibold font-sans leading-loose">{{ formatAmount(props.totalPurchasesEA) }}</div>
                </div>
            </div>
            <div class="self-stretch px-4 pt-5 pb-[60px] bg-gray-800 rounded-tl-3xl rounded-tr-3xl shadow flex-col justify-start items-start gap-2 flex -mb-8" @click.prevent="handleRedirectTo('pamm')">
                <div class="self-stretch justify-between items-start inline-flex">
                    <div class="w-9 h-9 p-2 bg-gray-900 rounded-[50px] justify-center items-center flex">
                        <PammFundInIcon class="w-5 h-5 relative flex-col justify-start items-start flex text-white" />
                    </div>
                    <ExpandIcon class="w-4 h-4 relative opacity-50 text-white" />
                </div>
                <div class="self-stretch justify-between items-center inline-flex">
                    <div class="text-gray-100 text-xs font-medium font-sans leading-[18px]">{{ $t('public.pamm_fund_in') }} ($)</div>
                    <div class="text-success-500 text-xl font-semibold font-sans leading-loose">{{ formatAmount(props.totalPammFundIn) }}</div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
