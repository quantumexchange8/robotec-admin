<script setup>
import {Link} from '@inertiajs/vue3'

defineProps({
    href: String
});
</script>

<template>
    <Link
        v-if="href"
        :href="href"
        class="block w-full p-3 rounded-lg text-sm leading-5 text-left transition duration-150 ease-in-out focus:text-white focus:bg-primary-900 text-white hover:text-white hover:bg-primary-900"
    >
        <slot/>
    </Link>
    <span
        v-else
        class="block w-full p-3 rounded-lg text-sm leading-5 text-left transition duration-150 ease-in-out focus:text-white focus:bg-primary-900 text-white hover:text-white hover:bg-primary-900 cursor-pointer"
    >
      <slot/>
    </span>

</template>
