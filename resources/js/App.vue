<script setup>
import { onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import {
  VueSpinner,
} from 'vue3-spinners';
// Import komponen UI dasar Anda di sini (misalnya Header, Sidebar, Footer)
// import TheHeader from './Components/Layout/TheHeader.vue'; 
// import TheSidebar from './Components/Layout/TheSidebar.vue';

const store = useStore();
const route = useRoute();

// Ambil status otentikasi dari Vuex Store
const isAuthenticated = computed(() => store.getters['auth/isAuthenticated']);
const authStatus = computed(() => store.getters['auth/authStatus']);

// Cek apakah rute saat ini adalah rute layout penuh (misalnya, Login/Register mungkin tidak butuh sidebar)
const requiresFullLayout = computed(() => !route.meta.fullPage);

// ----------------------------------------------------
// 💡 Inisialisasi: Periksa Status User Saat Aplikasi Dimuat
// ----------------------------------------------------
onMounted(() => {
    // Panggil action Vuex untuk mencoba memuat data pengguna dari API 
    // (misalnya, jika cookie Sanctum masih ada dari sesi sebelumnya)
    console.log('App Mounted: Attempting to fetch user status...');
    store.dispatch('auth/fetchUser'); 
});

// ----------------------------------------------------
// 💡 Helper untuk Loading State (opsional)
// ----------------------------------------------------
// Jika Anda ingin menampilkan layar loading saat status masih 'loading' (misalnya saat fetchUser berjalan)
const isLoading = computed(() => store.getters.isLoading);



</script>

<template>
    <!-- Global Loading Overlay -->
    <div v-if="isLoading" class="loading-overlay">
        <VueSpinner size="50" color="#10b981" /> <!-- warna hijau -->
    </div>

    <!-- Page Content -->
    <div :class="{ 'app-layout': requiresFullLayout }">
        <div class="main-content-wrapper">
            <main class="main-view">
                <router-view v-slot="{ Component }">
                    <!-- <transition name="fade" mode="out-in"> -->
                        <component :is="Component" />
                    <!-- </transition> -->
                </router-view>
            </main>
        </div>
    </div>
</template>


<style>
/* CSS Dasar untuk Aplikasi */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.7); /* transparan */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 99999; /* paling depan */
}

/* Transisi */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Layout dasar */
.app-layout {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.main-content-wrapper {
    flex-grow: 1;
    display: flex;
}

.main-view {
    flex-grow: 1;
}


</style>