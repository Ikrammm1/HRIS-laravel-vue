
// ============================================================
// store/modules/leaveType.js
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
      const res = await axios.get('/api/setup/leave-types');
      commit('SET_DATAS', res.data.data);
      commit('SET_LOADING', false);
    },
    async process({ commit }, { formData, id }) {
      if (id) {
        const res = await axios.put(`/api/setup/leave-types/${id}`, formData);
        commit('UPDATE_DATA', res.data.data);
      } else {
        const res = await axios.post('/api/setup/leave-types', formData);
        commit('ADD_DATA', res.data.data);
      }
    },
    async remove({ commit }, id) {
      await axios.delete(`/api/setup/leave-types/${id}`);
      commit('DELETE_DATA', id);
    },
  },
};