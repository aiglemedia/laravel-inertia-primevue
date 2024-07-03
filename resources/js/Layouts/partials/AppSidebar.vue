<script setup lang="ts">
import {h, ref, VNode, withDirectives} from 'vue';
import {onClickOutside} from '@vueuse/core';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {NavigationLinks, MenuItem} from '@/Layouts/nav/navigation';
import Ripple from 'primevue/ripple';
import StyleClass from 'primevue/styleclass';
import {Link} from '@inertiajs/vue3';
import Button from 'primevue/button';

type SidebarProp = {
    isSidebarOpen: boolean;
};
const props = defineProps<SidebarProp>();
const emit = defineEmits<{
    updatesidebar: [value: boolean];
}>();

const navlinks: MenuItem[] = NavigationLinks;

const target = ref(null);
onClickOutside(target, () => {
    emit('updatesidebar', false);
});

function toggleChevron(e: Event) {
    const el: HTMLElement = e.currentTarget as HTMLElement;
    el.children[2].classList.toggle('rotateChev');
}

function makeMenuItem(menu: MenuItem, path: string, level: number) {
    // const style = level > 0 ? { paddingLeft: `${16 + level * 20}px` } : {};
    // const menulevel = level > 0 ? 'submenu' : 'mainmenu';

    const menuitemClass =
        'flex gap-2 items-center cursor-pointer p-2 rounded-md dark:text-surface-300 text-surface-700 hover:bg-surface-200 dark:hover:bg-surface-800 duration-200 transition-colors';

    const elprops = {
        href: path,
        class: menuitemClass,
        // style,
    };

    if (menu.external === true) {
        Object.assign(elprops, {
            target: '_blank',
        });
    }

    const child: VNode[] = [];
    if (menu.icon) {
        child.push(h('span', {class: 'material-symbols-outlined zerofill'}, menu.icon));
    } else {
        child.push(h('span', {class: 'material-symbols-outlined zerofill'}, 'chevron_right'));
    }
    child.push(h('span', {class: 'font-medium', innerHTML: menu.title}));

    if (menu.children) {
        const itemlink = () =>
            withDirectives(
                h(
                    'a',
                    {
                        class: menuitemClass,
                        onClick: (e) => toggleChevron(e),
                    },
                    [
                        ...child,
                        h('span', {class: 'material-symbols-outlined zerofill ml-auto'}, 'keyboard_arrow_right'),
                    ]
                ),
                [
                    [Ripple],
                    [
                        StyleClass,
                        {
                            selector: '@next',
                            enterFromClass: 'hidden',
                            enterActiveClass: 'slidedown',
                            leaveToClass: 'hidden',
                            leaveActiveClass: 'slideup',
                        },
                    ],
                ]
            );
        return h('li', [h(itemlink), makeMenu(menu.children, level + 1)]);
    } else {
        const itemlink = h(Link, elprops, () => child);
        return h('li', {onClick: () => emit('updatesidebar', false)}, itemlink);
    }
}

function makeMenu(menu: MenuItem[], level: number): VNode {
    const ulClass =
        level == 0
            ? 'list-none p-0 m-0 overflow-hidden'
            : 'list-none py-0 pl-3 pr-0 m-0 hidden overflow-y-hidden transition-all duration-400 ease-in-out';
    return h(
        'ul',
        {class: ulClass},
        menu.map((item: MenuItem) => makeMenuItem(item, item.path, level))
    );
}

const toggleButtonNode = h(Button, {
    class: 'lg:invisible',
    icon: 'pi pi-arrow-left',
    text: true,
    onClick(event: any) {
        emit('updatesidebar', false);
    },
});
const appLogoNode = h(ApplicationLogo, {class: 'text-xl w-8 h-8'});
const homeRouteNode = h(Link, {href: route('home')}, () => appLogoNode);
const toggleMenuNode = h(
    'div',
    {
        class: 'flex items-center justify-between px-4 pt-3 pb-1 shrink-0',
    },
    [homeRouteNode, toggleButtonNode]
);
const menuWrapperNode = h(
    'div',
    {
        class: 'flex flex-col h-full',
    },
    [toggleMenuNode, makeMenu(navlinks, 0)]
);

const sbroot = () =>
    h(
        'aside',
        {
            ref: target,
            class: [
                'absolute border-r dark:border-r-slate-700 left-0 top-0 z-999 flex bg-slate-100 dark:bg-slate-800  h-screen w-[18rem] flex-col overflow-y-hidden duration-300 ease-linear lg:static lg:translate-x-0',
                {
                    'translate-x-0': props.isSidebarOpen,
                    '-translate-x-full': !props.isSidebarOpen,
                },
            ],
        },
        menuWrapperNode
    );
</script>
<style lang="postcss">
@keyframes slidedown {
    0% {
        max-height: 0;
    }
    100% {
        max-height: max-content;
    }
}

@keyframes slideup {
    0% {
        max-height: 1000px;
    }
    100% {
        max-height: 0;
    }
}

.rotateChev {
    transform: rotate(90deg);
}

.slidedown {
    animation: slidedown 0.2s ease-in-out;
}

.slideup {
    animation: slideup 0.2s cubic-bezier(0, 1, 0, 1);
}

.layout-ripple-disabled [data-pc-name='ripple'] {
    display: none !important;
}
</style>
<template>
    <sbroot/>
</template>
