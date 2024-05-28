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
        <Head title="Reset Password Success" />

        <div class="flex justify-center">
            <Success class="w-12 h-12 mb-3" />
        </div>

        <div class="text-center mb-10">
            <div class="mb-2 text-xl text-white font-semibold">
                Success!
            </div>

            <div class="text-sm text-gray-300">
                Your password has been changed successfully.
            </div>
        </div>

        <div class="flex items-center justify-end mt-10">
            <Button variant="primary" class="w-full" @click="goToLoginPage">
                Return to Login Page
            </Button>
        </div>
    </GuestLayout>
</template>
