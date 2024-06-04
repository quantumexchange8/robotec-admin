<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar/Sidebar.vue'
import Navbar from '@/Components/Navbar.vue'
import { sidebarState } from '@/Composables'
import ToastList from "@/Components/ToastList.vue";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Alert from "@/Components/Alert.vue";

defineProps({
    title: String
})
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

    <Head :title="title"></Head>
    <div class="min-h-screen flex justify-center bg-gray-900">
        <Sidebar />

        <div class="flex justify-center w-full sm:max-w-[360px]">
            <div class="w-full" style="transition-property: margin; transition-duration: 150ms">
                <!-- Navbar -->
                <nav class="bg-gray-800 w-full">
                    <div class="flex justify-between">
                        <Navbar />
                    </div>
                </nav>

                <div class="px-4 py-3">
                    <!-- Page Heading -->
                    <header v-if="$slots.header">
                        <div class="font-semibold">
                            <slot name="header" />
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main class="flex-1">
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
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>
