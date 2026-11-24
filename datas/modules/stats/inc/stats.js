/**
 * Module stats : traceur JS (sans cookie, sans stockage local).
 * Complète la page notée par le serveur (jeton data-pv) : résolution
 * d'écran, temps de lecture de la dernière page, liens sortants,
 * téléchargements de fichiers du site.
 *
 * @package SBUIADMIN
 * ©INFORMATUX.COM
 */
(function () {
	'use strict';
	var tag = document.currentScript;
	if (!tag || !window.URLSearchParams) return;
	var pv = tag.getAttribute('data-pv') || '';
	var endpoint = tag.getAttribute('data-endpoint') || '';
	if (!/^[a-f0-9]{16}$/.test(pv) || !endpoint) return;

	function body(data) {
		var p = new URLSearchParams();
		p.append('pv', pv);
		for (var k in data) if (Object.prototype.hasOwnProperty.call(data, k)) p.append(k, String(data[k]));
		return p;
	}

	// Départ de la page (ping, lien) : sendBeacon survit à la navigation.
	// same-origin et non omit : un site protégé par authentification HTTP
	// (préproduction) refuserait les messages sans elle (401)
	function send(data) {
		var b = body(data);
		try { if (navigator.sendBeacon && navigator.sendBeacon(endpoint, b)) return; } catch (e) {}
		try { fetch(endpoint, { method: 'POST', body: b, keepalive: true, credentials: 'same-origin' }); } catch (e) {}
	}

	// Vue : la page est notée en fin de requête côté serveur, un second
	// essai couvre un message arrivé avant (404 = jeton pas encore connu)
	function view(retry) {
		if (!window.fetch) { send({ e: 'view', res: screen.width + 'x' + screen.height }); return; }
		fetch(endpoint, { method: 'POST', body: body({ e: 'view', res: screen.width + 'x' + screen.height }), credentials: 'same-origin' })
			.then(function (r) { if (r.status === 404 && retry) setTimeout(function () { view(false); }, 3000); })
			.catch(function () {});
	}
	if (document.readyState === 'complete') view(true);
	else window.addEventListener('load', function () { view(true); });

	// Temps de lecture : seulement quand la page est visible
	var visible = 0, since = document.visibilityState === 'visible' ? Date.now() : 0, sent = 0;
	function flush() {
		if (since) { visible += Date.now() - since; since = Date.now(); }
		var t = Math.round(visible / 1000);
		if (t > sent) { sent = t; send({ e: 'ping', t: t }); }
	}
	document.addEventListener('visibilitychange', function () {
		if (document.visibilityState === 'hidden') { flush(); since = 0; }
		else since = Date.now();
	});
	window.addEventListener('pagehide', flush);

	// Liens sortants et fichiers (les images ouvertes en visionneuse ne comptent pas)
	var files = /\.(pdf|zip|rar|7z|gz|tgz|bz2|xz|tar|docx?|xlsx?|pptx?|odt|ods|odp|csv|txt|rtf|epub|mp3|wav|ogg|flac|m4a|mp4|mov|avi|mkv|webm|exe|msi|dmg|pkg|apk|iso)$/i;
	function click(ev) {
		var a = ev.target && ev.target.closest ? ev.target.closest('a[href]') : null;
		if (!a) return;
		var u;
		try { u = new URL(a.href, location.href); } catch (e) { return; }
		if (u.protocol !== 'http:' && u.protocol !== 'https:') return;
		if (u.hostname !== location.hostname) send({ e: 'outlink', url: u.href });
		else if (a.hasAttribute('download') || files.test(u.pathname)) send({ e: 'download', url: u.href });
	}
	document.addEventListener('click', click, true);
	document.addEventListener('auxclick', function (ev) { if (ev.button === 1) click(ev); }, true);
})();
