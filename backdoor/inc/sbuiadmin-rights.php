<?php
/**
 * Admin Startbootstrap
 * Droits granulaires par utilisateur et par module (voir/ajouter/modifier/supprimer)
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

/* -------------------------------
 * Available functions :
 * -------------------------------
 * sbGetRightsSubmodules
 * sbGetRightsGroups
 * sbGetRightsModules
 * sbGetEffectiveModuleKey
 * sbClassifyAction
 * sbCheckRightRow
 * sbGetCurrentUserId
 * sbHasRight
 * sbHasMenuLinkRight
 * sbGetRightsMatrix
 * sbSaveRightsMatrix
 * ------------------------------- */

/**
 * Sous-sections déclinées séparément dans la matrice de droits : certaines
 * actions d'un module forment un sous-ensemble assez distinct (catégories,
 * paramètres, sous-pages CMS Config...) pour mériter leur propre ligne
 * (voir/ajouter/modifier/supprimer indépendants du reste du module).
 * Contrairement à sbGetRightsModules(), cette liste n'est PAS auto-découverte
 * (rien dans le code ne signale "cette action forme une sous-section") : un
 * nouveau module qui veut ce découpage doit ajouter son entrée ici à la main.
 * @return array [module => [action => ['key' => sous-clé, 'label' => libellé]]]
 */
function sbGetRightsSubmodules() {
	return array(
		'faq'       => array(
			'category' => 'Catégories', 'categorydel' => 'Catégories', 'categoryadd' => 'Catégories', 'categoryedit' => 'Catégories',
		),
		'news'      => array(
			'category' => 'Catégories', 'categorydel' => 'Catégories', 'categoryadd' => 'Catégories', 'categoryedit' => 'Catégories',
			'settings' => 'Paramètres', 'settingscategory' => 'Paramètres',
		),
		'contact'   => array(
			'settings' => 'Paramètres',
		),
		'users'     => array(
			'blockedip' => 'IPs bloquées', 'delblockedip' => 'IPs bloquées',
			'blockedipsettings' => 'Paramètres IPs bloquées',
		),
		'settings'  => array(
			// Clé "samples" jamais atteinte par une vraie requête (assets/samples/
			// est un lien statique, pas de p=) - cette ligne n'existe que pour être
			// rattachée explicitement depuis main.php (li['rights']), afin de
			// pouvoir masquer ce lien dans le menu comme n'importe quel autre.
			'samples' => 'Thème sample',
		),
		'tabbs'     => array(
			'deltab' => 'Onglets', 'alltabs' => 'Onglets', 'tabadd' => 'Onglets', 'tabedit' => 'Onglets',
		),
		'cmsconfig' => array(
			'headerfooter' => 'En-tête/Pied de page',
			'css'          => 'CSS',
			'javascript'   => 'JavaScript',
			'comingsoon'   => 'Coming soon',
			'multilang'    => 'Multilingue',
			'plugins'      => 'Plugins',
			'fonts'        => 'Polices',
			'seo'          => 'SEO',
		),
	);
}

/**
 * Lignes de la matrice de droits, groupées comme le menu principal :
 * "Configuration" (contrôleurs à la racine de backdoor/ - settings,
 * logaccess, users, medias, cmsconfig, menu...) et "Modules" (contrôleurs
 * dans datas/modules/ - faq, news, pages, tabbs...), même distinction que
 * sbGetMenuModule('admin') vs sbGetMenuModule('main'). Une ligne par module
 * de $sb_safe_pages ayant un vrai contrôleur PHP, plus une ligne par
 * sous-section déclarée dans sbGetRightsSubmodules(). Le libellé vient de
 * $module_menu[$page]['main'] s'il existe, sinon le nom brut de la page
 * (ex: 'blocs', qui n'a pas d'entrée $module_menu propre). Auto-découverte
 * des MODULES : un nouveau module (ajouté à $sb_safe_pages + son fichier
 * .php, comme pour FAQ) apparaît ici sans code supplémentaire - ses
 * éventuelles sous-sections, non (voir sbGetRightsSubmodules()).
 * @return array ex: ['Configuration' => ['settings' => 'Configuration', ...], 'Modules' => ['faq' => 'FAQ', 'faq:category' => 'FAQ → Catégories', ...]]
 */
function sbGetRightsGroups() {
	global $sb_safe_pages, $module_menu;

	// $module_menu est peuplé par main.php - inclus ici défensivement
	// (include_once) au cas où cette fonction serait appelée avant
	// sbGetMenuModule() dans le flux de la requête.
	include_once(_AM_SITE_DIR . 'main.php');

	$submodules = sbGetRightsSubmodules();
	$groups     = array('Configuration' => array(), 'Modules' => array());

	foreach ((array)$sb_safe_pages as $page) {
		if ($page == 'index') continue;

		$is_root = file_exists(_AM_SITE_DIR . $page . '.php');
		$path    = $is_root ? _AM_SITE_DIR . $page . '.php' : _AM_SITE_DIR . 'datas/modules/' . $page . '.php';

		if (!file_exists($path)) continue;

		$label   = (!empty($module_menu[$page]['main'])) ? $module_menu[$page]['main'] : $page;
		$section = $is_root ? 'Configuration' : 'Modules';
		$groups[$section][$page] = $label;

		if (isset($submodules[$page])) {
			// Plusieurs actions peuvent partager la même sous-section
			// (ex: category/categoryadd/categoryedit/categorydel -> "Catégories")
			// - on ne veut qu'une seule ligne par sous-clé de libellé.
			$sub_labels = array_unique($submodules[$page]);
			foreach ($sub_labels as $sub_label) {
				$sub_key = array_search($sub_label, $submodules[$page]);
				$groups[$section][$page . ':' . $sub_key] = $label . ' → ' . $sub_label;
			}
		}
	}

	return $groups;
}

/**
 * Version à plat de sbGetRightsGroups() (module/sous-section => libellé,
 * sans le groupement Configuration/Modules) - pour tout ce qui n'a besoin
 * que de la liste, pas de l'affichage (calcul de la matrice, sauvegarde).
 * @return array ex: ['faq' => 'FAQ', 'faq:category' => 'FAQ → Catégories', 'blocs' => 'blocs', ...]
 */
function sbGetRightsModules() {
	$groups = sbGetRightsGroups();
	return array_merge($groups['Configuration'], $groups['Modules']);
}

/**
 * Détermine la ligne effective de la matrice de droits pour $module :
 * 'faq:category' si l'action fait partie d'une sous-section déclarée
 * (sbGetRightsSubmodules()), sinon $module lui-même. Par défaut, l'action
 * est lue sur la requête courante ($_GET['a']/['op']) ; on peut aussi la
 * passer explicitement (ex: pour évaluer un lien de menu qui n'est pas la
 * page actuellement affichée, voir sbHasMenuLinkRight()).
 *
 * Plusieurs actions peuvent partager une même sous-section (ex: tabbs ->
 * deltab/alltabs/tabadd/tabedit partagent toutes le libellé "Onglets") -
 * sbGetRightsGroups() ne crée alors qu'UNE seule ligne, sous la clé
 * déclarée en premier (ici "deltab"). Il faut donc résoudre vers CETTE clé
 * canonique, pas l'action brute de la requête, sinon la plupart de ces
 * actions pointeraient vers une ligne qui n'existe pas dans la table (donc
 * jamais restreignable - absence de ligne = accès complet par défaut).
 * @param string $module
 * @param string|null $action
 * @return string
 */
function sbGetEffectiveModuleKey($module, $action = null) {
	$submodules = sbGetRightsSubmodules();
	if (!isset($submodules[$module])) return $module;

	if ($action === null) {
		// op= avant a= : cmsconfig est le seul module qui route sur op=, et son
		// URL de soumission contient EN PLUS un a=edit vestige (jamais lu par le
		// switch interne de cmsconfig.php, qui ne regarde que op=) - si a= passe
		// en premier, cmsconfig&a=edit&op=headerfooter résout "edit" au lieu de
		// "headerfooter" et retombe sur la ligne du module parent au lieu de la
		// sous-section. Aucun autre module n'utilise op=, donc cet ordre ne
		// change rien pour eux.
		$action = '';
		if (isset($_GET['op'])) $action = $_GET['op'];
		elseif (isset($_GET['a'])) $action = $_GET['a'];
	}
	$action = trim((string)$action);

	if (!isset($submodules[$module][$action])) return $module;

	$sub_label     = $submodules[$module][$action];
	$canonical_key = array_search($sub_label, $submodules[$module]);

	return $module . ':' . $canonical_key;
}

/**
 * Classe la requête courante ($_GET['a'] ou $_GET['op']) en 'view' / 'add' /
 * 'edit' / 'delete' pour un module donné. Table d'exceptions par module
 * (actions qui ne suivent pas la convention add/edit/del générale, ex:
 * "sort", "settings", "menu"...) sinon heuristique par mot-clé sur le nom
 * de l'action. Issu d'un audit exhaustif de tous les contrôleurs.
 * @param string $module
 * @return string 'view'|'add'|'edit'|'delete'
 */
function sbClassifyAction($module) {
	// Cas particulier : medias.php n'a aucun paramètre d'action - $_GET['del']
	// y contient un nom de fichier (pas un mot-clé), pas de a=add non plus
	// (l'ajout se fait par upload POST direct sur la page).
	if ($module == 'medias') {
		if (isset($_GET['del']) && $_GET['del'] != '') return 'delete';
		if ($_SERVER['REQUEST_METHOD'] === 'POST') return 'add';
		return 'view';
	}

	// Cas particulier : cache.php/server.php n'ont qu'un seul "case" (default
	// ET "cache" regroupés) - op=cache ne distingue donc jamais "afficher la
	// page" de "vider les caches" : c'est la soumission POST du formulaire
	// (unlink() des fichiers) qui fait la différence, pas la valeur de op.
	if ($module == 'cache' || $module == 'server') {
		return ($_SERVER['REQUEST_METHOD'] === 'POST') ? 'delete' : 'view';
	}

	// Cas particulier : theme.php n'utilise ni a= ni op=, l'activation d'un
	// thème se fait via $_GET['th'] (nom du thème choisi).
	if ($module == 'theme') {
		return (isset($_GET['th']) && $_GET['th'] != '') ? 'edit' : 'view';
	}

	// Cas particulier : themeinfos.php/session.php/dashboard.php n'ont aucun
	// paramètre d'action du tout - formulaire unique qui se soumet en POST
	// sur lui-même (pas de a=/op= pour distinguer affichage et modification).
	if ($module == 'themeinfos' || $module == 'session' || $module == 'dashboard') {
		return ($_SERVER['REQUEST_METHOD'] === 'POST') ? 'edit' : 'view';
	}

	// Cas particulier : la page "Droits d'accès" (users, a=menu) doit se
	// comporter EXACTEMENT comme "Modifier" un utilisateur (a=edit, qui - lui -
	// tombe dans l'heuristique "edit" ci-dessous, toujours stricte) : 403
	// direct sans "modifier", pas de mode consultation-seule comme pour les
	// pages de paramètres/tri classiques (voir le traitement des $overrides
	// plus bas, qui lui autorise la simple vue en GET).
	if ($module == 'users' && isset($_GET['a']) && trim($_GET['a']) == 'menu') {
		return 'edit';
	}

	// Cas particulier : "Modifier" (a=edit) reste toujours accessible en
	// libre-service pour SA PROPRE fiche - au moins le mot de passe, users.php
	// restreint alors les autres champs - même sans "modifier" sur "users",
	// qui reste requis pour éditer la fiche d'un AUTRE utilisateur. La
	// restriction fine par champ est appliquée dans users.php lui-même.
	if ($module == 'users' && isset($_GET['a']) && trim($_GET['a']) == 'edit') {
		$target_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if ($target_id > 0 && $target_id == sbGetCurrentUserId()) return 'view';
	}

	// op= avant a= : l'URL de soumission de cmsconfig contient un a=edit
	// vestige EN PLUS de op=headerfooter/css/... (voir sbGetEffectiveModuleKey) -
	// a= passerait devant et résoudrait "edit" au lieu de la vraie sous-page.
	$action = '';
	if (isset($_GET['op'])) $action = $_GET['op'];
	elseif (isset($_GET['a'])) $action = $_GET['a'];
	$action = trim((string)$action);

	if ($action == '') return 'view';

	$overrides = array(
		'users'     => array('blockedipsettings' => 'edit', 'blockedip' => 'view'),
		'contact'   => array('settings' => 'edit'),
		'news'      => array('settings' => 'edit', 'settingscategory' => 'edit', 'tpl_list' => 'edit', 'tpl_single' => 'edit', 'sort' => 'edit'),
		'faq'       => array('sort' => 'edit', 'category' => 'view'),
		'slider'    => array('sort' => 'edit', 'photo' => 'view'),
		'tabbs'     => array('sort' => 'edit', 'alltabs' => 'view'),
		'table'     => array('sortstructure' => 'edit', 'sortdatas' => 'edit'),
		'menu'      => array('sort' => 'edit'),
		'blocs'     => array('sort' => 'edit', 'sortmod' => 'edit'),
		'sandbox'   => array('sort' => 'edit'),
		'cmsconfig' => array('headerfooter' => 'edit', 'css' => 'edit', 'javascript' => 'edit', 'comingsoon' => 'edit', 'multilang' => 'edit', 'plugins' => 'edit', 'fonts' => 'edit', 'seo' => 'edit'),
	);
	if (isset($overrides[$module][$action])) {
		$classified = $overrides[$module][$action];
		// La plupart de ces pages (paramètres, tri, sous-pages CMS Config...)
		// n'ont qu'une seule valeur d'action pour l'affichage ET l'enregistrement
		// - c'est $_POST['form_submit'] (ou équivalent) qui distingue les deux à
		// l'intérieur du contrôleur, pas l'action elle-même. Classer "edit" sans
		// condition bloquerait donc la simple CONSULTATION du formulaire dès que
		// "modifier" est décoché, alors que "voir" reste coché (ex: CMS Config →
		// En-tête/Pied de page). Seule une requête POST (soumission réelle)
		// justifie "edit" ; sinon c'est une simple lecture.
		if ($classified == 'edit' && $_SERVER['REQUEST_METHOD'] !== 'POST') return 'view';
		return $classified;
	}

	if (strpos($action, 'del') !== false)  return 'delete';
	if (strpos($action, 'add') !== false)  return 'add';
	if (strpos($action, 'edit') !== false) return 'edit';

	return 'view';
}

/**
 * Lit la ligne sb_users_rights de $key pour $user_id et vérifie $action.
 * "Voir" est la porte d'entrée de CETTE ligne : désactivé, plus aucune
 * action (add/edit/delete) n'y est accessible même si cochée par ailleurs.
 * Absence de ligne = accès complet par défaut (comportement d'avant
 * restriction explicite - évite de bloquer tout le monde tant que l'admin
 * n'a rien configuré).
 * @param string $key module ou "module:sous-section"
 * @param string $action 'view'|'add'|'edit'|'delete'
 * @param int $user_id
 * @return bool
 */
function sbCheckRightRow($key, $action, $user_id) {
	global $sbsql;

	$table   = _AM_DB_PREFIX . 'sb_users_rights';
	$key_esc = $sbsql->escape_string($key);
	$query   = "SELECT can_view, can_add, can_edit, can_delete FROM $table WHERE user_id = $user_id AND module = '$key_esc'";
	$request = $sbsql->query($query);
	$row     = $sbsql->assoc($request);

	if (!$row) return true;
	if ($row['can_view'] != 1) return false;
	if ($action == 'view') return true;

	$column = 'can_' . $action;
	return isset($row[$column]) ? ($row[$column] == 1) : true;
}

/**
 * Vérifie si l'utilisateur connecté a le droit $action ('view'/'add'/'edit'/
 * 'delete', déduit de la requête courante si omis) sur $module. Si l'action
 * courante appartient à une sous-section déclarée (sbGetRightsSubmodules(),
 * ex: "faq" + a=category -> "faq:category"), c'est la ligne de LA
 * SOUS-SECTION qui est vérifiée, en TOTALE INDÉPENDANCE du module parent -
 * décocher "voir" sur "Tabbs" ne retire plus l'accès à "Tabbs → Onglets" si
 * cette ligne reste cochée elle-même (chaque ligne de la matrice est sa
 * propre permission autonome). Un ancien garde-fou couplait les deux
 * (sous-section bloquée si le parent était masqué), mais ça cassait
 * exactement ce cas : une sous-section ouverte alors que le module parent,
 * lui, est fermé.
 *
 * Pas de contournement pour la page de gestion des droits elle-même (module
 * "users", action "menu") : un ancien garde-fou rendait cette page TOUJOURS
 * accessible, mais ça permettait à n'importe quel utilisateur de modifier
 * les droits de N'IMPORTE QUI, "modifier"/"supprimer" décochés ou pas. La
 * matrice s'applique donc ici normalement ; la protection anti-auto-blocage
 * est assurée ailleurs, à l'enregistrement (voir sbSaveRightsMatrix()), qui
 * empêche un utilisateur de retirer SES PROPRES "voir"/"modifier" sur
 * "users" - il garde toujours accès à cette page pour ses propres droits,
 * mais ne peut pas toucher à ceux des autres sans "modifier".
 * @param string $module
 * @param string|null $action
 * @return bool
 */
function sbHasRight($module, $action = null) {
	if ($action === null) $action = sbClassifyAction($module);

	$user_id = sbGetCurrentUserId();
	if ($user_id <= 0) return false;

	$effective = sbGetEffectiveModuleKey($module);

	return sbCheckRightRow($effective, $action, $user_id);
}

/**
 * Résout l'id numérique de l'utilisateur connecté à partir de la session
 * (seul $_SESSION['sbuiadmin_user_name'] existe, pas d'id en session).
 * @return int 0 si aucun utilisateur résolu
 */
function sbGetCurrentUserId() {
	global $sbusers;

	$username = isset($_SESSION['sbuiadmin_user_name']) ? trim($_SESSION['sbuiadmin_user_name']) : '';
	if ($username == '') return 0;

	return intval($sbusers->getUserInfo($username, 'id'));
}

/**
 * Variante de sbHasRight() pour un LIEN DE MENU quelconque (pas forcément
 * la page actuellement affichée) - utilisée pour décider si une entrée du
 * menu principal doit être affichée. Un module avec sous-menu (ex:
 * "Configuration" -> session/cache/server/..., "Pages" -> blocs) peut
 * pointer vers un module entièrement différent de celui du groupe parent :
 * chaque lien doit donc être vérifié pour lui-même, avec SON propre p=
 * (et a=/op= s'il en a un), pas avec la requête courante.
 * Toujours visible : liens externes ou hors routeur sans rattachement
 * explicite (pas de p=, ex: Gravatar) - rien à vérifier dans la matrice.
 * Un lien statique/externe peut malgré tout être rattaché à une ligne de la
 * matrice via $rights_key ("module" ou "module:sous-clé", posé sur le li
 * lui-même dans main.php - ex: "Thème sample" -> "settings:samples"), pour
 * rester masquable même sans p= à analyser.
 * @param string $link ex: "index.php?p=cache" ou "index.php?p=cmsconfig&op=css"
 * @param string|null $rights_key rattachement explicite, prioritaire sur $link
 * @return bool
 */
function sbHasMenuLinkRight($link, $rights_key = null) {
	$user_id = sbGetCurrentUserId();

	if ($rights_key !== null && $rights_key !== '') {
		if ($user_id <= 0) return false;
		return sbCheckRightRow($rights_key, 'view', $user_id);
	}

	if (empty($link)) return true;

	$params = array();
	parse_str((string)parse_url($link, PHP_URL_QUERY), $params);
	if (!isset($params['p']) || trim($params['p']) == '') return true;

	$module       = trim($params['p']);
	$action_param = isset($params['a']) ? $params['a'] : (isset($params['op']) ? $params['op'] : '');

	if ($user_id <= 0) return false;

	$effective = sbGetEffectiveModuleKey($module, $action_param);

	return sbCheckRightRow($effective, 'view', $user_id);
}

/**
 * Matrice complète des droits d'un utilisateur : tous les modules connus
 * (sbGetRightsModules()), croisés avec les lignes existantes en base -
 * accès complet par défaut pour tout module sans ligne dédiée. Utilisé
 * par l'écran de gestion des droits (users.php, action "menu").
 * @param int $user_id
 * @return array ex: ['faq' => ['label'=>'FAQ','can_view'=>1,'can_add'=>1,'can_edit'=>1,'can_delete'=>1], ...]
 */
function sbGetRightsMatrix($user_id) {
	global $sbsql;

	$user_id = intval($user_id);
	$modules = sbGetRightsModules();
	$table   = _AM_DB_PREFIX . 'sb_users_rights';

	$query   = "SELECT module, can_view, can_add, can_edit, can_delete FROM $table WHERE user_id = $user_id";
	$request = $sbsql->query($query);
	$rows    = $sbsql->toarray($request);

	$existing = array();
	if ($rows) {
		foreach ($rows as $row) {
			$existing[$row['module']] = $row;
		}
	}

	$matrix = array();
	foreach ($modules as $key => $label) {
		$matrix[$key] = array(
			'label'      => $label,
			'can_view'   => isset($existing[$key]) ? intval($existing[$key]['can_view'])   : 1,
			'can_add'    => isset($existing[$key]) ? intval($existing[$key]['can_add'])    : 1,
			'can_edit'   => isset($existing[$key]) ? intval($existing[$key]['can_edit'])   : 1,
			'can_delete' => isset($existing[$key]) ? intval($existing[$key]['can_delete']) : 1,
		);
	}

	return $matrix;
}

/**
 * Enregistre la matrice de droits soumise par le formulaire (case cochée =
 * autorisé, décochée = absente du POST). Upsert (INSERT ... ON DUPLICATE
 * KEY UPDATE) sur la clé unique (user_id, module).
 *
 * Garde-fou anti-auto-blocage : quand $user_id est l'utilisateur CONNECTÉ
 * (il modifie ses propres droits), "voir" et "modifier" sur "users" sont
 * forcés à 1 quoi qu'il ait coché - sans ça, il pourrait se retirer l'accès
 * à cette page et se retrouver dans l'impossibilité de jamais la rouvrir
 * pour se rétablir (pour lui-même ou qui que ce soit d'autre). Ne s'applique
 * qu'à SA PROPRE ligne : modifier les droits d'un AUTRE utilisateur reste
 * entièrement soumis à la matrice (voir sbHasRight()).
 * @param int $user_id
 * @param array $postData $_POST du formulaire - attend $postData['rights'][$module]['view'|'add'|'edit'|'delete']
 * @return bool
 */
function sbSaveRightsMatrix($user_id, $postData) {
	global $sbsql;

	$user_id      = intval($user_id);
	$is_self      = ($user_id == sbGetCurrentUserId());
	$modules      = sbGetRightsModules();
	$table        = _AM_DB_PREFIX . 'sb_users_rights';
	$ok           = true;

	foreach ($modules as $key => $label) {
		$can_view   = isset($postData['rights'][$key]['view'])   ? 1 : 0;
		$can_add    = isset($postData['rights'][$key]['add'])    ? 1 : 0;
		$can_edit   = isset($postData['rights'][$key]['edit'])   ? 1 : 0;
		$can_delete = isset($postData['rights'][$key]['delete']) ? 1 : 0;

		if ($is_self && $key == 'users') {
			$can_view = 1;
			$can_edit = 1;
		}

		$module_esc = $sbsql->escape_string($key);

		$query = "INSERT INTO $table (user_id, module, can_view, can_add, can_edit, can_delete)
				  VALUES ($user_id, '$module_esc', $can_view, $can_add, $can_edit, $can_delete)
				  ON DUPLICATE KEY UPDATE can_view = $can_view, can_add = $can_add, can_edit = $can_edit, can_delete = $can_delete";
		$result = $sbsql->query($query);
		if (!$result) $ok = false;
	}

	return $ok;
}

?>
