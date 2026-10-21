/**
 * Modifications non enregistrées : confirmation avant de quitter une page
 * portant un formulaire d'ajout / modification (POST avec un champ
 * form_submit, convention des modules ; data-sb-unsaved="off" l'exclut).
 *
 * Comparaison avec un instantané pris au chargement, pas des événements :
 * le sélecteur de médias, Tagify ou le Page Builder changent les valeurs
 * sans « input ». Éditeurs dont le champ n'est rempli qu'à l'envoi :
 * CKEditor (checkDirty), ACE (getValue), Page Builder (.htmlpage).
 *  - liens de la page : fenêtre de confirmation du CMS (sbShowConfirm) ;
 *  - rechargement, fermeture, retour : alerte du navigateur (beforeunload).
 * Envoi du formulaire (submit ou form.submit()) : pas de confirmation.
 */
(function () {
	'use strict';
	var baseline = null;
	var leaving = false;

	function forms() {
		return Array.prototype.filter.call(document.querySelectorAll('form'), function (f) {
			return String(f.getAttribute('method') || '').toLowerCase() === 'post'
				&& f.querySelector('input[name="form_submit"]')
				&& f.getAttribute('data-sb-unsaved') !== 'off';
		});
	}

	// Champ remplacé par un éditeur (CKEditor) ou contenu d'un bloc du Page Builder
	function ignored(el) {
		if (!el.name || el.disabled || el.type === 'file' || el.name === 'sbuiadmin_csrf_token') return true;
		if (el.closest('.htmlpage')) return true;
		return !!(window.CKEDITOR && (window.CKEDITOR.instances[el.id] || window.CKEDITOR.instances[el.name]));
	}

	function snapshot() {
		var parts = [];
		forms().forEach(function (form, i) {
			Array.prototype.forEach.call(form.elements, function (el) {
				if (ignored(el)) return;
				if ((el.type === 'checkbox' || el.type === 'radio') && !el.checked) return;
				if (el.tagName === 'SELECT' && el.multiple) {
					Array.prototype.forEach.call(el.selectedOptions, function (o) { parts.push(i + ':' + el.name + '=' + o.value); });
					return;
				}
				parts.push(i + ':' + el.name + '=' + el.value);
			});
			Array.prototype.forEach.call(form.querySelectorAll('.ace_editor'), function (el, n) {
				if (el.env && el.env.editor) parts.push(i + ':ace' + n + '=' + el.env.editor.getValue());
			});
			Array.prototype.forEach.call(form.querySelectorAll('.htmlpage'), function (el, n) {
				parts.push(i + ':pb' + n + '=' + el.innerHTML);
			});
		});
		return parts.join('\n');
	}

	function ckDirty() {
		if (!window.CKEDITOR) return false;
		var list = forms();
		for (var name in window.CKEDITOR.instances) {
			var ed = window.CKEDITOR.instances[name];
			// Champ envoyé seulement (pas l'éditeur des réglages d'un bloc du Page Builder)
			var el = ed.element && ed.element.$;
			try {
				if (el && el.name && list.indexOf(el.form) !== -1 && ed.status === 'ready' && ed.checkDirty()) return true;
			} catch (e) { /* éditeur détruit */ }
		}
		return false;
	}

	function dirty() {
		return !leaving && baseline !== null && (snapshot() !== baseline || ckDirty());
	}

	// Instantané une fois les éditeurs et Tagify en place
	window.addEventListener('load', function () {
		setTimeout(function () {
			if (forms().length) baseline = snapshot();
		}, 1000);
	});

	// Envoi : la page part avec les données (form.submit() n'émet pas « submit »)
	document.addEventListener('submit', function (e) {
		if (!e.defaultPrevented) leaving = true;
	});
	var formSubmit = HTMLFormElement.prototype.submit;
	HTMLFormElement.prototype.submit = function () {
		leaving = true;
		return formSubmit.apply(this, arguments);
	};

	window.addEventListener('beforeunload', function (e) {
		if (!dirty()) return;
		e.preventDefault();
		e.returnValue = '';
	});

	// Liens : écouteur sur window, après ceux de document (menus, onglets,
	// boutons du Page Builder qui annulent la navigation)
	window.addEventListener('click', function (e) {
		if (e.defaultPrevented || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
		var a = e.target && e.target.closest ? e.target.closest('a[href]') : null;
		if (!a || a.hasAttribute('download') || a.hasAttribute('data-confirm')) return;
		if (a.target && a.target !== '_self') return;
		var href = a.getAttribute('href');
		if (!href || href.charAt(0) === '#' || /^javascript:/i.test(href)) return;
		if (!dirty()) return;
		e.preventDefault();
		var go = function (ok) {
			if (!ok) return;
			leaving = true;
			window.location.href = a.href;
		};
		var msg = 'Des modifications n\'ont pas été enregistrées. Quitter cette page quand même ?';
		if (window.sbShowConfirm) window.sbShowConfirm(msg, 'Quitter sans enregistrer', 'Rester').then(go);
		else go(window.confirm(msg));
	});
})();
