import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router/index';
import store from './store/index';

require('./bootstrap');

const app = createApp(App);

store.dispatch('FETCH_USER')
    .finally(() => {
        app.use(store)
            .use(router);
        
        app.mount('#app');
    });
