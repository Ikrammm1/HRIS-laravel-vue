import { createStore } from 'vuex';
import AuthModule from './modules/auth';
import MenuModule from './modules/menu';
import UserModule from './modules/users';
import Autherization from './modules/authorizationGroup';
import Division from './modules/division';
import Department from './modules/department';
import Employe from './modules/employe';

export default createStore({
    state: {
        loading: false,   // <-- tambahkan
    },
    mutations: {
        SET_LOADING(state, value) {   // <-- tambahkan
            state.loading = value;
        },
    },
    actions: {},
    getters: {
        isLoading: state => state.loading
    },

    modules: {
        auth: AuthModule,
        menu: MenuModule,
        users: UserModule,
        authorizationGroup: Autherization,
        divisions: Division,
        departments: Department,
        employe: Employe,
    },
});
