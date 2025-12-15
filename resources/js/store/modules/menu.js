// src/store/modules/menu.js (atau path yang sesuai)

import axios from 'axios';

// Definisikan state awal
const state = {
    // Menu yang diotorisasi, dimuat dari API
    authorizedMenu: JSON.parse(localStorage.getItem("authorizedMenu") || "[]"), 
    // Status pemuatan
    status: "", 
    menus: [],
    // Menyimpan error jika ada
    error: null,
};

const mutations = {
    LIST_MENU(state, menuData) {
        state.menus = menuData;
    },
    MENU_REQUEST(state) {
        state.status = "loading";
        state.error = null;
    },
    MENU_SUCCESS(state, menuData) {
        state.status = "success";
        state.authorizedMenu = menuData;
        
        // Simpan menu ke localStorage agar tetap ada setelah refresh
        localStorage.setItem("authorizedMenu", JSON.stringify(menuData));
    },
    MENU_ERROR(state, errorMessage) {
        state.status = "error";
        state.error = errorMessage;
        state.authorizedMenu = [];
        
        // Hapus menu dari localStorage jika terjadi error/gagal
        localStorage.removeItem("authorizedMenu");
    },
    CLEAR_MENU(state) {
        state.authorizedMenu = [];
        state.status = "";
        state.error = null;
        
        localStorage.removeItem("authorizedMenu");
    },
    ADD_ITEM(state, newMenu) {
        state.menus.unshift(newMenu);
    },
    UPDATE_ITEM(state, updatedMenu) {
        const index = state.menus.findIndex(menu => menu.id === updatedMenu.id);
        if (index !== -1) {
            state.menus.splice(index, 1, updatedMenu);
        }
    },

    DELETE(state, id){
        const index = state.menus.findIndex(menu => menu.id == id);
        console.log(id)
        console.log(index)
        if(index != -1){
            state.menus.splice(index, 1);
        }
    }
};

const actions = {
    /**
     * Mengambil menu yang diotorisasi dari API backend.
     */
    async menuList({ commit }) {
        // Hanya jalankan request jika tidak sedang loading
        const response = await axios.post("/api/menu/list");
        commit("LIST_MENU", response.data.data);
        return response;
    },
    async process({ commit }, data) {
        const transactionType = data.id ? "update" : "add";
        // console.log(transactionType)

        return new Promise((resolve, reject) => {
        axios.post("/api/menu/" + transactionType, data.formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(response => {       
            // commit('SET_PARENTS', response.data)        
            if (data.id) {
                commit("UPDATE_ITEM", response.data.data); 
                // console.log(response.data.datas)// Pastikan struktur data benar
            } else {
                commit("ADD_ITEM", response.data.data); // Pastikan struktur data benar
            }   
            resolve(response.data.data);
            })
            .catch(error => {
                reject(error);
            });
        })
    },

    async delete({commit}, data){
        return new Promise((resolve, reject) => {
        axios.post("/api/menu/delete", data.formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(response => {       
                commit("DELETE", data.id);
                resolve(response.data.message);

            })
            .catch(error => {
                reject(error);
            });
        })
    },
    /**
     * Mengambil menu yang diotorisasi dari API backend.
     */
    async fetchAuthorizedMenu({ commit }) {
        // Hanya jalankan request jika tidak sedang loading
        if (state.status === "loading") return;

        commit("MENU_REQUEST");
        
        try {
            // Ganti endpoint API Anda
            const res = await axios.post("/api/menu/authorize-menu");
            
            // Asumsikan struktur respons API: { data: [menu_array] }
            const menuData = res.data.data;

            commit("MENU_SUCCESS", menuData);
            localStorage.setItem("authorizedMenu", JSON.stringify(menuData));

        } catch (error) {
            console.error("Error fetching menu:", error);
            const errorMessage = error.response?.data?.message || "Gagal memuat menu dari server.";
            
            commit("MENU_ERROR", errorMessage);
            
            // Opsional: Jika Anda ingin melempar error, tambahkan 'throw error;'
        }
    },
    
    /**
     * Menghapus menu dari state dan localStorage (berguna saat logout)
     */
    clearMenu({ commit }) {
        commit('CLEAR_MENU');
    }
};

const getters = {
    /**
     * Mendapatkan array menu yang diotorisasi untuk ditampilkan di sidebar
     */
    authorizedMenu: (state) => state.authorizedMenu,
    
    /**
     * Status pemuatan menu (loading, success, error)
     */
    menuStatus: (state) => state.status,
    
    /**
     * Mendapatkan pesan error jika terjadi kegagalan pemuatan
     */
    menuError: (state) => state.error,
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};