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

        <div class="flex flex-col items-center gap-10 px-4">
            <div class="flex flex-col items-center gap-2 text-center mt-10 w-full">
                <div class="text-lg text-white font-semibold">
                    {{ $t('public.choose_password') }}
                </div>

                <div class="text-gray-300 text-sm">
                    {{ $t('public.password_criteria') }}
                </div>
            </div>

            <form @submit.prevent="submit" class="flex flex-col justify-center items-center self-stretch gap-3 w-full">
                <div class="w-full">
                    <Label for="password" class="font-medium" :invalid="form.errors.password">{{ $t('public.password') }}</Label>

                    <Input
                        id="password"
                        type="password"
                        class="my-1.5 block w-full bg-transparent text-white"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        :invalid="form.errors.password"
                        :placeholder="$t('public.new_password')"
                    />

                    <InputError class="mt-1.5" :message="form.errors.password" />
                </div>
                <span class="text-gray-300">{{ $t('public.password_rule') }}</span>

                <div class="w-full">
                    <Label for="password_confirmation" class="font-medium" :invalid="form.errors.password_confirmation">{{ $t('public.confirm_password') }}</Label>

                    <Input
                        id="password_confirmation"
                        type="password"
                        class="mt-1.5 block w-full bg-transparent text-white"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        :invalid="form.errors.password_confirmation"
                        :placeholder="$t('public.confirm_password')"
                    />

                    <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
                </div>
            </form>

            <Button class="w-full text-sm font-semibold" size="lg" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.prevent="submit">
                {{ $t('public.reset_password') }}
            </Button>
        </div>
    </GuestLayout>
</template>
