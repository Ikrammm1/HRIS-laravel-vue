<template>
    <aside 
        :class="[
            'relative bg-gradient-to-b from-teal-50 to-gray-50 h-screen block shadow-xl transition-all duration-300 ease-in-out',
            isSidebarMinimized ? 'w-20' : 'w-60' // Menggunakan isSidebarMinimized lokal
        ]"
    >
        <div class="p-2 border-b border-gray-200">
            <router-link class="flex items-center " :class="isSidebarMinimized ? 'justify-center' : ''"to="/dashboard">
                <template v-if="!isSidebarMinimized">
                    <div class="flex items-center gap-3">
                        <div class="relative w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-yellow-400 rounded-full border-2 border-white"></div>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-xl font-bold text-gray-800 leading-none">
                                HR<span class="text-teal-600">Portal</span>
                            </h1>
                            <p class="text-[10px] text-gray-500 font-medium">Human Resource System</p>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="relative w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-yellow-400 rounded-full border-2 border-white"></div>
                    </div>
                </template>
            </router-link>
        </div>
        <div class="mt-4 relative flex-1 ps-3">
            <ul>
                <div v-for="header in mainMenu" :key="header.name" class="mt-2">

                    <!-- HEADER BUTTON -->
                    <li v-if="header.children.length > 0">
                        <button
                        @click.prevent="toggleMenu(header)"
                        type="button"
                        class="transition duration-200 group w-full flex items-center p-3 text-sm font-medium rounded-lg"
                        :class="isActive(header)
                            ? 'bg-teal-600 text-white'
                            : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700'"
                        >
                        <Icon
                            :name="header.icon"
                            class="w-5 h-5"
                            :class="isSidebarMinimized ? 'mx-auto' : 'mr-3'"
                        />

                        <template v-if="!isSidebarMinimized">
                            <span class="flex-1 text-left">{{ header.name }}</span>
                            <Icon
                                :name="expandedHeader === header.name ? 'angle-up' : 'angle-down'"
                                class="w-4 h-4"
                            />

                        </template>
                        </button>

                        <ul
                            v-if="!isSidebarMinimized"
                            class="py-2 space-y-1 ml-2"
                            :class="expandedHeader === header.name ? '' : 'hidden'"
                        >

                            <li v-for="parent in header.children" :key="parent.name">

                                <button
                                    v-if="parent.children.length > 0"
                                    @click.prevent="toggleSubmenu(parent)"
                                    class="transition duration-200 group w-full flex items-center p-2 pl-2 text-sm font-medium rounded-lg"
                                    :class="isActive(parent)
                                        ? 'bg-teal-600 text-white'
                                        : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700'"
                                    >
                                    <Icon :name="parent.icon" class="w-4 h-4 mr-2" />
                                    <span class="flex-1 text-left">{{ parent.name }}</span>
                                    <Icon
                                        :name="expandedParent === parent.name ? 'angle-up' : 'angle-down'"
                                        class="w-3 h-3"
                                    />
                                    
                                </button>
                                <router-link 
                                    v-else
                                        :to="parent.to ? parent.to : '#'" 
                                        class="flex items-center p-2 text-sm font-medium rounded-lg transition-colors group"
                                        :class="[
                                            'relative rounded-lg transition-all overflow-hidden',
                                            isActive(parent) 
                                                ? 'bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full' 
                                                : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100'
                                        ]"
                                    >
                                        <Icon :name="parent.icon" class="w-4 h-4 mr-2" />
                                        <span class="flex-1 text-left">{{ parent.name }}</span>
                                </router-link>


                                <ul
                                    v-if="parent.children.length > 0"
                                    class="ml-4 mt-1 space-y-1"
                                    :class="expandedParent === parent.name ? '' : 'hidden'"
                                >

                                    <li v-for="child in parent.children" :key="child.name">
                                        <router-link
                                        :to="child.to"
                                        class="flex items-center p-2 text-sm font-medium rounded-lg transition-colors group"
                                        :class="[
                                            'relative rounded-lg transition-all overflow-hidden',
                                            isActive(child) 
                                                ? 'bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full' 
                                                : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100'
                                        ]"
                                        >
                                        <Icon :name="child.icon" class="w-4 h-4 mr-2" />
                                        {{ child.name }}
                                        </router-link>
                                    </li>
                                </ul>

                            </li>
                        </ul>
                    </li>
                    <!-- MENU TANPA CHILDREN -->
                    <li v-else>
                        <a 
                            v-if="header.hasOwnProperty('onClick') && !header.to" 
                            href="#"
                            class="flex items-center p-3 text-sm font-medium rounded-lg transition-colors group"
                            :class="[
                                'relative rounded-xl transition-all overflow-hidden',
                                isActive(header) 
                                    ? 'bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full' 
                                    : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100'
                            ]"
                            @click.prevent="header.onClick"
                        >

                            <!-- ICON -->
                            <Icon 
                                :name="header.icon" 
                                class="w-5 h-5 flex-shrink-0"
                                :class="isSidebarMinimized ? 'mx-auto' : 'mr-3'"
                            />

                            <!-- LABEL -->
                            <template v-if="!isSidebarMinimized">
                                <span class="flex-1" v-html="header.name"></span>
                                <span class="sr-only" v-html="header.name"></span>

                                <span 
                                    v-if="header.hasOwnProperty('label') && header.label" 
                                    class="inline-flex justify-center items-center px-2 ml-3 text-xs font-medium text-gray-800 bg-gray-200 rounded-full" 
                                    v-html="header.label"
                                ></span>
                            </template>
                        </a>

                        <!-- ROUTER-LINK -->
                        <router-link 
                            v-else 
                            :to="header.to ? header.to : '#'" 
                            class="flex items-center p-3 text-sm font-medium rounded-lg transition-colors group"
                            :class="[
                                'relative rounded-xl transition-all overflow-hidden',
                                isActive(header) 
                                    ? 'bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full' 
                                    : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100'
                            ]"
                        >

                            <!-- ICON -->
                            <Icon 
                                :name="header.icon" 
                                class="w-5 h-5 flex-shrink-0"
                                :class="isSidebarMinimized ? 'mx-auto' : 'mr-3'"
                            />

                            <!-- LABEL -->
                            <template v-if="!isSidebarMinimized">
                                <span class="flex-1" v-html="header.name"></span>
                                <span class="sr-only" v-html="header.name"></span>

                                <span 
                                    v-if="header.hasOwnProperty('label') && header.label" 
                                    class="inline-flex justify-center items-center px-2 ml-3 text-xs font-medium text-gray-800 bg-gray-200 rounded-full" 
                                    v-html="header.label"
                                ></span>
                            </template>
                        </router-link>
                    </li>

                </div>



            </ul>
        </div>


        <button
            @click="isSidebarMinimized = !isSidebarMinimized" 
            class="absolute -right-3 top-1/2 transform -translate-y-1/2
                bg-white border-2 border-teal-600 rounded-full
                w-10 h-10 flex items-center justify-center
                shadow-lg hover:bg-teal-50 transition-colors z-50"
        >
            <Icon
                :name="isSidebarMinimized ? 'angle-right' : 'angle-left'" 
                class="text-teal-600"
            />
        </button>

        <template v-if="state.footerLeftLink && !isSidebarMinimized"> 
            <a v-if="state.footerLeftLink.href" :href="state.footerLeftLink.href" class="absolute w-full bottom-0 bg-teal-800 text-white flex items-center justify-center py-4 hover:bg-teal-700 transition-colors">
                <Icon :name="state.footerLeftLink.icon" class="mr-3"/>
                {{ state.footerLeftLink.name }}
            </a>
            <router-link v-else :to="state.footerLeftLink.to" class="absolute w-full bottom-0 bg-teal-800 text-white flex items-center justify-center py-4 hover:bg-teal-700 transition-colors">
                <Icon :name="state.footerLeftLink.icon" class="mr-3"/>
                {{ state.footerLeftLink.name }}
            </router-link>
        </template>
    </aside>
</template>

<script>
import Icon from "@/Components/icons/Icon.vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

export default {
    name: "Sidebar",
    components: { Icon },

    props: {
        state: {
            type: Object,
            default: () => ({ footerLeftLink: null }),
        },
        type: {
            type: String,
            default: "desktop",
        }
    },

    data() {
        return {
            isSidebarMinimized: false,
            expandedHeader: null,
            expandedParent: null,

            mainMenu: []
        };
    },

    computed: {
        router() {
            return useRouter();
        },
        store() {
            return useStore();
        }
    },

    mounted() {
        const currentPath = this.router.currentRoute.value.path;

        const menuAuthorized = localStorage.getItem('authorizedMenu');
        this.mainMenu = menuAuthorized ? JSON.parse(menuAuthorized) : [];

        this.mainMenu.forEach(header => {
            if (!header.children) return;

            header.children.forEach(parent => {
                // jika parent langsung punya route
                if (parent.to === currentPath) {
                    this.expandedHeader = header.name;
                }

                // jika parent punya children (level 3)
                if (parent.children && parent.children.length > 0) {
                    const activeChild = parent.children.find(
                        child => child.to === currentPath
                    );

                    if (activeChild) {
                        this.expandedHeader = header.name;
                        this.expandedParent = parent.name;
                    }
                }
            });
        });

        // Set minimized based on screen size
        this.handleResize();
        window.addEventListener('resize', this.handleResize);
    },


    methods: {
        // Cek active route
        // isActive(item) {
        //     const currentPath = this.router.currentRoute.value.path;

        //     const isMain = item.to === currentPath;
        //     const isChild =
        //         item.children &&
        //         item.children.some(child => child.to === currentPath);

        //     return isMain || isChild;
        // },
        isActive(item) {
            const currentPath = this.router.currentRoute.value.path;

            if (item.to === currentPath) return true;

            if (item.children && item.children.length > 0) {
                return item.children.some(child => this.isActive(child));
            }

            return false;
        },


        // Cek permission + visibility
        isEnabled(item, type) {
            if (!item) return false;

            // cek ability
            let hasPermission = true;
            if (item.requiresAbility) {
                hasPermission = this.store.getters["auth/hasAbilities"]
                    ? this.store.getters["auth/hasAbilities"](item.requiresAbility)
                    : false;
            }

            // count children
            let totalActive = 0;
            if (item.children) {
                item.children.forEach(child => {
                    if (this.isEnabled(child, type)) totalActive++;
                });
            } else {
                totalActive = 1;
            }

            // show on screen (desktop/mobile)
            let allowed = true;
            if (type === "desktop" && item.showDesktop !== undefined) {
                allowed = item.showDesktop;
            }
            if (type === "mobile" && item.showMobile !== undefined) {
                allowed = item.showMobile;
            }

            return hasPermission && allowed && totalActive > 0;
        },

        // buka / tutup submenu
        toggleMenu(header) {
            if (this.isSidebarMinimized) return;

            this.expandedHeader =
                this.expandedHeader === header.name
                    ? null
                    : header.name;

            // reset parent saat ganti header
            this.expandedParent = null;
        },
        toggleSubmenu(parent) {
            if (this.isSidebarMinimized) return;

            this.expandedParent =
                this.expandedParent === parent.name
                    ? null
                    : parent.name;
        },
        handleResize() {
            this.isSidebarMinimized = window.innerWidth < 640; // sm breakpoint
        },


    },

    beforeUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }
};
</script>


<style scoped>
/* Smooth scrollbar */
nav::-webkit-scrollbar {
    width: 6px;
}

nav::-webkit-scrollbar-track {
    background: transparent;
}

nav::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

nav::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>