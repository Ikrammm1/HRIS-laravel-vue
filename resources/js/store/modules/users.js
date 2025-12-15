import axios from 'axios';


const state = {
    datas : [],
}

const mutations = {
    LIST_USER(state, data) {
        state.datas = data;
    },
    ADD_USER(state, data){
        state.datas.unshift(data);
    },
    UPDATE_USERS(state,data){
        const index = state.datas.find(item => item.id == data.id)
        if(index != -1){
            state.datas.splice(index, 1, data)
        }
    }
}

const actions = {
    async list({commit}){
        const res = await axios.get('/api/users/list');
        commit("LIST_USER", res.data.datas)
        return res;
    },

    process({commit}, data){
        const transactionType = data.id ? "update" : "add";
        return new Promise((resolve, reject) => {  
            axios.post("/api/users/"+transactionType, data.formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response=>{
                if (data.id) {
                    commit("UPDATE_USER", response.data.data); 
                    // console.log(response.data.datas)// Pastikan struktur data benar
                } else {
                    commit("ADD_USER", response.data.data); // Pastikan struktur data benar
                }   
                resolve(response.data.data);
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