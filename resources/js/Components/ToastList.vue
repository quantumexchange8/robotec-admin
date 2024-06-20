<script setup>
import ToastListItem from "@/Components/ToastListItem.vue";
import {onUnmounted, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/vue3";
import toast from "@/Composables/toast.js"

const page = usePage();

let removeFinishEventListener = Inertia.on("finish", () => {
    if (page.props.toast) {
        toast.add({
            title: page.props.toast.title,
            message: page.props.toast.message,
            type: page.props.toast.type,
        });
    }
});

onUnmounted(() => removeFinishEventListener());

function remove(index) {
    toast.remove(index);
}
</script>
<template>
    <TransitionGroup
        tag="div"
        enter-from-class="translate-x-full opacity-0"
        enter-active-class="duration-500"
        leave-active-class="duration-500"
        leave-to-class="translate-x-full opacity-0"
        class="fixed top-4 middle z-50 w-full sm:max-w-[360px] space-y-4">
        <ToastListItem
            v-for="(item, index) in toast.items"
            :key="item.key"
            :title="item.title"
            :message="item.message"
            :type="item.type"
            @remove="remove(index)"
        />
    </TransitionGroup>
</template>
