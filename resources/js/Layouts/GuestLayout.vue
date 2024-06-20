<script setup>
import { GlobeAltIcon } from '@/Components/Icons/solid';
import { Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Button from '@/Components/Button.vue';
import Label from '@/Components/Label.vue';
import {Head, usePage} from '@inertiajs/vue3'
import ToastList from "@/Components/ToastList.vue";
import { ref, onMounted, computed } from "vue";
import {Inertia} from "@inertiajs/inertia";
import Alert from "@/Components/Alert.vue";
import {loadLanguageAsync} from "laravel-vue-i18n";

const props = defineProps({
    title: String,
});

const currentLocale = ref(usePage().props.locale);

const changeLanguage = async (langVal) => {
    try {
        currentLocale.value = langVal;
        await loadLanguageAsync(langVal);
        await axios.get(`/locale/${langVal}`);
    } catch (error) {
        console.error('Error changing locale:', error);
    }
};

const localeTextMap = {
    en: 'EN',
    cn: '简体中文',
    bm: 'Bahasa Melayu',
};

const currentLocaleText = computed(() => {
    return localeTextMap[currentLocale.value];
});

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

onMounted(() => {
    changeLanguage(currentLocale.value)
});

</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center" style="background: linear-gradient(180deg, rgba(0, 10, 255, 0.20) 0%, rgba(0, 0, 0, 0.00) 50%), #0C111D;">
        <div class="w-full sm:max-w-md">
            <div class="flex items-center gap-2 self-stretch w-full justify-end py-5 px-4">
                <Dropdown align="right">
                    <template #trigger>
                        <Button
                            iconOnly
                            variant="transparent"
                            type="button"
                            class="inline-flex"
                            srText="Change language"
                        >
                            <GlobeAltIcon
                                aria-hidden="true"
                                class="w-6 h-6"
                            />
                        </Button>
                    </template>
                    <template #content>
                        <DropdownLink @click="changeLanguage('en')" :class="{'bg-primary-900': currentLocale === 'en'}">
                            <div class="inline-flex items-center gap-2 text-white">
                                English
                            </div>
                        </DropdownLink>
                        <DropdownLink @click="changeLanguage('cn')" :class="{'bg-primary-900': currentLocale === 'cn'}">
                            <div class="inline-flex items-center gap-2 text-white">
                                简体中文
                            </div>
                        </DropdownLink>
                        <DropdownLink @click="changeLanguage('bm')" :class="{'bg-primary-900': currentLocale === 'bm'}">
                            <div class="inline-flex items-center gap-2 text-white">
                                Bahasa Melayu
                            </div>
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </div>

        <div
            class="w-full sm:max-w-md px-6 shadow-md overflow-hidden sm:rounded-lg"
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
        <!-- <div class="w-full sm:max-w-[360px] fixed bottom-0 bg-gray-900 z-[6]">
            <div class="w-full mx-auto h-6 px-[126px] justify-center items-center inline-flex">
                <div class="w-[108px] h-1 bg-white rounded-xl"></div>
            </div>
        </div> -->
    </div>
</template>
