/*
 * Configuration > Serveur : relevés en direct (index.php?p=server&ajax=live)
 * et espace occupé par le site (ajax=sizes). Une valeur null = « non
 * détecté » (open_basedir, mutualisé), jamais 0.
 */
(function () {
	var root = document.getElementById('sbsrv');
	if (!root) return;
	var url = root.getAttribute('data-url');
	var NA = 'non détecté';

	function bytes(n) {
		if (n === null || n === undefined || n === '') return NA;
		n = Number(n);
		var u = ['octets', 'Ko', 'Mo', 'Go', 'To'], i = 0;
		while (n >= 1024 && i < u.length - 1) { n /= 1024; i++; }
		return n.toLocaleString('fr-FR', { maximumFractionDigits: i ? (n < 10 ? 2 : 1) : 0 }) + ' ' + u[i];
	}
	function duration(s) {
		var d = Math.floor(s / 86400), h = Math.floor(s % 86400 / 3600), m = Math.floor(s % 3600 / 60);
		if (s < 60) return Math.floor(s) + ' s';
		return d ? d + ' j ' + h + ' h' : (h ? h + ' h ' + m + ' min' : m + ' min');
	}
	function set(key, text) {
		root.querySelectorAll('[data-live="' + key + '"]').forEach(function (el) { el.textContent = text; });
	}
	// Barre : couleur selon le seuil (vert < 70 %, orange < 90 %, rouge)
	function bar(key, pct) {
		root.querySelectorAll('[data-live-bar="' + key + '"]').forEach(function (el) {
			var p = el.parentNode;
			if (pct === null) { p.style.visibility = 'hidden'; return; }
			p.style.visibility = '';
			el.style.width = Math.min(100, pct) + '%';
			el.classList.remove('success', 'warning', 'danger', 'info');
			el.classList.add(pct < 70 ? 'success' : (pct < 90 ? 'warning' : 'danger'));
		});
	}
	function pct(used, total) { return total ? Math.round(used * 100 / total) : null; }

	// Anneau (sv-radial) : dégradé bleu < 80 %, orange < 90 %, rouge au-delà
	var RADIAL = 201.06; // 2 * PI * r (r = 32)
	function radial(key, p) {
		root.querySelectorAll('[data-live-radial="' + key + '"]').forEach(function (el) {
			var v = p === null ? 0 : Math.max(0, Math.min(100, p));
			el.setAttribute('stroke-dashoffset', (RADIAL * (1 - v / 100)).toFixed(2));
			el.setAttribute('stroke', 'url(#sbsrv-g-' + (v < 80 ? 'ok' : (v < 90 ? 'warn' : 'full')) + ')');
		});
	}

	// --- Charge du processeur en temps réel : courbe SVG maison (le Chart.js
	// du thème est privé au bundle webpack, voir dashboard-chart.js)
	var CPU_POINTS = 60; // 5 min à un relevé toutes les 5 s
	var cpu = [], cpuSum = 0, cpuCount = 0, cpuMax = 0, cpuPct = false;
	var chart = document.getElementById('sbsrv-cpu-chart');
	var SVG = 'http://www.w3.org/2000/svg';

	function fmt(v) { return v.toLocaleString('fr-FR', { maximumFractionDigits: cpuPct ? 0 : 2 }) + (cpuPct ? ' %' : ''); }
	function cssVar(n, def) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim() || def; }
	function node(tag, attrs, parent) {
		var el = document.createElementNS(SVG, tag);
		for (var k in attrs) el.setAttribute(k, attrs[k]);
		if (parent) parent.appendChild(el);
		return el;
	}
	// Courbe lissée (Catmull-Rom vers Bézier), même allure que tension: .4
	function smooth(pts) {
		var d = 'M' + pts[0][0] + ',' + pts[0][1];
		for (var i = 0; i < pts.length - 1; i++) {
			var p0 = pts[i - 1] || pts[i], p1 = pts[i], p2 = pts[i + 1], p3 = pts[i + 2] || p2, t = 0.2;
			d += ' C' + (p1[0] + (p2[0] - p0[0]) * t).toFixed(1) + ',' + (p1[1] + (p2[1] - p0[1]) * t).toFixed(1)
			   + ' ' + (p2[0] - (p3[0] - p1[0]) * t).toFixed(1) + ',' + (p2[1] - (p3[1] - p1[1]) * t).toFixed(1)
			   + ' ' + p2[0].toFixed(1) + ',' + p2[1].toFixed(1);
		}
		return d;
	}

	function cpuPush(d) {
		if (!chart || !d.load) return;
		cpuPct = !!d.cores;
		var v = cpuPct ? d.load[0] * 100 / d.cores : d.load[0];
		cpu.push({ v: v, t: d.time });
		if (cpu.length > CPU_POINTS) cpu.shift();
		cpuSum += v; cpuCount++; cpuMax = Math.max(cpuMax, v);
		set('cpu_unit', (cpuPct ? 'en % des ' + d.cores + ' cœurs' : 'charge moyenne sur 1 min (nombre de cœurs ' + NA + ')')
			+ (d.load_host ? ' · /proc/loadavg illisible : peut être la charge de la machine hôte' : ''));
		set('cpu_now', fmt(v));
		set('cpu_avg', fmt(cpuSum / cpuCount));
		set('cpu_max', fmt(cpuMax));
		set('cpu_long', d.load[1].toLocaleString('fr-FR') + ' · ' + d.load[2].toLocaleString('fr-FR'));
		drawCpu();
	}

	function drawCpu() {
		var w = chart.clientWidth || 600, h = chart.clientHeight || 240;
		var left = 44, right = 8, top = 10, bottom = 24;
		chart.innerHTML = '';
		var svg = node('svg', { viewBox: '0 0 ' + w + ' ' + h, width: w, height: h }, chart);
		var color = cssVar('--primary', '#2563eb');

		// Échelle : 100 % (ou la plus forte valeur) arrondie au palier supérieur
		var top_v = Math.max.apply(null, cpu.map(function (c) { return c.v; }).concat([cpuPct ? 100 : 1]));
		var step = Math.pow(10, Math.floor(Math.log10(top_v)));
		top_v = Math.ceil(top_v / step) * step;
		var y = function (v) { return top + (h - top - bottom) * (1 - v / top_v); };
		for (var i = 0; i <= 4; i++) {
			var gv = top_v * i / 4, gy = y(gv);
			node('line', { x1: left, x2: w - right, y1: gy, y2: gy, 'class': 'grid-line' }, svg);
			node('text', { x: left - 8, y: gy + 4, 'text-anchor': 'end', 'class': 'axis' }, svg).textContent = fmt(gv);
		}
		if (cpuPct && top_v > 100) node('line', { x1: left, x2: w - right, y1: y(100), y2: y(100), 'class': 'limit' }, svg);

		if (cpu.length < 2) {
			node('text', { x: (left + w) / 2, y: h / 2, 'text-anchor': 'middle', 'class': 'empty' }, svg).textContent = 'La courbe se construit (un relevé toutes les 5 secondes)…';
			return;
		}
		var stepX = (w - left - right) / (CPU_POINTS - 1);
		var x0 = w - right - (cpu.length - 1) * stepX; // les derniers relevés à droite
		var pts = cpu.map(function (c, i) { return [x0 + i * stepX, y(c.v)]; });

		var grad = node('linearGradient', { id: 'sbsrv-cpu-fill', x1: 0, y1: 0, x2: 0, y2: 1 }, node('defs', {}, svg));
		node('stop', { offset: 0, 'stop-color': color, 'stop-opacity': 0.28 }, grad);
		node('stop', { offset: 1, 'stop-color': color, 'stop-opacity': 0 }, grad);
		var line = smooth(pts);
		node('path', { d: line + ' L' + pts[pts.length - 1][0].toFixed(1) + ',' + y(0) + ' L' + pts[0][0].toFixed(1) + ',' + y(0) + ' Z', fill: 'url(#sbsrv-cpu-fill)' }, svg);
		node('path', { d: line, fill: 'none', stroke: color, 'stroke-width': 2.5, 'stroke-linejoin': 'round', 'stroke-linecap': 'round' }, svg);
		var last = pts[pts.length - 1];
		node('circle', { cx: last[0], cy: last[1], r: 4.5, fill: color, stroke: cssVar('--bg-card', '#fff'), 'stroke-width': 2.5 }, svg);

		// Heures : premier et dernier relevé, et le milieu s'il y a la place
		var marks = [0, cpu.length - 1], gap = pts[cpu.length - 1][0] - pts[0][0];
		if (gap < 140) marks = [cpu.length - 1];
		else if (gap > 280) marks.splice(1, 0, Math.floor((cpu.length - 1) / 2));
		marks.forEach(function (i) {
			var anchor = i == cpu.length - 1 ? 'end' : (i == 0 ? 'start' : 'middle');
			node('text', { x: pts[i][0], y: h - 6, 'text-anchor': anchor, 'class': 'axis' }, svg).textContent = cpu[i].t;
		});
	}
	if (chart) window.addEventListener('resize', function () { if (cpu.length) drawCpu(); });

	function render(d) {
		var p;
		if (d.disk) {
			p = pct(d.disk.used, d.disk.total);
			set('disk_free', bytes(d.disk.free) + ' libres'); set('disk_total', 'sur ' + bytes(d.disk.total) + ' (partition du site)');
			set('disk_pct', p + ' %'); radial('disk', p);
		} else { set('disk_free', NA); set('disk_total', ''); set('disk_pct', '—'); radial('disk', null); }

		if (d.ram) {
			p = pct(d.ram.used, d.ram.total);
			set('ram_free', bytes(d.ram.free) + ' libres'); set('ram_total', 'sur ' + bytes(d.ram.total));
			set('ram_pct', p + ' %'); bar('ram', p);
		} else { set('ram_free', NA); set('ram_total', ''); set('ram_pct', ''); bar('ram', null); }

		// Charge moyenne : en % seulement si le nombre de cœurs est connu
		if (d.load) {
			set('load', d.load.map(function (v) { return v.toLocaleString('fr-FR'); }).join(' · '));
			if (d.cores) {
				p = Math.round(d.load[0] * 100 / d.cores);
				set('load_pct', p + ' %'); bar('load', p);
				set('cores', '1, 5 et 15 min · ' + d.cores + ' cœur' + (d.cores > 1 ? 's' : '') + (d.cpu ? ' · ' + d.cpu : ''));
			} else {
				set('load_pct', ''); bar('load', null);
				set('cores', '1, 5 et 15 min · nombre de cœurs ' + NA);
			}
		} else { set('load', NA); set('load_pct', ''); bar('load', null); set('cores', ''); }

		cpuPush(d);
		set('uptime', d.uptime !== null ? duration(d.uptime) : NA);
		set('net', d.net ? 'Réseau : ' + bytes(d.net.rx) + ' reçus · ' + bytes(d.net.tx) + ' envoyés' : 'Réseau : ' + NA);
		set('time', d.time);
	}

	function refresh() {
		if (document.hidden) return;
		fetch(url + 'live', { credentials: 'same-origin' })
			.then(function (r) { return r.ok ? r.json() : null; })
			.then(function (d) { if (d) render(d); })
			.catch(function () {});
	}

	root.querySelectorAll('[data-srv-bytes]').forEach(function (el) {
		var v = el.getAttribute('data-srv-bytes');
		if (v !== '') el.textContent = bytes(v);
	});

	try { render(JSON.parse(root.getAttribute('data-live'))); } catch (e) {}
	// Mutualisé : les relevés affichés ne changent pas, pas de rafraîchissement
	if (root.getAttribute('data-type') !== 'shared') setInterval(refresh, 5000);

	fetch(url + 'sizes', { credentials: 'same-origin' })
		.then(function (r) { return r.ok ? r.json() : null; })
		.then(function (rows) {
			var t = document.getElementById('sbsrv-sizes');
			if (!t) return;
			t.innerHTML = '';
			if (!rows) { t.insertRow().insertCell().textContent = 'Calcul impossible'; return; }
			rows.forEach(function (s) {
				var tr = t.insertRow(), th = document.createElement('th');
				th.textContent = s.label; tr.appendChild(th);
				tr.insertCell().textContent = bytes(s.size) + (s.partial ? ' (au moins : calcul interrompu, trop de fichiers)' : '');
			});
		})
		.catch(function () {});
})();
