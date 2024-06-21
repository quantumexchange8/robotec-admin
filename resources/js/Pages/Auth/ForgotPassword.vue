<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@/Components/Icons/solid';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'), {
        onError: (errors) => {
            console.error('Error:', errors);
        }
    });
};

const goToLoginPage = () => {
    window.location.href = '/login'; // Redirect to the login page URL
}
</script>

<template>
    <GuestLayout>
        <Head :title="$t('public.forgot_password')" />

        <div class="w-full mt-10 px-4">
            <div class="flex flex-col items-center self-stretch gap-2">
                <div class="text-center text-lg text-white font-semibold">
                    {{ $t('public.forgot_password_1') }}
                </div>

                <div class="text-center text-sm text-gray-300">
                    {{ $t('public.forgot_password_1_message') }}
                </div>
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ status }}
            </div>

            <form class="py-10">
                <div>
                    <Label for="email" :invalid="form.errors.email">{{ $t('public.email') }}</Label>

                    <Input
                        id="email"
                        type="email"
                        class="mt-1.5 block w-full bg-transparent text-white"
                        v-model="form.email"
                        :placeholder="$t('public.email_placeholder')"
                        required
                        autocomplete="username"
                    />

                    <InputError class="mt-1.5" :message="form.errors.email" />
                </div>
            </form>

            <div class="w-full flex flex-col items-center justify-center self-stretch gap-3">
                <Button size="lg" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full font-semibold text-sm" @click.prevent="submit">
                    {{ $t('public.send_email') }}
                </Button>
                <Button variant="transparent" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full font-semibold text-sm" @click.prevent="goToLoginPage">
                    <ArrowLeftIcon class="w-5 h-5 mr-2"/>{{ $t('public.back_to_login') }}
                </Button>
            </div>

        </div>
    </GuestLayout>
</template>
