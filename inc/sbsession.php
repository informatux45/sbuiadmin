<?php
/* ******************************* *
 * Nom de la session PHP           *
 * ------------------------------- *
 * @link http://informatux.com/    *
 * @package SBUIADMIN              *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */

/*
 * POURQUOI CE FICHIER
 * -------------------
 * Sans appel a session_name(), PHP utilise le cookie PHPSESSID par defaut,
 * pose sur le domaine et le chemin "/". Deux applications servies par le
 * meme domaine partagent alors UNE SEULE ET MEME SESSION.
 *
 * C'est un vrai probleme pour SBUIADMIN, et pas une precaution theorique :
 * plusieurs sites du parc sont batis sur ce socle et ecrivent tous la meme
 * cle $_SESSION['sbuiadmin_user_name']. Passer de l'administration de l'un
 * a celle de l'autre ECRASE l'identite. Et comme la liste des
 * administrateurs (ligne 2 de inc/admin/settings.txt) differe d'une
 * installation a l'autre, l'utilisateur y perd son statut :
 * $sbuiadmin_user_type retombe a "user" et les rubriques declarees
 * group = "admin" dans main.php (Configuration, Journaux, Utilisateurs)
 * disparaissent du menu, sans le moindre message.
 * Symptome constate et diagnostique sur guyacadeau.com / KDOPRO le
 * 2026-09-08 (voir leur commit bd905734 et le briefing GUYASESSID).
 *
 * UN NOM PROPRE A CHAQUE INSTALLATION, TIRE AU SORT
 * -------------------------------------------------
 * Un nom en dur dans le code ne reglerait rien : toutes les installations
 * issues de la meme archive le partageraient, et deux SBUIADMIN sur un meme
 * domaine se marcheraient a nouveau dessus. Le nom est donc TIRE AU SORT une
 * fois, a l'installation, et conserve dans inc/sbsession.txt (fichier propre
 * a l'installation, jamais versionne - voir .gitignore).
 *
 * Le fichier est aussi cree A LA VOLEE si l'on arrive ici sans lui : c'est ce
 * qui rattrape les installations montees AVANT l'ajout de ce mecanisme, sans
 * rien leur demander.
 *
 * A NOTER : la premiere requete qui cree ce fichier change le nom du cookie,
 * ce qui INVALIDE toutes les sessions en cours. Les utilisateurs connectes
 * sont deconnectes une fois, ce jour-la, puis plus jamais.
 *
 * A FAIRE POUR TOUT NOUVEAU POINT D'ENTREE
 * ----------------------------------------
 * Tout fichier PHP appele DIRECTEMENT par le navigateur et qui demarre une
 * session doit inclure ce fichier juste avant son session_start(). Un point
 * d'entree oublie repose sa propre session sous PHPSESSID et perd tout ce
 * que les autres y ont mis.
 */

// --- Emplacement du nom tire au sort. Volontairement a cote de ce fichier :
// --- sbconfig.php n'est pas encore charge a ce stade (la session doit etre
// --- nommee avant tout), on ne peut donc pas s'appuyer sur SBADMIN ni sur la
// --- moindre constante du CMS.
defined('SB_SESSION_FILE') OR define('SB_SESSION_FILE', __DIR__ . DIRECTORY_SEPARATOR . 'sbsession.txt');

/**
* Un nom de cookie valide ? Lettres, chiffres et tiret bas uniquement.
* Sert a se premunir d'un fichier corrompu, tronque ou edite a la main : un
* nom invalide passe silencieusement a session_name() et casse la session.
* @param	string	$name
* @return	bool
*/
if (!function_exists('sbSessionNameIsValid')) {
	function sbSessionNameIsValid($name) {
		return (is_string($name) && preg_match('/^[A-Za-z0-9_]{4,64}$/', $name) === 1);
	}
}

/**
* Tire un nom de session au sort.
* @return	string
*/
if (!function_exists('sbSessionRandomName')) {
	function sbSessionRandomName() {
		try {
			return 'SBUI' . bin2hex(random_bytes(6));
		} catch (Exception $e) {
			// --- Entropie indisponible : on retombe sur le nom deterministe,
			// --- qui reste unique par installation (voir plus bas).
			return sbSessionFallbackName();
		}
	}
}

/**
* Nom de repli, calcule a partir du chemin de l'installation.
*
* Utilise quand le fichier ne peut etre ni lu ni ecrit (hebergement en
* lecture seule, droits mal poses). Il n'est pas aleatoire, mais il est
* STABLE d'une requete a l'autre - c'est la seule propriete indispensable -
* et il reste different d'une installation a l'autre. Un nom de cookie n'est
* pas un secret : il voyage en clair dans chaque en-tete Set-Cookie.
* @return	string
*/
if (!function_exists('sbSessionFallbackName')) {
	function sbSessionFallbackName() {
		return 'SBUI' . substr(md5(__DIR__), 0, 12);
	}
}

/**
* Le nom de session de cette installation : lu, ou tire au sort et conserve.
* @return	string
*/
if (!function_exists('sbSessionResolveName')) {
	function sbSessionResolveName() {
		// --- 1. Deja pose ?
		if (is_readable(SB_SESSION_FILE)) {
			$name = trim((string) @file_get_contents(SB_SESSION_FILE));
			if (sbSessionNameIsValid($name)) return $name;
			// --- Fichier illisible ou corrompu : on le remplace plus bas
		}

		// --- 2. Creation ATOMIQUE : le mode "x" echoue si le fichier existe
		// --- deja, ce qui regle la course entre deux premieres requetes
		// --- simultanees - la perdante relit ce que la gagnante a ecrit au
		// --- lieu d'ecrire un second nom.
		// --- is_writable() en amont : sur un hebergement en lecture seule, le
		// --- fopen echouerait a CHAQUE requete et remplirait le journal
		// --- d'erreurs du serveur pour rien. Le mode "x" reste indispensable
		// --- juste apres : c'est lui qui rend la creation atomique, is_writable
		// --- ne dit rien de la course entre deux requetes simultanees.
		$name = sbSessionRandomName();
		if (!is_writable(dirname(SB_SESSION_FILE))) return sbSessionFallbackName();

		$handle = @fopen(SB_SESSION_FILE, 'x');
		if ($handle !== false) {
			@fwrite($handle, $name . "\n");
			@fclose($handle);
			return $name;
		}

		// --- 3. Echec de creation : soit le fichier vient d'apparaitre
		// --- (course), soit il existe mais est corrompu, soit le repertoire
		// --- n'est pas inscriptible.
		if (is_readable(SB_SESSION_FILE)) {
			$existing = trim((string) @file_get_contents(SB_SESSION_FILE));
			if (sbSessionNameIsValid($existing)) return $existing;

			// --- Corrompu et inscriptible : on ecrase
			if (@file_put_contents(SB_SESSION_FILE, $name . "\n", LOCK_EX) !== false) return $name;
		}

		// --- 4. Rien n'est inscriptible : nom deterministe, stable.
		return sbSessionFallbackName();
	}
}

// --- SB_SESSION_NAME peut etre impose en amont (multi-sites gere a la main,
// --- tests). Sinon on resout depuis le fichier de l'installation.
defined('SB_SESSION_NAME') OR define('SB_SESSION_NAME', sbSessionResolveName());

// --- Uniquement si la session n'est pas DEJA demarree : plusieurs fichiers
// --- du CMS appellent session_start() sur une session active (destruction
// --- puis recreation lors d'une deconnexion). session_name() y emettrait un
// --- avertissement et n'aurait de toute facon aucun effet.
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_name(SB_SESSION_NAME);
}
