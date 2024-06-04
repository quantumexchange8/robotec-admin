<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    value: String,
    invalid: String,
    important: { type: Boolean, default: false },
});
</script>

<template>
    <label class="block text-sm font-medium">
      <span v-if="props.value" :class="{ 'text-error-500': props.invalid }">{{ props.value }}<span v-if="props.important" class="text-error-500">&nbsp;*</span></span>
      <span v-else><slot /></span>
    </label>
  </template>
  