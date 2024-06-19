<script setup>
import {computed, onMounted, onUnmounted, ref} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import { useFullscreen } from '@vueuse/core'
import {
    handleScroll,
    scrolling,
    sidebarState,
} from '@/Composables'
import Button from '@/Components/Button.vue'
import Logo from '@/Components/Logo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import { GlobeAltIcon, XMarkIcon, Bars3BottomLeftIcon } from '@/Components/Icons/solid';
import {loadLanguageAsync, trans} from "laravel-vue-i18n";

onMounted(() => {
    document.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
    document.removeEventListener('scroll', handleScroll)
})

const currentLocale = ref(usePage().props.locale);
const localeTextMap = {
    en: 'EN',
    cn: '简体中文',
    bm: 'Bahasa Melayu',
};

const currentLocaleText = computed(() => {
    return localeTextMap[currentLocale.value];
});

const changeLanguage = async (langVal) => {
    try {
        currentLocale.value = langVal;
        await loadLanguageAsync(langVal);
        await axios.get(`/locale/${langVal}`);
    } catch (error) {
        console.error('Error changing locale:', error);
    }
};
</script>

<template>
    <div
        class="relative flex items-center justify-between px-4 py-4 h-[72px] w-full shadow"
    >
        <Button
            iconOnly
            variant="transparent"
            type="button"
            @click="sidebarState.isOpen = !sidebarState.isOpen"
            v-slot="{ iconSizeClasses }"
            class="flex items-center justify-center"
            srText="Search"
            size="sm"
        >
            <Bars3BottomLeftIcon
                v-show="!sidebarState.isOpen"
                aria-hidden="true"
                :class="iconSizeClasses"
            />
            <XMarkIcon
                v-show="sidebarState.isOpen"
                aria-hidden="true"
                :class="iconSizeClasses"
            />
        </Button>

        <Link :href="route('dashboard.index')">
            <Logo />
            <span class="sr-only">home</span>
        </Link>

        <div class="flex items-center gap-2 self-stretch justify-center">
            <Dropdown align="right">
                <template #trigger>
                    <Button
                        iconOnly
                        variant="transparent"
                        type="button"
                        class="inline-flex"
                        size="sm"
                    >
                        <GlobeAltIcon
                            aria-hidden="true"
                            class="w-6 h-6"
                        />
                    </Button>
                </template>
                <template #content>
                    <DropdownLink @click="changeLanguage('en')">
                        <div class="text-white inline-flex items-center gap-2">
                            English
                        </div>
                    </DropdownLink>
                    <DropdownLink @click="changeLanguage('cn')">
                        <div class="inline-flex items-center gap-2">
                            简体中文
                        </div>
                    </DropdownLink>
                    <DropdownLink @click="changeLanguage('bm')">
                        <div class="inline-flex items-center gap-2">
                            Bahasa Melayu
                        </div>
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>

    </div>
</template>
