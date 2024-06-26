<script setup>
import { ref } from 'vue';
import { Users01Icon } from '@/Components/Icons/outline';

// Define props
const props = defineProps({
    clients: Array, // Adjusted type to Array for clients
    level: Number,
});

// // Function to toggle client and its children at the next level
// const toggleClient = (client) => {
//     if (client.children && client.children.length > 0) {
//         client.isActive = !client.isActive;
//     }
// };

// // Function to get all active children for the next level
// const getActiveChildren = (clients) => {
//     let children = [];
//     clients.forEach(client => {
//         if (client.isActive && client.children && client.children.length > 0) {
//             children = children.concat(client.children);
//         }
//     });
//     return children;
// };

// Function to toggle client and its children at the next level
const toggleClient = (clickedClient) => {
    props.clients.forEach(client => {
        if (client !== clickedClient) {
            client.isActive = false; // Deactivate all other clients
        }
    });
    clickedClient.isActive = !clickedClient.isActive; // Toggle the clicked client
};

// Function to get all active children for the next level
const getActiveChildren = (clients) => {
    let children = [];
    clients.forEach(client => {
        if (client.isActive && client.children && client.children.length > 0) {
            children = children.concat(client.children);
        }
    });
    return children;
};
</script>

<template>
    <!-- Level {{ level }} -->
    <div class="my-5">
        <div class="w-full py-1 my-3 justify-start items-center gap-1 inline-flex">
            <div class="text-gray-300 text-xs font-normal font-sans leading-[18px]">{{ $t('public.level') }} {{ level }}</div>
            <div class="grow shrink basis-0 h-px bg-gray-600 rounded-[10px]"></div>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <div v-for="(child, index) in props.clients" :key="index" class="flex items-center justify-center relative">
                <div class="w-full px-2 pt-4 pb-3 bg-gray-800 rounded-2xl flex-col justify-center items-center gap-2 inline-flex relative"
                    :class="{ 'shadow-inner border border-primary-500': child.children && child.children.length > 0 && child.isActive }"
                    @click="toggleClient(child)">
                    <img class="w-7 h-7 rounded-full" :src="child.profile_photo_url || '/data/profile_photo.svg'" />
                    <div class="self-stretch flex-col justify-start items-center flex">
                        <div class="self-stretch text-center text-white text-xs w-full overflow-hidden truncate">{{ child.name }}</div>
                        <div class="text-center text-gray-300 text-xxs">{{ $t('public.id') }}: {{ child.id_number }}</div>
                    </div>
                </div>
                <!-- Conditional rendering for children -->
                <div v-if="child.children && child.children.length > 0"
                    class="absolute -bottom-1.5 flex justify-center">
                    <div class="w-4 h-4 p-[3.20px] bg-primary-500 rounded-full justify-center items-center inline-flex cursor-pointer"
                        @click.stop="toggleClient(child)">
                        <div class="w-[9.60px] h-[9.60px] relative flex justify-center items-center">
                            <Users01Icon class="text-white" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Recursive rendering of child components -->
    <template v-if="getActiveChildren(props.clients).length > 0">
        <ClientChild 
            :clients="getActiveChildren(props.clients)" 
            :level="level + 1" 
        />
    </template>
</template>
