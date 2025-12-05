import axios from "@/plugins/axios"
import type {AxiosInstance} from "axios";

export default abstract class BaseService {

    api: AxiosInstance;
    url: string;

    setupAPI(baseURL) {
        this.api = axios.create({
            baseURL: baseURL,
            withCredentials: true,
        });
        this.api.interceptors.response.use(
            (response) => {
                return response;
            },
                        // Interceptor yang Dimodifikasi (dalam BaseService.ts)
            function (error) {
                let data = window.localStorage.getItem('currentUser');
                
                // Periksa apakah ini adalah endpoint /api/users/auth (atau endpoint otentikasi lain yang menyebabkan 401 saat load awal)
                const isAuthCheckEndpoint = error.config.url.includes('/api/users/auth');

                if (
                    error.response &&
                    [401, 419].includes(error.response.status) &&
                    data // Hanya jika ada data lokal yang menunjukkan pengguna pernah login
                ) {
                    // JANGAN memicu logout/redirect jika:
                    // 1. Ini adalah error 401 yang wajar pada endpoint cek otentikasi awal (isAuthCheckEndpoint)
                    // 2. DAN server TIDAK mengembalikan 419 (419 selalu berarti CSRF expired dan harus ditangani)
                    if (isAuthCheckEndpoint && error.response.status === 401) {
                        // Biarkan error ini ditangani oleh AuthService.getCurrentUser() (untuk mengatur status "Belum Login")
                        return Promise.reject(error);
                    }

                    // Ini adalah 419 (CSRF Expired) ATAU 401 pada endpoint LAIN (Sesi expired)
                    console.warn("Sesi expired atau tidak valid. Melakukan logout otomatis.");
                    let tmpAxios = axios.create({
                        withCredentials: true,
                    });
                    tmpAxios.post("/logout").then((r) => {
                        window.location.href = '/';
                    })
                }
                return Promise.reject(error);
            },
        )
    }


    protected post(url, data, config = {}) {
        if(!config) {
            config = {};
        }
        if(data instanceof FormData) {
            config = this.setHeaders(config);
        }
        return this.api.post(url, data, config);
    }

    protected put(url, data, config = {}) {
        if(!config) {
            config = {};
        }
        if(data instanceof FormData) {
            config = this.setHeaders(config);
            data.append('_method', 'PUT');
        } else {
            data._method = 'PUT';
        }
        return this.api.post(url, data, config);
    }

    protected patch(url, data, config = {}) {
        if(!config) {
            config = {};
        }
        if(data instanceof FormData) {
            config = this.setHeaders(config);
            data.append('_method', 'PATCH');
        } else {
            data._method = 'PATCH';
        }
        return this.api.post(url, data, config);
    }

    protected get(url, config = {}) {
        return this.api.get(url, config);
    }

    protected delete(url, config = {}) {
        return this.api.delete(url, config);
    }

    protected setHeaders(config) {
        if(!config) {
            config = {};
        }
        if(!config?.headers) {
            config.headers = {};
        }
        config.headers['Content-Type'] = 'multipart/form-data';
        return config;
    }
}

