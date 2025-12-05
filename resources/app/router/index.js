import {createWebHistory, createRouter} from "vue-router";

import routes from "@/router/routes";

import {useAuthStore} from "@/stores/auth";

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes,
})

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    const requiresAbility = to?.meta?.requiresAbility;
    const requiresAuth = to?.meta?.requiresAuth;
    const belongsToOwnerOnly = to?.meta?.isOwner;

    // 🎯 PERBAIKAN UTAMA: Bungkus panggilan async dengan try...catch
    if (!authStore.user) {
        try {
            // Memanggil action di Pinia store, yang memanggil AuthService.getCurrentUser()
            await authStore.getCurrentUser();
        } catch (error) {
            // Error 401 DITANGKAP DI SINI.
            // Karena error ditangkap, error tersebut TIDAK AKAN diteruskan
            // ke konsol sebagai "Uncaught Promise Rejection" / "AxiosError 401".
            // Logic di authStore.getCurrentUser() sudah mengatur authStore.user = null
            // jika statusnya 401, jadi kita tidak perlu melakukan apa-apa lagi di sini.
        }
    }
    
    // --- Logika Navigasi ---

    // 1. Cek jika pengguna belum terotentikasi dan halaman memerlukan otentikasi
    if (!authStore.user) {
        authStore.clearBrowserData();
        if(requiresAuth) {
            next({name: 'login'})
            return; // Penting: Hentikan alur Guard
        }
    }

    // 2. Cek jika pengguna sudah login tetapi mencoba mengakses halaman otentikasi publik
    if(to?.meta?.isPublicAuthPage && authStore.user) {
        next({name: 'dashboard'})
        return;
    }

    // 3. Cek Otorisasi (Ability)
    if (requiresAbility && requiresAuth) {
        if (authStore.hasAbilities(requiresAbility)) {
            next()
        } else {
            next({
                name: 'profile' // Ganti dengan nama rute 'unauthorized' jika ada
            })
        }
    } 
    // 4. Cek Kepemilikan (Owner)
    else if (belongsToOwnerOnly) {
        if (authStore.user.is_owner) {
            next()
        } else {
            next({name: 'dashboard'})
        }
    } 
    // 5. Default: Lanjutkan
    else {
        next()
    }
})

export default router;