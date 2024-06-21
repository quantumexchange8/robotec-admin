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

        <div class="flex flex-col items-center gap-10 px-4">
            <div class="flex flex-col justify-center items-center gap-3 self-stretch mt-10">
                <Success class="w-12 h-12" />

                <div class="flex flex-col items-center gap-2 self-stretch">
                    <div class="text-center text-lg text-white font-semibold">
                        {{ $t('public.success_1') }}
                    </div>

                    <div class="text-center text-sm text-gray-300">
                        {{ $t('public.password_changed_success_message') }}
                    </div>
                </div>

            </div>
            
            <Button variant="primary" size="lg" class="w-full text-sm font-semibold" @click.prevent="goToLoginPage">
                {{ $t('public.return_login') }}
            </Button>

        </div>
    </GuestLayout>
</template>
