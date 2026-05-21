import axios from 'axios';
window.axios = axios;

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
