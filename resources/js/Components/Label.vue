<script setup>
const props = defineProps({
    value: String,
    invalid: String,
});
</script>

<template>
    <label
        class="block text-xs font-medium"
        :class="[
            {'text-gray-300': !invalid},
            {'text-error-500': invalid},
        ]"
    >
        <div v-if="value">
            {{ value }}<span class="text-error-500 text-xs font-medium"> *</span>
        </div>
        <div v-else>
            <slot />
        </div>
    </label>
</template>
