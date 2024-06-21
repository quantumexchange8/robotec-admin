<script setup>
import { ref, computed, defineProps, defineEmits } from 'vue'
import { Switch } from '@headlessui/vue'

// Define props and emit
const props = defineProps({
    checked: {
        type: Boolean,
        required: true,
    },
});

// Define emit function
const emit = defineEmits(['update:checked']);

// Compute the checked state
const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});

const enabled = ref(proxyChecked.value);
const toggle = () => {
    enabled.value = !enabled.value;
    emit('update:checked', enabled.value);
};
</script>

<template>
    <!-- Use the `checked` state to conditionally style the button. -->
    <Switch v-model="enabled" as="template" v-slot="{ checked }">
        <button class="relative inline-flex w-[42px] h-6 items-center rounded-full":class="checked ? 'bg-primary-500 hover:bg-primary-800' : 'bg-gray-500 hover:bg-gray-700'" @click="toggle">
            <span :class="checked ? 'translate-x-5' : 'translate-x-0.5'"class="inline-block h-5 w-5 transform rounded-full bg-white hover:bg-gray-200 transition" />
        </button>
    </Switch>
</template>
