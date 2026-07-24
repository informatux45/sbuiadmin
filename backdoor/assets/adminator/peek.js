/*
 * Vanilla-JS "press and hold to reveal" toggle for the sensitive
 * password fields of Configuration :: Générale (DB credentials,
 * reCAPTCHA keys). Content stays masked at all times except while the
 * eye button is actively pressed (mouse or touch) — releasing it
 * re-masks immediately. Self-contained: injects its own styles.
 */
(function () {
	var STYLE = '\
.peek-wrap{align-items:center;display:flex;position:relative}\
.peek-wrap .input{padding-right:38px}\
.peek-btn{align-items:center;background:transparent;border:0;border-radius:6px;color:var(--t-muted);cursor:pointer;display:flex;height:26px;justify-content:center;position:absolute;right:6px;width:26px}\
.peek-btn:hover{background:var(--bg-hover);color:var(--t-base)}\
.peek-btn svg{height:16px;width:16px}\
';

	var EYE_SVG = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';

	function injectStyle() {
		if (document.getElementById('peek-style')) return;
		var s = document.createElement('style');
		s.id = 'peek-style';
		s.textContent = STYLE;
		document.head.appendChild(s);
	}

	function wrap(input) {
		if (input.dataset.peekReady) return;
		input.dataset.peekReady = '1';

		var wrapEl = document.createElement('div');
		wrapEl.className = 'peek-wrap';
		input.parentNode.insertBefore(wrapEl, input);
		wrapEl.appendChild(input);

		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'peek-btn';
		btn.tabIndex = -1;
		btn.setAttribute('aria-label', 'Maintenir appuyé pour afficher');
		btn.innerHTML = EYE_SVG;
		wrapEl.appendChild(btn);

		var reveal = function () { input.type = 'text'; };
		var hide   = function () { input.type = 'password'; };

		btn.addEventListener('mousedown', reveal);
		btn.addEventListener('touchstart', reveal, { passive: true });
		['mouseup', 'mouseleave', 'touchend', 'touchcancel'].forEach(function (evt) {
			btn.addEventListener(evt, hide);
		});
		btn.addEventListener('click', function (e) { e.preventDefault(); });
	}

	var PEEK_FIELDS = ['dbhost', 'dbname', 'dbuser', 'dbpwd', 'dbprefix', 'recaptcha_public', 'recaptcha_secret'];

	function init() {
		injectStyle();
		PEEK_FIELDS.forEach(function (name) {
			var input = document.querySelector('input[type="password"][name="' + name + '"]');
			if (input) wrap(input);
		});
	}

	document.addEventListener('DOMContentLoaded', init);
})();
