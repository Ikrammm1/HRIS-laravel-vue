// resources/js/bootstrap.js
import axios from 'axios';
window.axios = axios;

window.axios.defaults.withCredentials = true; // 🔑 Kunci untuk Sanctum SPA
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Atur URL dasar Laravel API Anda
// window.axios.defaults.baseURL = 'http://api.yourdomain.test';
axios.defaults.baseURL = import.meta.env.APP_URL || 'http://127.0.0.1:8000';