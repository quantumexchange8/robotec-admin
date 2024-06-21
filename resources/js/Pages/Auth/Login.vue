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
        <Head :title="$t('public.login')" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <div class="flex flex-col items-center px-4 gap-10">
            <div class="flex flex-col items-center self-stretch gap-3">
                <Link href="/">
                    <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
                </Link>
                <div class="flex flex-col items-center self-stretch gap-2 text-center">
                    <div class="text-center text-white text-lg font-semibold">{{ $t('public.admin_portal') }}</div>
                    <div class="text-gray-300 text-sm">{{ $t('public.welcome_back') }}</div>
                </div>
            </div>

            <form @submit.prevent="submit" class="w-full">
                <div class="flex flex-col items-center self-stretch gap-3">
                    <div class="flex flex-col items-start self-stretch gap-1.5">
                        <Label for="email" :invalid="form.errors.email">{{ $t('public.email') }}</Label>

                        <Input
                            id="email"
                            type="email"
                            class="block w-full bg-transparent text-white"
                            :invalid="form.errors.email"
                            v-model="form.email"
                            autofocus
                            autocomplete="email"
                            :placeholder="$t('public.email_placeholder')"
                        />

                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="flex flex-col items-start self-stretch gap-1.5">
                        <Label for="password" :invalid="form.errors.password">{{ $t('public.password') }}</Label>

                        <Input
                            id="password"
                            v-model="form.password"
                            :is_password="true"
                            :invalid="form.errors.password"
                            :placeholderText="$t('public.password_placeholder')"
                        />

                        <InputError :message="form.errors.password" />
                    </div>
                </div>
            </form>

            <div class="flex items-center justify-between self-stretch">
                <div class="flex items-center gap-2">
                    <Switch name="remember" v-model:checked="form.remember" />
                    <span class="text-sm text-white">{{ $t('public.remember_me') }}</span>
                </div>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-center text-white font-semibold text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ $t('public.forgot_password') }}
                </Link>
            </div>

            <Button variant="primary" size="lg" class="w-full text-sm font-semibold" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.prevent="submit">
                {{ $t('public.log_in') }}
            </Button>
        </div>
    </GuestLayout>
</template>
