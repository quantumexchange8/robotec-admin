<script setup>
import PerfectScrollbar from '@/Components/PerfectScrollbar.vue'
import SidebarLink from '@/Components/Sidebar/SidebarLink.vue'
import SidebarCollapsible from '@/Components/Sidebar/SidebarCollapsible.vue'
import SidebarCollapsibleItem from '@/Components/Sidebar/SidebarCollapsibleItem.vue'
import { HomeIcon, UserCircleIcon, GlobeAltIcon, Users01Icon, CoinsHandIcon, Speedometer04, CreditCardEditIcon, CreditCardDownloadIcon } from '@/Components/Icons/outline'
import {usePage} from "@inertiajs/vue3";
import {ref} from "vue";

const page = usePage();
const pendingCommissionCount = ref(page.props.pendingCommissionCount);
const pendingWithdrawalCount = ref(page.props.pendingWithdrawalCount);
</script>

<template>
    <PerfectScrollbar
        tagname="nav"
        aria-label="main"
        class="relative flex flex-col flex-1 max-h-full gap-4 px-3"
    >
        <SidebarLink
            :title="$t('public.dashboard')"
            :href="route('dashboard.index')"
            :active="route().current('dashboard.*')"
        >
            <template #icon>
                <HomeIcon
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="$t('public.network')"
            :href="route('network.client_network')"
            :active="route().current('network.client_network')"
        >
            <template #icon>
                <GlobeAltIcon
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="$t('public.client_listing')"
            :href="route('member.client_listing')"
            :active="route().current('member.client_listing')"
        >
            <template #icon>
                <Users01Icon
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="$t('public.commission_payout')"
            :href="route('commission.commission_payout')"
            :active="route().current('commission.commission_payout')"
            :pending-counts="pendingCommissionCount"
        >
            <template #icon>
                <CoinsHandIcon
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="$t('public.pamm_return')"
            :href="route('pamm.pamm_return')"
            :active="route().current('pamm.pamm_return')"
        >
            <template #icon>
                <Speedometer04
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="$t('public.wallet_adjustment')"
            :href="route('transaction.wallet_adjustment')"
            :active="route().current('transaction.wallet_adjustment')"
        >
            <template #icon>
                <CreditCardEditIcon
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="$t('public.withdrawal_request')"
            :href="route('transaction.withdrawal_request')"
            :active="route().current('transaction.withdrawal_request')"
            :pending-counts="pendingWithdrawalCount"
        >
            <template #icon>
                <CreditCardDownloadIcon
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="$t('public.my_profile')"
            :href="route('profile.edit')"
            :active="route().current('profile.edit')"
        >
            <template #icon>
                <UserCircleIcon
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <!-- <SidebarLink
            :title="$t('public.sidebar.account_info')"
            :href="route('account_info.account_info')"
            :active="route().current('account_info.*')"
        >
            <template #icon>
                <ViewGridIcon
                    class="flex-shrink-0 w-6 h-6"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarCollapsible
            :title="$t('public.sidebar.copy_trading')"
            :active="route().current('trading.*')"
        >
            <template #icon>
                <CoinsHandIcon
                    class="flex-shrink-0 w-6 h-6"
                    aria-hidden="true"
                />
            </template>

            <SidebarCollapsibleItem
                :href="route('trading.master_listing')"
                :title="$t('public.sidebar.master_trader_listing')"
                :active="route().current('trading.master_listing')"
            />
            <SidebarCollapsibleItem
                :href="route('trading.subscription_listing')"
                :title="$t('public.subscriptions')"
                :active="route().current('trading.subscription_listing')"
            />
        </SidebarCollapsible> -->


    </PerfectScrollbar>
</template>
