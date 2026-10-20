<?php
/**
 * Accroche du module de sécurité Sentinelle (datas/modules/sentinelle/)
 *
 * Le cœur signale ses événements de sécurité (sbSecurityEvent) et laisse
 * Sentinelle inspecter chaque requête (sbSentinelleGuard). Sans le module
 * (absent, non installé, ou fichier de secours inc/admin/sentinelle-disabled),
 * ces fonctions ne font rien. Une erreur du module n'empêche jamais une page
 * de s'afficher.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

/** Fichier de secours : sa présence désactive Sentinelle (blocage de soi-même) */
function sbSentinelleRescueFile() {
	return __DIR__ . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'sentinelle-disabled';
}

/** Sentinelle installée, chargée et non désactivée ? */
function sbSentinelleReady($reset = false) {
	static $ready = null;
	if ($reset) $ready = null;
	if ($ready !== null) return $ready;
	$ready = false;
	if (is_file(sbSentinelleRescueFile()) || !function_exists('sbModuleActive') || !function_exists('sbModulesDir')) return $ready;
	try {
		$guard = sbModulesDir() . 'sentinelle' . DIRECTORY_SEPARATOR . 'guard.php';
		if (sbModuleActive('sentinelle') && is_file($guard)) {
			require_once($guard);
			$ready = function_exists('sentinelle_event') && function_exists('sentinelle_guard');
		}
	} catch (Throwable $e) {
		$ready = false;
	}
	return $ready;
}

/**
 * Événement de sécurité (connexion refusée, jeton CSRF invalide, IP bloquée...)
 * @param string $type     identifiant court (login_failed, csrf_failed...)
 * @param string $severity info, warning ou critical
 * @param string $detail   précision lisible (jamais de mot de passe)
 * @param string $action   logged, blocked ou banned
 * @param string $user     identifiant concerné, s'il est connu
 */
function sbSecurityEvent($type, $severity = 'warning', $detail = '', $action = 'logged', $user = '') {
	if (!sbSentinelleReady()) return;
	try {
		sentinelle_event((string) $type, (string) $severity, (string) $detail, (string) $action, (string) $user);
	} catch (Throwable $e) {
	}
}

/** Inspection de la requête par Sentinelle ('front' ou 'admin') */
function sbSentinelleGuard($context) {
	if (!sbSentinelleReady()) return;
	try {
		sentinelle_guard((string) $context);
	} catch (Throwable $e) {
	}
}
