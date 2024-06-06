<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Switch from '@/Components/Switch.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <div class="flex flex-col items-center justify-center">
            <Link href="/">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
            </Link>
            <div class="text-center mb-10">
                <div class="mb-2 text-white text-xl font-semibold">Admin Portal</div>
                <div class="text-gray-300">Welcome back! Please enter your details.</div>
            </div>
        </div>

        <form @submit.prevent="submit">
            <div>
                <Label for="email" value="Email" class="text-gray-300" :invalid="form.errors.email" />

                <Input
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-transparent text-white"
                    :invalid="form.errors.email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <Label for="password" value="Password" class="text-gray-300" :invalid="form.errors.password" />

                <Input
                    id="password"
                    type="password"
                    class="mt-1 block w-full bg-transparent text-white"
                    v-model="form.password"
                    :invalid="form.errors.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-10">
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Switch name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-white">Remember me</span>
                    </label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Forgot Password
                    </Link>
                </div>
            </div>

            <div class="flex items-center justify-end mt-10">

                <Button variant="primary" class="w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
