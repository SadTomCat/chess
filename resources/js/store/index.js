import { createStore } from 'vuex';
import userLoggedRequest from '~/api/userLoggedRequest';
import gameModule from './modules/gameModule';
import searchInTableModule from './modules/searchInTableModule';

export default createStore({
    state: () => ({
        headerActive: true,

        timeDifference: 0,

        user: {
            id: '',
            name: '',
            email: '',
            blocked: '',
            role: '',
            logged: false,
        },
    }),

    getters: {
        HEADER_ACTIVE: (state) => state.headerActive,

        USER: (state) => state.user,
    },

    mutations: {
        CHANGE_HEADER_STATUS: (state, payload = true) => {
            state.headerActive = payload;
        },

        SET_USER: (state, user) => {
            state.user = user;
        },

        UNSET_USER: (state) => {
            state.user = {
                id: '',
                name: '',
                email: '',
                blocked: '',
                role: '',
                logged: false,
            };
        },

        UPDATE_USER: (state, user) => {
            Object.getOwnPropertyNames(user).forEach((field) => {
                state.user[field] = user[field];
            });
        },
    },

    actions: {
        FETCH_USER: async (context) => {
            const user = await userLoggedRequest();

            if (user === false) {
                context.commit('UNSET_USER');
            } else {
                context.commit('SET_USER', user);
            }
        },
    },

    modules: {
        game: gameModule,
        searchInTable: searchInTableModule,
    },
});
