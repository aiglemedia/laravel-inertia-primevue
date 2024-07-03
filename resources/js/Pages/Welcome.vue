<script setup lang="ts">
import {Head, Link} from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

defineOptions({
    layout: "",
});

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>

    <Head title="Welcome"/>

    <div class="flex flex-col h-screen justify-around items-center p-4">
        <div class="flex flex-col justify-center items-center">
            <ApplicationLogo alt="Logo" class="mb-8 h-40"/>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-center text-gray-700 mb-4">
                Laravel Inertiajs Primevue.
            </h1>
            <p class="text-center text-gray-500 text-lg md:text-xl lg:text-2xl mb-8">
                A starter template based on Laravel Inertiajs and Primevue!
            </p>
        </div>
        <div v-if="canLogin" class="flex w-full justify-center gap-2">
            <Link as="button" v-if="$page.props.auth.user" :href="route('dashboard')"
                  class="text-4xl font-semibold bg-gray-200 px-4 py-2 rounded-md text-gray-600 hover:text-gray-900">
                Admin
                Dashboard
            </Link>

            <template v-else>
                <Link as="button" :href="route('login')"
                      class="text-4xl font-semibold bg-gray-200 px-4 py-2 rounded-md text-gray-600 hover:text-gray-900">
                    Admin Login
                </Link>

                <Link as="button" v-if="canRegister" :href="route('register')"
                      class="text-4xl font-semibold bg-gray-200 px-4 py-2 rounded-md text-gray-600 hover:text-gray-900">
                    Register
                </Link>
            </template>
        </div>
        <div class="flex justify-center">
            <div class="text-sm text-gray-500">
                Copyrights - {{ new Date().getFullYear() }}
            </div>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
