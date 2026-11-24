/**
 * Module stats : graphiques SVG de l'administration (sans dépendance,
 * comme dashboard-chart.js : le Chart.js du thème est privé à son bundle).
 * Courbe (visites, pages vues) avec réticule et infobulle, barres, anneau.
 * Données : attribut data-sbst-chart (JSON produit par le contrôleur).
 * ©INFORMATUX.COM
 */
(function () {
	'use strict';
	var NS = 'http://www.w3.org/2000/svg';
	var nf = new Intl.NumberFormat('fr-FR');

	function el(name, attrs, parent) {
		var n = document.createElementNS(NS, name);
		for (var k in attrs) if (Object.prototype.hasOwnProperty.call(attrs, k)) n.setAttribute(k, attrs[k]);
		if (parent) parent.appendChild(n);
		return n;
	}
	function html(tag, cls, text) {
		var n = document.createElement(tag);
		if (cls) n.className = cls;
		if (text !== undefined) n.textContent = text;
		return n;
	}
	function css(name) { return getComputedStyle(document.documentElement).getPropertyValue(name).trim(); }
	// Graduations entières et rondes : pas de 1, 2, 5 x 10^n, $n intervalles
	function ticks(v, n) {
		v = Math.max(v, 1);
		var raw = v / n, p = Math.pow(10, Math.floor(Math.log10(raw))), r = raw / p;
		var step = Math.max(1, (r <= 1 ? 1 : r <= 2 ? 2 : r <= 5 ? 5 : 10) * p);
		var max = Math.ceil(v / step) * step, out = [];
		for (var t = 0; t <= max + step / 2; t += step) out.push(t);
		return { max: max, list: out };
	}
	function tip(box) {
		var t = html('div', 'sbst-tip');
		t.hidden = true;
		box.appendChild(t);
		return t;
	}
	function swatch(color) { var s = html('span', 'sw'); s.style.background = color; return s; }

	// ---------------------------------------------------------- Courbe
	function line(box, data) {
		var pts = data.points || [];
		if (!pts.length) return;
		var series = [
			{ key: 'visits', label: 'Visites', cls: 's1', color: css('--sbst-s1') },
			{ key: 'hits', label: 'Pages vues', cls: 's2', color: css('--sbst-s2') }
		];
		var W = box.clientWidth || 600, H = box.clientHeight || 260;
		var m = { t: 28, r: 12, b: 26, l: 40 };
		var w = W - m.l - m.r, h = H - m.t - m.b;
		var max = 0;
		pts.forEach(function (p) { series.forEach(function (s) { if (p[s.key] !== null && p[s.key] > max) max = p[s.key]; }); });
		var tk = ticks(max, 4);
		max = tk.max;
		var step = pts.length > 1 ? w / (pts.length - 1) : 0;
		var x = function (i) { return m.l + (pts.length > 1 ? i * step : w / 2); };
		var y = function (v) { return m.t + h - (v / max) * h; };
		var svg = el('svg', { width: W, height: H, viewBox: '0 0 ' + W + ' ' + H });
		// Grille et axe des valeurs
		tk.list.forEach(function (gv) {
			var gy = y(gv);
			el('line', { x1: m.l, x2: m.l + w, y1: gy, y2: gy, 'class': 'grid-line' }, svg);
			el('text', { x: m.l - 8, y: gy + 4, 'text-anchor': 'end', 'class': 'axis-text' }, svg).textContent = nf.format(gv);
		});
		// Axe du temps : une étiquette sur n, sans chevauchement
		var every = Math.max(1, Math.ceil(pts.length / Math.max(1, Math.floor(w / 64))));
		pts.forEach(function (p, i) {
			if (i % every === 0 || i === pts.length - 1 && pts.length - 1 - (pts.length - 1) % every >= every / 2) {
				el('text', { x: x(i), y: H - 6, 'text-anchor': 'middle', 'class': 'axis-text' }, svg).textContent = p.label;
			}
		});
		// Surface (visites) puis lignes
		var known = pts.map(function (p, i) { return p.visits === null ? null : i; }).filter(function (i) { return i !== null; });
		if (known.length) {
			var area = 'M' + x(known[0]) + ',' + y(0);
			known.forEach(function (i) { area += ' L' + x(i) + ',' + y(pts[i].visits); });
			area += ' L' + x(known[known.length - 1]) + ',' + y(0) + ' Z';
			el('path', { d: area, 'class': 'area' }, svg);
		}
		series.forEach(function (s) {
			var d = '', pen = false;
			pts.forEach(function (p, i) {
				if (p[s.key] === null) { pen = false; return; }
				d += (pen ? ' L' : ' M') + x(i) + ',' + y(p[s.key]);
				pen = true;
			});
			if (d) el('path', { d: d, 'class': 'line ' + s.cls }, svg);
			if (pts.length <= 14) pts.forEach(function (p, i) { if (p[s.key] !== null) el('circle', { cx: x(i), cy: y(p[s.key]), r: 3, 'class': 'dot ' + s.cls }, svg); });
		});
		// Réticule + infobulle
		var cross = el('line', { y1: m.t, y2: m.t + h, 'class': 'cross', visibility: 'hidden' }, svg);
		var hit = el('rect', { x: m.l, y: m.t, width: w, height: h, 'class': 'hit' }, svg);
		box.appendChild(svg);
		var legend = html('ul', 'sbst-legend');
		series.forEach(function (s) { var li = html('li'); li.appendChild(swatch(s.color)); li.appendChild(document.createTextNode(s.label)); legend.appendChild(li); });
		box.appendChild(legend);
		var t = tip(box);
		function show(ev) {
			var r = svg.getBoundingClientRect();
			var px = (ev.touches ? ev.touches[0].clientX : ev.clientX) - r.left;
			var i = pts.length > 1 ? Math.round((px - m.l) / step) : 0;
			i = Math.max(0, Math.min(pts.length - 1, i));
			var p = pts[i];
			cross.setAttribute('x1', x(i)); cross.setAttribute('x2', x(i)); cross.setAttribute('visibility', 'visible');
			t.textContent = '';
			t.appendChild(html('b', '', p.label));
			series.forEach(function (s) {
				var row = html('div');
				row.appendChild(swatch(s.color));
				row.appendChild(document.createTextNode(s.label + ' : ' + (p[s.key] === null ? '—' : nf.format(p[s.key]))));
				t.appendChild(row);
			});
			t.hidden = false;
			t.style.left = Math.max(70, Math.min(W - 70, x(i))) + 'px';
			t.style.top = (p.visits === null ? m.t + h / 2 : Math.max(m.t + 40, y(Math.max(p.visits, p.hits || 0)))) + 'px';
		}
		function hide() { t.hidden = true; cross.setAttribute('visibility', 'hidden'); }
		hit.addEventListener('mousemove', show);
		hit.addEventListener('touchstart', show, { passive: true });
		hit.addEventListener('mouseleave', hide);
	}

	// ---------------------------------------------------------- Barres
	function bars(box, data) {
		var rows = data.data || [];
		if (!rows.length) return;
		var W = box.clientWidth || 600, H = box.clientHeight || 180;
		var m = { t: 10, r: 4, b: 22, l: 36 };
		var w = W - m.l - m.r, h = H - m.t - m.b;
		var tk = ticks(Math.max.apply(null, rows.map(function (r) { return r.value; })), 2), max = tk.max;
		var slot = w / rows.length, bw = Math.max(4, Math.min(28, slot - 2));
		var svg = el('svg', { width: W, height: H, viewBox: '0 0 ' + W + ' ' + H });
		tk.list.forEach(function (gv) {
			var gy = m.t + h - (gv / max) * h;
			el('line', { x1: m.l, x2: m.l + w, y1: gy, y2: gy, 'class': 'grid-line' }, svg);
			el('text', { x: m.l - 6, y: gy + 4, 'text-anchor': 'end', 'class': 'axis-text' }, svg).textContent = nf.format(gv);
		});
		var every = Math.max(1, Math.ceil(rows.length / Math.max(1, Math.floor(w / 34))));
		var t = tip(box);
		rows.forEach(function (r, i) {
			var bh = r.value > 0 ? Math.max(2, (r.value / max) * h) : 0;
			var cx = m.l + slot * i + slot / 2;
			// Barre arrondie en haut, posée sur la ligne de base
			if (bh > 0) {
				var rad = Math.min(4, bw / 2, bh), x0 = cx - bw / 2, y0 = m.t + h - bh, yb = m.t + h;
				el('path', { d: 'M' + x0 + ',' + yb + ' V' + (y0 + rad) + ' Q' + x0 + ',' + y0 + ' ' + (x0 + rad) + ',' + y0 + ' H' + (x0 + bw - rad) + ' Q' + (x0 + bw) + ',' + y0 + ' ' + (x0 + bw) + ',' + (y0 + rad) + ' V' + yb + ' Z', 'class': 'bar' }, svg);
			}
			if (i % every === 0) el('text', { x: cx, y: H - 6, 'text-anchor': 'middle', 'class': 'axis-text' }, svg).textContent = r.label;
			var hit = el('rect', { x: m.l + slot * i, y: m.t, width: slot, height: h, 'class': 'hit' }, svg);
			hit.addEventListener('mouseenter', function () {
				t.textContent = '';
				t.appendChild(html('b', '', r.label));
				t.appendChild(html('div', '', nf.format(r.value) + ' visite' + (r.value > 1 ? 's' : '')));
				t.hidden = false;
				t.style.left = Math.max(50, Math.min(W - 50, cx)) + 'px';
				t.style.top = (m.t + h - bh) + 'px';
			});
			hit.addEventListener('mouseleave', function () { t.hidden = true; });
		});
		box.appendChild(svg);
	}

	// ---------------------------------------------------------- Anneau
	function donut(box, data) {
		var rows = data.data || [];
		var total = rows.reduce(function (a, r) { return a + r.value; }, 0);
		if (!total) return;
		var colors = ['--sbst-s1', '--sbst-s2', '--sbst-s3', '--sbst-s4', '--sbst-s5'].map(css);
		var S = 140, R = 62, r0 = 40, c = S / 2;
		var svg = el('svg', { width: S, height: S, viewBox: '0 0 ' + S + ' ' + S });
		var a0 = -Math.PI / 2;
		var t = tip(box);
		var legend = html('ul', 'sbst-legend');
		rows.forEach(function (r, i) {
			var color = r.label === 'Autres' ? css('--sbst-other') : colors[i % colors.length];
			var frac = r.value / total, a1 = a0 + frac * Math.PI * 2;
			var path;
			if (frac >= 0.9999) {
				path = el('circle', { cx: c, cy: c, r: (R + r0) / 2, fill: 'none', stroke: color, 'stroke-width': R - r0 }, svg);
			} else {
				var large = frac > 0.5 ? 1 : 0;
				var p = function (a, rr) { return (c + rr * Math.cos(a)).toFixed(2) + ',' + (c + rr * Math.sin(a)).toFixed(2); };
				path = el('path', {
					d: 'M' + p(a0, R) + ' A' + R + ',' + R + ' 0 ' + large + ' 1 ' + p(a1, R) + ' L' + p(a1, r0) + ' A' + r0 + ',' + r0 + ' 0 ' + large + ' 0 ' + p(a0, r0) + ' Z',
					fill: color, stroke: css('--bg-card') || '#fff', 'stroke-width': 2
				}, svg);
			}
			var pct = Math.round(frac * 1000) / 10;
			path.addEventListener('mousemove', function (ev) {
				var b = box.getBoundingClientRect();
				t.textContent = '';
				t.appendChild(html('b', '', r.label));
				t.appendChild(html('div', '', nf.format(r.value) + ' (' + String(pct).replace('.', ',') + ' %)'));
				t.hidden = false;
				t.style.left = (ev.clientX - b.left) + 'px';
				t.style.top = (ev.clientY - b.top) + 'px';
			});
			path.addEventListener('mouseleave', function () { t.hidden = true; });
			var li = html('li');
			li.appendChild(swatch(color));
			li.appendChild(document.createTextNode(r.label + ' '));
			li.appendChild(html('strong', '', String(pct).replace('.', ',') + ' %'));
			legend.appendChild(li);
			a0 = a1;
		});
		box.appendChild(svg);
		box.appendChild(legend);
	}


	// ---------------------------------------------------------- Tableau de bord
	function dashboard(root) {
		var saveUrl = root.getAttribute('data-save');
		var status = document.querySelector('[data-sbst-status]');
		var layout;
		try { layout = JSON.parse(root.getAttribute('data-layout') || '{}'); } catch (e) { layout = {}; }
		var timer;
		function say(text) {
			if (!status) return;
			status.textContent = text;
			clearTimeout(timer);
			timer = setTimeout(function () { status.textContent = ''; }, 3000);
		}
		function cols() { return Array.prototype.slice.call(root.querySelectorAll('.sbst-dash-col')); }
		function read() {
			return {
				cols: cols().length,
				columns: cols().map(function (c) { return Array.prototype.map.call(c.querySelectorAll('[data-widget]'), function (w) { return w.getAttribute('data-widget'); }); }),
				collapsed: Array.prototype.map.call(root.querySelectorAll('.sbst-widget.is-collapsed'), function (w) { return w.getAttribute('data-widget'); })
			};
		}
		function post(body, then) {
			say('Enregistrement…');
			fetch(saveUrl, { method: 'POST', body: body, credentials: 'same-origin' })
				.then(function (r) { return r.ok ? r.json() : { ok: false }; })
				.then(function (j) { say(j && j.ok ? 'Tableau de bord enregistré' : 'Enregistrement impossible'); if (j && j.ok && then) then(); })
				.catch(function () { say('Enregistrement impossible'); });
		}
		function save(data, then) {
			var b = new URLSearchParams();
			b.append('layout', JSON.stringify(data || read()));
			post(b, then);
		}
		function rerender(w) { w.querySelectorAll('[data-sbst-chart]').forEach(render); }

		// Glisser-déposer par la poignée (Pointer Events : souris, doigt,
		// stylet) : le widget se déplace en direct, enregistré au lâcher
		var dragged = null, before = '';
		function place(x, y) {
			var list = cols(), col = null, best = Infinity;
			list.forEach(function (c) {
				var r = c.getBoundingClientRect();
				var d = x < r.left ? r.left - x : (x > r.right ? x - r.right : 0);
				if (d < best) { best = d; col = c; }
			});
			if (!col) return;
			list.forEach(function (c) { c.classList.toggle('is-over', c === col); });
			var after = null;
			Array.prototype.some.call(col.querySelectorAll('.sbst-widget'), function (w) {
				if (w === dragged) return false;
				var r = w.getBoundingClientRect();
				if (y < r.top + r.height / 2) { after = w; return true; }
				return false;
			});
			if (after) { if (after.previousElementSibling !== dragged || dragged.parentNode !== col) col.insertBefore(dragged, after); }
			else if (col.lastElementChild !== dragged) col.appendChild(dragged);
		}
		root.addEventListener('pointerdown', function (ev) {
			var grip = ev.target.closest && ev.target.closest('.sbst-grip');
			if (!grip || ev.button !== 0) return;
			ev.preventDefault();
			dragged = grip.closest('.sbst-widget');
			before = JSON.stringify(read().columns);
			try { grip.setPointerCapture(ev.pointerId); } catch (e) {}
			root.classList.add('is-dragging');
			document.body.classList.add('sbst-noselect');
			dragged.classList.add('is-dragged');
		});
		root.addEventListener('pointermove', function (ev) {
			if (!dragged) return;
			ev.preventDefault();
			place(ev.clientX, ev.clientY);
			// Défilement près des bords de la fenêtre
			if (ev.clientY < 60) window.scrollBy(0, -18);
			else if (ev.clientY > window.innerHeight - 60) window.scrollBy(0, 18);
		});
		function drop() {
			if (!dragged) return;
			var w = dragged;
			dragged = null;
			w.classList.remove('is-dragged');
			root.classList.remove('is-dragging');
			document.body.classList.remove('sbst-noselect');
			cols().forEach(function (c) { c.classList.remove('is-over'); });
			if (JSON.stringify(read().columns) !== before) { rerender(w); save(); }
		}
		root.addEventListener('pointerup', drop);
		root.addEventListener('pointercancel', drop);
		root.addEventListener('lostpointercapture', drop);

		// Boutons des widgets (clavier, tactile)
		root.addEventListener('click', function (ev) {
			var btn = ev.target.closest && ev.target.closest('.sbst-widget-tools button');
			if (!btn) return;
			var w = btn.closest('.sbst-widget');
			var col = w.parentNode, list = cols(), ci = list.indexOf(col);
			var move = btn.getAttribute('data-move');
			if (move === 'up' && w.previousElementSibling) col.insertBefore(w, w.previousElementSibling);
			else if (move === 'down' && w.nextElementSibling) col.insertBefore(w.nextElementSibling, w);
			else if (move === 'left' && ci > 0) list[ci - 1].appendChild(w);
			else if (move === 'right' && ci < list.length - 1) list[ci + 1].appendChild(w);
			else if (btn.hasAttribute('data-collapse')) {
				var c = w.classList.toggle('is-collapsed');
				btn.setAttribute('aria-expanded', c ? 'false' : 'true');
				if (!c) rerender(w);
			} else if (btn.hasAttribute('data-remove')) {
				w.parentNode.removeChild(w);
				save(null, null);
				return;
			} else return;
			if (move) { rerender(w); btn.focus(); }
			save();
		});

		// Nombre de colonnes : les widgets des colonnes retirées passent dans la dernière
		document.querySelectorAll('[data-sbst-cols]').forEach(function (b) {
			b.addEventListener('click', function () {
				var n = parseInt(b.getAttribute('data-sbst-cols'), 10), d = read();
				if (n === d.cols) return;
				while (d.columns.length > n) { var extra = d.columns.pop(); d.columns[d.columns.length - 1] = d.columns[d.columns.length - 1].concat(extra); }
				while (d.columns.length < n) d.columns.push([]);
				d.cols = n;
				save(d, function () { location.reload(); });
			});
		});

		// Ajouter un widget (en tête de la première colonne)
		var addBtn = document.querySelector('[data-sbst-add]'), addSel = document.querySelector('[data-sbst-add-select]');
		if (addBtn && addSel) addBtn.addEventListener('click', function () {
			var id = addSel.value;
			if (!id) { addSel.focus(); return; }
			if (root.querySelector('[data-widget="' + id.replace(/[^a-z0-9_]/g, '') + '"]')) { say('Ce widget est déjà sur le tableau de bord'); return; }
			var d = read();
			d.columns[0].unshift(id);
			save(d, function () { location.reload(); });
		});

		// Réinitialiser : un second clic confirme (pas de boîte de dialogue du navigateur)
		var reset = document.querySelector('[data-sbst-reset]');
		if (reset) {
			var armed = false, label = reset.innerHTML, rt;
			reset.addEventListener('click', function () {
				if (!armed) {
					armed = true;
					reset.textContent = 'Confirmer la réinitialisation';
					rt = setTimeout(function () { armed = false; reset.innerHTML = label; }, 4000);
					return;
				}
				clearTimeout(rt);
				var b = new URLSearchParams();
				b.append('op', 'reset');
				post(b, function () { location.reload(); });
			});
		}
	}

	// ---------------------------------------------------------- Carte des pays
	// jsVectorMap et sa carte « world » sont livrés par le thème (vendors.js,
	// chargé après ce script) : dessin au chargement complet de la page
	function worldMap(box, data) {
		if (!window.jsVectorMap) { box.textContent = ''; return; }
		var values = data.data || {}, names = data.names || {};
		var max = 1;
		for (var k in values) if (values[k] > max) max = values[k];
		var id = box.id || ('sbst-map-' + Math.random().toString(36).slice(2));
		box.id = id;
		box.textContent = '';
		try {
			new window.jsVectorMap({
				selector: '#' + id, map: 'world', zoomButtons: false, zoomOnScroll: false,
				regionStyle: { initial: { fill: css('--border') || '#e2e8f0', stroke: css('--bg-card') || '#fff', strokeWidth: 0.4 } },
				series: { regions: [{ attribute: 'fill', values: values, scale: [css('--primary-soft') || '#dbeafe', css('--sbst-s1') || '#2a78d6'], normalizeFunction: 'polynomial' }] },
				onRegionTooltipShow: function (ev, tooltip, code) {
					var n = values[code] || 0;
					tooltip.text((names[code] || tooltip.text()) + ' : ' + nf.format(n) + ' visite' + (n > 1 ? 's' : ''));
				}
			});
		} catch (e) { box.textContent = ''; }
	}

	function render(box) {
		var data;
		try { data = JSON.parse(box.getAttribute('data-sbst-chart') || '{}'); } catch (e) { return; }
		box.textContent = '';
		if (data.type === 'line') line(box, data);
		else if (data.type === 'bars') bars(box, data);
		else if (data.type === 'donut') donut(box, data);
		else if (data.type === 'map') { if (document.readyState === 'complete') worldMap(box, data); else window.addEventListener('load', function () { worldMap(box, data); }); }
	}
	function renderAll() { document.querySelectorAll('[data-sbst-chart]').forEach(render); }

	function init() {
		renderAll();
		// Redessin au redimensionnement et au changement de thème
		var timer;
		window.addEventListener('resize', function () { clearTimeout(timer); timer = setTimeout(renderAll, 200); });
		new MutationObserver(renderAll).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

		var dash = document.querySelector('[data-sbst-dash]');
		if (dash) dashboard(dash);

		// Sélecteur de période : jour unique ou plage
		var form = document.querySelector('[data-sbst-period]');
		if (form) {
			var sel = form.querySelector('select[name="period"]');
			var single = form.querySelector('[data-sbst-single]'), range = form.querySelector('[data-sbst-range]');
			var sync = function () {
				var isRange = sel.value === 'range';
				single.hidden = isRange; range.hidden = !isRange;
				single.querySelectorAll('input').forEach(function (i) { i.disabled = isRange; });
				range.querySelectorAll('input').forEach(function (i) { i.disabled = !isRange; });
			};
			sel.addEventListener('change', sync);
			sync();
		}

		// Case qui enregistre aussitôt (mise à jour automatique de la base des pays)
		document.querySelectorAll('[data-sbst-autosubmit]').forEach(function (c) {
			c.addEventListener('change', function () { if (c.form.requestSubmit) c.form.requestSubmit(); else c.form.submit(); });
		});

		// Temps réel : rechargement tant que l'onglet est visible
		// Arrêt au bout de data-sbst-refresh-max minutes (début gardé d'un
		// rechargement à l'autre) : la session d'administration peut expirer
		var live = document.querySelector('[data-sbst-refresh]');
		if (live) {
			var sec = Math.max(10, parseInt(live.getAttribute('data-sbst-refresh'), 10) || 30);
			var maxMin = parseInt(live.getAttribute('data-sbst-refresh-max'), 10) || 0;
			var KEY = 'sbst-realtime-start';
			var store = function (v) { try { if (v === null) sessionStorage.removeItem(KEY); else sessionStorage.setItem(KEY, String(v)); } catch (e) {} };
			var start = 0;
			try { start = parseInt(sessionStorage.getItem(KEY), 10) || 0; } catch (e) {}
			if (!start || Date.now() - start > (maxMin + 60) * 60000) { start = Date.now(); store(start); }
			var note = document.querySelector('[data-sbst-refresh-note]');
			var expired = function () { return maxMin <= 0 || Date.now() - start >= maxMin * 60000; };
			if (maxMin > 0 && expired() && note) note.hidden = false;
			var resume = document.querySelector('[data-sbst-refresh-resume]');
			if (resume) resume.addEventListener('click', function () { store(Date.now()); location.reload(); });
			setInterval(function () {
				if (document.visibilityState !== 'visible') return;
				if (expired()) { if (note && maxMin > 0) note.hidden = false; return; }
				location.reload();
			}, sec * 1000);
		}
	}
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
	else init();
})();
