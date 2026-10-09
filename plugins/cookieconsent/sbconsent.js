/* SBUIADMIN - bandeau de consentement (plugin cookieconsent)
 * Bibliothèque : CookieConsent 3 (orestbida, MIT), cookieconsent.umd.js.
 * Réglages lus sur la balise <script> qui charge ce fichier :
 *   data-policy    URL de la politique de confidentialité (facultatif)
 *   data-analytics "1" : catégorie « Mesure d'audience » proposée
 *                  (Google Analytics de CMS config > SEO, bloqué tant que le
 *                  visiteur n'a pas accepté)
 * Tout script <script type="text/plain" data-category="analytics"> du site
 * n'est exécuté qu'après accord. Lien « Gérer les cookies » : tout élément
 * portant l'attribut data-cc="show-preferencesModal".
 */
(function () {
	var me = document.currentScript;
	var policy = me ? (me.getAttribute('data-policy') || '') : '';
	var analytics = me ? me.getAttribute('data-analytics') === '1' : false;
	if (!window.CookieConsent) return;

	var policyHtml = policy ? ' <a href="' + policy.replace(/"/g, '&quot;') + '">Politique de confidentialité</a>' : '';
	var categories = { necessary: { enabled: true, readOnly: true } };
	var sections = [
		{ title: 'Cookies nécessaires', description: 'Indispensables au fonctionnement du site (session, sécurité, mémorisation de vos choix). Ils ne peuvent pas être désactivés.', linkedCategory: 'necessary' }
	];
	if (analytics) {
		categories.analytics = { enabled: false, readOnly: false, autoClear: { cookies: [{ name: /^_ga/ }, { name: '_gid' }] } };
		sections.push({ title: 'Mesure d\'audience', description: 'Statistiques de visite anonymisées (Google Analytics), pour améliorer le site.', linkedCategory: 'analytics' });
	}
	sections.push({ title: 'En savoir plus', description: 'Vous pouvez modifier vos choix à tout moment avec le lien « Gérer les cookies ».' + policyHtml });

	window.CookieConsent.run({
		guiOptions: {
			// En bas à gauche : le bouton « Retour en haut » est en bas à droite
			consentModal: { layout: 'box', position: 'bottom left' },
			preferencesModal: { layout: 'box' }
		},
		categories: categories,
		language: {
			default: 'fr',
			translations: {
				fr: {
					consentModal: {
						title: 'Cookies',
						description: analytics
							? 'Ce site utilise des cookies nécessaires à son fonctionnement et, avec votre accord, des cookies de mesure d\'audience.' + policyHtml
							: 'Ce site n\'utilise que des cookies nécessaires à son fonctionnement.' + policyHtml,
						acceptAllBtn: analytics ? 'Tout accepter' : 'J\'ai compris',
						acceptNecessaryBtn: analytics ? 'Tout refuser' : '',
						showPreferencesBtn: analytics ? 'Personnaliser' : ''
					},
					preferencesModal: {
						title: 'Gérer les cookies',
						acceptAllBtn: 'Tout accepter',
						acceptNecessaryBtn: 'Tout refuser',
						savePreferencesBtn: 'Enregistrer mes choix',
						closeIconLabel: 'Fermer',
						sections: sections
					}
				}
			}
		}
	});
})();
