<script setup>
import { Link } from '@inertiajs/vue3'
import { sidebarState } from '@/Composables'
import { EmptyCircleIcon } from '@/Components/Icons/outline'

const props = defineProps({
    href: {
        type: String,
        required: false,
    },
    active: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        required: true,
    },
    external: {
        type: Boolean,
        default: false,
    },
    pendingCounts: Number
})

const Tag = !props.external ? Link : 'a'
</script>

<template>
    <component
        :is="Tag"
        v-if="href"
        :href="href"
        :class="[
            'px-4 py-3 flex items-center gap-3 rounded-md transition-colors text-center',
            {
                'text-gray-300 hover:bg-gray-500': !active,
                'text-white bg-gray-700 shadow-lg hover:bg-gray-500': active,
            },
        ]"
    >
        <slot name="icon">
            <EmptyCircleIcon aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-300" />
        </slot>

        <div class="flex items-center gap-2 w-full">
            <span
                class="text-base text-left font-medium w-full"
                v-show="sidebarState.isOpen || sidebarState.isHovered"
            >
                {{ title }}
            </span>
            <div v-if="pendingCounts > 0" class="h-5 w-5 grow-0 shrink-0 flex flex-col justify-center items-center bg-error-500 text-white rounded-full">
                <div class="text-white text-xs font-medium">{{ pendingCounts }}</div>
            </div>
        </div>
    </component>

    <button
        v-else
        type="button"
        :class="[
            'p-2 w-full flex items-center gap-3 rounded-md transition-colors',
            {
                'hover:bg-gray-500': !active,
                'bg-gray-500 shadow-lg hover:bg-gray-500': active,
            },
        ]"
    >
        <slot name="icon">
            <EmptyCircleIcon aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-300" />
        </slot>

        <div class="flex items-center gap-2">
            <span
                class="text-base font-medium"
                v-show="sidebarState.isOpen || sidebarState.isHovered"
            >
                {{ title }}
            </span>
            <div v-if="pendingCounts > 0" class="h-5 w-5 grow-0 shrink-0 flex flex-col justify-center items-center bg-error-500 text-white rounded-full">
                <div class="text-white text-xs font-medium">{{ pendingCounts }}</div>
            </div>
        </div>
    </button>
</template>
