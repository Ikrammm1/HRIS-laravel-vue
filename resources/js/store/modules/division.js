import axios from 'axios';


const state = {
    datas : [],
}

const mutations = {
    LIST_ITEM(state, data) {
        state.datas = data;
    },
    ADD_ITEM(state, data){
        state.datas.unshift(data);
    },
    UPDATE_ITEM(state,data){
        const index = state.datas.findIndex(item => item.id === data.id);
        if (index !== -1) {
            state.datas.splice(index, 1, data);
        }
    },
    DELETE(state, id){
        const index = state.datas.findIndex(item => item.id === id);
        if(index != -1){
            state.datas.splice(index, 1);
        }
    }
}

const actions = {
    async list({commit}){
        const res = await axios.get('/api/divisions/list');
        commit("LIST_ITEM", res.data)
        return res;
    },

    process({commit}, data){
        const transactionType = data.id ? "update" : "add";
          const url = data.id 
            ? `/api/divisions/update/${data.id}` 
            : `/api/divisions/add`;
        return new Promise((resolve, reject) => {  
            axios.post(url, data.formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response=>{
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
        axios.delete(`/api/divisions/delete/${data.id}`, {
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
}
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