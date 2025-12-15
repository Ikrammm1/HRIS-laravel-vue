import axios from 'axios';
import router from '@/router';

const state = {
    token: localStorage.getItem("authToken") || null,
    user: JSON.parse(localStorage.getItem("authUser") || "null"),
    status: false,
    message:null,

};

const mutations = {
    AUTH_REQUEST(state) {
        state.status = "loading";
    },
    AUTH_SUCCESS(state, payload) {
        state.status = payload.status || true;
        state.message = payload.message || "Login successful";
        state.token = payload.token;
        state.user = payload.user;

        // Simpan ke localStorage
        localStorage.setItem("authToken", payload.token);
        localStorage.setItem("authUser", JSON.stringify(payload.user));

        // Set axios header
        axios.defaults.headers.common["Authorization"] = "Bearer " + payload.token;
    },
    AUTH_ERROR(state, payload) {
        state.status = payload.status || false;
        state.message = payload.message || "Login failed";
        state.token = null;
        state.user = null;

        localStorage.removeItem("authToken");
        localStorage.removeItem("authUser");
    },
    LOGOUT(state) {
        state.status = "";
        state.token = null;
        state.user = null;
        state.message = null;

        localStorage.removeItem("authToken");
        localStorage.removeItem("authUser");

        delete axios.defaults.headers.common["Authorization"];
    },
};

const actions = {
    async login({ commit }, credentials) {
        commit("AUTH_REQUEST");
        try {
            const res = await axios.post("/api/auth/login", credentials);

            const token = res.data.token;
            const user = res.data.user;

            commit("AUTH_SUCCESS", { token, user });

            router.push({ name: "dashboard" });

        } catch (error) {
        // Ambil benar-benar error dari API
        const apiError = error.response?.data || {
            message: "Terjadi kesalahan pada server."
        };

        commit("AUTH_ERROR", apiError);

        return apiError; // ⬅️ kembalikan error API ke component
    }
    },

    async fetchUser({ commit, state }) {
        if (!state.token) return;

        axios.defaults.headers.common["Authorization"] =
            "Bearer " + state.token;

        try {
            const res = await axios.get("/api/auth/me");
            commit("AUTH_SUCCESS", { token: state.token, user: res.data });

        } catch (error) {
            commit("LOGOUT");
        }
    },

    async logout({ commit }) {
        try {
            await axios.post("/api/auth/logout");
        } catch {}

        commit("LOGOUT");
        router.push({ name: "login" });
    },
};

const getters = {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user,
    authStatus: (state) => state.status,
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
