<script setup>
import {computed, ref} from 'vue'
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue'
import {CheckIcon, ChevronDownIcon} from '@/Components/Icons/solid';
import BankImg from "/public/assets/bank.jpg"

const props = defineProps({
    options: Array,
    modelValue: [String, Number, Array, Object],
    placeholder: {
        type: String,
        default: 'Please Select'
    },
    optionMessage: {
        type: String,
        default: 'Please Select'
    },
    multiple: Boolean,
    error: {
        type: Boolean,
        default: false
    },
    withImg: {
        type: Boolean,
        default: false,
    },
    isPhoneCode: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['update:modelValue'])

const labelWithImg = computed(() => {
    return props.options
        .filter(option => {
            if (Array.isArray(props.modelValue)) {
                return props.modelValue.includes(option.value);
            }
            return props.modelValue === option.value;
        })
        .map(option => ({ label: option.label, value: option.value, imgUrl: option.imgUrl }));
});

const label = computed(() => {
    return props.options.filter(option => {
        if (Array.isArray(props.modelValue)) {
            return props.modelValue.includes(option.value);
        }

        return props.modelValue === option.value;
    }).map(option => option.label).join(', ')
})

const shouldShowLabelWithImg = computed(() => props.withImg && labelWithImg.value.length > 0);
const labelWithImgString = computed(() => labelWithImg.value.map(item => item.label).join(', '));
const labelWithValue = computed(() => labelWithImg.value.map(item => item.value).join(', '));
</script>

<template>
    <Listbox
        :multiple="props.multiple"
        :model-value="props.modelValue"
        @update:modelValue="value => emit('update:modelValue', value)"
    >
        <div
            class="relative rounded-lg border focus:ring-1 focus:outline-none"
            :class="[
                {
                      'border-error-300 focus-within:ring-error-300 hover:border-error-300 focus-within:border-error-300 focus-within:shadow-error-light': error,
                      'border-gray-800 focus:ring-primary-500 hover:border-primary-500 focus-within:border-primary-500 focus-within:shadow-primary-light': !error,
                }
            ]"
        >
            <ListboxButton
                class="relative w-full cursor-default rounded-lg shadow-xs bg-gray-800 py-4 px-3 text-left focus:ring-1 focus:outline-none"
                :class="[
                    {
                        'border-transparent focus-within:ring-error-300 focus:border-error-300 focus:shadow-error-light': error,
                        'border-transparent focus-within:ring-primary-500 focus-within:border-primary-500 focus-within:shadow-primary-light': !error
                    }
                ]"
            >
                <div class="flex gap-2 text-gray-50 items-center">
                    <template v-if="shouldShowLabelWithImg && withImg && isPhoneCode">
                        <img v-for="item in labelWithImg" :key="item.label" :src="item.imgUrl" width="24" alt="img">
                        <span class="block truncate">{{ labelWithValue }}</span>
                    </template>
                    <template v-else-if="shouldShowLabelWithImg && withImg && !isPhoneCode">
                        <img v-for="item in labelWithImg" class="rounded-full" :key="item.label" :src="item.imgUrl ? item.imgUrl : BankImg" width="24" alt="img">
                        <span class="block truncate">{{ labelWithImgString }}</span>
                    </template>
                    <template v-else-if="!withImg && label">
                        <span class="block truncate">{{ label }}</span>
                    </template>
                    <template v-else>
                        <span class="block truncate text-gray-300">{{ props.placeholder }}</span>
                    </template>
                </div>
                <span
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <ChevronDownIcon
                        class="h-5 w-5 text-white"
                        aria-hidden="true"
                    />
                </span>
            </ListboxButton>
            <transition
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <ListboxOptions
                    class="z-10 absolute border border-gray-800 mt-2 max-h-52 w-full overflow-auto rounded-lg bg-gray-800 p-2 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                    :style="{ width: isPhoneCode ? '360px' : '' }"
                >
                    <ListboxOption
                        v-slot="{ active, selected }"
                        v-for="option in props.options"
                        :key="option.label"
                        :value="option.value"
                        as="template"
                    >
                        <li
                            :class="[
                                active ? 'bg-primary-900 text-gray-50' : 'text-gray-50',
                                selected ? 'bg-primary-900' : '',
                                'relative cursor-default select-none p-3 rounded-lg',
                            ]"
                        >
                            <template v-if="withImg">
                                 <span
                                     :class="[
                                        'block truncate',
                                      ]"
                                 >
                                     <span class="flex items-center gap-2"><img class="rounded-full" :key="option.label" :src="option.imgUrl ? option.imgUrl : BankImg" width="24" alt="img">{{ option.label }} <span v-if="isPhoneCode">({{ option.value }})</span></span></span>
                            </template>
                            <template v-else>
                                 <span
                                     :class="[
                                selected ? 'font-medium' : 'font-normal',
                                'block truncate',
                              ]"
                                 >{{ option.label }}</span>
                            </template>
                            <!-- <span
                                v-if="selected"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-white"
                            >
                                <CheckIcon class="h-5 w-5 text-gray-300" aria-hidden="true"/>
                            </span> -->
                        </li>
                    </ListboxOption>
                    <li v-if="props.options.length === 0" class="text-gray-50 py-2.5 px-4">
                        {{ optionMessage }}
                    </li>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>
