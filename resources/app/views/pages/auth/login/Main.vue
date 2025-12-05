<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
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
                        {{ trans('global.phrases.login_desc') }}
                    </p>
                </div>

                <!-- Alert Component -->
                <Alert class="mb-4"></Alert>

                <!-- Form -->
                <Form id="login-form" @submit.prevent="onFormSubmit">
                    <div class="space-y-4">
                        <!-- Email Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ trans('users.labels.email') }}
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                v-model="form.email" 
                                autocomplete="email"
                                placeholder="Email address"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                            />
                        </div>

                        <!-- Password Input -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ trans('users.labels.password') }}
                            </label>
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                v-model="form.password"
                                placeholder="••••••••••••••"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent pr-10"
                            />
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-9 text-gray-400 hover:text-gray-600"
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
                                    class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500"
                                >
                                <span class="ml-2 text-gray-600">Remember me</span>
                            </label>
                            <router-link to="/forgot-password" class="text-yellow-600 hover:text-yellow-700 font-medium">
                                {{ trans('global.phrases.forgot_password_ask') }}
                            </router-link>
                        </div>

                        <!-- Login Button -->
                        <button 
                            type="submit"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-lg transition-colors duration-200"
                        >
                            {{ trans('global.buttons.login') }}
                        </button>
                    </div>
                </Form>

                <!-- Register Link -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center text-sm">
                        <p class="text-gray-600">{{ trans('global.phrases.register_ask') }}</p>
                        <router-link 
                            to="/register" 
                            class="py-2 px-5 bg-white border border-yellow-500 text-yellow-600 rounded-lg hover:bg-yellow-50 transition-colors duration-200 font-medium"
                        >
                            {{ trans('global.buttons.register') }}
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
                    <span>Copyright © 2022 </span>
                    <a href="#" class="text-yellow-600 hover:underline font-medium">Site</a>
                    <span> All rights reserved · </span>
                    <a href="#" class="text-yellow-600 hover:underline font-medium">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {trans} from "@/helpers/i18n"
import {reactive, ref, defineComponent} from "vue";
import {useAuthStore} from "@/stores/auth";
import Alert from "@/views/components/Alert";
import Form from "@/views/components/Form";

export default defineComponent({
    name: "LoginView",
    components: {
        Form,
        Alert,
    },
    setup() {
        const authStore = useAuthStore();
        const showPassword = ref(false);
        const form = reactive({
            email: null,
            password: null,
            remember: false,
        })

        function onFormSubmit() {
            const payload = {
                email: form.email,
                password: form.password,
            };
            authStore.login(payload)
        }

        return {
            onFormSubmit,
            form,
            showPassword,
            trans
        }
    }
});
</script>

<style scoped>
/* Additional custom styles if needed */
</style>