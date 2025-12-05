import { useAuthStore } from '@/stores/auth';
import AuthService from '@/services/AuthService';
// Asumsikan BaseService diimpor dengan benar di AuthService
// Asumsikan router Vue Anda (jika ada) diimpor/diakses di sini jika perlu redireksi

/**
 * Memeriksa token di localStorage, dan jika ada, memuat data pengguna.
 * Jika token ada tetapi gagal ditarik (401), token dianggap tidak valid dan dihapus.
 * * @returns {Promise<boolean>} Resolves true jika pengguna berhasil di autentikasi (token valid), false jika tidak.
 */
export async function loadAuthenticatedUser() {
    const authStore = useAuthStore();
    const authService = new AuthService(); // Inisialisasi service
    
    // 1. Cek apakah ada token yang dimuat dari localStorage saat store diinisialisasi
    if (authStore.token) {
        try {
            console.log("Token ditemukan. Mencoba memuat data pengguna dari /api/users/auth...");
            
            // 2. Permintaan ini secara otomatis akan melekatkan token 
            //    berkat Interceptor Axios yang sudah kita buat.
            const response = await authService.getCurrentUser();
            
            // 3. Jika berhasil, set data pengguna di store
            authStore.setUser(response.data);
            console.log("Pengguna berhasil dimuat dan terautentikasi.");
            return true;
            
        } catch (error) {
            // 4. Jika backend merespons 401 (token expired/invalid), Interceptor akan memicu logout()
            //    Kita hanya perlu memastikan token hilang dan mengalihkan.
            console.error("Gagal memuat pengguna (Token Invalid/Expired). Melakukan cleanup.");
            
            // Panggil logout (walaupun interceptor sudah memanggilnya, ini untuk kepastian)
            authStore.logout(); 
            return false;
        }
    }

    console.log("Tidak ada token ditemukan. Berada dalam status logout.");
    return false;
}