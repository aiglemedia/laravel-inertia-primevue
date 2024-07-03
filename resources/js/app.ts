import './bootstrap';
import '../css/app.css';
import 'primeicons/primeicons.css';

import {createApp, h, DefineComponent} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import DialogService from 'primevue/dialogservice';
import Ripple from 'primevue/ripple';
import StyleClass from 'primevue/styleclass';
import FormError from '@/Components/FormError.vue';

import AppState from '@/plugins/appState';
import {definePreset} from '@primevue/themes';
// import Aura from '@primevue/themes/aura';
import Material from '@primevue/themes/material';
import AppLayout from '@/Layouts/AppLayout.vue';
import ThemeSwitcher from '@/Layouts/partials/ThemeSwitcher.vue';

const MyPreset = definePreset(Material, {});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel-Inertia-Primevue';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name: string) => {
        const page = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>("./Pages/**/*.vue")
        )
        page.default.layout ??= AppLayout
        return page
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .component('ThemeSwitcher', ThemeSwitcher)
            .component('FormError', FormError)
            .directive('ripple', Ripple)
            .directive('styleclass', StyleClass)
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: MyPreset,
                    options: {
                        cssLayer: {
                            name: 'primevue',
                            order: 'tailwind-base, primevue, tailwind-utilities',
                        },
                        darkModeSelector: '.dark',
                    },
                },
                ripple: true,
            })
            .use(AppState)
            .use(ToastService)
            .use(ConfirmationService)
            .use(DialogService)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
