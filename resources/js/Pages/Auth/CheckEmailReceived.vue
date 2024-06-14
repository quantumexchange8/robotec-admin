<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@/Components/Icons/solid';
import toast from "@/Composables/toast.js";
import { trans } from 'laravel-vue-i18n';

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
            title: trans('public.password_reset_link_sent'),
            type: 'success',
        });
    } catch (error) {
        // If there's an error, show an error toast
        toast.add({
            title: trans('public.password_reset_link_sent_error'),
            type: 'error'
        });
        console.error('Error resending password reset email:', error);
    }
};

const goToLoginPage = () => {
    window.location.href = '/login'; // Redirect to the login page URL
}

const openEmailApp = () => {
    window.location.href = 'mailto:';
}
</script>

<template>
    <GuestLayout>
        <Head :title="$t('public.check_your_email')" />

        <div class="text-center my-10">
            <div class="mb-2 text-xl text-white font-semibold">
                {{ $t('public.check_your_email') }}
            </div>

            <div class="text-sm text-gray-300">
                {{ $t('public.check_email_message') }}
            </div>

            <div class="text-sm text-gray-300 font-bold">
                {{ props.email }}
            </div>
        </div>

        <!-- Didn't receive the email? and Click to resend -->
            <div>
                <Button @click.prevent="openEmailApp" variant="primary" size="lg" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full">
                    {{ $t('public.open_email_app') }}
                </Button>
            </div>
            
            <div class="grid grid-cols-6 gap-2.5 my-3 w-full">

            <!-- Didn't receive the email? -->
            <div class="col-span-3 text-sm text-gray-300 flex items-center justify-start">
                {{ $t('public.not_received_email') }}
            </div>

            <!-- Click to resend -->
            <div class="col-span-3">
                <Button @click.prevent="resendEmail(props.email)" variant="transparent" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full">
                    {{ $t('public.click_resend') }}
                </Button>
            </div>
        </div>

        <!-- Back to Log In -->
        <div class="col-span-6 mt-4">
            <Button @click.prevent="goToLoginPage" variant="transparent" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full">
                <ArrowLeftIcon class="w-5 h-5 mr-2"/>{{ $t('public.back_to_login') }}
            </Button>
        </div>
    </GuestLayout>
</template>
