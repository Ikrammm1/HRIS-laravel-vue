<template>
    <div class="bg-gray-50 flex" v-if="authStore.user && authStore.user.hasOwnProperty('id')">
        <!-- Sidebar/Navbar -->
        <aside 
            :class="[
                'relative bg-gradient-to-b from-teal-50 to-gray-50 h-screen hidden sm:block shadow-xl transition-all duration-300 ease-in-out',
                state.isSidebarMinimized ? 'w-20' : 'w-64'
            ]"
        >
            <!-- Logo Section -->
           <div class="p-2 border-b border-gray-200">
                <router-link class="flex items-center justify-center" to="/panel/dashboard">
                    <template v-if="!state.isSidebarMinimized">
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
                        <!-- Logo minimized (hanya icon) -->
                        <div class="relative w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-yellow-400 rounded-full border-2 border-white"></div>
                        </div>
                    </template>
                </router-link>
            </div>

            <!-- Menu Navigation -->
            <nav class="text-gray-700 text-base py-4 px-3">
                <Menu :state="state" :type="'desktop'" :isMinimized="state.isSidebarMinimized"/>
            </nav>

            <!-- Toggle Minimize Button -->
            <button
                @click="state.isSidebarMinimized = !state.isSidebarMinimized"
                class="absolute -right-3 top-1/2 transform -translate-y-1/2
                    bg-white border-2 border-teal-600 rounded-full
                    w-10 h-10 flex items-center justify-center
                    shadow-lg hover:bg-teal-50 transition-colors z-50"
            >
                <Icon
                    :name="state.isSidebarMinimized ? 'angle-right' : 'angle-left'"
                    class="text-teal-600"
                />
            </button>


            <!-- Footer Link (if needed) -->
            <template v-if="state.footerLeftLink && !state.isSidebarMinimized">
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

        <!-- Main Content Area -->
        <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
            <!-- Desktop Top Bar -->
            <header class="w-full items-center justify-between bg-gradient-to-r from-yellow-200 via-yellow-100 to-orange-100 px-4 hidden sm:flex shadow-sm">

                <!-- Search Bar -->
                <div class="flex-1 max-w-xl">
                    <div class="relative">
                        <Icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"/>
                        <input
                            type="text"
                            placeholder="Search"
                            class="w-full pl-10 pr-4 py-2 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        />
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <!-- Notification Bell -->
                    <button class="relative p-2 text-gray-600 hover:bg-white rounded-lg transition-colors">
                        <Icon name="bell" class="text-xl"/>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative">
                <a class="flex items-center space-x-3 cursor-pointer hover:bg-white px-3 py-2 rounded-lg transition-colors" @click="state.isAccountDropdownOpen = !state.isAccountDropdownOpen">
                    <button class="relative z-10 w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-md focus:outline-none">
                        <img :alt="authStore.user.full_name" v-if="authStore.user.avatar_thumb_url" :src="authStore.user.avatar_thumb_url">
                        <AvatarIcon v-else/>
                    </button>
                    <Icon :name="state.isAccountDropdownOpen ? 'angle-up' : 'angle-down'" class="text-gray-600"/>
                </a>
                <button v-if="state.isAccountDropdownOpen" @click="state.isAccountDropdownOpen = false" class="h-full w-full fixed inset-0 cursor-pointer z-30"></button>
                
                <div v-if="state.isAccountDropdownOpen" class="absolute right-0 w-48 bg-white rounded-lg shadow-lg py-2 mt-2 z-50">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800">{{ authStore.user.full_name }}</p>
                        <p class="text-xs text-gray-500">{{ authStore.user.email }}</p>
                    </div>
                    <router-link to="/panel/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-teal-600 hover:text-white transition-colors">
                        <Icon name="user" class="mr-2"/>
                        {{ trans('global.pages.profile') }}
                    </router-link>
                    
                    <button
                        :disabled="globalStateStore.loadingElements['logout-form']"
                        @click.prevent="onLogout" 
                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-teal-600 hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <Icon v-if="globalStateStore.loadingElements['logout-form']" name="spinner" class="mr-2 animate-spin"/>
                        <Icon v-else name="sign-out" class="mr-2"/>
                        {{ trans('global.phrases.sign_out') }}
                    </button>
                    </div>
            </div>
                </div>
            </header>

            <!-- Mobile Header & Nav -->
            <header class="w-full bg-teal-600 py-5 px-6 sm:hidden">
                <div class="flex items-center justify-between">
                    <router-link class="text-white text-2xl font-semibold uppercase hover:text-gray-200" to="/panel/dashboard">
                        {{ state.app.name }}
                    </router-link>
                    <button @click="state.isMobileMenuOpen = !state.isMobileMenuOpen" class="text-white text-3xl focus:outline-none">
                        <Icon :name="state.isMobileMenuOpen ? 'times' : 'bars'"/>
                    </button>
                </div>
                <nav :class="state.isMobileMenuOpen ? 'flex': 'hidden'" class="flex flex-col pt-4 text-base text-white">
                    <Menu :state="state" :type="'mobile'"/>
                </nav>
            </header>

            <!-- Main Content -->
            <div class="w-full h-screen overflow-x-hidden flex flex-col">
                <main class="w-full flex-grow p-2 overflow-y-auto">
                    <router-view/>
                </main>
                <footer class="w-full bg-white text-center text-sm p-3 border-t border-gray-200" v-html="trans('global.phrases.copyright')"></footer>
            </div>
        </div>
    </div>
    <template v-else>
        <router-view/>
    </template>
</template>

<script>
import {computed, onBeforeMount, reactive} from "vue";

import {trans} from '@/helpers/i18n';
import Menu from "@/views/layouts/Menu";
import Icon from "@/views/components/icons/Icon";
import AvatarIcon from "@/views/components/icons/Avatar";
import {useAuthStore} from "@/stores/auth";
import {useGlobalStateStore} from "@/stores";
import {useRoute} from "vue-router";
import {useAlertStore} from "@/stores";
import {getAbilitiesForRoute} from "@/helpers/routing";

export default {
    name: "app",
    components: {
        AvatarIcon,
        Menu,
        Icon
    },
    setup() {

        const alertStore = useAlertStore();
        const authStore = useAuthStore();
        const globalStateStore = useGlobalStateStore();
        const route = useRoute();

        const isLoading = computed(() => {
            var value = false;
            for(var i in globalStateStore.loadingElements) {
                if(globalStateStore.loadingElements[i]){
                    value = true;
                    break;
                }
            }
            return value || globalStateStore.isUILoading;
        })

        const state = reactive({
            mainMenu: [
                {
                    name: trans('global.pages.home'),
                    icon: 'tachometer',
                    showDesktop: true,
                    showMobile: true,
                    requiresAbility: false,
                    to: '/panel/dashboard',
                },
                {
                    name: trans('global.pages.users'),
                    icon: 'users',
                    showDesktop: true,
                    showMobile: true,
                    requiresAbility: getAbilitiesForRoute(['users.list', 'users.create', 'users.edit']),
                    to: '/panel/users/list',
                    children: [
                        {
                            name: trans('global.phrases.all_records'),
                            icon: '',
                            showDesktop: true,
                            showMobile: true,
                            requiresAbility: getAbilitiesForRoute('users.list'),
                            to: '/panel/users/list',
                        },
                        {
                            name: trans('global.buttons.add_new'),
                            icon: '',
                            showDesktop: true,
                            showMobile: true,
                            requiresAbility: getAbilitiesForRoute('users.create'),
                            to: '/panel/users/create',
                        }
                    ]
                },
                {
                    name: trans('global.phrases.sign_out'),
                    icon: 'sign-out',
                    showDesktop: false,
                    showMobile: true,
                    showIfRole: false,
                    onClick: onLogout,
                    to: '',
                }
            ],
            footerLeftLink: {
                name: trans('global.buttons.documentation'),
                icon: 'paperclip',
                to: '',
                href: '#',
            },
            isAccountDropdownOpen: false,
            isMobileMenuOpen: false,
            isSidebarMinimized: false, // ← TAMBAHAN BARU untuk fitur minimize
            currentExpandedMenuItem: null,
            app: window.AppConfig,
        });

        function onLogout() {
            authStore.logout()
        }

        onBeforeMount(() => {
            // Initialize auth state from localStorage on app startup
            authStore.initAuth();
            
            if (route.query.hasOwnProperty('verified') && route.query.verified) {
                alertStore.success(trans('global.phrases.email_verified'));
            }
        });

        return {
            state,
            authStore,
            globalStateStore,
            trans,
            onLogout,
            isLoading,
        }
    }
};
</script>