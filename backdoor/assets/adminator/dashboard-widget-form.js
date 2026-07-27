/*
 * Formulaire d'ajout/modification d'un widget dashboard (dashboard.php) :
 * cascade Table -> Colonnes côté client, à partir de window.sbDashboardSchema
 * (mapping complet injecté une fois par dashboard.tpl) - pas d'aller-retour
 * AJAX. Désactive aussi "Afficher un graphique" tant qu'aucune colonne date
 * n'est choisie (le graphique a besoin d'une colonne date pour grouper).
 */
(function () {
	function addOption(select, value, label) {
		var opt = document.createElement('option');
		opt.value = value;
		opt.textContent = label;
		select.appendChild(opt);
	}

	function updateChartAvailability(dateSelect) {
		var hasDate = dateSelect.value !== '';
		document.querySelectorAll('input[name="show_chart"]').forEach(function (radio) {
			radio.disabled = !hasDate;
		});
		if (!hasDate) {
			var noRadio = document.querySelector('input[name="show_chart"][value="0"]');
			if (noRadio) noRadio.checked = true;
		}
	}

	function updateTypeFields(typeSelect) {
		var groups = document.querySelectorAll('[data-widget-type-fields]');
		groups.forEach(function (group) {
			group.style.display = (group.getAttribute('data-widget-type-fields') === typeSelect.value) ? '' : 'none';
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		var typeSelect = document.getElementById('type');
		if (typeSelect) {
			typeSelect.addEventListener('change', function () { updateTypeFields(typeSelect); });
			updateTypeFields(typeSelect);
		}

		var tableSelect = document.getElementById('table_name');
		var valueSelect = document.getElementById('value_column');
		var dateSelect  = document.getElementById('date_column');
		var linkInput   = document.getElementById('link');
		if (!tableSelect || !valueSelect || !dateSelect || !window.sbDashboardSchema) return;

		tableSelect.addEventListener('change', function () {
			var columns = Object.keys(window.sbDashboardSchema[tableSelect.value] || {});

			valueSelect.innerHTML = '';
			columns.forEach(function (col) { addOption(valueSelect, col, col); });

			dateSelect.innerHTML = '';
			addOption(dateSelect, '', 'Aucune');
			columns.forEach(function (col) { addOption(dateSelect, col, col); });

			updateChartAvailability(dateSelect);

			// Préremplit le lien avec celui du module associé à la table
			// choisie (window.sbDashboardTableLinks, injecté par
			// dashboard.tpl) - seulement les tables du cœur SBUIADMIN sont
			// couvertes, une table inconnue laisse le champ tel quel.
			if (linkInput && window.sbDashboardTableLinks && window.sbDashboardTableLinks[tableSelect.value]) {
				linkInput.value = window.sbDashboardTableLinks[tableSelect.value];
			}
		});

		dateSelect.addEventListener('change', function () {
			updateChartAvailability(dateSelect);
		});

		updateChartAvailability(dateSelect);
	});
})();
