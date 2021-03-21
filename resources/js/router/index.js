import { createWebHistory, createRouter } from 'vue-router';
import middlewares from './middleware';
import Home from '~/pages/Home.vue';
import Login from '~/pages/Login.vue';
import Registration from '~/pages/Registration.vue';
import Game from '~/pages/Game.vue';
import SearchGame from '~/pages/SearchGame.vue';

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
