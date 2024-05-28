<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@/Components/Icons/solid';
import toast from "@/Composables/toast.js";

const props = defineProps({
    email: {
        type: String,
    },
});

const form = useForm({
    email: props.email,
});

const resendEmail = async (email) => {
    try {
        // Send the request to resend the email
        await form.post(route('password.resend'), {
            email: email
        });
        // If the request is successful, show a success toast
        toast.add({
            title: 'Password Reset Link Resent!',
            type: 'success',
        });
    } catch (error) {
        // If there's an error, show an error toast
        toast.add({
            title: 'Password Reset Link Resent Error!',
            type: 'error'
        });
        console.error('Error resending password reset email:', error);
    }
};

const goToLoginPage = () => {
    window.location.href = '/login'; // Redirect to the login page URL
}

</script>

<template>
    <GuestLayout>
        <Head title="Check your email" />

        <div class="text-center mb-10">
            <div class="mb-2 text-xl text-white font-semibold">
                Check your email
            </div>

            <div class="text-sm text-gray-300">
                We've sent a password reset link to
            </div>

            <div class="text-sm text-gray-300 font-bold">
                {{ props.email }}
            </div>
        </div>

        <!-- Didn't receive the email? and Click to resend -->
        <div class="grid grid-cols-6 gap-4 w-full mt-10">
            <div class="col-span-6">
                <Button variant="primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full">
                    Open email app
                </Button>
            </div>

            <!-- Didn't receive the email? -->
            <div class="col-span-3 text-sm text-gray-300 flex items-center justify-start">
                Didn't receive the email?
            </div>

            <!-- Click to resend -->
            <div class="col-span-3">
                <Button @click="resendEmail(props.email)" variant="transparent" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full">
                    Click to resend
                </Button>
            </div>
        </div>

        <!-- Back to Log In -->
        <div class="col-span-6 mt-4">
            <Button @click="goToLoginPage" variant="transparent" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full">
                <ArrowLeftIcon class="w-5 h-5 mr-2"/>Back to Log In
            </Button>
        </div>
    </GuestLayout>
</template>
