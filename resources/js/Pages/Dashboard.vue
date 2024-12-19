<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

</script>

<template>
    <Head title="Dashboard"/>
    <div class="p-2 lg:p-4">
        <div class="flex flex-col items-center p-4">
            <div class="flex flex-col justify-center items-center">
                <ApplicationLogo alt="Logo" class="mb-8 h-40"/>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-center text-gray-700 dark:text-gray-200 mb-4">
                    DASHBOARD
                </h1>
                <p class="text-center text-gray-500 text-lg md:text-xl lg:text-2xl mb-8">
                    This is the dashboard page.
                </p>
            </div>
        </div>
    </div>
</template>
