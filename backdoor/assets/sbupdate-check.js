/*
 * SBUIADMIN - vérification quotidienne des mises à jour (tableau de bord,
 * administrateurs). Chargé seulement quand la dernière vérification date de
 * plus de 24 h ; le serveur ne contacte GitHub qu'à ce moment-là.
 */
(function () {
	if (!window.fetch) return;
	fetch('index.php?p=update&ajax=autocheck', { credentials: 'same-origin', cache: 'no-store' })
		.then(function (r) { return r.json(); })
		.then(function (j) {
			if (j && j.available && typeof window.sbToast === 'function') {
				window.sbToast('SBUIADMIN ' + j.version + ' est disponible (version installée : ' + j.current + ').', 'info', 'index.php?p=update', 'Voir');
			}
		})
		.catch(function () { /* GitHub injoignable : nouvel essai plus tard */ });
})();
