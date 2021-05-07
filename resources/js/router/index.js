import { createWebHistory, createRouter } from 'vue-router';
import middlewares from './middleware';
import Home from '~/pages/Home.vue';
import Login from '~/pages/Login.vue';
import Registration from '~/pages/Registration.vue';
import Game from '~/pages/Game.vue';
import SearchGame from '~/pages/SearchGame.vue';
import ForgotPassword from '~/pages/ForgotPassword.vue';
import Settings from '~/pages/Settings.vue';
import Admin from '~/pages/admin/Admin.vue';
import AdminChessRules from '~/pages/admin/AdminChessRules.vue';
import AdminChessRulesCategories from '~/pages/admin/AdminChessRulesCategories.vue';
import AdminUsers from '~/pages/admin/AdminUsers.vue';
import AdminGames from '~/pages/admin/AdminGames.vue';
import AdminWebsocket from '~/pages/admin/AdminWebsocket.vue';
import AdminGamesView from '../pages/admin/view/AdminGamesView';
import AdminUsersView from '../pages/admin/view/AdminUsersView';

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
    {
        path: '/admin',
        component: Admin,
        children: [
            {
                path: 'chess-rules',
                component: AdminChessRules,
            },
            {
                path: 'chess-rules-categories',
                component: AdminChessRulesCategories,
            },
            {
                path: 'users',
                component: AdminUsers,
            },
            {
                path: 'games',
                component: AdminGames,
            },
            {
                path: 'websockets',
                component: AdminWebsocket,
            },
            {
                path: 'view/games/:id',
                component: AdminGamesView,
            },
            {
                path: 'view/users/:id',
                component: AdminUsersView,
            },
        ],
        meta: {
            auth: true,
            admin: true,
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
