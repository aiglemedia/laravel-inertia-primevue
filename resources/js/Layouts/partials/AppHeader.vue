<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import {route} from 'ziggy-js';
import DropdownUser from './DropdownUser.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

type SidebarProp = {
    isSidebarOpen: boolean;
};
const props = defineProps<SidebarProp>();
const emit = defineEmits<{
    updatesidebar: [value: boolean];
}>();
</script>

<template>
    <header
        class="sticky top-0 left-0 right-0 z-99 flex w-full bg-gray-50 dark:bg-gray-900 shadow-md dark:shadow-slate-700">
        <div class="flex flex-grow items-center justify-between py-4 px-4">
            <div class="flex items-center gap-2 lg:hidden">
                <Button text icon="pi pi-bars" @click="emit('updatesidebar', !props.isSidebarOpen)"/>
                <Link :href="route('home')">
                    <ApplicationLogo class="text-xl w-10 h-10"/>
                </Link>
            </div>
            <div class="grow"></div>
            <div class="flex items-center">
                <ThemeSwitcher/>
                <DropdownUser/>
            </div>
        </div>
    </header>
</template>
