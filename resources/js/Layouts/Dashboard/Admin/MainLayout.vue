<script setup>
import NavIcon from '@/Components/Admin/NavIcon.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const SIDEBAR_KEY = 'poema_admin_sidebar_collapsed';
const ADMIN_FAVICON_PATH = '/assets/brand/favicon.svg';

const page = usePage();

const user = computed(() => page.props.auth?.user ?? null);
const adminNotifications = computed(() => page.props.adminNotifications ?? { items: [], unread_count: 0 });
const notifications = computed(() => adminNotifications.value.items ?? []);
const unreadNotificationsCount = computed(() => adminNotifications.value.unread_count ?? 0);
const validationMessages = computed(() => {
    const errors = page.props.errors ?? {};
    const messages = [];

    for (const value of Object.values(errors)) {
        if (Array.isArray(value)) {
            for (const nested of value) {
                if (typeof nested === 'string' && nested.trim() !== '') {
                    messages.push(nested);
                }
            }
            continue;
        }

        if (typeof value === 'string' && value.trim() !== '') {
            messages.push(value);
        }
    }

    return [...new Set(messages)];
});

const sidebarCollapsed = ref(false);
const notificationsOpen = ref(false);
const notificationsRoot = ref(null);
const profileOpen = ref(false);
const profileRoot = ref(null);

/** @type {Record<string, boolean>} */
const groupOpen = ref({
    crm: false,
    packages: false,
    content: false,
    reservations: false,
    settings: false,
});

const navGroups = [
    {
        id: 'overview',
        label: 'Overview',
        collapsible: false,
        items: [
            { label: 'Dashboard', href: '/admin', icon: 'dashboard', match: 'dashboard' },
        ],
    },
    {
        id: 'crm',
        label: 'CRM',
        collapsible: true,
        items: [
            {
                label: 'Contacts',
                href: '/admin/crm-contacts',
                icon: 'users',
                placeholder: false,
                match: 'crm-contacts',
            },
            {
                label: 'Services',
                href: '/admin/crm-services',
                icon: 'tag',
                placeholder: false,
                match: 'crm-services',
            },
        ],
    },
    {
        id: 'packages',
        label: 'Packages',
        collapsible: true,
        items: [
            {
                label: 'Packages',
                href: '/admin/packages',
                icon: 'package',
                placeholder: false,
                match: 'packages',
            },
            {
                label: 'Destinations',
                href: '/admin/destinations',
                icon: 'map',
                placeholder: false,
                match: 'destinations',
            },
            {
                label: 'Package categories',
                href: '/admin/package-categories',
                icon: 'folder',
                placeholder: false,
                match: 'package-categories',
            },
            {
                label: 'Label groups',
                href: '/admin/package-label-groups',
                icon: 'tags',
                placeholder: false,
                match: 'package-label-groups',
            },
            {
                label: 'Package labels',
                href: '/admin/package-labels',
                icon: 'tag',
                placeholder: false,
                match: 'package-labels',
            },
            {
                label: 'Hotels',
                href: '/admin/hotels',
                icon: 'hotel',
                placeholder: false,
                match: 'hotels',
            },
            {
                label: 'Boats',
                href: '/admin/boats',
                icon: 'boat',
                placeholder: false,
                match: 'boats',
            },
            {
                label: 'Activities',
                href: '/admin/activities',
                icon: 'image',
                placeholder: false,
                match: 'activities',
            },
            {
                label: 'Package inclusions',
                href: '/admin/package-inclusions',
                icon: 'tag',
                placeholder: false,
                match: 'package-inclusions',
            },
        ],
    },
    {
        id: 'content',
        label: 'Content',
        collapsible: true,
        items: [
            {
                label: 'Pages content',
                href: '/admin/pages',
                icon: 'folder',
                placeholder: false,
                match: 'pages',
            },
            {
                label: 'Reels',
                href: '/admin/reels',
                icon: 'image',
                placeholder: false,
                match: 'reels',
            },
            {
                label: 'Blog categories',
                href: '/admin/blog-categories',
                icon: 'folder',
                placeholder: false,
                match: 'blog-categories',
            },
            {
                label: 'Blogs',
                href: '/admin/blogs',
                icon: 'image',
                placeholder: false,
                match: 'blogs',
            },
        ],
    },
    {
        id: 'reservations',
        label: 'Reservations',
        collapsible: true,
        items: [
            {
                label: 'Reservations',
                href: '/admin/reservations',
                icon: 'users',
                placeholder: false,
                match: 'reservations',
            },
            {
                label: 'Reservation questions',
                href: '/admin/reservation-questions',
                icon: 'tag',
                placeholder: false,
                match: 'reservation-questions',
            },
            {
                label: 'Customize requests',
                href: '/admin/customize-requests',
                icon: 'users',
                placeholder: false,
                match: 'customize-requests',
            }
        ],
    },
    {
        id: 'settings',
        label: 'Settings',
        collapsible: true,
        items: [
            { label: 'Languages', href: '/admin/languages', icon: 'globe', placeholder: false, match: 'languages' },
            { label: 'Currencies', href: '/admin/currencies', icon: 'currency', placeholder: false, match: 'currencies' },
            { label: 'Countries', href: '/admin/countries', icon: 'map', placeholder: false, match: 'countries' },
            { label: 'General', href: '/admin/settings/general', icon: 'cog', placeholder: false, match: 'settings-general' },
            { label: 'Contact', href: '/admin/settings/contact', icon: 'users', placeholder: false, match: 'settings-contact' },
            { label: 'Payment', href: '/admin/settings/payment', icon: 'currency', placeholder: false, match: 'settings-payment' },
            { label: 'SEO', href: '/admin/settings/seo', icon: 'tag', placeholder: false, match: 'settings-seo' },
            { label: 'Users', href: '#', icon: 'users', placeholder: true },
        ],
    },
];

function toggleGroup(id) {
    groupOpen.value[id] = !groupOpen.value[id];
}

function toggleSidebar() {
    sidebarCollapsed.value = !sidebarCollapsed.value;
}

function toggleProfile() {
    profileOpen.value = !profileOpen.value;
}

function toggleNotifications() {
    notificationsOpen.value = !notificationsOpen.value;
}

function markNotificationAsRead(notificationId) {
    router.post(`/admin/notifications/${notificationId}/read`, {}, {
        preserveScroll: true,
        preserveState: true,
        only: ['adminNotifications'],
    });
}

function markAllNotificationsAsRead() {
    router.post('/admin/notifications/read-all', {}, {
        preserveScroll: true,
        preserveState: true,
        only: ['adminNotifications'],
    });
}

function notificationTitle(notification) {
    const value = notification.data?.title ?? notification.data?.message ?? notification.notification_class;

    return String(value ?? 'Notification');
}

function notificationSummary(notification) {
    if (typeof notification.data?.message === 'string' && notification.data.message.trim() !== '') {
        return notification.data.message;
    }

    if (typeof notification.channel === 'string' && notification.channel.trim() !== '') {
        return `Channel: ${notification.channel}`;
    }

    return 'New system notification';
}

function onDocumentClick(event) {
    if (profileRoot.value && !profileRoot.value.contains(event.target)) {
        profileOpen.value = false;
    }
    if (notificationsRoot.value && !notificationsRoot.value.contains(event.target)) {
        notificationsOpen.value = false;
    }
}

function confirmLogout() {
    if (!window.confirm('Are you sure you want to log out?')) {
        return;
    }
    profileOpen.value = false;
    router.post('/admin/logout');
}

function applyAdminFavicon() {
    if (typeof document === 'undefined') {
        return;
    }

    let iconLink = document.querySelector('link[rel="icon"]');

    if (!iconLink) {
        iconLink = document.createElement('link');
        iconLink.setAttribute('rel', 'icon');
        document.head.appendChild(iconLink);
    }

    iconLink.setAttribute('type', 'image/svg+xml');
    iconLink.setAttribute('href', ADMIN_FAVICON_PATH);
}

onMounted(() => {
    try {
        sidebarCollapsed.value = window.localStorage.getItem(SIDEBAR_KEY) === '1';
    } catch {
        /* ignore */
    }
    applyAdminFavicon();
    document.addEventListener('click', onDocumentClick);
});

onUnmounted(() => {
    document.removeEventListener('click', onDocumentClick);
});

watch(sidebarCollapsed, (v) => {
    try {
        window.localStorage.setItem(SIDEBAR_KEY, v ? '1' : '0');
    } catch {
        /* ignore */
    }
});

function itemIsActive(item) {
    if (!item.match) {
        return false;
    }
    if (item.match === 'dashboard') {
        return page.url === '/admin' || page.url === '/admin/';
    }
    if (item.match === 'languages') {
        return page.url.startsWith('/admin/languages');
    }
    if (item.match === 'currencies') {
        return page.url.startsWith('/admin/currencies');
    }
    if (item.match === 'countries') {
        return page.url.startsWith('/admin/countries');
    }
    if (item.match === 'crm-contacts') {
        return page.url.startsWith('/admin/crm-contacts');
    }
    if (item.match === 'crm-services') {
        return page.url.startsWith('/admin/crm-services');
    }
    if (item.match === 'destinations') {
        return page.url.startsWith('/admin/destinations');
    }
    if (item.match === 'packages') {
        return page.url.startsWith('/admin/packages');
    }
    if (item.match === 'package-categories') {
        return page.url.startsWith('/admin/package-categories');
    }
    if (item.match === 'package-label-groups') {
        return page.url.startsWith('/admin/package-label-groups');
    }
    if (item.match === 'package-labels') {
        return page.url.startsWith('/admin/package-labels');
    }
    if (item.match === 'hotels') {
        return page.url.startsWith('/admin/hotels');
    }
    if (item.match === 'boats') {
        return page.url.startsWith('/admin/boats');
    }
    if (item.match === 'activities') {
        return page.url.startsWith('/admin/activities');
    }
    if (item.match === 'package-inclusions') {
        return page.url.startsWith('/admin/package-inclusions');
    }
    if (item.match === 'reels') {
        return page.url.startsWith('/admin/reels');
    }
    if (item.match === 'blog-categories') {
        return page.url.startsWith('/admin/blog-categories');
    }
    if (item.match === 'blogs') {
        return page.url.startsWith('/admin/blogs');
    }
    if (item.match === 'customize-requests') {
        return page.url.startsWith('/admin/customize-requests');
    }
    if (item.match === 'reservation-questions') {
        return page.url.startsWith('/admin/reservation-questions');
    }
    if (item.match === 'reservations') {
        return page.url.startsWith('/admin/reservations');
    }
    if (item.match === 'pages') {
        return page.url.startsWith('/admin/pages');
    }
    if (item.match === 'settings-general') {
        return page.url.startsWith('/admin/settings/general');
    }
    if (item.match === 'settings-contact') {
        return page.url.startsWith('/admin/settings/contact');
    }
    if (item.match === 'settings-payment') {
        return page.url.startsWith('/admin/settings/payment');
    }
    if (item.match === 'settings-seo') {
        return page.url.startsWith('/admin/settings/seo');
    }
    return false;
}
</script>

<template>
    <div class="flex h-screen min-h-0 overflow-hidden bg-slate-50 text-slate-900">
        <!-- Sidebar -->
        <aside
            class="flex h-full shrink-0 flex-col border-r border-slate-200 bg-white transition-[width] duration-200 ease-out"
            :class="sidebarCollapsed ? 'w-[4.25rem]' : 'w-60'"
            aria-label="Admin navigation"
        >
            <div
                class="flex h-14 shrink-0 items-center justify-between gap-2 border-b border-slate-200 px-3"
            >
                <span
                    class="truncate text-sm font-semibold tracking-tight text-slate-800"
                    :class="{ 'sr-only': sidebarCollapsed }"
                >
                    Poema Admin
                </span>
                <button
                    type="button"
                    class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500"
                    :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                    :aria-expanded="!sidebarCollapsed"
                    aria-controls="admin-sidebar-nav"
                    @click="toggleSidebar"
                >
                    <svg
                        class="size-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true"
                    >
                        <path
                            v-if="!sidebarCollapsed"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"
                        />
                        <path
                            v-else
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M13 5l7 7-7 7M5 5l7 7-7 7"
                        />
                    </svg>
                </button>
            </div>

            <nav
                id="admin-sidebar-nav"
                class="min-h-0 flex-1 overflow-y-auto overscroll-contain px-2 py-3"
            >
                <ul class="list-none space-y-4 p-0">
                    <li v-for="group in navGroups" :key="group.id" class="m-0">
                        <button
                            v-if="group.collapsible"
                            type="button"
                            class="mb-1 flex w-full items-center gap-2 rounded-lg px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                            :class="{ 'justify-center px-0': sidebarCollapsed }"
                            :title="sidebarCollapsed ? group.label : undefined"
                            @click="!sidebarCollapsed && toggleGroup(group.id)"
                        >
                            <span
                                v-if="group.id === 'packages'"
                                class="inline-flex size-5 shrink-0 text-slate-400"
                                aria-hidden="true"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                    />
                                </svg>
                            </span>
                            <span
                                v-else
                                class="inline-flex size-5 shrink-0 text-slate-400"
                                aria-hidden="true"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </span>
                            <span v-show="!sidebarCollapsed" class="min-w-0 flex-1 truncate">{{ group.label }}</span>
                            <svg
                                v-show="!sidebarCollapsed && groupOpen[group.id]"
                                class="size-4 shrink-0 text-slate-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                            </svg>
                            <svg
                                v-show="!sidebarCollapsed && !groupOpen[group.id]"
                                class="size-4 shrink-0 text-slate-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <p
                            v-else
                            class="mb-2 px-2 text-xs font-semibold uppercase tracking-wider text-slate-400"
                            :class="{ 'sr-only': sidebarCollapsed }"
                        >
                            {{ group.label }}
                        </p>

                        <ul
                            v-show="sidebarCollapsed || !group.collapsible || groupOpen[group.id]"
                            class="m-0 list-none space-y-0.5 p-0"
                            :class="{ 'mt-1': !group.collapsible }"
                        >
                            <li v-for="item in group.items" :key="item.label">
                                <Link
                                    v-if="!item.placeholder"
                                    :href="item.href"
                                    class="flex items-center gap-3 rounded-lg px-2 py-2 text-sm font-medium transition"
                                    :class="[
                                        itemIsActive(item)
                                            ? 'bg-sky-50 text-sky-800 ring-1 ring-inset ring-sky-200'
                                            : 'text-slate-700 hover:bg-slate-100',
                                        sidebarCollapsed ? 'justify-center' : '',
                                    ]"
                                    :title="sidebarCollapsed ? item.label : undefined"
                                >
                                    <NavIcon :name="item.icon || 'dashboard'" />
                                    <span v-show="!sidebarCollapsed" class="truncate">{{ item.label }}</span>
                                </Link>
                                <a
                                    v-else
                                    href="#"
                                    class="flex cursor-not-allowed items-center gap-3 rounded-lg px-2 py-2 text-sm font-medium text-slate-400 opacity-80"
                                    :class="sidebarCollapsed ? 'justify-center' : ''"
                                    :title="sidebarCollapsed ? `${item.label} (soon)` : undefined"
                                    @click.prevent
                                >
                                    <span class="inline-flex size-5 shrink-0" aria-hidden="true">
                                        <svg
                                            v-if="item.icon === 'package'"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                            />
                                        </svg>
                                        <svg
                                            v-else-if="item.icon === 'map'"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                                            />
                                        </svg>
                                        <svg
                                            v-else-if="item.icon === 'image'"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                        <svg
                                            v-else-if="item.icon === 'cog'"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                            />
                                        </svg>
                                    </span>
                                    <span v-show="!sidebarCollapsed" class="truncate">{{ item.label }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div
                class="shrink-0 border-t border-slate-200 bg-slate-50/90 px-2 py-3 text-center text-[11px] font-medium text-slate-500"
            >
                <span v-if="!sidebarCollapsed">Powered by <span class="text-slate-700">iNote</span></span>
                <span v-else class="block leading-tight text-[10px] text-slate-500" title="Powered by iNote">iNote</span>
            </div>
        </aside>

        <!-- Main column -->
        <div class="flex min-h-0 min-w-0 flex-1 flex-col">
            <header class="flex h-14 shrink-0 items-center justify-end border-b border-slate-200 bg-white px-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div ref="notificationsRoot" class="relative">
                        <button
                            type="button"
                            class="relative inline-flex size-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-slate-300 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500"
                            :aria-expanded="notificationsOpen"
                            aria-haspopup="menu"
                            aria-label="Notifications menu"
                            @click.stop="toggleNotifications"
                        >
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                />
                            </svg>
                            <span
                                v-if="unreadNotificationsCount > 0"
                                class="absolute -right-0.5 -top-0.5 inline-flex min-w-[1.2rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold leading-4 text-white"
                            >
                                {{ unreadNotificationsCount > 99 ? '99+' : unreadNotificationsCount }}
                            </span>
                        </button>

                        <div
                            v-show="notificationsOpen"
                            class="absolute right-0 z-50 mt-2 w-[26rem] origin-top-right rounded-xl border border-slate-200 bg-white py-1 shadow-lg ring-1 ring-black/5"
                            role="menu"
                            aria-orientation="vertical"
                            @click.stop
                        >
                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Notifications</p>
                                    <p class="text-xs text-slate-500">{{ unreadNotificationsCount }} unread</p>
                                </div>
                                <button
                                    type="button"
                                    class="text-xs font-medium text-sky-700 hover:text-sky-900 disabled:opacity-40"
                                    :disabled="unreadNotificationsCount === 0"
                                    @click="markAllNotificationsAsRead"
                                >
                                    Mark all read
                                </button>
                            </div>

                            <div class="max-h-96 overflow-y-auto">
                                <ul v-if="notifications.length" class="divide-y divide-slate-100">
                                    <li
                                        v-for="notification in notifications"
                                        :key="notification.id"
                                        class="px-4 py-3 transition hover:bg-slate-50"
                                        :class="notification.is_read ? 'bg-white' : 'bg-sky-50/40'"
                                    >
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-slate-900">
                                                    {{ notificationTitle(notification) }}
                                                </p>
                                                <p class="mt-1 line-clamp-2 text-xs text-slate-600">
                                                    {{ notificationSummary(notification) }}
                                                </p>
                                                <p class="mt-1 text-[11px] text-slate-500">
                                                    {{ notification.created_at ?? '—' }}
                                                </p>
                                            </div>

                                            <button
                                                v-if="!notification.is_read"
                                                type="button"
                                                class="shrink-0 text-xs font-medium text-sky-700 hover:text-sky-900"
                                                @click="markNotificationAsRead(notification.id)"
                                            >
                                                Mark read
                                            </button>
                                            <span v-else class="shrink-0 text-xs text-emerald-700">Read</span>
                                        </div>
                                    </li>
                                </ul>

                                <div v-else class="px-4 py-8 text-center text-sm text-slate-500">
                                    No notifications yet.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div ref="profileRoot" class="relative">
                        <button
                            type="button"
                            class="inline-flex size-10 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-slate-500 transition hover:border-slate-300 hover:bg-slate-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500"
                            :aria-expanded="profileOpen"
                            aria-haspopup="menu"
                            aria-label="Account menu"
                            @click.stop="toggleProfile"
                        >
                            <svg class="size-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"
                                />
                            </svg>
                        </button>

                        <div
                            v-show="profileOpen"
                            class="absolute right-0 z-50 mt-2 w-64 origin-top-right rounded-xl border border-slate-200 bg-white py-1 shadow-lg ring-1 ring-black/5"
                            role="menu"
                            aria-orientation="vertical"
                            @click.stop
                        >
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="truncate text-sm font-semibold text-slate-900">
                                    {{ user?.name }}
                                </p>
                                <p class="mt-0.5 truncate text-xs text-slate-500">
                                    {{ user?.email }}
                                </p>
                            </div>
                            <button
                                type="button"
                                class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm font-medium text-red-600 transition hover:bg-red-50"
                                role="menuitem"
                                @click="confirmLogout"
                            >
                                <svg
                                    class="size-4 shrink-0"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    />
                                </svg>
                                Log out
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="min-h-0 flex-1 overflow-y-auto overscroll-contain p-6">
                <div
                    v-if="validationMessages.length"
                    class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
                    role="alert"
                >
                    <p class="mb-2 font-semibold">Please fix the following:</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li v-for="(message, index) in validationMessages" :key="`validation-message-${index}`">
                            {{ message }}
                        </li>
                    </ul>
                </div>
                <slot />
            </main>
        </div>
    </div>
</template>
