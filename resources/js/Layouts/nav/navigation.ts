export interface MenuItem {
    title: string;
    icon?: string;
    path: string;
    external?: boolean;
    badge?: string;
    roles?: string[];
    children?: MenuItem[];
}

export const NavigationLinks: MenuItem[] = [
    {
        title: 'Dashboard',
        icon: 'dashboard',
        path: route('dashboard'),
    },
    {
        title: 'Submenu Example',
        icon: 'dataset_linked',
        path: 'sub',
        children: [
            {
                title: 'Sub 1',
                icon: 'link',
                path: route('sub.pageone'),
            },
            {
                title: 'Sub 2',
                icon: 'link',
                path: route('sub.pagetwo'),
            },
        ],
    },
];
