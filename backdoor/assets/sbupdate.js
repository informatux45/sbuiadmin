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
	function nb(n, word, plural) { return '<strong>' + n + '</strong> ' + (n > 1 ? (plural || word + 's') : word); }
	// Adresse qui change à chaque fois : un simple « #ancre » ne recharge pas la page
	function reloadUrl(anchor) { return 'index.php?p=update&r=' + Date.now() + (anchor ? '#' + anchor : ''); }
	function esc(s) { var d = document.createElement('div'); d.textContent = String(s); return d.innerHTML; }

	function post(action, extra) {
		var fd = new FormData();
		fd.append('token', token);
		if (extra) Object.keys(extra).forEach(function (k) { fd.append(k, extra[k]); });
		return fetch(base + action, { method: 'POST', body: fd, credentials: 'same-origin' })
			.then(function (r) { return r.json().catch(function () { return { error: 'Réponse inattendue du serveur (HTTP ' + r.status + ')' }; }); })
			.then(function (j) { if (j && j.error) throw new Error(j.error); return j; });
	}

	// Progression et erreurs affichées là où l'action a lieu : carte de la
	// mise à jour, carte du retour arrière, ou ligne du module concerné
	function place(container, atTop) {
		if (!container) return;
		['sbupd-progress', 'sbupd-error'].forEach(function (id) {
			var el = $(id);
			if (!el) return;
			el.className = '';
			el.style.marginTop = '12px';
			if (atTop) container.insertBefore(el, container.firstChild); else container.appendChild(el);
		});
	}
	function placeFor(btn, atTop) {
		var section = btn.closest('section');
		place(section ? (section.querySelector('.card-body') || section) : null, atTop);
	}
	// Ligne glissée sous le module : panneau, progression, erreurs
	function modSlot(btn) {
		var tr = btn.closest('tr');
		var slot = $('sbmod-slot');
		if (!slot) {
			slot = document.createElement('tr');
			slot.id = 'sbmod-slot';
			var td = document.createElement('td');
			td.colSpan = 7;
			td.style.padding = '4px 0 16px';
			slot.appendChild(td);
		}
		tr.parentNode.insertBefore(slot, tr.nextSibling);
		slot.style.display = '';
		var td0 = slot.firstChild;
		var panel = $('sbmod-panel');
		panel.style.marginTop = '0';
		td0.appendChild(panel);
		return td0;
	}

	// Panneau du module : attente (cercle animé), résultat, message de saisie
	function modForm(on) {
		show('sbmod-form', on);
		if (!on) { show('sbmod-confirm-wrap', false); show('sbmod-remove-wrap', false); }
		var m = $('sbmod-msg'); if (m) m.parentNode.removeChild(m);
	}
	function modBusy(title, text, sub) {
		$('sbmod-panel-title').textContent = title;
		$('sbmod-panel-body').innerHTML = '<div class="sbmod-busy"><div class="sbmod-spin" role="status" aria-label="En cours"></div><strong>' + esc(text) + '</strong>' + (sub ? '<small>' + esc(sub) + '</small>' : '') + '</div>';
		modForm(false);
		show('sbmod-panel', true);
	}
	function modResult(ok, text) {
		$('sbmod-panel-body').innerHTML = '<div class="alert ' + (ok ? 'success' : 'danger') + '"><span class="ico"><svg viewBox="0 0 24 24">'
			+ (ok ? '<circle cx="12" cy="12" r="10"/><path d="m8 12 3 3 5-6"/>' : '<circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/>')
			+ '</svg></span><div class="body">' + esc(text) + '</div></div><div style="margin-top:12px">'
			+ (ok ? '<a class="btn btn--primary" href="' + reloadUrl('sbmod') + '">Recharger la page</a> ' : '')
			+ '<button type="button" class="btn btn--ghost" data-mod-action="close">Fermer</button></div>';
		modForm(false);
		show('sbmod-panel', true);
	}
	function modMsg(text) {
		var m = $('sbmod-msg');
		if (!m) {
			m = document.createElement('div');
			m.id = 'sbmod-msg';
			m.className = 'alert danger';
			m.style.margin = '12px 0 0';
			$('sbmod-form').insertBefore(m, $('sbmod-form').firstChild);
		}
		m.innerHTML = '<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span><div class="body">' + esc(text) + '</div>';
	}

	function error(msg) {
		show('sbupd-progress', false);
		$('sbupd-error-text').textContent = msg;
		show('sbupd-error', true);
		busy = false;
		setButtons(false);
	}

	function setButtons(disabled) {
		root.querySelectorAll('[data-upd-action], [data-mod-action]').forEach(function (b) { b.disabled = disabled; });
		// Boutons « Vérifier » : texte d'origine remis après un échec
		if (!disabled) root.querySelectorAll('[data-label]').forEach(function (b) { b.innerHTML = b.getAttribute('data-label'); b.removeAttribute('data-label'); });
	}

	function progress(done, total, text) {
		show('sbupd-progress', true);
		show('sbupd-busy', false);
		show('sbupd-bar', true);
		var pct = total > 0 ? Math.round(done * 100 / total) : 0;
		$('sbupd-bar-fill').style.width = pct + '%';
		$('sbupd-step-text').textContent = text || '';
	}
	// Attente sans avancement chiffré : cercle animé et message mis en valeur
	function coreBusy(text, sub) {
		show('sbupd-progress', true);
		show('sbupd-busy', true);
		show('sbupd-bar', false);
		$('sbupd-busy-text').textContent = text;
		$('sbupd-busy-sub').textContent = sub || '';
		$('sbupd-step-text').textContent = '';
	}
	function coreResult(text) {
		show('sbupd-progress', true);
		show('sbupd-busy', false);
		show('sbupd-bar', false);
		$('sbupd-step-text').innerHTML = '<div class="alert success" style="text-align:left"><span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m8 12 3 3 5-6"/></svg></span><div class="body">' + esc(text) + '</div></div><div style="margin-top:12px;text-align:left"><a class="btn btn--primary" href="' + reloadUrl('') + '">Recharger la page</a></div>';
	}
	// Bouton « Vérifier » : petit cercle pendant l'appel à GitHub
	function btnBusy(btn, text) { btn.setAttribute('data-label', btn.innerHTML); btn.innerHTML = '<span class="sbbtn-spin"></span>' + esc(text); }

	function list(title, items, max) {
		if (!items || !items.length) return '';
		var html = '<p style="margin-top:10px"><strong>' + esc(title) + ' (' + items.length + ')</strong></p><ul style="max-height:180px;overflow:auto;font-family:monospace;font-size:12px">';
		items.slice(0, max || 500).forEach(function (f) { html += '<li>' + esc(f) + '</li>'; });
		return html + '</ul>';
	}

	function renderPlan(p) {
		plan = p;
		var html = '<p>Mise à jour <strong>' + esc(p.from) + ' → ' + esc(p.to) + '</strong> vérifiée (signature, archive, liste des fichiers) : '
			+ nb(p.write, 'fichier') + ' à écrire, ' + nb(p['delete'], 'fichier') + ' à retirer.</p>';
		if (p.unchecked) {
			html += '<div class="alert warning"><span class="ico"><svg viewBox="0 0 24 24"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg></span><div class="body">Impossible de repérer les fichiers modifiés localement : ' + esc(p.check_note)
				+ '. Tous les fichiers du cœur seront remplacés par ceux de la version ' + esc(p.to) + '.</div></div>';
		}
		if (p.migrations && p.migrations.length) {
			html += '<p style="margin-top:10px"><strong>Base de données</strong> : ' + nb(p.migrations.length, 'migration') + ' (' + esc(p.migrations.join(', ')) + '), après sauvegarde complète de la base.</p>';
		}
		html += list('Fichiers modifiés localement, qui seront remplacés', p.modified);
		html += list('Fichiers propres au site modifiés dans la nouvelle version (non touchés : à reporter à la main si besoin)', p.protected_changed);
		if (p.modules_skipped) html += '<p style="margin-top:10px;color:var(--t-muted)">' + p.modules_skipped + (p.modules_skipped > 1 ? ' fichiers de modules laissés' : ' fichier de module laissé') + ' de côté : les modules se mettent à jour dans la carte « Modules ».</p>';
		html += list('Modules avec une version plus récente (à mettre à jour ensuite, carte « Modules »)', p.modules_newer);
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
				if (s.phase === 'backup' || s.phase === 'migrate' || s.phase === 'verify' || !s.total) coreBusy(stopFlag.text, s.step);
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
		var mainText = action === 'rollback' ? 'Retour arrière en cours…' : 'Mise à jour en cours…';
		coreBusy(mainText, 'Sauvegarde chiffrée, puis copie ; le site affiche « mise à jour en cours », ne fermez pas la page');
		var pr = $('sbupd-progress'); if (pr && pr.scrollIntoView) pr.scrollIntoView({ behavior: 'smooth', block: 'center' });
		var flag = { stop: false, text: mainText };
		setTimeout(function () { poll(flag); }, 400);
		post(action, extra).then(function (r) {
			flag.stop = true;
			busy = false;
			coreResult(r.step || doneText);
			if (typeof sbToast === 'function') sbToast(r.step || doneText, 'success');
		}).catch(function (e) {
			flag.stop = true;
			error(e.message);
		});
	}

	// ---------------------------------------------------------------
	// Modules : plan (rien n'est écrit), puis confirmation + mot de passe
	// ---------------------------------------------------------------
	var modCurrent = null;
	var modLabels = { update: 'Mettre à jour', install: 'Installer', uninstall: 'Désinstaller', rollback: 'Annuler' };

	// « Supprimer le module » (désinstallation) : ce qui sera perdu, d'après le site
	function modRemoveInfo(p) {
		var html = '<p><strong>Seront supprimés définitivement du serveur (une sauvegarde chiffrée est faite avant) :</strong></p><ul>';
		var tables = Object.keys(p.rows || {});
		if (tables.length) {
			html += '<li>Données : ' + tables.map(function (t) { return esc(t) + ' (' + p.rows[t] + ' ligne' + (p.rows[t] > 1 ? 's' : '') + ')'; }).join(', ') + ' — tables SQL supprimées</li>';
		} else {
			html += '<li>Aucune table en base</li>';
		}
		html += '<li>Fichiers : ' + nb(p.files, 'fichier') + ' du module</li>';
		if (p.uses && p.uses.length) html += '<li>Codes courts encore utilisés, qui s\'afficheront vides : ' + esc(p.uses.join(', ')) + '</li>';
		html += '</ul>';
		if (p.modified && p.modified.length) html += list('Fichiers modifiés sur le site, supprimés aussi', p.modified);
		else if (p.unchecked) html += '<p>Les fichiers n\'ont pas pu être comparés à la version publiée (cliquez sur « Vérifier les modules » pour le permettre) : d\'éventuelles modifications faites sur le site seront supprimées aussi.</p>';
		html += '<p>Le module redeviendra disponible sur GitHub : « Installer » le remettra, sans ces données.</p>';
		return html;
	}

	function modPanel(p) {
		modCurrent = p;
		modForm(true);
		$('sbmod-panel-title').textContent = (p.op === 'rollback')
			? 'Annuler ' + p.label + ' (module ' + p.title + ')'
			: modLabels[p.op] + ' le module ' + p.title + (p.version ? ' (' + p.version + ')' : '');
		var html = '';
		if (p.op === 'rollback') html += '<p>Le module revient à son état d\'avant ' + esc(p.label) + ', faite le ' + esc(p.date) + '. Un seul retour possible : la sauvegarde est ensuite supprimée.</p>';
		if (p.op === 'update' || (p.op === 'install' && p.download)) {
			html += '<p>Archive vérifiée (signature de la version, empreinte de l\'archive et de chaque fichier) : ' + nb(p.write, 'fichier') + ' à écrire' + (p.op === 'update' ? ', ' + nb(p['delete'], 'fichier') + ' à retirer' : ', téléchargés depuis GitHub') + '.</p>';
		}
		if (p.op === 'install') {
			html += '<p>Tables créées : <strong>' + (p.tables_new && p.tables_new.length ? esc(p.tables_new.join(', ')) : 'aucune') + '</strong>. Le module sera activé : menu, droits, pages et codes courts.</p>';
		} else if (p.op === 'uninstall') {
			html += '<p>Le module sera désactivé : menu, droits, pages et codes courts disparaissent du site et de l\'administration. Ses données et ses fichiers sont gardés, « Installer » le remettra tel quel.</p>';
		}
		(p.info || []).forEach(function (n) { html += '<p style="color:var(--t-muted)">' + esc(n) + '</p>'; });
		(p.notes || []).forEach(function (n) { html += '<p style="color:var(--warning)">' + esc(n) + '</p>'; });
		if (p.op !== 'uninstall') {
			if (p.unchecked) html += '<p style="color:var(--warning)">Impossible de savoir si les fichiers de ce module ont été modifiés localement.</p>';
			html += list('Fichiers modifiés localement', p.modified);
		}
		$('sbmod-panel-body').innerHTML = html;
		var needConfirm = p.op !== 'uninstall' && (p.unchecked || (p.modified && p.modified.length > 0));
		show('sbmod-confirm-wrap', needConfirm);
		$('sbmod-confirm').checked = false;
		$('sbmod-confirm-label').textContent = 'Je sais que ces fichiers seront remplacés (leur version actuelle est gardée dans la sauvegarde).';
		show('sbmod-remove-wrap', p.op === 'uninstall');
		$('sbmod-remove').checked = false;
		$('sbmod-remove-body').innerHTML = p.op === 'uninstall' ? modRemoveInfo(p) : '';
		show('sbmod-remove-info', false);
		$('sbmod-run').textContent = (p.op === 'rollback') ? 'Annuler l\'opération' : modLabels[p.op];
		show('sbmod-panel', true);
		var el = $('sbmod-panel'); if (el.scrollIntoView) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
	}

	var removeBox = $('sbmod-remove');
	if (removeBox) removeBox.addEventListener('change', function () {
		show('sbmod-remove-info', removeBox.checked);
		$('sbmod-run').textContent = removeBox.checked ? 'Supprimer le module' : 'Désinstaller';
		$('sbmod-run').className = removeBox.checked ? 'btn btn--danger' : 'btn btn--primary';
	});

	root.addEventListener('click', function (ev) {
		var btn = ev.target.closest('[data-mod-action]');
		if (!btn || busy) return;
		var action = btn.getAttribute('data-mod-action');
		show('sbupd-error', false);
		if (action === 'check') {
			placeFor(btn, true);
			btnBusy(btn, 'Vérification…');
			setButtons(true);
			busy = true;
			post('modcheck').then(function () { location.reload(); }).catch(function (e) { error(e.message); });
		} else if (action === 'plan') {
			var op = btn.getAttribute('data-op');
			var mtitle = btn.closest('tr').querySelector('td:nth-child(2) strong').textContent;
			modSlot(btn);
			modCurrent = null;
			var local = (op === 'uninstall' || op === 'rollback');
			modBusy((op === 'rollback' ? 'Annulation — module ' : modLabels[op] + ' le module ') + mtitle,
				local ? 'Préparation…' : 'Vérification du module en cours…',
				local ? 'Inventaire des données, des fichiers et de la sauvegarde du module' : 'Téléchargement depuis GitHub, contrôle de la signature et de chaque fichier');
			var pnl = $('sbmod-panel'); if (pnl.scrollIntoView) pnl.scrollIntoView({ behavior: 'smooth', block: 'center' });
			setButtons(true);
			busy = true;
			post('modplan', { module: btn.getAttribute('data-mod'), op: op }).then(function (p) {
				busy = false;
				setButtons(false);
				modPanel(p);
			}).catch(function (e) { busy = false; setButtons(false); modResult(false, e.message); });
		} else if (action === 'close') {
			show('sbmod-panel', false);
			if ($('sbmod-slot')) $('sbmod-slot').style.display = 'none';
			modCurrent = null;
		} else if (action === 'run' && modCurrent) {
			var need = modCurrent.op !== 'uninstall' && (modCurrent.unchecked || (modCurrent.modified && modCurrent.modified.length));
			var remove = modCurrent.op === 'uninstall' && $('sbmod-remove').checked;
			if (need && !$('sbmod-confirm').checked) { modMsg('Cochez la case de confirmation.'); return; }
			var pw = $('sbmod-password').value;
			if (!pw) { modMsg('Saisissez votre mot de passe pour confirmer.'); return; }
			$('sbmod-password').value = '';
			setButtons(true);
			busy = true;
			var runText = remove ? 'Suppression du module en cours…' : ({ install: 'Installation du module en cours…', update: 'Mise à jour du module en cours…', uninstall: 'Désinstallation du module en cours…', rollback: 'Annulation en cours…' })[modCurrent.op];
			modBusy($('sbmod-panel-title').textContent, runText, 'Sauvegarde chiffrée puis opération ; ne fermez pas la page');
			post('modrun', { module: modCurrent.name, op: modCurrent.op, password: pw, confirm: need ? '1' : '', remove: remove ? '1' : '' }).then(function (r) {
				busy = false;
				setButtons(false);
				modResult(true, r.step);
				if (typeof sbToast === 'function') sbToast(r.step, 'success');
			}).catch(function (e) {
				busy = false;
				setButtons(false);
				// Mot de passe refusé : le formulaire revient, avec le message
				if (/Mot de passe incorrect|Jeton invalide/.test(e.message) && modCurrent) { modPanel(modCurrent); modMsg(e.message); }
				else modResult(false, e.message);
			});
		}
	});

	root.addEventListener('click', function (ev) {
		var btn = ev.target.closest('[data-upd-action]');
		if (!btn || busy) return;
		var action = btn.getAttribute('data-upd-action');
		show('sbupd-error', false);
		placeFor(btn, false);

		if (action === 'check') {
			btnBusy(btn, 'Vérification…');
			setButtons(true);
			post('check').then(function () { location.reload(); }).catch(function (e) { error(e.message); });
		} else if (action === 'prepare') {
			setButtons(true);
			busy = true;
			coreBusy('Préparation de la mise à jour…', 'Téléchargement de la version, contrôle de la signature, de l\'archive et de chaque fichier ; rien n\'est encore modifié');
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
		} else if (action === 'dbupgrade') {
			var dpw = $('sbupd-db-password').value;
			if (!dpw) { error('Saisissez votre mot de passe pour confirmer.'); return; }
			$('sbupd-db-password').value = '';
			setButtons(true);
			busy = true;
			coreBusy('Mise à niveau de la base en cours…', 'Sauvegarde de la base, puis migrations ; le site affiche « mise à jour en cours », ne fermez pas la page');
			post('dbupgrade', { password: dpw }).then(function (r) {
				busy = false;
				coreResult(r.step);
				if (typeof sbToast === 'function') sbToast(r.step, 'success');
			}).catch(function (e) { error(e.message); });
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
