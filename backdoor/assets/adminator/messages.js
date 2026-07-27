/*
 * Messagerie interne 1-à-1 (Point 13) : pas d'infra WebSocket disponible sur
 * cet hébergement, donc "temps réel" = polling AJAX authentifié à travers le
 * routeur normal (index.php?p=messages), jamais un endpoint standalone.
 * - Badge de non-lus : pollé sur TOUTES les pages admin (léger, ~15s).
 * - Fil de discussion ouvert : pollé plus vite (~4s) uniquement sur
 *   messages.php. Les messages reçus en JSON sont insérés via textContent
 *   (jamais innerHTML) pour éviter toute XSS stockée entre utilisateurs.
 */
(function () {
	var POLL_UNREAD_MS = 15000;
	var POLL_THREAD_MS = 4000;

	function pad2(n) {
		return ('0' + n).slice(-2);
	}

	function formatDate(ts) {
		var d = new Date(ts * 1000);
		return pad2(d.getDate()) + '/' + pad2(d.getMonth() + 1) + ' ' + pad2(d.getHours()) + ':' + pad2(d.getMinutes());
	}

	function updateUnreadBadges(count) {
		document.querySelectorAll('.sb-messages-badge').forEach(function (el) {
			if (count > 0) {
				el.textContent = count;
				el.style.display = '';
			} else {
				el.style.display = 'none';
			}
		});
	}

	function pollUnread() {
		fetch('index.php?p=messages&ajax=unread', { credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (data) { updateUnreadBadges(data.count || 0); })
			.catch(function () {});
	}

	function initUnreadPolling() {
		pollUnread();
		setInterval(pollUnread, POLL_UNREAD_MS);
	}

	function initThread() {
		var shell = document.getElementById('sbMessagesShell');
		if (!shell) return;

		var withId = parseInt(shell.getAttribute('data-with'), 10) || 0;
		var thread = document.getElementById('sbMessagesThread');
		if (!withId || !thread) return;

		var baseUrl = shell.getAttribute('data-url');
		var input   = document.getElementById('sbMessagesInput');
		var sendBtn = document.getElementById('sbMessagesSend');

		function lastCreatedAt() {
			var msgs = thread.querySelectorAll('.chat-msg');
			if (!msgs.length) return 0;
			return parseInt(msgs[msgs.length - 1].getAttribute('data-created-at'), 10) || 0;
		}

		function appendMessage(msg) {
			var wrap = document.createElement('div');
			wrap.className = 'chat-msg' + (msg.mine ? ' me' : '');
			wrap.setAttribute('data-id', msg.id);
			wrap.setAttribute('data-created-at', msg.created_at);

			var stack = document.createElement('div');
			stack.className = 'chat-msg-stack';

			var bub = document.createElement('div');
			bub.className = 'chat-bub';
			bub.style.whiteSpace = 'pre-wrap';
			bub.textContent = msg.message; // jamais innerHTML : message = texte libre d'un autre utilisateur

			var meta = document.createElement('div');
			meta.className = 'chat-msg-meta';
			meta.textContent = formatDate(msg.created_at);

			stack.appendChild(bub);
			stack.appendChild(meta);
			wrap.appendChild(stack);
			thread.appendChild(wrap);
			thread.scrollTop = thread.scrollHeight;
		}

		function pollThread() {
			fetch(baseUrl + '&ajax=thread&with=' + withId + '&since=' + lastCreatedAt(), { credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (data) { (data.messages || []).forEach(appendMessage); })
				.catch(function () {});
		}

		function sendMessage() {
			var text = input.value.trim();
			if (!text) return;
			input.value = '';
			input.disabled = true;

			fetch(baseUrl + '&ajax=send&with=' + withId, {
				method: 'POST',
				credentials: 'same-origin',
				body: text,
			})
				.then(function (r) { return r.json(); })
				.then(function (data) {
					if (data.ok && data.message) appendMessage(data.message);
				})
				.catch(function () {})
				.then(function () {
					input.disabled = false;
					input.focus();
				});
		}

		if (sendBtn) sendBtn.addEventListener('click', sendMessage);
		if (input) {
			input.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' && !e.shiftKey) {
					e.preventDefault();
					sendMessage();
				}
			});
		}

		var emojiMenu = document.getElementById('sbMessagesEmojiMenu');
		if (emojiMenu && input) {
			emojiMenu.addEventListener('click', function (e) {
				var btn = e.target.closest('.sb-emoji-item');
				if (!btn) return;
				e.preventDefault();

				var start = input.selectionStart != null ? input.selectionStart : input.value.length;
				var end   = input.selectionEnd != null ? input.selectionEnd : input.value.length;
				var emoji = btn.textContent;

				input.value = input.value.slice(0, start) + emoji + input.value.slice(end);
				input.focus();
				input.setSelectionRange(start + emoji.length, start + emoji.length);
			});
		}

		thread.scrollTop = thread.scrollHeight;
		setInterval(pollThread, POLL_THREAD_MS);
	}

	document.addEventListener('DOMContentLoaded', function () {
		initUnreadPolling();
		initThread();
	});
})();
