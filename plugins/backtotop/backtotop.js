/* SBUIADMIN - bouton « Retour en haut » (plugin backtotop), sans dépendance.
 * Apparaît après 400 px de défilement. Couleurs : variables CSS
 * --sb-backtotop-bg et --sb-backtotop-color (CSS du thème ou CMS config > CSS). */
(function () {
	function init() {
		if (document.querySelector('.sb-backtotop')) return;
		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'sb-backtotop';
		btn.setAttribute('aria-label', 'Retour en haut de la page');
		btn.title = 'Retour en haut';
		btn.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 15l6-6 6 6"/></svg>';
		document.body.appendChild(btn);
		var smooth = !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
		btn.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: smooth ? 'smooth' : 'auto' }); });
		var ticking = false;
		function update() { btn.classList.toggle('is-visible', window.scrollY > 400); ticking = false; }
		window.addEventListener('scroll', function () { if (!ticking) { ticking = true; window.requestAnimationFrame(update); } }, { passive: true });
		update();
	}
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
