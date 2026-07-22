/*
 * Vanilla-JS replacement for jQuery DataTables, matching Adminator's
 * .data-table markup (datatable.html). Operates purely client-side on
 * rows already rendered server-side — no change to how the PHP/Smarty
 * side fetches or renders data.
 *
 * Usage:
 *   <input data-datatable-search="dataTables-users" class="input" type="search">
 *   <table id="dataTables-users" class="data-table" data-datatable data-page-size="15">
 *     <thead><tr><th data-sort="false">...</th><th>Name<span class="sort">...</span></th></tr></thead>
 *     <tbody><tr class="data-row"><td>...</td><td data-sort-value="20260722143000">22/07/2026</td></tr></tbody>
 *   </table>
 *   <div data-datatable-foot="dataTables-users">
 *     <div class="data-foot-info" data-foot-info></div>
 *     <select data-page-size-select><option value="15">15 par page</option>...</select>
 *     <div class="pager"></div>
 *   </div>
 */
(function () {
	var PREV_SVG = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>';
	var NEXT_SVG = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>';

	function initTable(table) {
		var tbody = table.tBodies[0];
		if (!tbody) return;
		var headRow = table.tHead ? table.tHead.rows[0] : null;
		var headers = headRow ? Array.prototype.slice.call(headRow.cells) : [];
		var allRows = Array.prototype.slice.call(tbody.rows);

		var id = table.id;
		var pageSize = parseInt(table.getAttribute('data-page-size'), 10) || 15;
		var currentPage = 1;
		var sortCol = -1;
		var sortDir = 1;
		var searchTerm = '';

		var searchInput = id ? document.querySelector('[data-datatable-search="' + id + '"]') : null;
		var foot = id ? document.querySelector('[data-datatable-foot="' + id + '"]') : null;
		var infoEl = foot ? foot.querySelector('[data-foot-info]') : null;
		var pagerEl = foot ? foot.querySelector('.pager') : null;
		var pageSizeSelect = foot ? foot.querySelector('[data-page-size-select]') : null;

		function rowMatches(row) {
			if (!searchTerm) return true;
			return row.textContent.toLowerCase().indexOf(searchTerm) !== -1;
		}

		function cellValue(row, colIndex) {
			var cell = row.cells[colIndex];
			if (!cell) return '';
			var raw = cell.getAttribute('data-sort-value');
			return (raw !== null ? raw : cell.textContent).trim();
		}

		function compareRows(a, b) {
			if (sortCol < 0) return 0;
			var av = cellValue(a, sortCol).toLowerCase();
			var bv = cellValue(b, sortCol).toLowerCase();
			var an = parseFloat(av);
			var bn = parseFloat(bv);
			var cmp;
			if (!isNaN(an) && !isNaN(bn) && String(an) === av && String(bn) === bv) {
				cmp = an - bn;
			} else {
				cmp = av < bv ? -1 : (av > bv ? 1 : 0);
			}
			return cmp * sortDir;
		}

		function renderPager(totalPages) {
			if (!pagerEl) return;
			pagerEl.innerHTML = '';

			function addBtn(label, page, opts) {
				opts = opts || {};
				var b = document.createElement('button');
				b.type = 'button';
				b.className = 'pager-btn' + (opts.active ? ' is-active' : '');
				b.innerHTML = label;
				if (opts.ariaLabel) b.setAttribute('aria-label', opts.ariaLabel);
				if (opts.disabled) {
					b.disabled = true;
				} else {
					b.addEventListener('click', function () {
						currentPage = page;
						render();
					});
				}
				pagerEl.appendChild(b);
			}

			addBtn(PREV_SVG, currentPage - 1, { disabled: currentPage <= 1, ariaLabel: 'Précédent' });

			var shown = [];
			if (totalPages <= 7) {
				for (var p = 1; p <= totalPages; p++) shown.push(p);
			} else {
				shown.push(1);
				if (currentPage > 3) shown.push('…');
				for (var p2 = Math.max(2, currentPage - 1); p2 <= Math.min(totalPages - 1, currentPage + 1); p2++) shown.push(p2);
				if (currentPage < totalPages - 2) shown.push('…');
				shown.push(totalPages);
			}
			shown.forEach(function (p) {
				if (p === '…') {
					addBtn('…', 0, { disabled: true });
				} else {
					addBtn(String(p), p, { active: p === currentPage });
				}
			});

			addBtn(NEXT_SVG, currentPage + 1, { disabled: currentPage >= totalPages, ariaLabel: 'Suivant' });
		}

		function render() {
			var filtered = allRows.filter(rowMatches);
			if (sortCol >= 0) filtered.sort(compareRows);

			var totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
			if (currentPage > totalPages) currentPage = totalPages;
			var start = (currentPage - 1) * pageSize;
			var visible = filtered.slice(start, start + pageSize);

			allRows.forEach(function (r) {
				if (r.parentNode === tbody) tbody.removeChild(r);
			});
			visible.forEach(function (r) {
				tbody.appendChild(r);
			});

			if (infoEl) {
				infoEl.innerHTML = filtered.length
					? 'Affichage <strong>' + (start + 1) + '–' + Math.min(start + pageSize, filtered.length) + '</strong> sur <strong>' + filtered.length + '</strong>'
					: 'Aucun résultat';
			}

			renderPager(totalPages);
		}

		headers.forEach(function (th, idx) {
			if (th.getAttribute('data-sort') === 'false') return;
			th.style.cursor = 'pointer';
			th.addEventListener('click', function () {
				if (sortCol === idx) {
					sortDir = -sortDir;
				} else {
					sortCol = idx;
					sortDir = 1;
				}
				headers.forEach(function (h) {
					h.classList.remove('sorted-asc', 'sorted-desc');
				});
				th.classList.add(sortDir === 1 ? 'sorted-asc' : 'sorted-desc');
				currentPage = 1;
				render();
			});
		});

		if (searchInput) {
			searchInput.addEventListener('input', function () {
				searchTerm = searchInput.value.trim().toLowerCase();
				currentPage = 1;
				render();
			});
		}

		if (pageSizeSelect) {
			pageSizeSelect.addEventListener('change', function () {
				pageSize = parseInt(pageSizeSelect.value, 10) || pageSize;
				currentPage = 1;
				render();
			});
		}

		render();
	}

	function init() {
		Array.prototype.forEach.call(document.querySelectorAll('table[data-datatable]'), initTable);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
