<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Button from '@/Components/Button.vue';
import { Upload01Icon, PhotoLibrary, PhotoIcon, FolderIcon } from '@/Components/Icons/outline';
import { ref } from 'vue';
import { usePage, useForm } from "@inertiajs/vue3";
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    profileImg: String,
})

const form = useForm({
    profile_photo: null,
});

const user = usePage().props.auth.user;
const profile_photo = usePage().props.auth.user.profile_photo;
const isDropdownOpen = ref(false);

// Function to toggle the dropdown menu
function toggleDropdown() {
    isDropdownOpen.value = !isDropdownOpen.value;
}

// Function to handle uploading profile photo from Photo Library
function uploadFromPhotoLibrary() {
    document.getElementById('fileInputLibrary').click();
}

// Function to handle uploading profile photo from Camera
function uploadPhotoFromCamera() {
    document.getElementById('fileInputCamera').click();
}

// Function to handle uploading profile photo from File
function uploadFromFile() {
    document.getElementById('fileInputFile').click();
}

// Function to handle the selected file from the input element
function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file) {
        form.profile_photo = file;
        form.post(route('profile.upload_profile_photo'));
    }
}
</script>

<template>
    <Head :title="$t('public.profile')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex-1 text-white text-xl mb-3">{{ $t('public.my_profile') }}</div>
        </template>

        <div class="flex flex-col items-center gap-3">
            <div class="flex py-8 px-4 flex-col items-center gap-8 self-stretch rounded-2xl bg-gray-800">
                <div class="flex flex-col items-center gap-3">
                    <img :src="props.profileImg || '/data/profile_photo.svg' " class="w-14 h-14 rounded-full" alt="profile_picture" />
                    <Dropdown :contentClasses="'p-2 bg-[#252525]'">
                        <template #trigger>
                            <Button
                                type="button"
                                size="lg"
                                class="font-semibold flex gap-2 self-stretch"
                                @click.prevent="toggleDropdown"
                            >
                                <Upload01Icon />
                                {{ $t('public.upload_profile_photo') }}
                            </Button>
                        </template>
                        <template #content>
                            <!-- Hidden file input elements -->
                            <input id="fileInputLibrary" type="file" accept="image/*" style="display: none" @change="handleFileUpload">
                            <input id="fileInputCamera" type="file" accept="image/*" capture="environment" style="display: none" @change="handleFileUpload">
                            <input id="fileInputFile" type="file" accept="image/*" style="display: none" @change="handleFileUpload">

                            <DropdownLink @click.prevent="uploadFromPhotoLibrary">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        {{ $t('public.photo_library') }}
                                    </div>
                                    <PhotoLibrary />
                                </div>
                            </DropdownLink>
                            <DropdownLink @click.prevent="uploadPhotoFromCamera">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        {{ $t('public.take_photo') }}
                                    </div>
                                    <PhotoIcon />
                                </div>
                            </DropdownLink>
                            <DropdownLink @click.prevent="uploadFromFile">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        {{ $t('public.choose_file') }}
                                    </div>
                                    <FolderIcon />
                                </div>
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>

                <div class="flex flex-col items-start gap-5 self-stretch">
                    <div class="flex flex-col items-start gap-1 self-stretch">
                        <div class="text-neutral-500 text-sm font-medium">{{ $t('public.name') }}</div>
                        <div class="text-gray-100 font-semibold">{{ user.name }}</div>
                    </div>
                    <div class="flex flex-col items-start gap-1 self-stretch">
                        <div class="text-neutral-500 text-sm font-medium">{{ $t('public.email') }}</div>
                        <div class="text-gray-100 font-semibold">{{ user.email }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
