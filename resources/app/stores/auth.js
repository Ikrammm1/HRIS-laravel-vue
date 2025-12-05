import router from "@/router";
import {defineStore} from 'pinia'
import {getResponseError} from "@/helpers/api";
import {useAlertStore} from "@/stores/alert";
import AuthService from "@/services/AuthService";
import UserService from "@/services/UserService";
import {trans} from "@/helpers/i18n";
import axios from "@/plugins/axios";

export const useAuthStore = defineStore("auth", {
    state: () => {
        return {
            user: null,
            error: null,
            loading: false,
        };
    },
    actions: {
        /**
         * Initialize auth state on app startup:
         * - Restore token from localStorage
         * - Set axios Authorization header
         * - Fetch current user if token exists
         */
        // BAGIAN 2: useAuthStore -> actions -> initAuth
async initAuth() {
    const token = window.localStorage.getItem('api_token');
    if (token) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        try {
            await this.getCurrentUser(); 
            
        } catch (error) {
            // Ini menangkap error 401 yang dilempar dari getCurrentUser.
            
            // 🎯 Pastikan kita melakukan cleanup:
            window.localStorage.removeItem('api_token');
            delete axios.defaults.headers.common['Authorization'];
            this.user = null;
            
            // PENTING: JANGAN ada 'throw error' atau 'return Promise.reject(error)' di sini.
            // Dengan membiarkan fungsi berakhir, Promise dari initAuth akan secara implisit
            // menjadi resolved, dan Router Guard tidak akan melihat kegagalan.
        }
    }
    // Jika tidak ada token, atau jika token dibersihkan, Promise akan resolved secara normal.
},
        async login(payload) {
            const authService = new AuthService();
            const alertStore = useAlertStore();
            console.log(payload);
            try {
                const response = await authService.login(payload);
                // Expecting API token in response: { token: 'plain_text_token' }
                const token = response?.data?.token || response?.data?.data?.token;
                if (token) {
                    window.localStorage.setItem('api_token', token);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                }

                alertStore.clear();
                await this.getCurrentUser();
                await router.push("/panel/dashboard");
            } catch (error) {
                alertStore.error(getResponseError(error));
            }
        },
        async register(payload) {
            const authService = new AuthService();
            const alertStore = useAlertStore();
            try {
                const response = await authService.registerUser(payload);
                await router.push("/panel/dashboard");
                alertStore.clear();
            } catch (error) {
                alertStore.error(getResponseError(error));
            }
        },
        // BAGIAN 1: useAuthStore -> actions -> getCurrentUser
async getCurrentUser() {
    this.loading = true;
    const authService = new AuthService();
    
    try {
        const response = await authService.getCurrentUser();
        this.user = response.data.data;
        this.setBrowserData();
    } catch (error) {
        // Jika 401, jangan tampilkan ke UI (tidak atur this.error), tapi re-throw.
        if (error.response && error.response.status === 401) {
            this.user = null;
            throw error; // Lempar kembali ke initAuth
        }
        
        // Untuk error lain (500, jaringan, dll.)
        this.user = null;
        this.error = getResponseError(error); 
        throw error; // Lempar error lain juga
        
    } finally {
        this.loading = false;
    }
},
        updateAvatar(id, payload) {
            const alertStore = useAlertStore();
            const userService = new UserService();
            return new Promise((resolve, reject) => {
                return userService
                    .updateAvatar(id, payload)
                    .then((response) => {
                        this.getCurrentUser().then(() => {
                            alertStore.success(trans('global.phrases.file_uploaded'));
                            resolve(response)
                        });
                    })
                    .catch((err) => {
                        alertStore.error(getResponseError(err));
                        reject(err)
                    })
            })
        },
        logout() {
            return new Promise((resolve, reject) => {
                const alertStore = useAlertStore();
                const authService = new AuthService();
                return authService
                    .logout()
                    .then((response) => {
                        // Remove token and any stored user data
                        window.localStorage.removeItem('api_token');
                        delete axios.defaults.headers.common['Authorization'];
                        this.clearBrowserData();
                        this.user = null;
                        if (router.currentRoute.name !== "login") {
                            router.push({path: "/login"});
                            console.log('logout...');
                        }
                        resolve(response)
                    })
                    .catch((err) => {
                        alertStore.error(getResponseError(err));
                        reject(err)
                    })
            });
        },
        hasBrowserData() {
            // consider user or api_token present
            let data = window.localStorage.getItem('currentUser');
            let token = window.localStorage.getItem('api_token');
            return !!data || !!token;
        },
        setBrowserData() {
            window.localStorage.setItem('currentUser', JSON.stringify(this.user))
        },
        clearBrowserData() {
            window.localStorage.removeItem('currentUser');
        },
        hasAbilities(abilities) {
            return this.user && this.user.hasOwnProperty('abilities') && !!this.user.abilities.find((ab) => {
                if (ab.name === '*') {
                    return true
                }
                if (typeof abilities === 'string') {
                    return ab.name === abilities
                }
                return !!abilities.find((p) => {
                    return ab.name === p
                })
            })
        },

        hasAllAbilities(abilities) {
            let isAvailable = true
            if (this.user && this.user.hasOwnProperty('abilities')) {
                this.user.abilities.filter((ab) => {
                    let hasContain = !!abilities.find((p) => {
                        return ab.name === p
                    })
                    if (!hasContain) {
                        isAvailable = false
                    }
                })
            }

            return isAvailable
        },
    },
    getters: {
        isAdmin: (state) => {
            return state.user ? state.user.is_admin : false;
        },
        loggedIn: (state) => {
            return !!state.user;
        },
        guest: (state) => {
            return !state.hasBrowserData();
        },
    }
});
