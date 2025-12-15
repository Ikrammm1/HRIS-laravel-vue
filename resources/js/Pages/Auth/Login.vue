<script setup>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();

const showPassword = ref(false);
const form = ref({
    email: '',
    password: '',
    remember: false,
});

// Get loading and error state from Vuex
const isLoading = computed(() => store.state.auth.loading);
const error = computed(() => store.state.auth.error);
const status = computed(() => store.state.auth.status);
const message = computed(() => store.state.auth.message);

const submit = async () => {
    try {
        const payload = {
            email: form.value.email,
            password: form.value.password,
            remember: form.value.remember,
        };
        store.commit("SET_LOADING", true);
        await store.dispatch('auth/login', payload);
        await store.dispatch('menu/fetchAuthorizedMenu');
        store.commit("SET_LOADING", false);
        
        // After successful login, redirect to dashboard
        router.push({ name: 'dashboard' });
    } catch (err) {
        // Error handling is done in Vuex store
        console.error('Login failed:', err);
    }
};

const clearError = () => {
    store.commit('auth/CLEAR_ERROR');
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center  p-4">
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden flex">
            <!-- Left Side - Form -->
            <div class="w-full md:w-5/12 p-8 md:p-12">
                <!-- Logo -->
                <div class="flex items-center gap-3 mb-8">
                    <div class="relative w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-yellow-400 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold text-gray-800 leading-none">
                            HR<span class="text-teal-600">Portal</span>
                        </span>
                        <span class="text-xs text-gray-500 font-medium">Human Resource System</span>
                    </div>
                </div>

                <!-- Welcome Text -->
                <div class="mb-6">
                    <p class="text-gray-600 text-sm mb-2">
                        Welcome to <span class="text-yellow-600 font-medium">HRIS!</span>
                    </p>
                    <h1 class="text-3xl font-bold text-gray-800 mb-3">Login</h1>
                    <p class="text-sm text-gray-600">
                        If you are already a member, easily log in.
                    </p>
                </div>

                <!-- Status Message -->
                <div v-if="message" class="mb-4 text-sm font-medium p-3 rounded-lg" 
                    :class="status ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50'">
                    {{ message }}
                </div>

                <!-- Error Message -->
                <div v-if="error" class="mb-4 text-sm font-medium text-red-600 bg-red-50 p-3 rounded-lg flex items-center justify-between">
                    <span>{{ error }}</span>
                    <button @click="clearError" class="text-red-800 hover:text-red-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit">
                    <div class="space-y-4">
                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input 
                                id="email"
                                type="email" 
                                v-model="form.email" 
                                autocomplete="username"
                                placeholder="Email address"
                                required
                                autofocus
                                :disabled="isLoading"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                            />
                        </div>

                        <!-- Password Input -->
                        <div class="relative">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <input 
                                id="password"
                                :type="showPassword ? 'text' : 'password'" 
                                v-model="form.password"
                                autocomplete="current-password"
                                placeholder="••••••••••••••"
                                required
                                :disabled="isLoading"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent pr-10 disabled:bg-gray-100 disabled:cursor-not-allowed"
                            />
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                :disabled="isLoading"
                                class="absolute right-3 top-9 text-gray-400 hover:text-gray-600 disabled:cursor-not-allowed"
                            >
                                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    v-model="form.remember"
                                    :disabled="isLoading"
                                    class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500 disabled:cursor-not-allowed"
                                />
                                <span class="ml-2 text-gray-600">Remember me</span>
                            </label>
                            <router-link 
                                to="/forgot-password" 
                                class="text-yellow-600 hover:text-yellow-700 font-medium"
                            >
                                Forgot your password?
                            </router-link>
                        </div>

                        <!-- Login Button -->
                        <button 
                            type="submit"
                            :disabled="isLoading"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                        >
                            <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ isLoading ? 'Logging in...' : 'Log in' }}
                        </button>
                    </div>
                </form>

                <!-- Register Link -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center text-sm">
                        <p class="text-gray-600">Don't have an account?</p>
                        <router-link 
                            to="/register" 
                            class="py-2 px-5 bg-white border border-yellow-500 text-yellow-600 rounded-lg hover:bg-yellow-50 transition-colors duration-200 font-medium"
                        >
                            Register
                        </router-link>
                    </div>
                </div>
            </div>

            <!-- Right Side - Image -->
            <div class="hidden md:flex md:w-7/12 bg-gradient-to-br from-green-50 to-green-50 relative items-center justify-center p-12">
                <div class="relative w-full h-full flex items-center justify-center">
                    <img 
                        src="/assets/images/illustrationlogin.png" 
                        alt="Login Illustration" 
                        class="max-w-full max-h-full object-contain drop-shadow-2xl"
                    />
                </div>

                <!-- Footer -->
                <div class="absolute bottom-6 right-6 text-xs text-gray-600">
                    <span>Copyright © 2025 </span>
                    <a href="#" class="text-yellow-600 hover:underline font-medium">Site</a>
                    <span> All rights reserved · </span>
                    <a href="#" class="text-yellow-600 hover:underline font-medium">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </div>
</template>