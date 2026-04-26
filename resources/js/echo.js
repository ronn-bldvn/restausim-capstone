import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const key = import.meta.env.VITE_PUSHER_APP_KEY;
const cluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;

if (key && cluster) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key,
        cluster,
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
        withCredentials: true,
    });
}
