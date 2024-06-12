<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { PlusCircleIcon } from '@/Components/Icons/outline'
import Label from '@/Components/Label.vue';
import Input from '@/Components/Input.vue';
import InputError from '@/Components/InputError.vue';
import Button from "@/Components/Button.vue";
import Modal from "@/Components/Modal.vue";
import toast from "@/Composables/toast.js";
import Combobox from "@/Components/Combobox.vue";
import CountryLists from '/public/data/countries.json'

// Find the index of Malaysia in the country list
const malaysiaIndex = CountryLists.findIndex(country => country.label === "Malaysia");

// Set the default dial code to +60 for Malaysia
const defaultDialCode = malaysiaIndex !== -1 ? CountryLists[malaysiaIndex] : null;

// Initialize the form with default values
const form = useForm({
    name: '',
    email: '',
    dial_code: defaultDialCode, // Set default dial code to +60 for Malaysia
    phone: '',
    upline: null,
});
    
const addNewClientModal = ref(false);
const isFormValid = ref(false);

const openModal = () => {
    addNewClientModal.value = true;
};

const closeModal = () => {
    addNewClientModal.value = false;
};

// Watch for changes in the form fields
watch(() => form.data().upline, () => {
    // Check if all form fields are filled
    isFormValid.value = Object.values(form.data()).every(value => value !== '');
});

function loadUpline(query, setOptions) {
    fetch('/member/getAllClients?query=' + query)
        .then(response => response.json())
        .then(results => {
            setOptions(
                results.map(user => {
                    return {
                        value: user.id,
                        label: user.name,
                        img: user.profile_photo
                    }
                })
            )
        });
}

const addClient = () => {
    form.post(route('member.addClient'),{
        onSuccess: () => {
            closeModal();
            window.location.reload();
        },
        onError: (errors) => {
            // Handle any errors
            console.error(errors);
        }
    });
};
</script>
<template>
    <div>
      <!-- Button to open the modal -->
        <Button variant="primary" class="px-3 py-2 justify-end" @click="openModal">
          <PlusCircleIcon class="w-5 h-5 mr-2 relative" /> {{ $t('public.new_client') }}
        </Button>
      
      <!-- Modal for adding a new client -->
      <Modal :show="addNewClientModal" :title="$t('public.add_new_client')" @close="closeModal" max-width="sm">
        <form class="my-5 px-1">
          <div class="mb-5">
            <Label for="name" :value="$t('public.client_name')" class="mb-1.5" :invalid="form.errors.name" />
  
            <Input
              id="name"
              class="block w-full bg-transparent text-white"
              :invalid="form.errors.name"
              :placeholder="$t('public.client_name_placeholder')"
              v-model="form.name"
              required
            />
  
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
  
          <div class="mb-5">
            <Label for="email" :value="$t('public.email')" class="mb-1.5" :invalid="form.errors.email" />
  
            <Input
              id="email"
              type="email"
              class="block w-full bg-transparent text-white"
              :invalid="form.errors.email"
              :placeholder="$t('public.email_placeholder_1')"
              v-model="form.email"
              required
            />
  
            <InputError class="mt-2" :message="form.errors.email" />
          </div>
          
          <div class="mb-5">
            <Label for="phone_number" :value="$t('public.phone_number')" class="mb-1.5" :invalid="form.errors.phone || form.errors.dial_code" />
                        
            <div class="grid grid-cols-5">
                <div class="col-span-2">
                    <div class="mr-1.5">
                        <Combobox 
                            :options="CountryLists"
                            id="dial_code"
                            class="block w-full"
                            :invalid="form.errors.phone"
                            v-model="form.dial_code"
                            required
                            isPhoneCode
                        />
                    </div>
                </div>

                <div class="col-span-3">
                    <Input
                        id="phone"
                        class="block w-full bg-transparent text-white"
                        :invalid="form.errors.phone"
                        :placeholder="$t('public.phone_number')"
                        v-model="form.phone"
                        required
                    />
                </div>
            </div>

            <InputError class="mt-2" :message="form.errors.phone" />
        </div>
            <div>
                <Label for="upline" :value="$t('public.assign_upline')" class="mb-1.5" :invalid="form.errors.upline" />

                <Combobox
                    :load-options="loadUpline"
                    v-model="form.upline"
                    :placeholder="$t('public.upline')"
                    image
                />

                <InputError class="mt-2" :message="form.errors.upline" />

            </div>

            <div class="w-full flex justify-end gap-3 pt-8">
                <Button variant="transparent" class="w-full border border-gray-600" @click="closeModal">{{ $t('public.cancel') }}</Button>
                <Button variant="primary" class="w-full" :disabled="!isFormValid || form.processing" @click="addClient">{{ $t('public.add') }}</Button>
            </div>
        </form>
      </Modal>
    </div>
  </template>
  
  