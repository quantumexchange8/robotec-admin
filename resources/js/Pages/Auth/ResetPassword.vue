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
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="text-center mb-10">
            <div class="mb-2 text-xl text-white font-semibold">
                Choose a password
            </div>

            <div class="text-gray-300">
                Your password must fulfil the following criteria.
            </div>
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 gap-1.5">
                <Label for="password" value="Password" class="text-gray-300 gap-1.5" :invalid="form.errors.password" />

                <Input
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="New Password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <span class="text-gray-300 mb-3">Must be at least 8 characters containing one uppercase letter, one lowercase letter, one number, and one special character.</span>

            <div class="mt-4">
                <Label for="password_confirmation" value="Confirm Password" class="text-gray-300 gap-1.5" :invalid="form.errors.password_confirmation" />

                <Input
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm Password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>
        </form>

        <div class="flex items-center justify-end mt-10">
            <Button class="w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submit">
                Reset Password
            </Button>
        </div>
    </GuestLayout>
</template>
