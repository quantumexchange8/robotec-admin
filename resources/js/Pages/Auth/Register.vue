<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ProgressBar from '@/Pages/Auth/Partials/ProgressBar.vue'
import { computed, ref } from 'vue';
import { ArrowNarrowLeftIcon } from '@/Components/Icons/outline';
import Combobox from "@/Components/Combobox.vue";

const props = defineProps({
    referral_code: String,
});

const step = ref('0');
const dialCode = ref({value: '+60'});

const form = useForm({
    full_name: '',
    email: '',
    dial_code: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
    formStep: 0,
    referral_code: props.referral_code,
});

const firstStep = () => {
    form.post(route('register.first.step'), {
        onSuccess: () => {
            step.value = '1';
            form.formStep++;
        }
    });
}

const backToFirstStep = () => {
    step.value = '0';
    form.formStep--;
}

const passwordRules = [
    { regex: /.{8,}/, message: 'At least 8 characters' },
    { regex: /[A-Z]+/, message: 'At least one uppercase letter' },
    { regex: /[a-z]+/, message: 'At least one lowercase letter' },
    { regex: /[0-9]+/, message: 'At least one number' },
    { regex: /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]+/, message: 'At least one special character' }
];

const passwordValidation = computed(() => {
    const errors = passwordRules
    .filter(rule => !rule.regex.test(form.password))
    .map(rule => rule.message);

    if (form.password !== form.password_confirmation) {
        errors.push('Passwords do not match');
    }

    return errors;
});

const validatePassword = () => {
    if (passwordValidation.value.length === 0) {
        return true;
    }
};

const submitForm = () => {
    form.dial_code = dialCode.value.value
    form.post(route('register.store', props.referral_code), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

function loadDialCodes(query, setOptions) {
    fetch('/getDialCodes?query=' + query)
        .then(response => response.json())
        .then(results => {
            setOptions(
                results.map(country => {
                    return {
                        value: country.phone_code,
                        label: country.name,
                    }
                })
            )
        });
}
</script>

<template>
    <GuestLayout>

        <Head title="Register" />

        <div class="px-4 pb-10 flex flex-col items-center gap-3 self-stretch">
            <Link href="/">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
            </Link>
            <div class="flex flex-col items-center gap-2 self-stretch">
                <div class="text-white text-center text-lg font-semibold">
                    {{ $t('public.create_your_account') }}
                </div>
                <div class="self-stretch text-gray-300 text-center text-sm">
                    {{ $t('public.register_desc') }}
                </div>
            </div>
        </div>

        <ProgressBar :step="validatePassword() && step === '1' ? '2' : step" />

        <form
            @submit.prevent="submitForm"
            class="px-4 pt-3 flex flex-col items-center gap-10 self-stretch"
        >
<!-- page 1 (step 0) -->
            <div class="flex flex-col items-center gap-3 self-stretch">
                <div
                    v-show="form.formStep === 0"
                    class="flex flex-col items-start gap-1.5 self-stretch"
                >
                    <Label for="fullName" :value="$t('public.full_name')" :invalid="form.errors.full_name" />
                    <Input
                        v-model="form.full_name"
                        id="fullName"
                        type="text"
                        class="block w-full"
                        :invalid="form.errors.full_name"
                        :placeholder="$t('public.full_name_placeholder')"
                    />
                    <InputError :message="form.errors.full_name" />
                </div>

                <div
                    v-show="form.formStep === 0"
                    class="flex flex-col items-start gap-1.5 self-stretch"
                >
                    <Label for="email" :value="$t('public.email')" :invalid="form.errors.email" />
                    <Input
                        v-model="form.email"
                        id="email"
                        type="text"
                        class="block w-full"
                        :invalid="form.errors.email"
                        :placeholder="$t('public.enter_email')"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div
                    v-show="form.formStep === 0"
                    class="flex flex-col items-start gap-1.5 self-stretch"
                >
                    <Label for="phoneNumber" :value="$t('public.phone_number')" :invalid="form.errors.phone_number" />
                    <div class="flex gap-1.5 items-center self-stretch">
                        <Combobox
                            :load-options="loadDialCodes"
                            id="dial_code"
                            class="block w-1/3"
                            :invalid="form.errors.phone_number"
                            v-model="dialCode"
                            isPhoneCode
                        />

                        <Input
                            v-model="form.phone_number"
                            id="phoneNumber"
                            type="text"
                            class="block w-full"
                            :invalid="form.errors.phone_number"
                            :placeholder="$t('public.phone_number')"
                        />
                    </div>
                    <InputError :message="form.errors.phone_number" />
                </div>
<!-- page 2 (step 1) -->
                <div
                    v-show="form.formStep === 1"
                    class="flex flex-col items-start gap-1.5 self-stretch"
                >
                    <Label for="password" :value="$t('public.create_password')" :invalid="form.errors.password" />
                    <Input
                        v-model="form.password"
                        id="password"
                        :is_password="true"
                        :invalid="form.errors.password"
                        :placeholderText="$t('public.new_password')"
                    />
                    <InputError :message="form.errors.password" />
                    <div class="self-stretch text-gray-300 text-xs">
                        {{ $t('public.password_condition') }}
                    </div>
                </div>

                <div
                    v-show="form.formStep === 1"
                    class="flex flex-col items-start gap-1.5 self-stretch"
                >
                    <Label for="confirmPassword" :value="$t('public.confirm_password')" :invalid="form.errors.password_confirmation" />
                    <Input
                        v-model="form.password_confirmation"
                        id="confirmPassword"
                        :is_password="true"
                        :invalid="form.errors.password_confirmation"
                        :placeholderText="$t('public.confirm_password_placeholder')"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>
            </div>
<!-- based on step(0 / 1), display next block/ register block -->
            <div class="flex flex-col justify-center items-center gap-3 self-stretch">
                <template v-if="form.formStep === 0">
                    <Button
                        size="lg"
                        type="button"
                        class="w-full font-semibold"
                        :disabled="form.processing"
                        @click.prevent="firstStep"
                    >
                        {{ $t('public.next') }}
                    </Button>
                    <div class="flex justify-center items-center gap-3 self-stretch">
                        <div class="text-gray-300 text-center text-sm">
                            {{ $t('public.already_have_account') }}
                        </div>
                        <Link
                            :href="route('login')"
                            class="text-white text-center text-sm font-semibold"
                        >
                            {{ $t('public.log_in_here') }}
                        </Link>
                    </div>
                </template>

                <template v-else>
                    <div class="self-stretch text-gray-300 text-xxs">
                        {{ $t('public.register_agree_desc') }}
                    </div>
                    <Button
                        size="lg"
                        class="w-full font-semibold"
                        :disabled="form.processing"
                    >
                        {{ $t('public.register') }}
                    </Button>
                    <Button
                        variant="transparent"
                        type="button"
                        size="lg"
                        class="w-full font-semibold flex gap-2"
                        :disabled="form.processing"
                        v-slot="{ iconSizeClasses }"
                        @click.prevent="backToFirstStep"
                    >
                        <ArrowNarrowLeftIcon />
                        {{ $t('public.go_back') }}
                    </Button>
                </template>
            </div>
        </form>
    </GuestLayout>
</template>
