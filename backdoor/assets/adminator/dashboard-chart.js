/*
 * Petit graphique SVG maison (ligne + points), sans dépendance. Chart.js
 * est bien chargé sur chaque page (vendor-chartjs.js) mais son scope est
 * privé au bundle webpack du thème (2026.js) - impossible d'y injecter de
 * vraies données sans patcher un fichier minifié. Composant dans le même
 * esprit que datatable.js (Phase 7 : jQuery DataTables remplacé par du
 * vanilla JS maison).
 */
(function () {
	function cssVar(name) {
		var val = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
		return val || '#2563eb';
	}

	function renderChart(container) {
		var labels, values;
		try {
			labels = JSON.parse(container.getAttribute('data-sb-chart-labels') || '[]');
			values = JSON.parse(container.getAttribute('data-sb-chart-values') || '[]');
		} catch (e) {
			return;
		}
		if (!values.length) return;

		var color = cssVar('--' + (container.getAttribute('data-sb-chart-color') || 'primary'));
		var w = container.clientWidth || 300;
		var h = container.clientHeight || 90;
		var pad = 4;
		var max = Math.max.apply(null, values.concat([1]));
		var stepX = (w - pad * 2) / (values.length - 1 || 1);

		var points = values.map(function (v, i) {
			var x = pad + i * stepX;
			var y = h - pad - (v / max) * (h - pad * 2);
			return x.toFixed(1) + ',' + y.toFixed(1);
		});

		var svgNs = 'http://www.w3.org/2000/svg';
		var svg = document.createElementNS(svgNs, 'svg');
		svg.setAttribute('viewBox', '0 0 ' + w + ' ' + h);
		svg.setAttribute('width', '100%');
		svg.setAttribute('height', '100%');
		svg.setAttribute('preserveAspectRatio', 'none');

		var area = document.createElementNS(svgNs, 'polygon');
		area.setAttribute('points', pad + ',' + (h - pad) + ' ' + points.join(' ') + ' ' + (w - pad) + ',' + (h - pad));
		area.setAttribute('fill', color);
		area.setAttribute('opacity', '0.12');
		svg.appendChild(area);

		var line = document.createElementNS(svgNs, 'polyline');
		line.setAttribute('points', points.join(' '));
		line.setAttribute('fill', 'none');
		line.setAttribute('stroke', color);
		line.setAttribute('stroke-width', '2');
		line.setAttribute('stroke-linecap', 'round');
		line.setAttribute('stroke-linejoin', 'round');
		svg.appendChild(line);

		container.innerHTML = '';
		container.appendChild(svg);
		container.title = labels.map(function (l, i) { return l + ' : ' + values[i]; }).join('\n');
	}

	function renderAll() {
		document.querySelectorAll('[data-sb-chart]').forEach(renderChart);
	}

	document.addEventListener('DOMContentLoaded', renderAll);
	// Re-dessine au changement de thème clair/sombre (les couleurs CSS --primary
	// etc. changent), même mécanisme d'observation que 2026.js pour ses propres graphiques.
	new MutationObserver(renderAll).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
})();
