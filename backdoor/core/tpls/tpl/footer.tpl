{literal}
<script>
// Œil afficher/masquer sur chaque champ mot de passe (admin et connexion).
// Styles : .pw-wrap / .pw-toggle dans assets/adminator/bridge.css.
(function () {
	var EYE     = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>';
	var EYE_OFF = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-7 0-11-7-11-7a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 7 11 7a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/></svg>';
	var fields = document.querySelectorAll('input[type="password"]');
	for (var i = 0; i < fields.length; i++) {
		var input = fields[i];
		if (input.parentNode.classList.contains('pw-wrap')) continue;
		// Sans autocomplete, le navigateur y injecte le mot de passe admin
		// enregistré (SMTP, mot de passe d'un autre utilisateur...), qui est
		// ensuite sauvé à l'enregistrement. La connexion déclare current-password.
		if (!input.hasAttribute('autocomplete')) input.setAttribute('autocomplete', 'new-password');
		var wrap = document.createElement('span');
		wrap.className = 'pw-wrap';
		// Largeur posée en ligne par sbform (style="width: 250px") : portée par l'enveloppe
		wrap.style.width = input.style.width || '100%';
		input.style.width = '';
		input.parentNode.insertBefore(wrap, input);
		wrap.appendChild(input);
		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'pw-toggle';
		btn.setAttribute('aria-label', 'Afficher le mot de passe');
		btn.innerHTML = EYE;
		btn.addEventListener('click', function () {
			var field = this.parentNode.querySelector('input');
			var show  = (field.type === 'password');
			field.type = show ? 'text' : 'password';
			this.innerHTML = show ? EYE_OFF : EYE;
			this.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
		});
		wrap.appendChild(btn);
	}
})();
</script>
{/literal}
</body>

</html>