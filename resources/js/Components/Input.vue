<script setup>
import { onMounted, ref, watch } from 'vue'

defineProps({
    modelValue: [String, Number],
    withIcon: {
        type: Boolean,
        default: false,
    },
    invalid: [String, Array]
})

defineEmits(['update:modelValue'])

const input = ref(null)
const isFocused = ref(false)

const focus = () => input.value?.focus()

defineExpose({
    input,
    focus
})

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus()
    }

    input.value.addEventListener('focus', () => {
        isFocused.value = true
    })
    input.value.addEventListener('blur', () => {
        isFocused.value = false
    })
})

watch(isFocused, (newValue) => {
    if (newValue) {
        focus()
    }
})
</script>

<template>
    <input
        :class="[
            'py-2.5 rounded-lg text-base font-normal shadow-xs border placeholder:text-gray-500 caret-primary-500',
            'disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-800 disabled:text-gray-400 dark:disabled:text-gray-500',
            {
                'px-4': !withIcon,
                'pl-11 pr-4': withIcon,
            },
            {
                'border-gray-600 focus:ring-primary-500 hover:border-primary-500 focus:border-primary-500': !invalid,
                'border-error-500 focus:ring-error-500 hover:border-error-500 focus:border-error-500': invalid,
            },
            {
                'bg-gray-700 text-gray-300': modelValue && !isFocused,
                'bg-transparent text-white': !modelValue || isFocused
            }
        ]"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
