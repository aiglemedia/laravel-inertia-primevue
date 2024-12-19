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
        title: 'Instrument',
        icon: 'dataset_linked',
        path: 'instruments',
        children: [
            {
                title: 'List',
                icon: 'list',
                path: route('instruments.index'),
            },
            {
                title: 'New',
                icon: 'add',
                path: route('instruments.create'),
            },
        ],
    },
];
