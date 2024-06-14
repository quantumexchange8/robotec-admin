<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/Button.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Success from '@/Components/Success.vue';

const goToLoginPage = () => {
    window.location.href = '/login'; // Redirect to the login page URL
}

</script>

<template>
    <GuestLayout>
        <Head :title="$t('public.reset_password_success')" />

        <div class="flex justify-center mt-10">
            <Success class="w-12 h-12 mb-3" />
        </div>

        <div class="text-center mb-10">
            <div class="mb-2 text-xl text-white font-semibold">
                {{ $t('public.success_1') }}
            </div>

            <div class="text-md text-gray-300">
                {{ $t('public.password_changed_success_message') }}
            </div>
        </div>

        <div class="flex items-center justify-end mt-10">
            <Button variant="primary" size="lg" class="w-full" @click.prevent="goToLoginPage">
                {{ $t('public.return_login') }}
            </Button>
        </div>
    </GuestLayout>
</template>
