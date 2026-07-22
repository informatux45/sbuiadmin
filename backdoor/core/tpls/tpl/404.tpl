<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>404 &middot; {$smarty.const._AM_SITE_CUSTOMER_NAME}</title>
	<script>
		(function () {
			try {
				var stored = localStorage.getItem('dash26-theme');
				var theme = stored || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
				document.documentElement.setAttribute('data-theme', theme);
			} catch (e) {
				document.documentElement.setAttribute('data-theme', 'light');
			}
		})();
	</script>
	<link href="assets/adminator/fonts/fonts.css" rel="stylesheet">
	<link href="assets/adminator/style.css" rel="stylesheet">
</head>
<body>
	<div class="error-shell">
		<div class="error-card">
			<span class="error-eyebrow">Erreur &middot; Introuvable</span>
			<div class="error-code">404</div>
			<h1 class="error-title">Cette page est introuvable</h1>
			<p class="error-sub">Le lien est peut-être obsolète, ou la page a été déplacée ou supprimée.</p>
			<div class="error-actions">
				<a href="{$smarty.const._AM_SITE_URL}" class="btn btn--primary">
					<svg viewBox="0 0 24 24"><path d="M3 12 12 3l9 9"/><path d="M5 10v10h14V10"/></svg>
					Retour à l'accueil
				</a>
				<a href="javascript:history.back()" class="btn btn--ghost">
					<svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
					Page précédente
				</a>
			</div>
		</div>
	</div>
</body>
</html>
