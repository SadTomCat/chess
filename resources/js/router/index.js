import { createWebHistory, createRouter } from 'vue-router';
import middlewares from './middleware';

const Home = () => import('~/pages/Home.vue');
const Rules = () => import('~/pages/Rules.vue');
const Login = () => import('~/pages/Login.vue');
const Registration = () => import('~/pages/Registration.vue');
const Game = () => import('~/pages/Game.vue');
const SearchGame = () => import('~/pages/SearchGame.vue');
const ForgotPassword = () => import('~/pages/ForgotPassword.vue');
const Settings = () => import('~/pages/Settings.vue');
const Statistics = () => import('~/pages/Statistics.vue');
const ViewGame = () => import('~/pages/ViewGame');
const Admin = () => import('~/pages/admin/Admin.vue');
const AdminChessRules = () => import('~/pages/admin/AdminChessRules.vue');
const AdminChessRuleCategories = () => import('~/pages/admin/AdminChessRuleCategories.vue');
const AdminUsers = () => import('~/pages/admin/AdminUsers.vue');
const AdminGames = () => import('~/pages/admin/AdminGames.vue');
const AdminWebsocket = () => import('~/pages/admin/AdminWebsocket.vue');
const AdminViewGame = () => import('~/pages/admin/view/AdminViewGame.vue');
const AdminUsersView = () => import('~/pages/admin/view/AdminUsersView.vue');

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
        path: '/rules/:rule?',
        component: Rules,
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
        path: '/statistics',
        component: Statistics,
        name: 'statistic',
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
                path: 'chess-rule-categories',
                component: AdminChessRuleCategories,
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
