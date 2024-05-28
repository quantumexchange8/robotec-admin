<script setup>
import {onMounted} from "vue";
import { CheckCircleIcon, XCircleIcon, ExclamationCircleIcon } from '@/Components/Icons/solid';

const props = defineProps({
    title: String,
    message: String,
    type: String, // Accepts 'success', 'info', 'warning', 'error'
    duration: {
        type: Number,
        default: 5000
    }
});

onMounted(() => {
    setTimeout(() => emit('remove'), props.duration);
});

const emit = defineEmits(['remove']);

const alertStyle = {
    success: {
        background: 'linear-gradient(to right, rgba(82, 206, 57, 0.2), #182230, #182230)',
    },
    warning: {
        background: 'linear-gradient(to right, rgba(255, 225, 0, 0.2), #182230, #182230)',
    },
    error: {
        background: 'linear-gradient(to right, rgba(249, 61, 54, 0.2), #182230, #182230)',
    },
    info: {
        background: 'linear-gradient(to right, rgba(17, 144, 255, 0.2), #182230, #182230)',
    },
}[props.type];

const iconComponent = {
    success: CheckCircleIcon,
    warning: ExclamationCircleIcon,
    error: XCircleIcon,
    info: ExclamationCircleIcon,
}[props.type];

const textColor = {
    success: 'text-success-500', // Green color for success
    warning: 'text-warning-500', // Yellow color for warning
    error: 'text-error-500', // Red color for error
    info: 'text-info-500', // Sky color for info
}[props.type];

</script>
<template>
    <div class="flex items-center p-4 text-gray-500 rounded-lg shadow" :style="alertStyle" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 bg-gray-500 rounded-full">
            <component :is="iconComponent" class="h-8 w-8 bg-transparent" :class="textColor" />
            <span class="sr-only">Check icon</span>
        </div>
        <div>
            <div class="ml-3 text-white font-semibold">{{ props.title }}</div>
            <div class="ml-3 text-gray-300 before:text-sm font-normal">{{ props.message }}</div>
        </div>
    </div>
</template>

<!-- <template>
    <div class="flex items-center p-4 text-gray-500 bg-gray-100 rounded-lg shadow dark:text-gray-900 dark:bg-white" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-200">
            <svg class="h-8 w-8 text-green-500"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M5 12l5 5l10 -10" /></svg>
            <span class="sr-only">Check icon</span>
        </div>
        <div class="ml-3 ml-3 before:textml-3 before:-sm font-normal">{{ props.message }}</div>
        <button @click="emit('remove')" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-300 inline-flex h-8 w-8 dark:text-gray-600 dark:hover:ml-3 text-white dark:bg-dark-eval-4 dark:hover:bg-gray-400" data-dismiss-target="#toast-default" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    </div>
</template> -->
