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

$sb_install_lock_file     = __DIR__ . '/installer/installer.lock';

$sb_install_authorized = false;

/*
 * Cas de la PREMIÈRE installation : tant que l'installeur n'a jamais été
 * mené à son terme (installer.lock absent - ce fichier n'est écrit qu'à
 * la toute fin, par complete_installation.php), il n'existe évidemment
 * aucun compte administrateur avec lequel se connecter au préalable.
 * Exiger une session admin dès cet instant rendrait le CMS purement et
 * simplement ininstallable après téléchargement.
 *
 * Mais laisser l'assistant ouvert à n'importe quel visiteur permettrait à
 * un tiers d'installer le CMS à sa place (base, compte administrateur).
 * On exige donc une CLÉ D'INSTALLATION, créée au premier passage dans
 * installer/install-key.php (jamais affichée par le site : seule une
 * personne qui a accès aux fichiers du serveur peut la lire). La clé
 * saisie une fois est mémorisée dans la session.
 */
$sb_install_key_file = __DIR__ . '/installer/install-key.php';

if (!file_exists($sb_install_lock_file)) {
	if (!empty($_SESSION['sbuiadmin_install_key_ok'])) {
		$sb_install_authorized = true;
	} else {
		$sb_install_key = '';
		if (is_readable($sb_install_key_file) && preg_match('/KEY:([0-9a-f]{32})/', (string) @file_get_contents($sb_install_key_file), $m)) {
			$sb_install_key = $m[1];
		} elseif (is_dir(dirname($sb_install_key_file)) && is_writable(dirname($sb_install_key_file))) {
			// Création atomique ('x') : deux premières requêtes simultanées
			// ne peuvent pas produire deux clés différentes.
			$sb_install_new_key = bin2hex(random_bytes(16));
			$h = @fopen($sb_install_key_file, 'x');
			if ($h !== false) {
				@fwrite($h, "<?php http_response_code(404); exit; // KEY:" . $sb_install_new_key . "\n");
				@fclose($h);
				@chmod($sb_install_key_file, 0640);
				$sb_install_key = $sb_install_new_key;
			} elseif (is_readable($sb_install_key_file) && preg_match('/KEY:([0-9a-f]{32})/', (string) @file_get_contents($sb_install_key_file), $m)) {
				$sb_install_key = $m[1];
			}
		}

		$sb_install_key_error = '';
		if ($sb_install_key !== '' && isset($_POST['install_key'])) {
			if (hash_equals($sb_install_key, trim((string) $_POST['install_key']))) {
				session_regenerate_id(true);
				$_SESSION['sbuiadmin_install_key_ok'] = true;
				$sb_install_authorized = true;
				// La requête qui portait la clé était un POST de formulaire de
				// clé : on repart d'une page propre plutôt que de laisser
				// l'assistant traiter ce POST.
				if (!isset($_POST['task'])) {
					header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
					exit;
				}
			} else {
				usleep(500000);
				$sb_install_key_error = 'Clé incorrecte.';
			}
		}

		if (!$sb_install_authorized) {
			http_response_code(403);
			$e = function ($t) { return htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); };
			echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Installation</title>'
			   . '<style>body{font-family:sans-serif;max-width:560px;margin:60px auto;padding:0 16px;line-height:1.5}code{background:#eee;padding:2px 5px}input{padding:6px;width:100%;box-sizing:border-box}button{margin-top:10px;padding:8px 16px}.err{color:#b00}</style></head><body>'
			   . '<h2>Installation de SBUIADMIN</h2>';
			if ($sb_install_key === '') {
				echo '<p>Le dossier <code>backdoor/install/installer/</code> n\'est pas inscriptible : la clé d\'installation n\'a pas pu être créée. '
				   . 'Rendez ce dossier inscriptible, puis rechargez cette page.</p>';
			} else {
				$sb_key_path = basename(dirname(__DIR__)) . '/install/installer/install-key.php';
				echo '<p>Pour protéger l\'installation, saisissez la clé d\'installation. Elle est écrite sur le serveur, dans le fichier '
				   . '<code>' . $e($sb_key_path) . '</code> (après <code>KEY:</code>) : ouvrez-le avec votre client FTP ou SSH, ou le gestionnaire de fichiers de votre hébergeur.</p>'
				   . '<p style="color:#555;font-size:.92em">Pourquoi ? Tant que l\'installation n\'est pas terminée, cette page est publique : sans cette clé, n\'importe qui pourrait installer le site à votre place et en devenir l\'administrateur. Seul celui qui a accès aux fichiers du serveur peut la lire. Elle ne sert qu\'une fois et disparaît à la fin de l\'installation.</p>';
				if ($sb_install_key_error !== '') echo '<p class="err">' . $e($sb_install_key_error) . '</p>';
				echo '<form method="post"><input type="text" name="install_key" autocomplete="off" autofocus>'
				   . '<button type="submit">Continuer</button></form>';
			}
			echo '</body></html>';
			exit;
		}
	}
} else {
	// Installation terminée : la clé n'a plus d'objet.
	if (file_exists($sb_install_key_file)) @unlink($sb_install_key_file);
}

if (!$sb_install_authorized && isset($_SESSION['sbuiadmin_user_name']) && trim((string)$_SESSION['sbuiadmin_user_name']) != '') {
	// Liste des administrateurs : réglage "administrators" (en base depuis
	// la migration de settings.txt, voir inc/sbuiadmin-settings.php) - même
	// convention que $sbadministrators dans inc/sbuiadmin-config.php.
	require_once(__DIR__ . '/../inc/sbuiadmin-settings.php');
	$sb_install_admins = array_map('trim', explode(',', sbSetting('administrators')));
	if (in_array(trim($_SESSION['sbuiadmin_user_name']), $sb_install_admins, true)) {
		$sb_install_authorized = true;
	}
}
if (!$sb_install_authorized) {
	http_response_code(403);
	die('Accès refusé. Connectez-vous d\'abord à l\'administration en tant qu\'administrateur avant d\'accéder à cette page.');
}
