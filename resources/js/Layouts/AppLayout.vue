<script setup lang="ts">
import AppHeader from './partials/AppHeader.vue';
import AppSidebar from './partials/AppSidebar.vue';
import {onUpdated, ref} from 'vue';
import {useToast} from 'primevue/usetoast';
import {usePage} from '@inertiajs/vue3';
import {ConfirmContainerSlotProps} from '@/types';

const sideBarOpen = ref(false);

const toast = useToast();
const page = usePage();
onUpdated(() => {
    if (page.props.flash!.message) {
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: page.props.flash!.message,
            life: 7000,
        });
    }
    if (page.props.flash!.error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: page.props.flash!.error,
            life: 7000,
        });
    }
});
</script>

<template>
    <div class="flex h-screen">
        <AppSidebar :is-sidebar-open="sideBarOpen" @updatesidebar="(value: boolean) => (sideBarOpen = value)"/>
        <div class="relative flex flex-1 flex-col w-full overflow-auto">
            <AppHeader :is-sidebar-open="sideBarOpen" @updatesidebar="(value: boolean) => (sideBarOpen = value)"/>
            <main class="flex grow">
                <div class="w-full">
                    <slot/>
                </div>
                <Toast/>
                <DynamicDialog/>
                <ConfirmDialog group="headless" class="z-9999 max-w-[90%]">
                    <template #container="{ message, acceptCallback, rejectCallback }: ConfirmContainerSlotProps">
                        <div class="flex flex-col items-center p-5 bg-surface-0 dark:bg-surface-700 rounded-md">
                            <div
                                class="rounded-full bg-primary-500 text-surface-0 inline-flex justify-center items-center h-[6rem] w-[6rem] -mt-[4rem]">
                                <i class="pi pi-question text-5xl"></i>
                            </div>
                            <span class="font-bold text-2xl block mb-2 mt-4">{{ message.header }}</span>
                            <p class="mb-0">{{ message.message }}</p>
                            <div class="flex items-center gap-2 mt-4">
                                <Button label="Yes" @click="acceptCallback"></Button>
                                <Button label="No" outlined @click="rejectCallback"></Button>
                            </div>
                        </div>
                    </template>
                </ConfirmDialog>
            </main>
            <footer class="flex justify-center py-1">
                <div class="text-sm text-gray-500">Copyrights - {{ new Date().getFullYear() }}</div>
            </footer>
        </div>
    </div>
</template>
