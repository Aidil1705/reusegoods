import './bootstrap';

// Realtime chat listener: append incoming messages when on a conversation page
if (typeof window !== 'undefined' && window.CONVERSATION_ID && window.Echo) {
	const convId = window.CONVERSATION_ID;
	const userId = window.CURRENT_USER_ID || null;

	window.Echo.private(`chat.${convId}`)
		.listen('MessageSent', (e) => {
			try {
				const container = document.getElementById('messagesContainer');
				if (!container) return;

				const isCurrentUser = userId && e.message && e.message.sender && (e.message.sender.id == userId);

				const wrapper = document.createElement('div');
				wrapper.className = `mb-4 ${isCurrentUser ? 'text-right' : 'text-left'}`;

				const bubble = document.createElement('div');
				bubble.className = `inline-block ${isCurrentUser ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-800'} rounded-2xl px-4 py-2 max-w-xs`;

				const p = document.createElement('p');
				p.className = 'text-sm';
				p.textContent = e.message.message || e.message;

				const meta = document.createElement('p');
				meta.className = `text-xs ${isCurrentUser ? 'text-emerald-100' : 'text-gray-600'} mt-1`;
				meta.textContent = new Date(e.message.created_at).toLocaleTimeString();

				bubble.appendChild(p);
				bubble.appendChild(meta);
				wrapper.appendChild(bubble);

				container.appendChild(wrapper);
				container.scrollTop = container.scrollHeight;
			} catch (err) {
				console.error('Error handling MessageSent:', err);
			}
		});
}
