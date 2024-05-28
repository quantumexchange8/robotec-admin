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
        <button class="relative inline-flex h-8 w-16 items-center rounded-full":class="checked ? 'bg-primary-500 hover:bg-primary-800' : 'bg-gray-500 hover:bg-gray-700'" @click="toggle">
            <span :class="checked ? 'translate-x-9' : 'translate-x-1'"class="inline-block h-6 w-6 transform rounded-full bg-white hover:bg-gray-200 transition" />
        </button>
    </Switch>
</template>
