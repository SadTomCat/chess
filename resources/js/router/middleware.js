import store from '~/store/index';

/*
* If in middleware next is called need return false
* */

export default {
    headerMiddleware: (to) => {
        if (to.matched.some((record) => record.meta.needHeader === false)) {
            store.commit('CHANGE_HEADER_STATUS', false);
        } else {
            store.commit('CHANGE_HEADER_STATUS');
        }
    },
};
