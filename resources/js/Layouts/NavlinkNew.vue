<template>
    <aside 
        :class="[
            'relative bg-gradient-to-b from-teal-50 to-gray-50 h-screen hidden sm:block shadow-xl transition-all duration-300 ease-in-out',
            state.isSidebarMinimized ? 'w-20' : 'w-64'
        ]"
    >
        <div class="p-2 border-b border-gray-200">
            <router-link class="flex items-center justify-center" to="/dashboard">
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
            <ul class="space-y-2">
                <template v-for="item in mainMenu">
                    <!-- Menu dengan Children (Submenu) -->
                    <li v-if="item.hasOwnProperty('children') && item.children.length > 0 && isEnabled(item, $props.type)">
                        <button 
                            @click.prevent="toggleSubmenu(item)" 
                            type="button" 
                            class="transition duration-200 group w-full flex items-center p-3 text-sm font-medium rounded-lg"
                            :class="[
                                'relative rounded-xl transition-all overflow-hidden',
                                isActive(item) 
                                    ? 'bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-10 before:bg-yellow-400 before:rounded-l-full' 
                                    : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-10 before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100'
                            ]"
                        >
                            <Icon 
                                :name="item.icon" 
                                :class="[
                                    'flex-shrink-0 w-5 h-5',
                                    $props.isMinimized ? 'mx-auto' : 'mr-3'
                                ]"
                            />
                            <template v-if="!$props.isMinimized">
                                <span class="flex-1 text-left" v-html="item.name"></span>
                                <Icon 
                                    :name="JSON.stringify(state.currentExpandedMenuItem) === JSON.stringify(item) ? 'angle-up' : 'angle-down'" 
                                    class="w-4 h-4"
                                />
                            </template>
                        </button>
                        
                        <!-- Submenu Items -->
                        <ul 
                            v-if="!$props.isMinimized"
                            class="py-2 space-y-1 ml-4" 
                            :class="JSON.stringify(state.currentExpandedMenuItem) === JSON.stringify(item) ? '' : 'hidden'"
                        >
                            <template v-for="subitem in item.children">
                                <template v-if="isEnabled(subitem, $props.type)">
                                    <li v-if="subitem.hasOwnProperty('onClick')">
                                        <router-link 
                                            :to="subitem.to ? subitem.to : '#'" 
                                            @click.prevent="subitem.onClick" 
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
                                    <li v-else>
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
                            </template>
                        </ul>
                    </li>
                    
                    <!-- Menu Biasa (Tanpa Submenu) -->
                    <li v-else-if="isEnabled(item, $props.type)">
                        <a 
                            v-if="item.hasOwnProperty('onClick') && !item.to" 
                            href="#" 
                            class="flex items-center p-3 text-sm font-medium rounded-lg transition-colors group"
                            :class="[
                                'relative rounded-xl transition-all overflow-hidden',
                                isActive(item) 
                                    ? 'bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-10 before:bg-yellow-400 before:rounded-l-full' 
                                    : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-10 before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100'
                            ]"
                            @click.prevent="item.onClick"
                        >
                            <Icon 
                                :name="item.icon" 
                                :class="[
                                    'flex-shrink-0 w-5 h-5',
                                    $props.isMinimized ? 'mx-auto' : 'mr-3'
                                ]"
                            />
                            <template v-if="!$props.isMinimized">
                                <span class="flex-1" v-html="item.name"></span>
                                <span class="sr-only" v-html="item.name"></span>
                                <span 
                                    v-if="item.hasOwnProperty('label') && item.label" 
                                    class="inline-flex justify-center items-center px-2 ml-3 text-xs font-medium text-gray-800 bg-gray-200 rounded-full" 
                                    v-html="item.label"
                                ></span>
                            </template>
                        </a>
                        <router-link 
                            v-else 
                            :to="item.to ? item.to : '#'" 
                            class="flex items-center p-3 text-sm font-medium rounded-lg transition-colors group"
                            :class="[
                                'relative rounded-xl transition-all overflow-hidden',
                                isActive(item) 
                                    ? 'bg-teal-600 text-white shadow-md before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-10 before:bg-yellow-400 before:rounded-l-full' 
                                    : 'text-gray-600 hover:bg-teal-600/20 hover:text-teal-700 before:content-[\'\'] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-10 before:bg-yellow-400 before:rounded-l-full before:opacity-0 hover:before:opacity-100'
                            ]"
                        >
                            <Icon 
                                :name="item.icon" 
                                :class="[
                                    'flex-shrink-0 w-5 h-5',
                                    $props.isMinimized ? 'mx-auto' : 'mr-3'
                                ]"
                            />
                            <template v-if="!$props.isMinimized">
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
                </template>
            </ul>
        </div>

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
</template>

<script>
import { defineComponent, ref, computed, onMounted } from "vue"; // Tambahkan 'ref', 'computed', 'onMounted'
import { useRouter } from "vue-router";
import { useStore } from 'vuex';
import Icon from "@/Components/icons/Icon.vue";

export default defineComponent({
    name: "Sidebar",
    components: {
        Icon
    },
    props: {
        state: {
            type: Object,
            // Perhatikan: properti 'state' dari props TIDAK disarankan diubah secara langsung
            // kecuali Anda tahu properti mana yang memang dimaksudkan untuk diubah (misal: isSidebarMinimized)
            default: () => ({}),
        },
        type: {
            type: String,
            default: 'desktop',
        }
    },
    setup(props) {
        const router = useRouter();
        const store = useStore(); // Ubah nama variabel untuk menghindari kebingungan dengan 'authStore'

        // 1. Ambil data menu dari Vuex Store
        const mainMenu = localStorage.getItem("authorizedMenu") 
            ? ref(JSON.parse(localStorage.getItem("authorizedMenu"))) 
            : computed(() => store.state.menu.authorizedMenu);
        
        console.log(mainMenu);
        // 2. State lokal untuk mengontrol submenu yang sedang dibuka
        // Menggantikan props.state.currentExpandedMenuItem
        // Kita gunakan nama menu sebagai identifier unik
        const currentExpandedMenuItem = ref(null); 

        // 3. Panggil Aksi untuk memuat menu saat komponen dibuat
        onMounted(() => {
            // Memuat menu jika belum ada (atau jika perlu di-refresh)
            if (mainMenu.value.length === 0 && store.getters['menu/menuStatus'] !== 'loading') {
                 store.dispatch('menu/fetchAuthorizedMenu');
            }

            // Opsional: Coba buka submenu jika salah satu sub-itemnya adalah rute aktif saat ini
            // Ini akan memastikan menu yang aktif tetap terbuka saat refresh.
            const currentPath = router.currentRoute.value.path;
            const activeParent = mainMenu.value.find(item => 
                item.children && item.children.some(sub => sub.to === currentPath)
            );

            if (activeParent) {
                currentExpandedMenuItem.value = activeParent.name;
            }
        });


        function isActive(obj) {
            let currentPath = router.currentRoute.value.path;
            let isActiveMainItem = obj.to === currentPath;
            let isActiveSubItem = false;
            
            if (obj.hasOwnProperty('children')) {
                for (let i in obj.children) {
                    if (obj.children[i].to === currentPath) {
                        isActiveSubItem = true;
                        break;
                    }
                }
            }
            return isActiveMainItem || isActiveSubItem;
        }

        function isEnabled(obj, type) {
            if (!obj) {
                return false;
            }

            // Ganti authStore.hasAbilities dengan pemanggilan ke auth module di store.
            // ASUMSI: Anda memiliki getter/action `hasAbilities` di Vuex Auth Module.
            let hasPermission = obj.hasOwnProperty('requiresAbility') && false !== obj.requiresAbility 
                ? store.getters['auth/hasAbilities'] && store.getters['auth/hasAbilities'](obj.requiresAbility)
                : true;

            let totalEnabledSubItems = 0;
            if (obj.hasOwnProperty('children')) {
                for (let i in obj.children) {
                    // Rekursif check untuk submenu
                    if (isEnabled(obj.children[i], type)) {
                        totalEnabledSubItems++;
                    }
                }
            } else {
                totalEnabledSubItems = 1;
            }

            if (type === 'desktop') {
                return hasPermission && obj.showDesktop && totalEnabledSubItems > 0;
            } else if (type === 'mobile') {
                return hasPermission && obj.showMobile && totalEnabledSubItems > 0;
            }
            return false;
        }

        function toggleSubmenu(item) {
            // Jika sidebar diminimized, jangan toggle submenu
            if (props.state.isSidebarMinimized) {
                return;
            }
            
            // Toggle submenu menggunakan nama item sebagai identifier
            if (currentExpandedMenuItem.value === item.name) {
                currentExpandedMenuItem.value = null;
            } else {
                currentExpandedMenuItem.value = item.name;
            }
        }

        return {
            mainMenu, // Ekspos data menu dari Store
            currentExpandedMenuItem, // Ekspos state lokal untuk submenu
            isEnabled,
            isActive,
            toggleSubmenu
        }
    }
});
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