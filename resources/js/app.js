import '../css/app.css';
import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

// --- Import Font Awesome ---
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// Import ikon yang Anda butuhkan (Contoh: home, user, angle-down)
import { faHome, faUser, faAngleDown, faAngleUp, faUserTie } from '@fortawesome/free-solid-svg-icons';
import VueGoodTablePlugin from 'vue-good-table-next';
import 'vue-good-table-next/dist/vue-good-table-next.css';

library.add(faHome, faUser, faAngleDown, faAngleUp, faUserTie);

const app = createApp(App);

// ⬅ Tambahkan ini: ambil user dulu sebelum router jalan
store.dispatch("auth/fetchUser").finally(() => {
    app.use(router);
    app.use(store);

    // Daftarkan Komponen Font Awesome secara global
    app.component('Icon', FontAwesomeIcon); // Catatan: Gunakan nama 'font-awesome-icon' atau 'Icon' (sesuai yang Anda gunakan)
    app.use(VueGoodTablePlugin);
    app.mount('#app');
});
