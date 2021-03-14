import { createWebHistory, createRouter } from 'vue-router';
import middlewares from './middleware';
import Home from '~/pages/Home.vue';
import Login from '~/pages/Login.vue';
import Registration from '~/pages/Registration.vue';
import GameForAuth from '../pages/GameForAuth.vue';

const routes = [
    {
        path: '/',
        component: GameForAuth,
        name: 'gameForAuth',
    },
    {
        path: '/login',
        component: Login,
        name: 'login',
        meta: { needHeader: false },
    },
    {
        path: '/registration',
        component: Registration,
        name: 'register',
        meta: { needHeader: false },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    for (const middleware in middlewares) {
        if (middlewares.hasOwnProperty(middleware)) {
            const status = middlewares[middleware](to, from, next);

            if (status === false) {
                return;
            }
        }
    }

    next();
});

export default router;
