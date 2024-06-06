<script setup>
import PerfectScrollbar from '@/Components/PerfectScrollbar.vue'
import SidebarLink from '@/Components/Sidebar/SidebarLink.vue'
// import { DashboardIcon, CoinsHandIcon, ReportIcon, Wallet01Icon } from '@/Components/Icons/outline'
import SidebarCollapsible from '@/Components/Sidebar/SidebarCollapsible.vue'
import SidebarCollapsibleItem from '@/Components/Sidebar/SidebarCollapsibleItem.vue'
// import { TemplateIcon, ViewGridIcon, SwitchHorizontalIcon, UserGroupIcon, UserIcon } from '@heroicons/vue/outline'
import { HomeIcon, UserCircleIcon, GlobeAltIcon, Users01Icon, CoinsHandIcon, Speedometer04, CreditCardEditIcon, CreditCardDownloadIcon } from '@/Components/Icons/outline'
</script>

<template>
    <PerfectScrollbar
        tagname="nav"
        aria-label="main"
        class="relative flex flex-col flex-1 max-h-full gap-4 px-3"
    >
        <SidebarLink
            :title="$t('public.sidebar.dashboard')"
            :href="route('dashboard')"
            :active="route().current('dashboard')"
        >
            <template #icon>
                <HomeIcon
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="$t('public.sidebar.network')"
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
            :title="$t('public.sidebar.client_listing')"
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
            :title="$t('public.sidebar.commission_payout')"
            :href="route('commission.commission_payout')"
            :active="route().current('commission.commission_payout')"
        >
            <template #icon>
                <CoinsHandIcon
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="$t('public.sidebar.pamm_return')"
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
            :title="$t('public.sidebar.wallet_adjustment')"
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

        <!-- <SidebarLink
            :title="$t('public.sidebar.withdrawal_request')"
            :href="route('.withdrawal_request')"
            :active="route().current('.withdrawal_request')"
        >
            <template #icon>
                <CreditCardDownloadIcon
                    class="flex-shrink-0 w-5 h-5"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink> -->

        <SidebarLink
            :title="$t('public.sidebar.my_profile')"
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
