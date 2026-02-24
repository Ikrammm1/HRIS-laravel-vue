
// ============================================================
// store/modules/holidayCalendar.js
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
      const res = await axios.get('/api/setup/holiday-calendars');
      commit('SET_DATAS', res.data.data);
      commit('SET_LOADING', false);
    },
    async process({ commit }, { formData, id }) {
      if (id) {
        const res = await axios.put(`/api/setup/holiday-calendars/${id}`, formData);
        commit('UPDATE_DATA', res.data.data);
      } else {
        const res = await axios.post('/api/setup/holiday-calendars', formData);
        commit('ADD_DATA', res.data.data);
      }
    },
    async remove({ commit }, id) {
      await axios.delete(`/api/setup/holiday-calendars/${id}`);
      commit('DELETE_DATA', id);
    },
  },
};