<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { EyeIcon, EyeOffIcon } from '@/Components/Icons/outline';

const props = defineProps({
    modelValue: [String, Number],
    withIcon: {
        type: Boolean,
        default: false,
    },
    invalid: [String, Array],
    is_disabled: {
        type: Boolean,
        default: false
    },
    is_password: {
        type: Boolean,
        default: false
    },
    placeholderText: String,
})

defineEmits(['update:modelValue'])

const input = ref(null)
const isFocused = ref(false)
const showPassword = ref(false);

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

const baseClasses = [
    'py-3 rounded-lg text-sm font-normal shadow-xs border placeholder:text-gray-500 caret-primary-500',
    'disabled:bg-gray-700 disabled:cursor-not-allowed dark:disabled:bg-gray-800 disabled:text-gray-300 dark:disabled:text-gray-500',
]

const classes = computed(() => [
    ...baseClasses,
    {
        'px-4': !props.withIcon,
        'pl-11 pr-4': props.withIcon,
    },
    {
        'border-gray-600 focus:ring-primary-500 hover:border-primary-500 focus:border-primary-500': !props.invalid,
        'border-error-500 focus:ring-error-500 hover:border-error-500 focus:border-error-500': props.invalid,
    },
    {
        'bg-gray-900 text-white': props.modelValue && !isFocused,
        'bg-transparent text-white': !props.modelValue || isFocused
    },
    {
        'w-full pr-12': props.is_password
    }
])
</script>

<template>
    <div 
        v-if="is_password"
        class="relative w-full"
    >
        <input
            :class="classes"
            :value="modelValue"
            :type="showPassword ? 'text' : 'password'"
            @input="$emit('update:modelValue', $event.target.value)"
            ref="input"
            :disabled="is_disabled"
            :placeholder="placeholderText"
        />

        <div 
            class="absolute top-3 right-4 hover:cursor-pointer"
            @click="showPassword = !showPassword"
        >
            <EyeIcon v-show="showPassword" />
            <EyeOffIcon v-show="!showPassword" />
        </div>
    </div>
    
    <input
        v-else
        :class="classes"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
        :disabled="is_disabled"
    />
</template>
