<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'));
};
</script>

<template>
    <GuestLayout>
        <Head :title="$t('public.reset_password')" />

        <div class="text-center mb-10">
            <div class="mb-2 text-xl text-white font-semibold">
                {{ $t('public.choose_password') }}
            </div>

            <div class="text-gray-300">
                {{ $t('public.password_criteria') }}
            </div>
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 gap-1.5">
                <Label for="password" class="gap-1.5" :invalid="form.errors.password">{{ $t('public.password') }}</Label>

                <Input
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    :placeholder="$t('public.new_password')"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <span class="text-gray-300 mb-3">{{ $t('public.password_rule') }}</span>

            <div class="mt-4">
                <Label for="password_confirmation" class="gap-1.5" :invalid="form.errors.password_confirmation">{{ $t('public.confirm_password') }}</Label>

                <Input
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    :placeholder="$t('public.confirm_password')"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>
        </form>

        <div class="flex items-center justify-end mt-10">
            <Button class="w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submit">
                {{ $t('public.reset_password') }}
            </Button>
        </div>
    </GuestLayout>
</template>
