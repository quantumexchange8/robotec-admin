<script setup>
import { computed, ref, onMounted, watch, defineProps, defineEmits, defineExpose } from 'vue';
import { SearchIcon } from '@/Components/Icons/outline'; // Assuming correct import path
import { XCircleIcon } from '@/Components/Icons/solid'; // Assuming correct import path

const props = defineProps({
    modelValue: [String, Number],
    invalid: [String, Array],
    is_disabled: {
        type: Boolean,
        default: false
    },
    placeholder: String,
});

const emit = defineEmits(['update:modelValue']); // Define emit handler

const inputRef = ref(null); // Reference to input element

const clearInput = () => {
    emit('update:modelValue', ''); // Emit event to clear input value
    focus(); // Ensure focus after clearing
};

const focus = () => {
    inputRef.value.focus(); // Focus on the input element
};

// Handle autofocus on mount
onMounted(() => {
    if (inputRef.value && inputRef.value.hasAttribute('autofocus')) {
        inputRef.value.focus();
    }

    const inputElement = inputRef.value;

    inputElement.addEventListener('focus', () => {
        isFocused.value = true;
    });

    inputElement.addEventListener('blur', () => {
        isFocused.value = false;
    });
});

const isFocused = ref(false); // State for focus tracking

// Watch for focus changes and adjust focus if needed
watch(isFocused, (newValue) => {
    if (newValue) {
        focus();
    }
});

// Define computed classes for dynamic styling
const baseClasses = [
    'py-3 rounded-lg text-sm font-normal shadow-xs border-none placeholder:text-gray-300 caret-primary-500 bg-gray-700',
    'disabled:bg-gray-700 disabled:cursor-not-allowed dark:disabled:bg-gray-800 disabled:text-gray-300 dark:disabled:text-gray-500',
];

const classes = computed(() => [
    ...baseClasses,
    {
        'w-full pl-11 pr-11': true,
    },
    {
        'bg-gray-900 text-white': props.modelValue && isFocused.value,
        'text-white': !props.modelValue || !isFocused.value
    },
]);

// Expose input and focus function if needed
defineExpose({
    input: inputRef,
    focus
});
</script>

<template>
    <div class="relative text-gray-400 focus-within:text-primary-500 dark:focus-within:text-primary-400">
        <div class="relative w-full">
            <!-- Icon at the start -->
            <div class="absolute inset-y-0 flex items-center px-4 pointer-events-none">
                <SearchIcon class="h-5 w-5 text-gray-300" /> <!-- Adjust size and color as needed -->
            </div>
            
            <!-- Input field with clear icon at the end for search -->
            <input         
                :class="classes"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                :placeholder="placeholder"
                ref="inputRef"
                :disabled="is_disabled"
            />

            <!-- Clear input icon at the end for search -->
            <div v-show="modelValue" class="absolute top-3 right-4 flex items-center space-x-2 hover:cursor-pointer" @click="clearInput">
                <XCircleIcon class="h-5 w-5 text-gray-600" /> <!-- Adjust size and color as needed -->
            </div>
        </div>
    </div>
</template>
