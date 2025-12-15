<template>
    <aside 
        :class="[
            'relative bg-gradient-to-b from-teal-50 to-gray-50 h-screen hidden sm:block shadow-xl transition-all duration-300 ease-in-out',
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
                <div v-for="item in mainMenu" :key="item.name" class="mt-2">

                    <!-- MENU DENGAN CHILDREN -->
                    <li v-if="item.hasOwnProperty('children')">
                        <button 
                            @click.prevent="toggleSubmenu(item)" 
                            type="button"
                            class="transition duration-200 group w-full flex items-center p-3 text-sm font-medium rounded-lg"
                            :class="[
                                'relative rounded-xl transition-all overflow-hidden',
                                isActive(item) 
                                    ? 'bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full' 
                                    : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100'
                            ]"
                        >

                            <!-- ICON UTAMA -->
                            <Icon 
                                :name="item.icon" 
                                class="w-5 h-5 flex-shrink-0"
                                :class="isSidebarMinimized ? 'mx-auto' : 'mr-3'"
                            />

                            <!-- TEKS + PANAH -->
                            <template v-if="!isSidebarMinimized">
                                <span class="flex-1 text-left" v-html="item.name"></span>

                                <Icon 
                                    :name="currentExpandedMenuItem === item.name ? 'angle-up' : 'angle-down'" 
                                    class="w-4 h-4 flex-shrink-0 self-center"
                                />
                            </template>

                        </button>

                        <!-- SUBMENU -->
                        <ul 
                            v-if="!isSidebarMinimized" 
                            class="py-2 space-y-1 ml-4" 
                            :class="currentExpandedMenuItem === item.name ? '' : 'hidden'"
                        >
                            <template v-for="subitem in item.children" :key="subitem.name">
                                <li>
                                    
                                    <router-link 
                                        :to="subitem.to ? subitem.to : '#'" 
                                        class="flex items-center p-2 pl-8 w-full text-sm font-medium rounded-lg transition-colors"
                                        :class="[
                                            isActive(subitem) 
                                                ? 'bg-teal-600 text-white' 
                                                : 'text-gray-600 hover:bg-teal-100 hover:text-teal-700'
                                        ]"
                                    >
                                        {{ subitem.name }}
                                        <span class="sr-only" v-html="subitem.name"></span>
                                    </router-link>
                                </li>
                            </template>
                        </ul>
                    </li>

                    <!-- MENU TANPA CHILDREN -->
                    <li v-else>
                        <a 
                            v-if="item.hasOwnProperty('onClick') && !item.to" 
                            href="#"
                            class="flex items-center p-3 text-sm font-medium rounded-lg transition-colors group"
                            :class="[
                                'relative rounded-xl transition-all overflow-hidden',
                                isActive(item) 
                                    ? 'bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full' 
                                    : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100'
                            ]"
                            @click.prevent="item.onClick"
                        >

                            <!-- ICON -->
                            <Icon 
                                :name="item.icon" 
                                class="w-5 h-5 flex-shrink-0"
                                :class="isSidebarMinimized ? 'mx-auto' : 'mr-3'"
                            />

                            <!-- LABEL -->
                            <template v-if="!isSidebarMinimized">
                                <span class="flex-1" v-html="item.name"></span>
                                <span class="sr-only" v-html="item.name"></span>

                                <span 
                                    v-if="item.hasOwnProperty('label') && item.label" 
                                    class="inline-flex justify-center items-center px-2 ml-3 text-xs font-medium text-gray-800 bg-gray-200 rounded-full" 
                                    v-html="item.label"
                                ></span>
                            </template>
                        </a>

                        <!-- ROUTER-LINK -->
                        <router-link 
                            v-else 
                            :to="item.to ? item.to : '#'" 
                            class="flex items-center p-3 text-sm font-medium rounded-lg transition-colors group"
                            :class="[
                                'relative rounded-xl transition-all overflow-hidden',
                                isActive(item) 
                                    ? 'bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full' 
                                    : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-full before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100'
                            ]"
                        >

                            <!-- ICON -->
                            <Icon 
                                :name="item.icon" 
                                class="w-5 h-5 flex-shrink-0"
                                :class="isSidebarMinimized ? 'mx-auto' : 'mr-3'"
                            />

                            <!-- LABEL -->
                            <template v-if="!isSidebarMinimized">
                                <span class="flex-1" v-html="item.name"></span>
                                <span class="sr-only" v-html="item.name"></span>

                                <span 
                                    v-if="item.hasOwnProperty('label') && item.label" 
                                    class="inline-flex justify-center items-center px-2 ml-3 text-xs font-medium text-gray-800 bg-gray-200 rounded-full" 
                                    v-html="item.label"
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
            currentExpandedMenuItem: null,

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
        this.mainMenu = menuAuthorized ? JSON.parse(menuAuthorized) : this.mainMenu;

        const activeParent = this.mainMenu.find(item =>
            item.children &&
            item.children.some(child => child.to === currentPath)
        );

        if (activeParent) {
            this.currentExpandedMenuItem = activeParent.name;
        }

    },

    methods: {
        // Cek active route
        isActive(item) {
            const currentPath = this.router.currentRoute.value.path;

            const isMain = item.to === currentPath;
            const isChild =
                item.children &&
                item.children.some(child => child.to === currentPath);

            return isMain || isChild;
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
        toggleSubmenu(item) {
            if (this.isSidebarMinimized) return;

            this.currentExpandedMenuItem =
                this.currentExpandedMenuItem === item.name
                    ? null
                    : item.name;
        }
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