import { createWebHistory, createRouter } from 'vue-router';
import Home from '~/pages/Home.vue';
import Login from '~/pages/Login.vue';
import Registration from '~/pages/Registration.vue';

const routes = [
    {
        path: '/',
        component: Home,
    },
    {
        path: '/login',
        component: Login,
    },
    {
        path: '/registration',
        component: Registration,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
