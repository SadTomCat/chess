import { createApp } from 'vue';
import App from './components/App.vue';
import routerSettings from './router/index';
import storeSettings from './store/index';

require('./bootstrap');

const app = createApp(App);

app.use(storeSettings)
    .use(routerSettings)
    .mount('#app');
