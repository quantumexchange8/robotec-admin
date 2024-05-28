<script setup>
import Button from '@/Components/Button.vue'
import { XMarkIcon } from '@/Components/Icons/solid'
import { sidebarState } from '@/Composables'
</script>

<template>
    <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-gray-800">
        <Button
            iconOnly
            variant="transparent"
            type="button"
            v-slot="{ iconSizeClasses }"
            size="sm"
            v-show="sidebarState.isOpen || sidebarState.isHovered"
            @click="sidebarState.isOpen = !sidebarState.isOpen"
            :srText="sidebarState.isOpen ? 'Close sidebar' : 'Open sidebar'"
        >

            <XMarkIcon
                aria-hidden="true"
                :class="[iconSizeClasses]" />
        </Button>
    </div>
</template>
