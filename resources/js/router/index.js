import { createWebHistory, createRouter } from 'vue-router';
import middlewares from './middleware';
import Error404 from '../pages/errors/Error404.vue';

import Home from '~/pages/Home.vue';
import SearchGame from '~/pages/SearchGame.vue';

/* ------------------- Auth ------------------- */
const Login = () => import('~/pages/Login.vue');
const Registration = () => import('~/pages/Registration.vue');
const ForgotPassword = () => import('~/pages/ForgotPassword.vue');

/* ------------------- Pages in header ------------------- */
const ChessRules = () => import('~/pages/ChessRules.vue');

/* ------------------- Pages in view profile -------------------*/
const Settings = () => import('~/pages/Settings.vue');
const Statistics = () => import('~/pages/Statistics.vue');

/* ------------------- Game pages -------------------*/
const Game = () => import('~/pages/Game.vue');

/* ------------------- Other pages ------------------- */
const ViewGame = () => import('~/pages/ViewGame');

/* ------------------- Admin ------------------- */
const Admin = () => import('~/pages/admin/Admin.vue');
const AdminChessRules = () => import('~/pages/admin/AdminChessRules.vue');
const AdminChessRuleNames = () => import('~/pages/admin/AdminChessRuleNames.vue');
const AdminUsers = () => import('~/pages/admin/AdminUsers.vue');
const AdminGames = () => import('~/pages/admin/AdminGames.vue');
const AdminWebsocket = () => import('~/pages/admin/AdminWebsocket.vue');
const AdminViewGame = () => import('~/pages/admin/view/AdminViewGame.vue');
const AdminUsersView = () => import('~/pages/admin/view/AdminUsersView.vue');

const routes = [
    {
        path: '/',
        component: SearchGame,
        name: 'home',
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
        path: '/chess-rules/:rule?',
        component: ChessRules,
        name: 'chessRules',
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
        path: '/statistics',
        component: Statistics,
        name: 'statistics',
        meta: {
            auth: true,
        },
    },
    {
        path: '/view/games/:gameId',
        component: ViewGame,
        name: 'gameReplay',
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
        name: 'registration',
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
                path: 'chess-rules/names',
                component: AdminChessRuleNames,
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
                component: AdminViewGame,
            },
            {
                path: 'view/users/:id',
                component: AdminUsersView,
            },
        ],
        meta: {
            auth: true,
            accessRoles: ['admin', 'moderator', 'support', 'redactor'],
        },
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'pageNotFound',
        component: Error404,
        meta: {
            needHeader: false,
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    let status = true;

    const middlewaresNames = Object.getOwnPropertyNames(middlewares);

    for (let i = 0; i < middlewaresNames.length; i++) {
        const middlewaresName = middlewaresNames[i];
        status = middlewares[middlewaresName](to, from, next);

        if (status === false) {
            next('/');
            return;
        }
    }

    next();
});

export default router;
