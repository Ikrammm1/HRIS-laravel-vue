import axios from 'axios';

const state = {
    datas: [],
};

const mutations = {
    LIST_DATA(state, data) {
        state.datas = data;
    },
    ADD_ITEM(state, data) {
        state.datas.unshift(data);
    },
    UPDATE_ITEM(state, data) {
        const index = state.datas.findIndex(item => item.id === data.id);
        if (index !== -1) {
            state.datas.splice(index, 1, data);
        }
    },
    DELETE_ITEM(state, id) {
        const index = state.datas.findIndex(item => item.id === id);
        if (index !== -1) {
            state.datas.splice(index, 1);
        }
    }
};

const actions = {

    // 🔹 LIST GROUP
    async list({ commit }) {
        const res = await axios.get('/api/groups/list');
        commit('LIST_DATA', res.data.datas);
        return res;
    },

    // 🔹 ADD / UPDATE GROUP + PIVOT
    process({ commit }, payload) {
        const isUpdate = !!payload.id;

        const url = isUpdate
            ? `/api/groups/update/${payload.id}` 
            : `/api/groups/add`;

        return new Promise((resolve, reject) => {
            axios.post(url, payload.formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                if (isUpdate) {
                    commit('UPDATE_ITEM', response.data.data);
                } else {
                    commit('ADD_ITEM', response.data.data);
                }

                resolve(response.data);
            })
            .catch(error => {
                reject(error);
            });
        });
    },

    // 🔹 DELETE GROUP
    delete({ commit }, id) {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/groups/delete/${id}`)
                .then(response => {
                    commit('DELETE_ITEM', id);
                    resolve(response.data.message);
                })
                .catch(error => {
                    reject(error);
                });
        });
    }
};

const getters = {
    groups: state => state.datas,
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
