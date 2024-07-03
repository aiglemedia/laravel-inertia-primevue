export interface MenuItem {
    title: string;
    icon?: string;
    path: string;
    external?: boolean;
    badge?: string;
    children?: MenuItem[];
}

export const NavigationLinks: MenuItem[] = [
    {
        title: 'Dashboard',
        icon: 'pi pi-home',
        path: route('dashboard'),
    },
    {
        title: 'Submenu Example',
        icon: 'pi pi-circle-fill',
        path: 'sub',
        children: [
            {
                title: 'Sub 1',
                icon: 'pi pi-bars',
                path: route('sub.subone'),
            },
            {
                title: 'Sub 2',
                icon: 'pi pi-bars',
                path: route('sub.subtwo'),
            },
        ],
    },
];
