/*
 * Vanilla-JS replacement for the browser's native alert(), matching
 * Adminator's visual language (same spirit as confirm.js/modal.js).
 * Usage: sbToast("message") or sbToast("message", "success"|"error"|"info").
 * Optional action link: sbToast("message", "error", "href", "label").
 * Self-contained: injects its own styles, no separate CSS file needed.
 */
(function () {
	var STYLE = '\
.sb-toast-stack{top:24px;display:flex;flex-direction:column;gap:10px;position:fixed;right:24px;z-index:1100}\
.sb-toast{align-items:flex-start;animation:sb-toast-in .18s ease both;background:var(--bg-card);border:1px solid var(--border);border-left:3px solid var(--info);border-radius:10px;box-shadow:var(--shadow-lg);color:var(--t-base);display:flex;font-size:13px;gap:10px;line-height:1.5;max-width:360px;padding:14px 16px;white-space:pre-line}\
.sb-toast.success{border-left-color:var(--success)}\
.sb-toast.error{border-left-color:var(--danger)}\
.sb-toast-msg{flex:1}\
.sb-toast-link{color:inherit;display:inline-block;font-weight:600;margin-top:4px;text-decoration:underline}\
.sb-toast-close{background:none;border:0;color:var(--t-muted);cursor:pointer;flex-shrink:0;font-size:16px;line-height:1;padding:0}\
.sb-toast-close:hover{color:var(--t-base)}\
.sb-toast.is-leaving{animation:sb-toast-out .16s ease both}\
@keyframes sb-toast-in{from{opacity:0;transform:translate(-60px,-8px)}to{opacity:1;transform:translate(0,0)}}\
@keyframes sb-toast-out{from{opacity:1;transform:translate(0,0)}to{opacity:0;transform:translate(-60px,-8px)}}\
';

	function injectStyle() {
		if (document.getElementById('sb-toast-style')) return;
		var s = document.createElement('style');
		s.id = 'sb-toast-style';
		s.textContent = STYLE;
		document.head.appendChild(s);
	}

	function getStack() {
		var stack = document.querySelector('.sb-toast-stack');
		if (!stack) {
			stack = document.createElement('div');
			stack.className = 'sb-toast-stack';
			document.body.appendChild(stack);
		}
		return stack;
	}

	window.sbToast = function (message, type, actionHref, actionLabel) {
		injectStyle();

		var toast = document.createElement('div');
		toast.className = 'sb-toast' + (type ? ' ' + type : '');
		toast.setAttribute('role', 'alert');

		var msg = document.createElement('div');
		msg.className = 'sb-toast-msg';
		msg.textContent = message;
		toast.appendChild(msg);

		if (actionHref && actionLabel) {
			msg.appendChild(document.createElement('br'));
			var link = document.createElement('a');
			link.className = 'sb-toast-link';
			link.href = actionHref;
			link.textContent = actionLabel;
			msg.appendChild(link);
		}

		var close = document.createElement('button');
		close.type = 'button';
		close.className = 'sb-toast-close';
		close.setAttribute('aria-label', 'Fermer');
		close.innerHTML = '&times;';
		toast.appendChild(close);

		var timer = null;

		function remove() {
			clearTimeout(timer);
			toast.classList.add('is-leaving');
			toast.addEventListener('animationend', function () { toast.remove(); }, { once: true });
		}
		close.addEventListener('click', remove);

		// Survol = pause du décompte, sans le remettre à zéro à la sortie.
		var duration  = window.SB_TOAST_DURATION || 7000;
		var remaining = duration;
		var startedAt = 0;

		function start() {
			startedAt = Date.now();
			timer = setTimeout(remove, remaining);
		}
		toast.addEventListener('mouseenter', function () {
			clearTimeout(timer);
			remaining -= Date.now() - startedAt;
		});
		toast.addEventListener('mouseleave', start);

		getStack().appendChild(toast);
		start();
	};
})();
