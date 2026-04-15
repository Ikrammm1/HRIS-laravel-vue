<template>
    <header class="w-full items-center justify-between bg-gradient-to-r from-yellow-200 via-yellow-100 to-orange-100 px-4 flex shadow-sm">
        
        <!-- Search Bar -->
        <div class="flex-1 max-w-xl relative">
            <div class="relative">
                <Icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"/>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search menus..."
                    class="w-full pl-10 pr-4 py-2 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    @focus="showSuggestions = true"
                    @blur="hideSuggestions"
                    @keydown="handleKeydown"
                />
            </div>
            <!-- Suggestions Dropdown -->
            <div v-if="showSuggestions && filteredMenus.length" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 z-50 max-h-60 overflow-y-auto">
                <div v-for="(menu, index) in filteredMenus" :key="menu.name + menu.to" @mousedown="selectMenu(menu)" :class="['px-4 py-2 cursor-pointer text-sm text-gray-700', index === selectedIndex ? 'bg-teal-100' : 'hover:bg-teal-50']">
                    {{ menu.name }}
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4">

            <!-- Notifications -->
            <button class="relative p-2 text-gray-600 hover:bg-white rounded-lg transition-colors">
                <Icon name="bell" class="text-xl"/>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- User Dropdown -->
            <div class="relative">
                
                <div 
                    class="flex items-center space-x-3 cursor-pointer hover:bg-white px-3 py-2 rounded-lg transition-colors"
                    @click="isAccountDropdownOpen = !isAccountDropdownOpen"
                >
                    <button class="relative z-10 w-10 h-10 rounded-full overflow-hidden bg-teal-600 text-white flex items-center justify-center text-lg border-2 border-white shadow-md">
                        <!-- Jika ada foto -->
                        <img v-if="photo" :src="photo" :alt="userName" class="object-cover w-full h-full" />

                        <!-- Jika tidak ada foto → inisial -->
                        <span v-else>
                            {{ avatarInitial }}
                        </span>
                    </button>

                    <Icon :name="isAccountDropdownOpen ? 'angle-up' : 'angle-down'" class="text-gray-600"/>
                </div>

                <!-- Overlay close area -->
                <button 
                    v-if="isAccountDropdownOpen" 
                    @click="isAccountDropdownOpen = false" 
                    class="fixed inset-0 z-30 cursor-pointer"
                ></button>

                <!-- Dropdown menu -->
                <div 
                    v-if="isAccountDropdownOpen" 
                    class="absolute right-0 w-48 bg-white rounded-lg shadow-lg py-2 mt-2 z-50"
                >
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800">{{ userName }}</p>
                        <p class="text-xs text-gray-500">{{ email }}</p>
                    </div>

                    <router-link 
                        to="/panel/profile" 
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-teal-600 hover:text-white"
                    >
                        <Icon name="user" class="mr-2"/>
                        Profile
                    </router-link>

                   <button
                      @click.prevent="onLogout" 
                      class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-teal-600 hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                      <Icon name="sign-out" class="mr-2"/>
                      Logout
                  </button>
                </div>

            </div>
        </div>

    </header>
</template>

<script>
import Icon from "@/Components/icons/Icon.vue";

export default {
    components: { Icon },

    data() {
        return {
            id: null,
            userName: JSON.parse(localStorage.getItem('authUser')).name || '',
            email: JSON.parse(localStorage.getItem('authUser')).email || '',
            photo: '',
            isAccountDropdownOpen: false,  // FIX
            searchQuery: '',
            showSuggestions: false,
            menus: [],
            selectedIndex: -1,
        };
    },

    computed: {
        avatarInitial() {
            if (!this.userName) return "?";
            return this.userName.charAt(0).toUpperCase();
        },
        flattenedMenus() {
            return this.flattenMenus(this.menus);
        },
        filteredMenus() {
            if (!this.searchQuery) return [];
            return this.flattenedMenus.filter(menu => 
                menu.name && menu.name.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        }
    },
    methods: {
        flattenMenus(menus) {
            let result = [];
            menus.forEach(menu => {
                if (menu.name && menu.to && menu.to !== '-') {
                    result.push(menu);
                }
                if (menu.children && menu.children.length > 0) {
                    result = result.concat(this.flattenMenus(menu.children));
                }
            });
            return result;
        },
        async onLogout() {
            this.$store.commit("SET_LOADING", true);
            try{
                await this.$store.dispatch("auth/logout");
            }finally{
                this.$store.commit("SET_LOADING", false);
            }

            // Redirect ke login
            // this.$router.push({ name: "login" });
        },
        selectMenu(menu) {
            this.searchQuery = menu.name;
            this.showSuggestions = false;
            // Navigate to menu route if available
            if (menu.to && menu.to !== '-') {
                this.$router.push(menu.to);
            }
        },
        hideSuggestions() {
            setTimeout(() => {
                this.showSuggestions = false;
                this.selectedIndex = -1;
            }, 200);
        },
        handleKeydown(event) {
            if (!this.showSuggestions || this.filteredMenus.length === 0) return;

            switch (event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    this.selectedIndex = (this.selectedIndex + 1) % this.filteredMenus.length;
                    break;
                case 'ArrowUp':
                    event.preventDefault();
                    this.selectedIndex = this.selectedIndex <= 0 ? this.filteredMenus.length - 1 : this.selectedIndex - 1;
                    break;
                case 'Enter':
                    event.preventDefault();
                    if (this.selectedIndex >= 0) {
                        this.selectMenu(this.filteredMenus[this.selectedIndex]);
                    }
                    break;
                case 'Escape':
                    this.showSuggestions = false;
                    this.selectedIndex = -1;
                    break;
            }
        }
    },

    watch: {
        '$store.state.menu.authorizedMenu'(newMenus) {
            this.menus = newMenus || [];
        },
        searchQuery() {
            this.selectedIndex = -1;
        }
    },

    created() {
        const authUser = JSON.parse(localStorage.getItem('authUser'));

        if (authUser?.user) {
            const userData = authUser.user;
            this.id = userData.id;
            this.userName = userData.name;
            this.email = userData.email;
            this.photo = userData.photo ?? ""; // aman jika null
        }

        // Load menus from localStorage
        const storedMenus = localStorage.getItem('authorizedMenu');
        if (storedMenus) {
            this.menus = JSON.parse(storedMenus);
        }
    },

    // mounted() {
    //     document.title = "Dashboard";
    // },
};
</script>
