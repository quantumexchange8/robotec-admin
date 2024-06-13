<script setup>
import { toRefs, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator(value) {
            return ['primary', 'gray', 'success', 'danger', 'transparent', 'outline'].includes(value)
        },
    },
    type: {
        type: String,
        default: 'submit',
    },
    size: {
        type: String,
        default: 'sm',
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
    'inline-flex items-center justify-center rounded-lg transition-colors text-sm font-medium select-none disabled:cursor-not-allowed',
]

const variantClasses = (variant) => ({
    'bg-primary-500 focus:bg-primary-600 text-white disabled:bg-primary-900 disabled:text-primary-700': variant === 'primary',
    'bg-gray-600 focus:bg-gray-700 text-white disabled:bg-gray-700 disabled:text-gray-600': variant === 'gray',
    'bg-success-500 focus:bg-success-600 text-white disabled:bg-success-900 disabled:text-success-800': variant === 'success',
    'bg-error-500 focus:bg-error-600 text-white disabled:bg-error-700 disabled:text-error-600': variant === 'danger',
    'bg-transparent focus:text-gray-400 text-white disabled:text-gray-600': variant === 'transparent',
    'bg-gray-900 focus:bg-gray-800 text-white border border-gray-600 disabled:border-gray-700 disabled:text-gray-600': variant === 'outline',
})

const classes = computed(() => [
    ...baseClasses,
    iconOnly
        ? {
                'p-2': size === 'sm',
                'p-2.5': size === 'base',
                'p-3': size === 'lg',
            }
        : {
                'px-3 py-2': size === 'sm',
                'px-4 py-2': size === 'base',
                'px-4 py-3': size === 'lg',
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
    'w-5 h-5'
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
