<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Link} from '@inertiajs/vue3';
import {route} from 'ziggy-js';
</script>

<template>
    <div class="flex flex-col h-screen bg-surface-50 dark:bg-surface-900">
        <Toast/>
        <header class="sticky top-0 z-999 flex w-full bg-gray-50 dark:bg-gray-950 shadow-md">
            <div class="flex flex-grow items-center justify-between py-4 px-4">
                <div class="flex items-center gap-2">
                    <Link :href="route('home')">
                        <ApplicationLogo class="h-8"/>
                    </Link>
                </div>
                <div class="grow"></div>
                <div class="flex items-center text-xs font-thin">
                    <ThemeSwitcher/>
                </div>
            </div>
        </header>
        <main class="flex justify-center items-center grow">
            <slot/>
        </main>
        <footer class="flex justify-center gap-2 py-1 text-sm text-gray-500">
            <span>Copyrights - {{ new Date().getFullYear() }}</span>
            <span>Made With <span class="text-red-600">♥</span> aasiph.</span>
        </footer>
    </div>
</template>
