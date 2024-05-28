<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    value: String,
    invalid: String,
});
</script>

<template>
    <label class="block text-sm font-medium">
        <span v-if="props.value" :class="{ 'text-error-500': props.invalid }">{{ props.value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
