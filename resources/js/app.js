import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router/index';
import store from './store/index';

require('./bootstrap');

const app = createApp(App);

app.use(store)
    .use(router);

store.dispatch('FETCH_USER')
    .finally(() => {
        app.mount('#app');
    });
