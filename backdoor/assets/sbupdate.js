/*
 * SBUIADMIN - Configuration > Mise à jour
 * Préparation, plan, lancement et suivi de la copie. La requête « run »
 * fait toute la mise à jour ; pendant ce temps, update-status.php (qui ne
 * charge pas le CMS) est interrogé pour afficher la progression.
 */
(function () {
	var root = document.getElementById('sbupd');
	if (!root) return;
	var token = root.getAttribute('data-token');
	var base  = root.getAttribute('data-url');
	var statusUrl = root.getAttribute('data-status');
	var plan = null;
	var busy = false;

	function $(id) { return document.getElementById(id); }
	function show(id, on) { var el = $(id); if (el) el.style.display = on ? '' : 'none'; }
	function esc(s) { var d = document.createElement('div'); d.textContent = String(s); return d.innerHTML; }

	function post(action, extra) {
		var fd = new FormData();
		fd.append('token', token);
		if (extra) Object.keys(extra).forEach(function (k) { fd.append(k, extra[k]); });
		return fetch(base + action, { method: 'POST', body: fd, credentials: 'same-origin' })
			.then(function (r) { return r.json().catch(function () { return { error: 'Réponse inattendue du serveur (HTTP ' + r.status + ')' }; }); })
			.then(function (j) { if (j && j.error) throw new Error(j.error); return j; });
	}

	function error(msg) {
		$('sbupd-error-text').textContent = msg;
		show('sbupd-error', true);
		busy = false;
		setButtons(false);
	}

	function setButtons(disabled) {
		root.querySelectorAll('[data-upd-action]').forEach(function (b) { b.disabled = disabled; });
	}

	function progress(done, total, text) {
		show('sbupd-progress', true);
		var pct = total > 0 ? Math.round(done * 100 / total) : 0;
		$('sbupd-bar-fill').style.width = pct + '%';
		$('sbupd-step-text').textContent = text || '';
	}

	function list(title, items, max) {
		if (!items || !items.length) return '';
		var html = '<p style="margin-top:10px"><strong>' + esc(title) + ' (' + items.length + ')</strong></p><ul style="max-height:180px;overflow:auto;font-family:monospace;font-size:12px">';
		items.slice(0, max || 500).forEach(function (f) { html += '<li>' + esc(f) + '</li>'; });
		return html + '</ul>';
	}

	function renderPlan(p) {
		plan = p;
		var html = '<p>Mise à jour <strong>' + esc(p.from) + ' → ' + esc(p.to) + '</strong> vérifiée (signature, archive, liste des fichiers) : '
			+ '<strong>' + p.write + '</strong> fichier(s) à écrire, <strong>' + p['delete'] + '</strong> à retirer.</p>';
		if (p.unchecked) {
			html += '<div class="alert warning"><span class="ico"><svg viewBox="0 0 24 24"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg></span><div class="body">Impossible de repérer les fichiers modifiés localement : ' + esc(p.check_note)
				+ '. Tous les fichiers du cœur seront remplacés par ceux de la version ' + esc(p.to) + '.</div></div>';
		}
		if (p.migrations && p.migrations.length) {
			html += '<p style="margin-top:10px"><strong>Base de données</strong> : ' + p.migrations.length + ' migration(s) (' + esc(p.migrations.join(', ')) + '), après sauvegarde complète de la base.</p>';
		}
		html += list('Fichiers modifiés localement, qui seront remplacés', p.modified);
		html += list('Fichiers propres au site modifiés dans la nouvelle version (non touchés : à reporter à la main si besoin)', p.protected_changed);
		$('sbupd-plan').innerHTML = html;
		show('sbupd-confirm-wrap', p.unchecked || (p.modified && p.modified.length > 0));
		if (p.unchecked && !(p.modified && p.modified.length)) {
			$('sbupd-confirm-label').textContent = 'Je sais que les fichiers du cœur seront remplacés sans vérification des modifications locales (la version actuelle est gardée dans la sauvegarde).';
		}
		show('sbupd-step1', false);
		show('sbupd-step2', true);
	}

	// Suivi de la copie pendant la requête « run » / « restore »
	function poll(stopFlag) {
		if (stopFlag.stop) return;
		fetch(statusUrl + '&_=' + Date.now(), { credentials: 'same-origin', cache: 'no-store' })
			.then(function (r) { return r.json(); })
			.then(function (s) {
				if (stopFlag.stop || !s || !s.status) return;
				if (s.phase === 'backup') progress(0, 1, s.step);
				else progress(s.done, s.total, s.step);
			})
			.catch(function () { /* réessai au tour suivant */ })
			.then(function () { if (!stopFlag.stop) setTimeout(function () { poll(stopFlag); }, 700); });
	}

	function longAction(action, extra, doneText) {
		busy = true;
		setButtons(true);
		show('sbupd-error', false);
		show('sbupd-step2', false);
		progress(0, 1, 'Démarrage…');
		var pr = $('sbupd-progress'); if (pr && pr.scrollIntoView) pr.scrollIntoView({ behavior: 'smooth', block: 'center' });
		var flag = { stop: false };
		setTimeout(function () { poll(flag); }, 400);
		post(action, extra).then(function (r) {
			flag.stop = true;
			progress(1, 1, r.step || doneText);
			busy = false;
			$('sbupd-step-text').innerHTML = esc(r.step || doneText) + ' — <a href="index.php?p=update">recharger la page</a>';
			if (typeof sbToast === 'function') sbToast(r.step || doneText, 'success');
		}).catch(function (e) {
			flag.stop = true;
			error(e.message);
		});
	}

	root.addEventListener('click', function (ev) {
		var btn = ev.target.closest('[data-upd-action]');
		if (!btn || busy) return;
		var action = btn.getAttribute('data-upd-action');
		show('sbupd-error', false);

		if (action === 'check') {
			setButtons(true);
			post('check').then(function () { location.reload(); }).catch(function (e) { error(e.message); });
		} else if (action === 'prepare') {
			setButtons(true);
			busy = true;
			progress(0, 1, 'Téléchargement et vérification de la nouvelle version…');
			post('prepare').then(function (p) {
				busy = false;
				setButtons(false);
				show('sbupd-progress', false);
				renderPlan(p);
			}).catch(function (e) { show('sbupd-progress', false); error(e.message); });
		} else if (action === 'run') {
			var needConfirm = plan && (plan.unchecked || (plan.modified && plan.modified.length));
			if (needConfirm && !$('sbupd-confirm').checked) { error('Cochez la case de confirmation pour remplacer les fichiers modifiés localement.'); return; }
			var pw = $('sbupd-password').value;
			if (!pw) { error('Saisissez votre mot de passe pour confirmer.'); return; }
			$('sbupd-password').value = '';
			longAction('run', { confirm: needConfirm ? '1' : '', password: pw }, 'Mise à jour terminée');
		} else if (action === 'cancel') {
			post('cancel').then(function () { location.reload(); }).catch(function (e) { error(e.message); });
		} else if (action === 'rollback') {
			var name = btn.getAttribute('data-backup');
			var version = btn.getAttribute('data-version');
			var needsDump = btn.getAttribute('data-needs-dump') === '1';
			if (needsDump && !($('sbupd-accept-loss') && $('sbupd-accept-loss').checked)) { error('Cochez la case d\'acceptation de la perte des contenus saisis depuis la mise à jour.'); return; }
			var rpw = $('sbupd-rb-password').value;
			if (!rpw) { error('Saisissez votre mot de passe pour confirmer.'); return; }
			var ask = (typeof window.sbShowConfirm === 'function')
				? window.sbShowConfirm('Revenir à la version ' + version + ' ? La dernière mise à jour sera annulée (fichiers et base de données). Impossible de reculer davantage ensuite.', 'Revenir à ' + version, 'Annuler')
				: Promise.resolve(false);
			ask.then(function (ok) {
				if (!ok) return;
				$('sbupd-rb-password').value = '';
				longAction('rollback', { backup: name, password: rpw, accept_data_loss: needsDump ? '1' : '' }, 'Retour arrière terminé');
			});
		}
	});
})();
