import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router/index';
import store from './store/index';

require('./bootstrap');

const app = createApp(App);

store.dispatch('FETCH_USER')
    .finally(async () => {
        const serverTime = await window.axios.get('/api/time').then((res) => res.data.time);
        store.state.timeDifference = serverTime - Math.floor(Date.now() / 1000);

        app.use(store)
            .use(router);

        app.mount('#app');
    });
