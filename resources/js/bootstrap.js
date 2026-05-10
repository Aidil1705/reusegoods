import axios from "axios";
window.axios = axios;

<<<<<<< HEAD
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

const scheme = import.meta.env.VITE_REVERB_SCHEME ?? "https";

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: scheme === "https",
    encrypted: scheme === "https",
    disableStats: true,
});
=======
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Real-time: initialize Laravel Echo with Pusher (loaded dynamically)
(async () => {
	try {
		const Pusher = (await import('pusher-js')).default;
		const { default: Echo } = await import('laravel-echo');

		window.Pusher = Pusher;

		window.Echo = new Echo({
			broadcaster: 'pusher',
			key: import.meta.env.VITE_PUSHER_APP_KEY || 'local',
			wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
			wsPort: import.meta.env.VITE_PUSHER_PORT || 6001,
			forceTLS: false,
			disableStats: true,
			enabledTransports: ['ws', 'wss']
		});
	} catch (e) {
		console.warn('Echo/Pusher not initialized:', e?.message || e);
	}
})();
>>>>>>> ba632d81e36ff1a3d09e2e21b0b8364b25ca53b8
