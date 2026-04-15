// ============================================================
// store/modules/shift.js
// ============================================================
import axios from 'axios';

export default {
  namespaced: true,
  state: {
    datas: [],
    loading: false,
  },
  mutations: {
    SET_DATAS(state, data) { state.datas = data; },
    SET_LOADING(state, val) { state.loading = val; },
    ADD_DATA(state, item) { state.datas.unshift(item); },
    UPDATE_DATA(state, item) {
      const idx = state.datas.findIndex(d => d.id === item.id);
      if (idx !== -1) state.datas.splice(idx, 1, item);
    },
    DELETE_DATA(state, id) {
      state.datas = state.datas.filter(d => d.id !== id);
    },
  },
  actions: {
    async fetchAll({ commit }) {
      commit('SET_LOADING', true);
      const res = await axios.get('/api/setup/shifts/list');
      commit('SET_DATAS', res.data.data);
      commit('SET_LOADING', false);
    },
    async process({ commit }, { formData, id }) {
      return new Promise((resolve, reject) => {
        axios.post("/api/setup/shifts/" + (id ? `update/${id}` : 'add'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(response => {       
            // commit('SET_PARENTS', response.data)        
            if (id) {
                commit("UPDATE_DATA", response.data.data); 
                // console.log(response.data.datas)// Pastikan struktur data benar
            } else {
                commit("ADD_DATA", response.data.data); // Pastikan struktur data benar
            }   
            resolve(response.data.data);
            })
            .catch(error => {
                reject(error);
            });
        })
    },
    // async remove({ commit }, id) {
    //   await axios.delete(`/api/setup/shifts/delete/${id}`);
    //   commit('DELETE_DATA', id);
    // },
    async remove({commit}, data){
      console.log('Removing item with id:', data.id)
        return new Promise((resolve, reject) => {
        axios.delete(`/api/setup/shifts/delete/${data.id}`, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(response => {       
                commit("DELETE_DATA", data.id);
                resolve(response.data.message);

            })
            .catch(error => {
                reject(error);
            });
        })
    },
  },
};
