/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.axios = require('axios');

window.qs = require('qs');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.isBoolean = (value) => typeof value === 'boolean';
window.isNumber = (value) => typeof value === 'number';
window.isString = (value) => typeof value === 'string';
window.isObject = (value) => typeof value === 'object';
window.isFunction = (value) => typeof value === 'function';

window.Pusher = require('pusher-js');

function echoLoad() {
    window.echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
    });

    window.echo.connector.pusher.bind('pusher:error', () => {
        echoLoad();
    });
}

echoLoad();
