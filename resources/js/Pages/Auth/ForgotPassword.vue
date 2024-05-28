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
        <Head title="Forgot Password" />

        <div class="text-center mb-10">
            <div class="mb-2 text-xl text-white font-semibold">
                Forgot password
            </div>

            <div class="text-sm text-gray-300">
                No worries, we'll send you reset instructions.
            </div>
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form>
            <div>
                <Label for="email" value="Email" class="text-gray-300" :invalid="form.errors.email" />

                <Input
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="Enter Email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>
        </form>

        <div class="grid grid-cols-6 gap-4 w-full mt-4">
                <div class="col-span-6">
                    <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full" @click="submit">
                        Send Email
                    </Button>
                </div>
                <div class="col-span-6">
                    <Button variant="transparent" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full" @click="goToLoginPage">
                        <ArrowLeftIcon class="w-5 h-5 mr-2"/>Back to Log In
                    </Button>
                </div>
            </div>

    </GuestLayout>
</template>
