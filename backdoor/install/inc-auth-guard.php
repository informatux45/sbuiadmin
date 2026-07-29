<?php
/**
 * Point 1 (audit sécurité, 2026-07-29) : backdoor/install/ (installeur
 * tiers ApPHP EasyInstaller) restait accessible sans AUCUNE vérification
 * une fois l'installation terminée - installer.lock n'est écrit qu'en fin
 * d'installation (complete_installation.php), jamais lu/vérifié nulle
 * part pour bloquer un accès ultérieur. N'importe quel visiteur pouvait
 * rouvrir l'assistant (voir/modifier les identifiants de base de données,
 * créer un compte admin...) sur un site déjà en production.
 *
 * Gardé volontairement (pas supprimé, décision du client) pour permettre
 * de futures mises à niveau assistées - mais désormais réservé à un admin
 * déjà connecté au CMS. Repose sur la MÊME session PHP que backdoor/
 * (session déjà démarrée par le fichier appelant avant cet include, même
 * cookie de session que le reste du site) - pas de bootstrap complet du
 * CMS ici (Smarty, connexion DB...) pour rester indépendant du reste de
 * l'installeur tiers.
 */

$sb_install_settings_file = __DIR__ . '/../inc/admin/settings.txt';

$sb_install_authorized = false;
if (isset($_SESSION['sbuiadmin_user_name']) && trim((string)$_SESSION['sbuiadmin_user_name']) != '' && is_readable($sb_install_settings_file)) {
	$sb_install_settings_lines = file($sb_install_settings_file);
	// Ligne 2 (index 1) = liste des administrateurs, séparés par des
	// virgules - même convention que $sbadministrators dans
	// inc/sbuiadmin-config.php.
	$sb_install_admins = isset($sb_install_settings_lines[1]) ? explode(',', trim($sb_install_settings_lines[1])) : array();
	$sb_install_admins = array_map('trim', $sb_install_admins);

	if (in_array(trim($_SESSION['sbuiadmin_user_name']), $sb_install_admins, true)) {
		$sb_install_authorized = true;
	}
}

if (!$sb_install_authorized) {
	http_response_code(403);
	die('Accès refusé. Connectez-vous d\'abord à l\'administration en tant qu\'administrateur avant d\'accéder à cette page.');
}
