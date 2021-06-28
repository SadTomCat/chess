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

    auth: (to, from, next) => {
        if (to.meta.auth === undefined) {
            return true;
        }

        if (store.state.user.logged === false) {
            next('/login');
            return false;
        }

        return true;
    },

    authorize: (to, from, next) => {
        const { role } = store.state.user;
        const { accessRoles } = to.meta;

        if (accessRoles === undefined) {
            return true;
        }

        if (store.state.user.logged === false) {
            next('/login');
            return false;
        }

        return accessRoles.indexOf(role) > -1;
    },

    guest: (to, from, next) => {
        if (to.meta.guest === undefined) {
            return true;
        }

        if (store.state.user.logged === true) {
            next('/');
            return false;
        }

        return true;
    },
};
