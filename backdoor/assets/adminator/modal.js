/*
 * Vanilla-JS replacement for the Bootstrap modal plugin, matching the
 * existing markup contract used across the app: a trigger with
 * data-toggle="modal" data-target="#id" opens the #id.modal element (shown
 * by toggling a class), and any data-dismiss="modal" control inside it
 * closes it. Self-contained: injects its own styles, no separate CSS file
 * needed, no dependency on jQuery or Bootstrap.
 */
(function () {
	var STYLE = '\
.modal{display:none}\
.modal.is-open{align-items:center;background:var(--overlay);display:flex;inset:0;justify-content:center;overflow:auto;padding:24px;position:fixed;z-index:1000}\
.modal .modal-dialog{animation:sb-modal-in .16s ease both;margin:auto;max-width:600px;width:100%}\
.modal .modal-content{background:var(--bg-card);border-radius:14px;box-shadow:var(--shadow-lg);color:var(--t-base);overflow:hidden}\
.modal .modal-header{align-items:center;border-bottom:1px solid var(--border);display:flex;gap:12px;justify-content:space-between;padding:16px 20px}\
.modal .modal-title{margin:0}\
.modal .modal-body{padding:20px}\
.modal .modal-footer{border-top:1px solid var(--border);display:flex;gap:10px;justify-content:flex-end;padding:16px 20px}\
.modal .close{background:none;border:0;color:var(--t-muted);cursor:pointer;font-size:22px;line-height:1;order:2;padding:0}\
.modal .close:hover{color:var(--t-base)}\
@keyframes sb-modal-in{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}\
';

	function injectStyle() {
		if (document.getElementById('sb-modal-style')) return;
		var s = document.createElement('style');
		s.id = 'sb-modal-style';
		s.textContent = STYLE;
		document.head.appendChild(s);
	}

	function openModal(modal) {
		modal.style.display = '';
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
	}

	function closeModal(modal) {
		modal.classList.remove('is-open');
		modal.style.display = 'none';
		modal.setAttribute('aria-hidden', 'true');
	}

	injectStyle();

	document.addEventListener('click', function (e) {
		var trigger = e.target.closest('[data-toggle="modal"]');
		if (trigger) {
			var targetSel = trigger.getAttribute('data-target') || trigger.getAttribute('href');
			var modal = targetSel && document.querySelector(targetSel);
			if (modal) {
				e.preventDefault();
				openModal(modal);
			}
			return;
		}

		var dismiss = e.target.closest('[data-dismiss="modal"]');
		if (dismiss) {
			var openModalEl = dismiss.closest('.modal');
			if (openModalEl) closeModal(openModalEl);
			return;
		}

		if (e.target.classList.contains('modal') && e.target.classList.contains('is-open')) {
			closeModal(e.target);
		}
	});

	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') return;
		var open = document.querySelector('.modal.is-open');
		if (open) closeModal(open);
	});
})();
