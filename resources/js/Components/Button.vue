<script setup>
import { toRefs, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator(value) {
            return ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'black', 'transparent', 'primary-transparent', 'primary-opacity', 'gray', 'purple'].includes(value)
        },
    },
    type: {
        type: String,
        default: 'submit',
    },
    size: {
        type: String,
        default: 'base',
        validator(value) {
            return ['sm', 'base', 'lg'].includes(value)
        },
    },
    squared: {
        type: Boolean,
        default: false,
    },
    pill: {
        type: Boolean,
        default: false,
    },
    href: {
        type: String,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    iconOnly: {
        type: Boolean,
        default: false,
    },
    srText: {
        type: String || undefined,
        default: undefined,
    },
    external: {
        type: Boolean,
        default: false,
    }
})

const emit = defineEmits(['click'])

const { type, variant, size, squared, pill, href, iconOnly, srText, external } = props

const { disabled } = toRefs(props)

const baseClasses = [
    'inline-flex items-center justify-center rounded-lg transition-colors font-medium select-none disabled:opacity-50 disabled:cursor-not-allowed',
]

const variantClasses = (variant) => ({
    'bg-primary-500 text-white focus:bg-primary-900 focus:text-primary-700 shadow-lg': variant === 'primary',
    'bg-gray-600 text-white focus:bg-gray-700 focus:text-gray-600 shadow-lg': variant === 'gray',
    'bg-success-500 text-white focus:bg-success-900 focus:text-success-800 shadow-lg': variant === 'success',
    'bg-error-500 text-white focus:bg-error-700 focus:text-error-600 shadow-lg': variant === 'warning',
    'bg-transparent text-white focus:text-gray-600 focus:bg-transparent': variant === 'transparent',
    'bg-transparent text-white-600 ring ring-gray-600 focus:ring-gray-700 focus:text-gray-600': variant === 'ring-transparent',
    'bg-white text-gray-500 focus:bg-primary-500 focus:text-primary-700': variant === 'secondary',
    'bg-red-500 text-white focus:bg-red-500 shadow-lg': variant === 'danger',
    'bg-primary-400 text-white focus:bg-primary-500': variant === 'info',
    'bg-black text-gray-300 focus:bg-black ': variant === 'black',
    'bg-purple-500 text-white focus:bg-purple-600 shadow-lg': variant === 'purple',
})

const classes = computed(() => [
    ...baseClasses,
    iconOnly
        ? {
                'p-1.5': size === 'sm',
                'p-2': size === 'base',
                'p-3': size === 'lg',
            }
        : {
                'px-2.5 py-1.5 text-sm': size === 'sm',
                'px-3 py-2 text-base': size === 'base',
                'px-4 py-3 text-xl': size === 'lg',
            },
    variantClasses(variant),
    {
        'rounded-md': !squared && !pill,
        'rounded-full': pill,
    },
    {
        'pointer-events-none opacity-50': href && disabled.value,
    },
])

const iconSizeClasses = [
    {
        'w-6 h-6': size === 'sm',
        'w-8 h-8': size === 'base',
        'w-11 h-11': size === 'lg',
    },
]

const handleClick = (e) => {
    if (disabled.value) {
        e.preventDefault()
        e.stopPropagation()
        return
    }
    emit('click', e)
}

const Tag = external ?  'a' : Link
</script>

<template>
    <component
        :is="Tag"
        v-if="href"
        :href="!disabled ? href : null"
        :class="classes"
        :aria-disabled="disabled.toString()"
    >
        <span
            v-if="srText"
            class="sr-only"
        >
            {{ srText }}
        </span>

        <slot :iconSizeClasses="iconSizeClasses" />
    </component>

    <button
        v-else
        :type="type"
        :class="classes"
        @click="handleClick"
        :disabled="disabled"
    >
        <span
            v-if="srText"
            class="sr-only"
        >
            {{ srText }}
        </span>

        <slot :iconSizeClasses="iconSizeClasses" />
    </button>
</template>