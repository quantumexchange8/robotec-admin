<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, defineProps } from "vue";
import { usePage } from "@inertiajs/vue3";
import NoClient from "@/Components/NoClient.vue";
import Button from '@/Components/Button.vue';
import { PlusCircleIcon, Users01Icon } from '@/Components/Icons/outline';
import ClientChild from '@/Pages/Network/ClientChild.vue'

const user = usePage().props.auth.user;
const profile_photo = usePage().props.auth.user.profile_photo;
const level = 1;

const props = defineProps({
    clients: Object,
});
console.log(props.clients);

const toggleClient = (client) => {
    if (client.children && client.children.length > 0) {
        client.isActive = !client.isActive;
    }
};

const getActiveChildren = () => {
    let children = [];
    props.clients.forEach(client => {
        if (client.isActive && client.children && client.children.length > 0) {
            children = children.concat(client.children);
        }
    });
    return children;
};
</script>

<template>
    <Head title="Network" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xxl text-white leading-loose">Network</h2>
        </template>

        <div class="w-full px-4 py-3 bg-gray-800 rounded-2xl justify-start items-center gap-3 inline-flex">
            <img class="w-8 h-8 rounded-full" :src="profile_photo || 'https://via.placeholder.com/32x32'" alt="User profile picture"/>
            <div class="flex-col justify-start items-start inline-flex">
                <div class="text-white text-sm font-semibold font-sans leading-tight">{{ user.name }}</div>
                <div class="text-gray-400 text-xs font-normal font-sans leading-[18px]">ID: {{ user.id }}</div>
            </div>
        </div>

        <!-- Level 1 -->
        <div class="w-full py-1 my-3 justify-start items-center gap-1 inline-flex">
            <div class="text-gray-300 text-xs font-normal font-sans leading-[18px]">Level {{ level }}</div>
            <div class="grow shrink basis-0 h-px bg-gray-600 rounded-[10px]"></div>
        </div>

        <div v-if="props.clients.length === 0">
            <div class="px-4 py-5 flex items-center justify-center">
                <NoClient class="w-40 h-[120px]" />
            </div>

            <div class="flex justify-center mt-8">
                <Button variant="primary" class="px-3 py-2"><PlusCircleIcon class="w-5 h-5 mr-2 relative" /> New Client</Button>
            </div>
        </div>

        <!-- Display Level 1 clients -->
        <div v-if="props.clients.length > 0" class="grid grid-cols-3 gap-4">
            <div v-for="(client, index) in props.clients" :key="index" class="flex items-center justify-center relative">
                <div 
                    class="w-full px-2 pt-4 pb-3 bg-gray-800 rounded-2xl flex-col justify-center items-center gap-2 inline-flex relative"
                    :class="{ 'border border-primary-500': client.children && client.children.length > 0 && client.isActive }"
                    @click="toggleClient(client)"
                >
                    <img class="w-7 h-7 rounded-full" :src="client.profile_photo_url || 'https://via.placeholder.com/28x28'" />
                    <div class="self-stretch h-[34px] flex-col justify-start items-center flex">
                        <div class="self-stretch text-center text-white text-xs font-medium font-sans leading-[18px]">{{ client.name }}</div>
                        <div class="text-center text-gray-300 text-xxs font-normal font-sans leading-none">ID: {{ client.id }}</div>
                    </div>
                </div>
                <!-- Conditional rendering for children -->
                <div v-if="client.children && client.children.length > 0" class="absolute -bottom-1.5 flex justify-center">
                    <div 
                        class="w-4 h-4 p-[3.20px] bg-primary-500 rounded-2xl justify-center items-center inline-flex cursor-pointer"
                        @click.stop="toggleClient(client)"
                    >
                        <div class="w-[9.60px] h-[9.60px] relative flex justify-center items-center">
                            <Users01Icon class="text-white" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Level n + 1 -->
        <ClientChild v-if="getActiveChildren().length > 0" :clients="getActiveChildren()" :level="level + 1" />

    </AuthenticatedLayout>
</template>
