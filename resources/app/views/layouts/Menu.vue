<template>
    <ul class="space-y-2">
        <template v-for="item in $props.state.mainMenu">
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
</template>

<script>

import {defineComponent} from "vue";
import {useRouter} from "vue-router";
import {useAuthStore} from "@/stores";
import Icon from "@/views/components/icons/Icon"

export default defineComponent({
    name: "Menu",
    components: {
        Icon
    },
    props: {
        state: {
            type: Object,
            default: {},
        },
        type: {
            type: String,
            default: '',
        },
        isMinimized: {
            type: Boolean,
            default: false,
        }
    },
    setup(props) {

        const router = useRouter();
        const authStore = useAuthStore();

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

            let hasPermission = obj.hasOwnProperty('requiresAbility') && false !== obj.requiresAbility ? authStore.hasAbilities(obj.requiresAbility) : true;

            let totalEnabledSubItems = 0;
            if (obj.hasOwnProperty('children')) {
                for (let i in obj.children) {
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
            if (props.isMinimized) {
                return;
            }
            
            // Toggle submenu
            if (JSON.stringify(props.state.currentExpandedMenuItem) === JSON.stringify(item)) {
                props.state.currentExpandedMenuItem = null;
            } else {
                props.state.currentExpandedMenuItem = item;
            }
        }

        return {
            isEnabled,
            isActive,
            toggleSubmenu
        }
    }
});
</script>