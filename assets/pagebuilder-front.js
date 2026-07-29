/*
 * Interactivité front du bloc "Onglets" du Page Builder (voir
 * addPageBuilderTags() / pagebuilder.js côté admin). Vanilla JS, aucune
 * dépendance jQuery - un thème front peut très bien ne pas la charger
 * (même principe que le script d'init Leaflet du bloc "Carte", voir
 * insert_sbGetHeaders()).
 */
(function () {
	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.sb-tabs-btn');
		if (!btn) return;
		var tabs = btn.closest('.sb-tabs');
		if (!tabs) return;
		var target = btn.getAttribute('data-tab');

		tabs.querySelectorAll(':scope > .sb-tabs-nav > .sb-tabs-btn').forEach(function (b) {
			b.classList.toggle('is-active', b === btn);
		});
		tabs.querySelectorAll(':scope > .sb-tabs-panel').forEach(function (p) {
			p.classList.toggle('is-active', p.getAttribute('data-tab') === target);
		});
	});
})();
