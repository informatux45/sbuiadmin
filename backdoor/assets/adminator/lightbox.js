/*
 * Vanilla-JS replacement for Facebox, matching Adminator's visual language.
 * Usage: add rel="facebox" (kept for backward compatibility with existing
 * markup) to any <a href="image-url"> — click opens the image in an overlay.
 * Self-contained: injects its own styles, no separate CSS file needed.
 */
(function () {
	var STYLE = '\
.sb-lightbox-backdrop{align-items:center;background:var(--overlay);display:flex;inset:0;justify-content:center;padding:32px;position:fixed;z-index:1000}\
.sb-lightbox-box{animation:sb-lightbox-in .16s ease both;max-width:min(90vw,900px);max-height:90vh;position:relative}\
.sb-lightbox-box img{background:var(--bg-card);border-radius:10px;box-shadow:var(--shadow-lg);display:block;max-width:100%;max-height:90vh;object-fit:contain}\
.sb-lightbox-close{align-items:center;background:var(--bg-card);border:1px solid var(--border);border-radius:50%;color:var(--t-base);cursor:pointer;display:flex;height:32px;justify-content:center;position:absolute;right:-14px;top:-14px;width:32px;box-shadow:var(--shadow-lg)}\
@keyframes sb-lightbox-in{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}\
';

	function injectStyle() {
		if (document.getElementById('sb-lightbox-style')) return;
		var s = document.createElement('style');
		s.id = 'sb-lightbox-style';
		s.textContent = STYLE;
		document.head.appendChild(s);
	}

	function openLightbox(src) {
		injectStyle();
		var backdrop = document.createElement('div');
		backdrop.className = 'sb-lightbox-backdrop';
		backdrop.innerHTML =
			'<div class="sb-lightbox-box">' +
				'<img src="' + src + '" alt="">' +
				'<button type="button" class="sb-lightbox-close" aria-label="Fermer">' +
					'<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>' +
				'</button>' +
			'</div>';
		document.body.appendChild(backdrop);

		function close() {
			document.body.removeChild(backdrop);
			document.removeEventListener('keydown', onKey);
		}
		function onKey(e) {
			if (e.key === 'Escape') close();
		}
		document.addEventListener('keydown', onKey);
		backdrop.addEventListener('click', function (e) {
			if (e.target === backdrop) close();
		});
		backdrop.querySelector('.sb-lightbox-close').addEventListener('click', close);
	}

	document.addEventListener('click', function (e) {
		var el = e.target.closest('[rel*="facebox"]');
		if (!el) return;
		var href = el.getAttribute('href');
		if (!href) return;
		e.preventDefault();
		openLightbox(href);
	});
})();
