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
    <div class="mr-8 rounded-lg shadow bg-gray-700" role="alert">
        <div class="flex items-center p-3 text-gray-700 rounded-lg" :style="alertStyle">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 bg-gray-500 rounded-full">
                <component :is="iconComponent" class="h-8 w-8 bg-transparent" :class="textColor" />
                <span class="sr-only">Check icon</span>
            </div>
            <div>
                <div class="ml-3 text-sm text-white font-semibold">{{ props.title }}</div>
                <div class="ml-3 text-xs text-gray-300 before:text-sm font-normal">{{ props.message }}</div>
            </div>
        </div>
    </div>
</template>
