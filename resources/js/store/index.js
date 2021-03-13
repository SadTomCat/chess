import { createStore } from 'vuex';

export default createStore({
    state: {
        headerActive: true,
    },
    getters: {
        HEADER_ACTIVE: (state) => state.headerActive,
    },
    mutations: {
        CHANGE_HEADER_STATUS: (state, payload = true) => {
            state.headerActive = payload;
        },
    },
});
