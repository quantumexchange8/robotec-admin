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
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;

    if (/android/i.test(userAgent)) {
        // Intent URL with chooser for Android to prompt user choice
        const emailIntentUrl = "intent://#Intent;scheme=mailto;action=android.intent.action.VIEW;chooser=1;end";
        window.location.href = emailIntentUrl;
    } else if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        // Check if device supports mailto: and can handle email links
        if (navigator.canShare && navigator.canShare({ url: 'mailto:' })) {
            window.location.href = 'mailto:';
        } else {
            // Fallback for iOS devices without default Mail app support
            alert('Please set up a default Mail app to send emails.');
        }
    } else {
        // Fallback for unsupported platforms or unknown user agents
        alert('Opening email app is not supported on your device.');
    }
}
</script>

<template>
    <GuestLayout>
        <Head :title="$t('public.check_your_email')" />

        <div class="flex flex-col items-center gap-10">
            <div class="flex flex-col items-center justify-center self-stretch gap-2 mt-10">
                <div class="text-center text-lg text-white font-semibold">
                    {{ $t('public.check_your_email') }}
                </div>

                <div class="text-center text-sm text-gray-300">
                    {{ $t('public.check_email_message') }}
                    <div class="font-bold">
                        {{ props.email }}
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center self-stretch gap-3 w-full">
                <!-- Didn't receive the email? and Click to resend -->
                    <Button @click.prevent="openEmailApp" variant="primary" size="lg" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full text-sm font-semibold">
                        {{ $t('public.open_email_app') }}
                    </Button>
                    

                    <div class="flex justify-between items-center self-stretch w-auto">
                        <!-- Didn't receive the email? -->
                        <div class="text-sm text-gray-300">
                            {{ $t('public.not_received_email') }}
                        </div>

                        <Button @click.prevent="resendEmail(props.email)" variant="transparent" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="py-2 gap-2.5">
                            {{ $t('public.click_resend') }}
                        </Button>
                    </div>

                <!-- Back to Log In -->
                <Button @click.prevent="goToLoginPage" variant="transparent" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="gap-2 text-sm font-semibold">
                    <ArrowLeftIcon class="w-5 h-5"/>{{ $t('public.back_to_login') }}
                </Button>
            </div>
        </div>
    </GuestLayout>
</template>
