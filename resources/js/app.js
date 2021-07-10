import { createApp } from 'vue';
import CKEditor from '@ckeditor/ckeditor5-vue';
import App from './components/App.vue';
import router from './router/index';
import store from './store/index';

require('./bootstrap');

const app = createApp(App);

app.config.errorHandler = (err, vm, info) => {
    console.error(
        `Message - ${err.message}
        \nInfo - ${info}
        \n\nStack - ${err.stack}`,
    );

    return true;
};

store.dispatch('FETCH_USER')
    .finally(async () => {
        const serverTime = await window.axios.get('/api/time').then((res) => res.data.time);
        store.state.timeDifference = serverTime - Math.floor(Date.now() / 1000);

        app.use(store)
            .use(router)
            .use(CKEditor);

        app.mount('#app');
    });
