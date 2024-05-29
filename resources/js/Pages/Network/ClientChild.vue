<script setup>
import { ref } from 'vue';
import { Users01Icon } from '@/Components/Icons/outline';

// Define props
const props = defineProps({
    clients: Object,
    level: Number,
});

// Function to toggle client and its children at the next level
const toggleClient = (client) => {
    if (client.children && client.children.length > 0 && client.level === props.level) {
        client.isActive = !client.isActive;
    } else {
        client.isActive = true; // Make sure children with active children are always expandable
    }

    // If the client was already active, toggle it back to inactive
    if (client.isActive && client.level === props.level) {
        client.isActive = !client.isActive;
    }
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
    <div>
        <div class="w-full py-1 my-3 justify-start items-center gap-1 inline-flex">
            <div class="text-gray-300 text-xs font-normal font-sans leading-[18px]">Level {{ level }}</div>
            <div class="grow shrink basis-0 h-px bg-gray-600 rounded-[10px]"></div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div v-for="(child, index) in props.clients" :key="index" class="flex items-center justify-center relative">
                <div class="w-full px-2 pt-4 pb-3 bg-gray-800 rounded-2xl flex-col justify-center items-center gap-2 inline-flex relative"
                    :class="{ 'border border-primary-500': child.children && child.children.length > 0 && child.isActive }"
                    @click="toggleClient(child)">
                    <img class="w-7 h-7 rounded-full"
                        :src="child.profile_photo_url || 'https://via.placeholder.com/28x28'" />
                    <div class="self-stretch h-[34px] flex-col justify-start items-center flex">
                        <div class="self-stretch text-center text-white text-xs font-medium font-sans leading-[18px]">{{
                child.name }}</div>
                        <div class="text-center text-gray-300 text-xxs font-normal font-sans leading-none">ID: {{
                child.id }}</div>
                    </div>
                </div>
                <!-- Conditional rendering for children -->
                <div v-if="child.children && child.children.length > 0"
                    class="absolute -bottom-1.5 flex justify-center">
                    <div class="w-4 h-4 p-[3.20px] bg-primary-500 rounded-2xl justify-center items-center inline-flex cursor-pointer"
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