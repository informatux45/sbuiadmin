/*
 * Vanilla-JS replacement for jQuery jConfirm, matching Adminator's visual
 * language. Usage: add data-confirm="message" (and optionally
 * data-confirm-ok="Label"/data-confirm-cancel="Label") to any <a href="...">
 * — click is intercepted, and navigation only happens if the user confirms.
 * Self-contained: injects its own styles, no separate CSS file needed.
 */
(function () {
	var STYLE = '\
.sb-confirm-backdrop{align-items:center;background:var(--overlay);display:flex;inset:0;justify-content:center;position:fixed;z-index:1000}\
.sb-confirm-box{animation:sb-confirm-in .16s ease both;background:var(--bg-card);border:1px solid var(--border);border-radius:14px;box-shadow:var(--shadow-lg);max-width:360px;padding:22px;width:90%}\
.sb-confirm-message{color:var(--t-base);font-size:14px;line-height:1.5;margin-bottom:18px}\
.sb-confirm-actions{display:flex;gap:10px;justify-content:flex-end}\
@keyframes sb-confirm-in{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}\
';

	function injectStyle() {
		if (document.getElementById('sb-confirm-style')) return;
		var s = document.createElement('style');
		s.id = 'sb-confirm-style';
		s.textContent = STYLE;
		document.head.appendChild(s);
	}

	function showConfirm(message, okLabel, cancelLabel) {
		injectStyle();
		return new Promise(function (resolve) {
			var backdrop = document.createElement('div');
			backdrop.className = 'sb-confirm-backdrop';
			backdrop.innerHTML =
				'<div class="sb-confirm-box" role="alertdialog" aria-modal="true">' +
					'<div class="sb-confirm-message"></div>' +
					'<div class="sb-confirm-actions">' +
						'<button type="button" class="btn btn--ghost" data-sb-cancel></button>' +
						'<button type="button" class="btn btn--primary" data-sb-ok></button>' +
					'</div>' +
				'</div>';
			backdrop.querySelector('.sb-confirm-message').textContent = message;
			backdrop.querySelector('[data-sb-cancel]').textContent = cancelLabel;
			backdrop.querySelector('[data-sb-ok]').textContent = okLabel;
			document.body.appendChild(backdrop);

			var okBtn = backdrop.querySelector('[data-sb-ok]');
			okBtn.focus();

			function cleanup(result) {
				document.body.removeChild(backdrop);
				document.removeEventListener('keydown', onKey);
				resolve(result);
			}
			function onKey(e) {
				if (e.key === 'Escape') cleanup(false);
				if (e.key === 'Enter') cleanup(true);
			}

			document.addEventListener('keydown', onKey);
			backdrop.addEventListener('click', function (e) {
				if (e.target === backdrop) cleanup(false);
			});
			backdrop.querySelector('[data-sb-cancel]').addEventListener('click', function () { cleanup(false); });
			okBtn.addEventListener('click', function () { cleanup(true); });
		});
	}

	document.addEventListener('click', function (e) {
		var el = e.target.closest('[data-confirm]');
		if (!el) return;
		e.preventDefault();

		var message = el.getAttribute('data-confirm') || 'Êtes-vous sûr de vouloir continuer ?';
		var okLabel = el.getAttribute('data-confirm-ok') || 'OK';
		var cancelLabel = el.getAttribute('data-confirm-cancel') || 'Annuler';

		showConfirm(message, okLabel, cancelLabel).then(function (ok) {
			if (!ok) return;
			var href = el.getAttribute('href');
			if (href) window.location.href = href;
		});
	});
})();
