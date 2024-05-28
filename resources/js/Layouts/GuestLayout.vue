<script setup>
import { GlobeAltIcon } from '@/Components/Icons/solid';
import { Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Button from '@/Components/Button.vue';
import Label from '@/Components/Label.vue';
import {Head, usePage} from '@inertiajs/vue3'
import ToastList from "@/Components/ToastList.vue";
import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import Alert from "@/Components/Alert.vue";
import {loadLanguageAsync} from "laravel-vue-i18n";

const props = defineProps({
    title: String,
});

const changeLanguage = async (langVal) => {
    try {
        await loadLanguageAsync(langVal);
        await axios.get(`/locale/${langVal}`);
    } catch (error) {
        console.error('Error changing locale:', error);
    }
};

const page = usePage();
const showAlert = ref(false);
const intent = ref(null);
const alertTitle = ref('');
const alertMessage = ref(null);
const alertButton = ref(null);

let removeFinishEventListener = Inertia.on("finish", () => {
    if (page.props.success) {
        showAlert.value = true
        intent.value = 'success'
        alertTitle.value = page.props.title
        alertMessage.value = page.props.success
        alertButton.value = page.props.alertButton
    } else if (page.props.warning) {
        showAlert.value = true
        intent.value = 'warning'
        alertTitle.value = page.props.title
        alertMessage.value = page.props.warning
        alertButton.value = page.props.alertButton
    }
});

</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background: linear-gradient(180deg, rgba(0, 10, 255, 0.20) 0%, rgba(0, 0, 0, 0.00) 50%), var(--gray-900, #0C111D);">
        <div class="flex items-center gap-2 self-stretch w-full justify-end pt-2 pr-2 sm:pr-8">
            <Dropdown align="right">
                <template #trigger>
                    <Button
                        iconOnly
                        variant="transparent"
                        type="button"
                        v-slot="{ iconSizeClasses }"
                        class="inline-flex"
                        srText="Toggle dark mode"
                    >
                        <GlobeAltIcon
                            aria-hidden="true"
                            :class="iconSizeClasses"
                        />
                    </Button>
                </template>
                <template #content>
                    <DropdownLink @click="changeLanguage('en')" class="bg-gray-900 hover:bg-primary-900 focus:bg-primary-900">
                        <div class="inline-flex items-center gap-2 text-white">
                            English
                        </div>
                    </DropdownLink>
                    <DropdownLink @click="changeLanguage('cn')" class="bg-gray-900 hover:bg-primary-900 focus:bg-primary-900">
                        <div class="inline-flex items-center gap-2 text-white">
                            中文
                        </div>
                    </DropdownLink>
                    <DropdownLink @click="changeLanguage('kr')" class="bg-gray-900 hover:bg-primary-900 focus:bg-primary-900">
                        <div class="inline-flex items-center gap-2 text-white">
                            한국어
                        </div>
                    </DropdownLink>
                    <DropdownLink @click="changeLanguage('vn')" class="bg-gray-900 hover:bg-primary-900 focus:bg-primary-900">
                        <div class="inline-flex items-center gap-2 text-white">
                            tiếng Việt
                        </div>
                    </DropdownLink>
                    <DropdownLink @click="changeLanguage('ja')" class="bg-gray-900 hover:bg-primary-900 focus:bg-primary-900">
                        <div class="inline-flex items-center gap-2 text-white">
                            日本語
                        </div>
                    </DropdownLink>
                    <DropdownLink @click="changeLanguage('th')" class="bg-gray-900 hover:bg-primary-900 focus:bg-primary-900">
                        <div class="inline-flex items-center gap-2 text-white">
                            ไทย
                        </div>
                    </DropdownLink>
                    <DropdownLink @click="changeLanguage('id')" class="bg-gray-900 hover:bg-primary-900 focus:bg-primary-900">
                        <div class="inline-flex items-center gap-2 text-white">
                            Bahasa Indonesia
                        </div>
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg"
        >
            <Alert
                :show="showAlert"
                :on-dismiss="() => showAlert = false"
                :title="alertTitle"
                :intent="intent"
                :alertButton="alertButton"
            >
                {{ alertMessage }}
            </Alert>
            <ToastList />
            <slot />
        </div>
    </div>
</template>
