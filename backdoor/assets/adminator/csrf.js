/**
 * Jeton CSRF ajouté automatiquement à tout ce que l'administration envoie
 * vers le site (le routeur l'exige pour tout POST, voir index.php) :
 *  - requêtes XMLHttpRequest (donc jQuery) et fetch non GET : en-tête X-SB-CSRF ;
 *  - formulaires POST : champ caché sbuiadmin_csrf_token, à l'envoi (y
 *    compris form.submit() appelé par un script) ;
 *  - liens vers une action GET sensible d'un module ('csrf_get' de son
 *    module.php, window.sbCsrfGet) : paramètre t= au clic.
 * Chargé avant les autres scripts (scripts.tpl), sans defer.
 */
(function () {
	'use strict';
	var token = window.sbCsrfToken;
	if (!token) return;

	function sameOrigin(url) {
		try { return new URL(url, window.location.href).origin === window.location.origin; } catch (e) { return false; }
	}
	function writes(method) {
		method = String(method || 'GET').toUpperCase();
		return method !== 'GET' && method !== 'HEAD';
	}

	// --- XMLHttpRequest (jQuery passe par là)
	var xhrOpen = XMLHttpRequest.prototype.open;
	var xhrSend = XMLHttpRequest.prototype.send;
	XMLHttpRequest.prototype.open = function (method, url) {
		this.sbCsrf = writes(method) && sameOrigin(url);
		return xhrOpen.apply(this, arguments);
	};
	XMLHttpRequest.prototype.send = function () {
		if (this.sbCsrf) {
			try { this.setRequestHeader('X-SB-CSRF', token); } catch (e) { /* requête déjà partie */ }
		}
		return xhrSend.apply(this, arguments);
	};

	// --- fetch
	if (window.fetch) {
		var nativeFetch = window.fetch;
		window.fetch = function (input, init) {
			var isRequest = (typeof Request !== 'undefined') && (input instanceof Request);
			var url = isRequest ? input.url : String(input);
			var method = (init && init.method) || (isRequest ? input.method : 'GET');
			if (writes(method) && sameOrigin(url)) {
				var headers = new Headers((init && init.headers) || (isRequest ? input.headers : undefined));
				headers.set('X-SB-CSRF', token);
				init = Object.assign({}, init || {}, { headers: headers });
			}
			return nativeFetch.call(this, input, init);
		};
	}

	// --- Formulaires POST (getAttribute : un champ nommé « action » ou
	// « method » masquerait la propriété du formulaire)
	function addField(form) {
		if (String(form.getAttribute('method') || 'get').toLowerCase() !== 'post') return;
		if (!sameOrigin(form.getAttribute('action') || window.location.href)) return;
		if (form.querySelector('input[name="sbuiadmin_csrf_token"]')) return;
		var input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'sbuiadmin_csrf_token';
		input.value = token;
		form.appendChild(input);
	}
	document.addEventListener('submit', function (e) {
		if (e.target && e.target.tagName === 'FORM') addField(e.target);
	}, true);
	var formSubmit = HTMLFormElement.prototype.submit;
	HTMLFormElement.prototype.submit = function () {
		addField(this);
		return formSubmit.apply(this, arguments);
	};

	// --- Liens des actions GET sensibles des modules (les suppressions,
	// liens data-confirm, reçoivent le jeton par confirm.js)
	var sensitive = window.sbCsrfGet || {};
	document.addEventListener('click', function (e) {
		var a = e.target && e.target.closest ? e.target.closest('a[href]') : null;
		if (!a || a.hasAttribute('data-confirm')) return;
		var url;
		try { url = new URL(a.getAttribute('href'), window.location.href); } catch (err) { return; }
		if (url.origin !== window.location.origin || url.searchParams.has('t')) return;
		var page = url.searchParams.get('p'), action = url.searchParams.get('a');
		if (!page || !action || !Array.isArray(sensitive[page]) || sensitive[page].indexOf(action) < 0) return;
		url.searchParams.set('t', token);
		a.setAttribute('href', url.toString());
	}, true);
})();
