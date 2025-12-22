<template>
    <header class="w-full items-center justify-between bg-gradient-to-r from-yellow-200 via-yellow-100 to-orange-100 px-4 hidden sm:flex shadow-sm">
        
        <!-- Search Bar -->
        <div class="flex-1 max-w-xl">
            <div class="relative">
                <Icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"/>
                <input
                    type="text"
                    placeholder="Search"
                    class="w-full pl-10 pr-4 py-2 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
                />
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
        };
    },

    computed: {
        avatarInitial() {
            if (!this.userName) return "?";
            return this.userName.charAt(0).toUpperCase();
        }
    },
    methods: {
        async onLogout() {
            this.$store.commit("SET_LOADING", true);
            try{
                await this.$store.dispatch("auth/logout");
            }finally{
                this.$store.commit("SET_LOADING", false);
            }

            // Redirect ke login
            // this.$router.push({ name: "login" });
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
    },

    // mounted() {
    //     document.title = "Dashboard";
    // },
};
</script>
