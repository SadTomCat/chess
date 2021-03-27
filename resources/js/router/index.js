import { createWebHistory, createRouter } from 'vue-router';
import middlewares from './middleware';
import Home from '~/pages/Home.vue';
import Login from '~/pages/Login.vue';
import Registration from '~/pages/Registration.vue';
import Game from '~/pages/Game.vue';
import SearchGame from '~/pages/SearchGame.vue';
import ForgotPassword from '~/pages/ForgotPassword.vue';
import Settings from '~/pages/Settings.vue';

const routes = [
    {
        path: '/',
        component: SearchGame,
        name: 'searchGame',
    },
    {
        path: '/game/:token',
        component: Game,
        name: 'game',
        meta: {
            auth: true,
        },
    },
    {
        path: '/rating',
        component: Home,
        name: 'rating',
    },
    {
        path: '/rules',
        component: Home,
        name: 'rules',
    },
    {
        path: '/support',
        component: Home,
        name: 'support',
    },
    {
        path: '/settings',
        component: Settings,
        name: 'settings',
        meta: {
            auth: true,
        },
    },
    {
        path: '/statistic',
        component: Home,
        name: 'statistic',
        meta: {
            auth: true,
        },
    },
    {
        path: '/login',
        component: Login,
        name: 'login',
        meta: {
            needHeader: false,
            guest: true,
        },
    },
    {
        path: '/registration',
        component: Registration,
        name: 'register',
        meta: {
            needHeader: false,
            guest: true,
        },
    },
    {
        path: '/forgot-password',
        component: ForgotPassword,
        name: 'forgotPassword',
        meta: {
            needHeader: false,
            guest: true,
        },
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
