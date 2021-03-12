import { createApp } from 'vue';
import App from './components/App.vue';
import routerSettings from './router/index';
import storeSettings from './store/index';

require('./bootstrap');

const app = createApp(App);

app.use(routerSettings)
    .use(storeSettings)
    .mount('#app');
