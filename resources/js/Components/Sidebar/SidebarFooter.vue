<script setup>
import Button from '@/Components/Button.vue'
import { LogOutIcon } from '@/Components/Icons/outline'
import { sidebarState } from '@/Composables'

</script>

<template>
<div class="flex-shrink-0 px-4 pt-3 pb-8 border-t border-gray-800">
    <Button
        iconOnly
        type="button"
        v-slot="{ iconSizeClasses }"
        size="sm"
        class="bg-transparent hover:bg-gray-500 px-1 py-3 w-full"
        @click="sidebarState.isOpen = !sidebarState.isOpen"
        :href="route('logout')"
        method="post"
        as="button"
    >
        <div class="flex items-center justify-start w-full px-4 py-3">
            <LogOutIcon
                aria-hidden="true"
                :class="[iconSizeClasses]" 
                size="sm"
                class="w-5 h-5"
            />
            <span class="pl-4">Log Out</span>
        </div>
    </Button>
</div>
</template>
