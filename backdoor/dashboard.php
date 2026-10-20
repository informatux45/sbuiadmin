<?php
/**
 * Admin Startbootstrap
 * Manage DASHBOARD (widgets configurables du tableau de bord)
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');

// -----------------------
// Start Session
// -----------------------
// --- Nom de la session : DOIT etre pose avant session_start(), sinon ce
// --- point d'entree repose sa propre session sous PHPSESSID et perd tout
// --- ce que les autres y ont mis. Voir inc/sbsession.php.
require_once(__DIR__ . '/../inc/sbsession.php');
if (session_status() !== PHP_SESSION_ACTIVE) session_start(); // inclus par index.php : session déjà ouverte

// -----------------------
// Module URL
// -----------------------
$module_page = 'dashboard';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);

// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------
$table = _AM_DB_PREFIX . "sb_dashboard_widgets";
$text  = "Widget";

$sb_color_options = array(
	'primary' => 'Bleu',
	'success' => 'Vert',
	'info'    => 'Cyan',
	'purple'  => 'Violet',
	'danger'  => 'Rouge',
	'warning' => 'Orange',
);

// Les 3 types de widgets (Point 3, suite) : "table" (existant, une tuile +
// une liste "Récent" à partir d'une table SQL), "system" (une métrique
// serveur/PHP toute faite, voir sbGetSystemWidgetValue()) et "weather"
// (météo d'une ville via Open-Meteo, voir sbGeocodeCity()/
// sbGetWeatherWidgetValue()). Un seul mécanisme de gestion pour les 3.
$sb_type_options = array(
	'table'   => 'Table SQL',
	'system'  => 'Widget système',
	'weather' => 'Météo',
	'html'    => 'Code HTML personnalisé',
	'text'    => 'Texte (éditeur enrichi)',
	'rss'       => 'Flux RSS',
	'iframe'    => 'Contenu externe (iframe)',
	'logs'      => 'Journal (fichier log)',
	'logaccess' => 'Dernières connexions',
	'module'    => 'Widget de module',
);

// Icône du sélecteur de type (boutons cliquables du formulaire) - purement
// cosmétique, sans rapport avec le champ "Icône" du widget lui-même.
$sb_type_icons = array(
	'table'     => 'table',
	'system'    => 'hdd-o',
	'weather'   => 'cloud',
	'html'      => 'code',
	'text'      => 'align-left',
	'rss'       => 'rss',
	'iframe'    => 'external-link',
	'logs'      => 'file-text-o',
	'logaccess' => 'sign-in',
	'module'    => 'puzzle-piece',
);

$sb_system_options = array(
	'users_count'     => 'Nombre d\'utilisateurs',
	'php_version'     => 'Version PHP',
	'db_host'         => 'DB Host',
	'upload_limit'    => 'Limite d\'upload',
	'disk_free'       => 'Espace disque libre',
	'media_size'      => 'Espace utilisé par les médias',
	'active_sessions' => 'Sessions actives',
);

// Lien admin par défaut associé à chaque table connue (préremplissage JS du
// champ "Lien" au choix de la table, voir dashboard-widget-form.js) - pas de
// métadonnée en base reliant une table SQL à sa page d'admin : tables du core
// ici, celles des modules installés viennent de leur module.php ('tables') ;
// une table inconnue (table "Tableaux" custom...) laisse le champ vide.
$sb_table_links = array(
	'sb_sandbox'      => 'index.php?p=sandbox',
	'sb_pages'        => 'index.php?p=pages',
	'sb_blocs'        => 'index.php?p=blocs',
	'sb_menu'         => 'index.php?p=menu',
	'sb_users'        => 'index.php?p=users',
	'sb_messages'     => 'index.php?p=messages',
	'sb_logaccess'    => 'index.php?p=logaccess',
);

// Titre lisible associé à chaque table connue - utilisé à la place du nom
// SQL brut dans le <select> "Table" du formulaire et dans la colonne
// "Table" de la liste. Une table absente de cette liste (module tiers,
// table "Tableaux" custom...) affiche simplement son nom SQL tel quel.
$sb_table_titles = array(
	'sb_sandbox'            => 'Sandbox (démo)',
	'sb_pages'              => 'Pages',
	'sb_blocs'              => 'Blocs',
	'sb_blocs_sort'         => 'Ordre des blocs',
	'sb_menu'               => 'Menus',
	'sb_users'              => 'Utilisateurs',
	'sb_users_rights'       => 'Droits utilisateurs',
	'sb_messages'           => 'Messages',
	'sb_dashboard_widgets'  => 'Widgets du dashboard',
	'sb_logaccess'          => 'Journaux de connexion',
	'sb_config'             => 'Configuration',
	'sb_country'            => 'Pays',
	'sb_flood'              => 'Anti-flood',
	'sb_attempts'           => 'Tentatives de connexion',
	'sb_blocked_ip'         => 'IPs bloquées',
	'sb_blocked_history'    => 'Historique des IP bloquées',
	'sb_sessions'           => 'Sessions',
);
foreach (sbModulesCollect('tables') as $sb_tname => $sb_tinfo) {
	if (!empty($sb_tinfo['link']))  $sb_table_links[$sb_tname]  = $sb_tinfo['link'];
	if (!empty($sb_tinfo['title'])) $sb_table_titles[$sb_tname] = $sb_tinfo['title'];
}
unset($sb_tname, $sb_tinfo);

// Widgets livrés par les modules installés (datas/modules/<nom>/widgets/,
// voir sbModuleWidgets()) : type "module", widget_key = "nom:clé",
// paramètres en JSON dans "content"
$sb_module_widgets = sbModuleWidgets();

$action = $_GET['a'] ?? '';
// PHP 8 : variables lues par certaines branches seulement
$query_2 = '';
switch ($action) {
	case "del":
	case "up":
	case "down":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id  = intval($_GET['id'] ?? 0);
			$query_2 = "DELETE FROM $table WHERE id = '$get_id'";
			$request = $sbsql->query($query_2);

			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Réordonnancement simple : échange de position avec le voisin
		// immédiat (pas de glisser-déposer, juste ↑/↓ sur chaque ligne).
		if ($action == 'up' || $action == 'down') {
			$get_id  = intval($_GET['id'] ?? 0);
			$current = $sbsql->assoc($sbsql->query("SELECT id, position FROM $table WHERE id = '$get_id'"));

			if ($current) {
				$op       = ($action == 'up') ? '<' : '>';
				$order    = ($action == 'up') ? 'DESC' : 'ASC';
				$neighbor = $sbsql->assoc($sbsql->query("SELECT id, position FROM $table WHERE position $op '" . intval($current['position']) . "' ORDER BY position $order LIMIT 1"));

				if ($neighbor) {
					$sbsql->query("UPDATE $table SET position = '" . intval($neighbor['position']) . "' WHERE id = '" . intval($current['id']) . "'");
					$sbsql->query("UPDATE $table SET position = '" . intval($current['position']) . "' WHERE id = '" . intval($neighbor['id']) . "'");
				}
			}
		}

		// Initialisation
		$sb_table_header = ['Position', 'Titre', 'Type', 'Source', 'Tendance/graphique', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);

		// Contents table
		$query_0  = "SELECT * FROM $table ORDER BY position ASC";
		$request0 = $sbsql->query($query_0);
		$result0  = $sbsql->toarray($request0);
		foreach ($result0 as &$sb_row) {
			// Affichage liste uniquement : "location" est stockée
			// "Ville|lat|lon" (voir sbGeocodeCity()), seule la ville est utile ici.
			$sb_row['location'] = ($sb_row['location'] != '') ? explode('|', $sb_row['location'])[0] : '';
		}
		unset($sb_row);

		$sbsmarty->assign('all', true);
		$sbsmarty->assign('all_widgets', $result0);
		$sbsmarty->assign('sb_table_titles', $sb_table_titles);
		$sbsmarty->assign('sb_type_options', $sb_type_options);
		$sbsmarty->assign('sb_system_options', $sb_system_options);
		$sb_module_widget_labels = array();
		foreach ($sb_module_widgets as $sb_mw_id => $sb_mw) $sb_module_widget_labels[$sb_mw_id] = $sb_mw['module_title'] . ' › ' . $sb_mw['title'];
		$sbsmarty->assign('sb_module_widget_labels', $sb_module_widget_labels);

		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query_0;
			if (isset($action) && $action == 'del') {
				$alldel_debug .= "\n" . 'DEL: ' . $query_2;
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}

	break;

	case "add":
	case "edit":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$id              = ($action == 'edit' && isset($_GET['id'])) ? intval($_GET['id']) : 0;
		$formName        = ($action == 'add') ? "add_form" : "edit_form";
		$formType        = ($action == 'add' || ($_POST['form_submit'] ?? '') == 'add_form') ? "add" : "edit";
		$btn_add_edit    = ($action == 'add') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'add') ? "Ajouter un widget" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";

		// Schéma réel de la base - jamais faire confiance à une table/
		// colonne soumise sans la confronter à cette liste (voir
		// sbGetDbSchema(), inc/sbuiadmin-functions.php).
		$sb_schema = sbGetDbSchema();

		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		$query = '';
		if (!empty($_POST['form_submit'])) {

			// Injection des données
			$id           = intval($_POST['id'] ?? 0);
			$type         = in_array($_POST['type'] ?? null, array('table', 'system', 'weather', 'html', 'text', 'rss', 'iframe', 'logs', 'logaccess', 'module')) ? ($_POST['type'] ?? '') : 'table';
			// Widget de module : seul le jeu de paramètres du widget choisi compte
			$module_widget = is_string($_POST['module_widget'] ?? null) ? $_POST['module_widget'] : '';
			$mw_params     = (isset($_POST['mwp'][$module_widget]) && is_array($_POST['mwp'][$module_widget])) ? $_POST['mwp'][$module_widget] : array();
			// Largeur d'une carte : vide = celle du widget, stockée dans "value_column"
			$mw_width      = in_array($_POST['mw_width'] ?? '', array('4', '6', '8', '12'), true) ? $_POST['mw_width'] : '';
			$table_name   = $sbsanitize->displayText($_POST['table_name'] ?? '', 'UTF-8', 1, 0);
			$value_column = $sbsanitize->displayText($_POST['value_column'] ?? '', 'UTF-8', 1, 0);
			$date_column  = $sbsanitize->displayText($_POST['date_column'] ?? '', 'UTF-8', 1, 0);
			$widget_key   = $sbsanitize->displayText($_POST['widget_key'] ?? '', 'UTF-8', 1, 0);
			$city_input   = $sbsanitize->displayText($_POST['city'] ?? '', 'UTF-8', 1, 0);
			// content_html (widget "html") et content_text (widget "text",
			// CKEditor) alimentent la même colonne "content" en base - seul
			// le champ pertinent pour $type est non vide côté formulaire.
			$content_html = $sbsanitize->displayText($_POST['content_html'] ?? '', 'UTF-8', 1, 0);
			$content_text = $sbsanitize->displayText($_POST['content_text'] ?? '', 'UTF-8', 1, 0);
			// rss/iframe/logs réutilisent "location" (URL ou nom de fichier
			// selon le type) et "value_column" (nombre d'éléments/lignes) -
			// pas de nouvelle colonne, mêmes colonnes que "weather"/"table"
			// mais avec un sens différent selon $type.
			$rss_url      = $sbsanitize->displayText($_POST['rss_url'] ?? '', 'UTF-8', 1, 0);
			$rss_count    = $sbsanitize->displayText($_POST['rss_count'] ?? '', 'UTF-8', 1, 0);
			$iframe_url   = $sbsanitize->displayText($_POST['iframe_url'] ?? '', 'UTF-8', 1, 0);
			$log_filename    = $sbsanitize->displayText($_POST['log_filename'] ?? '', 'UTF-8', 1, 0);
			$log_lines       = $sbsanitize->displayText($_POST['log_lines'] ?? '', 'UTF-8', 1, 0);
			$logaccess_count = $sbsanitize->displayText($_POST['logaccess_count'] ?? '', 'UTF-8', 1, 0);
			$widget_title    = $sbsanitize->displayText($_POST['widget_title'] ?? '', 'UTF-8', 1, 0);
			$link         = $sbsanitize->displayText($_POST['link'] ?? '', 'UTF-8', 1, 0);
			$icon         = $sbsanitize->displayText($_POST['icon'] ?? '', 'UTF-8', 1, 0);
			$color        = $sbsanitize->displayText($_POST['color'] ?? '', 'UTF-8', 1, 0);
			$show_chart   = $sbsanitize->displayText($_POST['show_chart'] ?? '', 'UTF-8', 1, 0);
			$active       = $sbsanitize->displayText($_POST['active'] ?? '', 'UTF-8', 1, 0);

			// Normalisation : les radios "show_chart" sont désactivées côté
			// JS tant qu'aucune colonne date n'est choisie (voir
			// dashboard-widget-form.js) - un champ disabled n'est jamais
			// envoyé dans le POST, ce qui laisserait une chaîne vide arriver
			// jusqu'à une colonne tinyint (rejet MySQL en mode strict).
			// Toujours '0' ou '1', et jamais '1' sans colonne date (le
			// contournement du JS désactivé ne doit pas suffire à l'activer).
			$show_chart = ($show_chart == '1') ? '1' : '0';
			$active     = ($active == '1') ? '1' : '0';

			// Validation propre à chaque type - un seul des 3 jeux de champs
			// est réellement pertinent selon $type, le reste est vidé pour
			// ne rien laisser d'incohérent en base.
			$location = '';
			$content  = '';
			switch ($type) {
				case 'system':
					$sb_valid = array_key_exists($widget_key, $sb_system_options);
					if (!$sb_valid) $sb_msg_error = 'Métrique système invalide.';
					$table_name = $value_column = $date_column = '';
					$show_chart = '0';
					break;

				case 'weather':
					$sb_valid = false;
					if ($city_input == '') {
						$sb_msg_error = 'Veuillez indiquer une ville.';
					} else {
						// Géocodage une seule fois ici, jamais à l'affichage
						// du dashboard (voir sbGeocodeCity()).
						$sb_geo = sbGeocodeCity($city_input);
						if ($sb_geo) {
							$location = $sb_geo['city'] . '|' . $sb_geo['lat'] . '|' . $sb_geo['lon'];
							$sb_valid = true;
						} else {
							$sb_msg_error = 'Ville introuvable (ou service de géocodage indisponible) - vérifiez l\'orthographe.';
						}
					}
					$table_name = $value_column = $date_column = $widget_key = '';
					$show_chart = '0';
					break;

				case 'html':
					$content  = $content_html;
					$sb_valid = ($content != '');
					if (!$sb_valid) $sb_msg_error = 'Le code HTML ne peut pas être vide.';
					$table_name = $value_column = $date_column = $widget_key = '';
					$show_chart = '0';
					break;

				case 'text':
					$content  = $content_text;
					$sb_valid = ($content != '');
					if (!$sb_valid) $sb_msg_error = 'Le texte ne peut pas être vide.';
					$table_name = $value_column = $date_column = $widget_key = '';
					$show_chart = '0';
					break;

				case 'rss':
					$sb_valid = false;
					$sb_url   = trim($rss_url);
					if ($sb_url == '' || !filter_var($sb_url, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $sb_url)) {
						$sb_msg_error = 'URL de flux RSS invalide.';
					} else {
						$location = $sb_url;
						$sb_valid = true;
					}
					// Nombre d'articles (1 à 20, 5 par défaut) - réutilise
					// "value_column" (inutilisé pour ce type).
					$value_column = (ctype_digit($rss_count) && $rss_count >= 1 && $rss_count <= 20) ? $rss_count : '5';
					$table_name = $date_column = $widget_key = '';
					$show_chart = '0';
					break;

				case 'iframe':
					$sb_valid = false;
					$sb_url   = trim($iframe_url);
					if ($sb_url == '' || !filter_var($sb_url, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $sb_url)) {
						$sb_msg_error = 'URL invalide (http/https uniquement).';
					} else {
						$location = $sb_url;
						$sb_valid = true;
					}
					$table_name = $value_column = $date_column = $widget_key = '';
					$show_chart = '0';
					break;

				case 'logs':
					// basename() retire tout séparateur/traversée de chemin -
					// le fichier est toujours cherché dans backdoor/logs/
					// (voir sbTailLogFile()), jamais ailleurs sur le disque.
					$sb_safe_filename = basename(trim($log_filename));
					$sb_valid = ($sb_safe_filename != '');
					if (!$sb_valid) {
						$sb_msg_error = 'Veuillez indiquer un nom de fichier.';
					} else {
						$location = $sb_safe_filename;
					}
					// Nombre de lignes (1 à 100, 15 par défaut) - réutilise
					// "value_column" (inutilisé pour ce type).
					$value_column = (ctype_digit($log_lines) && $log_lines >= 1 && $log_lines <= 100) ? $log_lines : '15';
					$table_name = $date_column = $widget_key = '';
					$show_chart = '0';
					break;

				case 'logaccess':
					// Aucune saisie à valider - source fixe (sb_logaccess,
					// connexions réussies uniquement, voir sbGetLastLoginsWidgetValue()).
					$sb_valid = true;
					// Nombre de connexions (1 à 50, 10 par défaut) - réutilise
					// "value_column" (inutilisé pour ce type).
					$value_column = (ctype_digit($logaccess_count) && $logaccess_count >= 1 && $logaccess_count <= 50) ? $logaccess_count : '10';
					$table_name = $date_column = $widget_key = '';
					$show_chart = '0';
					break;

				case 'module':
					$sb_valid = isset($sb_module_widgets[$module_widget]);
					if (!$sb_valid) {
						$sb_msg_error = 'Widget de module invalide (module désactivé ou retiré ?).';
					} else {
						list($sb_mw_values, $sb_msg_error) = sbModuleWidgetParams($sb_module_widgets[$module_widget], $mw_params);
						$sb_valid = ($sb_msg_error === '');
						if ($sb_valid) {
							$sb_msg_error = false;
							$content      = json_encode($sb_mw_values, JSON_UNESCAPED_UNICODE);
							$widget_key   = $module_widget;
						}
					}
					$value_column = $mw_width;
					$table_name = $date_column = '';
					$show_chart = '0';
					break;

				case 'table':
				default:
					// Validation contre le vrai schéma de la base.
					$sb_valid = isset($sb_schema[$table_name])
						&& array_key_exists($value_column, $sb_schema[$table_name])
						&& ($date_column == '' || array_key_exists($date_column, $sb_schema[$table_name]));
					if (!$sb_valid) $sb_msg_error = 'Table ou colonne invalide.';
					if ($date_column == '') $show_chart = '0';
					$widget_key = '';
					break;
			}

			if ($sb_valid) {
				$type_esc         = $sbsql->escape_string($type);
				$table_name_esc   = $sbsql->escape_string($table_name);
				$value_column_esc = $sbsql->escape_string($value_column);
				$date_column_esc  = $sbsql->escape_string($date_column);
				$widget_key_esc   = $sbsql->escape_string($widget_key);
				$location_esc     = $sbsql->escape_string($location);
				$content_esc      = $sbsql->escape_string($content);
				$widget_title_esc = $sbsql->escape_string($widget_title);
				$link_esc         = $sbsql->escape_string($link);
				$icon_esc         = $sbsql->escape_string($icon);
				$color_esc        = $sbsql->escape_string($color);

				// ADD or EDIT
				if ($formType == 'add') {
					// Prochaine position libre (à la suite des widgets existants)
					$row_next_pos  = $sbsql->assoc($sbsql->query("SELECT MAX(position) AS maxpos FROM $table"));
					$next_position = (($row_next_pos && $row_next_pos['maxpos'] !== null) ? intval($row_next_pos['maxpos']) : -1) + 1;

					$query = "INSERT INTO $table (position, type, table_name, value_column, date_column, widget_key, location, content, title, link, icon, color, show_chart, active)
							  VALUES ('$next_position','$type_esc','$table_name_esc','$value_column_esc','$date_column_esc','$widget_key_esc','$location_esc','$content_esc','$widget_title_esc','$link_esc','$icon_esc','$color_esc','$show_chart','$active')";
					$result_add = $sbsql->query($query);
					if ($result_add) {
						// --- Vider les champs du formulaire
						$table_name = $value_column = $date_column = $widget_key = $city_input = $content_html = $content_text = $rss_url = $rss_count = $iframe_url = $log_filename = $log_lines = $logaccess_count = $widget_title = $link = $icon = $module_widget = $mw_width = '';
						$mw_params = array();
						$type = 'table'; $color = 'primary'; $show_chart = '0'; $active = '1';
						// --- Message SUCCESS
						$sb_msg_valid = $text . ' ajouté avec succès';
					} else {
						// --- Message ERROR
						$sb_msg_error = 'Error: Write Error (ADD)!';
					}

				} elseif ($formType == 'edit' && $id > 0) {
					$query = "UPDATE $table SET type = '$type_esc'
																	,table_name = '$table_name_esc'
																	,value_column = '$value_column_esc'
																	,date_column = '$date_column_esc'
																	,widget_key = '$widget_key_esc'
																	,location = '$location_esc'
																	,content = '$content_esc'
																	,title = '$widget_title_esc'
																	,link = '$link_esc'
																	,icon = '$icon_esc'
																	,color = '$color_esc'
																	,show_chart = '$show_chart'
																	,active = '$active'
																	WHERE id = '$id'";
					$result_edit = $sbsql->query($query);
					if ($result_edit) {
						// --- On ne vide pas les champs du formulaire
						// --- Message SUCCES
						$sb_msg_valid = $text . ' modifié avec succès';
					} else {
						// --- Message ERROR
						$sb_msg_error = 'Error: Write Error (EDIT)!';
					}
				}
			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);

		} else {
			// Si AJOUT (First time)
			// --- Vider les champs du formulaire
			$type = 'table';
			$table_name = $value_column = $date_column = $widget_key = $city_input = $content_html = $content_text = $rss_url = $rss_count = $iframe_url = $log_filename = $log_lines = $logaccess_count = $widget_title = $link = $icon = $module_widget = $mw_width = '';
			$mw_params = array();
			$color = 'primary'; $show_chart = '0'; $active = '1';
		}
		// --------------------------------
		if ($formType == 'edit' && empty($_POST['form_submit'])) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id'] ?? 0);
			$query_1  = "SELECT * FROM $table WHERE id = $id";
			$requestQ = $sbsql->query($query_1);
			$assoc    = $sbsql->assoc($requestQ);
			$type         = $assoc['type'];
			$table_name   = $assoc['table_name'];
			$value_column = $assoc['value_column'];
			$date_column  = $assoc['date_column'];
			$widget_key   = $assoc['widget_key'];
			// "location" est stockée "Ville|lat|lon" (voir sbGeocodeCity()) -
			// seule la ville est réaffichée dans le champ texte du formulaire.
			$city_input   = ($assoc['location'] != '') ? explode('|', $assoc['location'])[0] : '';
			// "content" alimente indifféremment le champ html ou texte selon
			// $type - un seul des deux est réellement affiché (voir les
			// groupes conditionnels du formulaire ci-dessous).
			$content_html = ($assoc['type'] == 'html') ? sb_utf8_encode($assoc['content']) : '';
			$content_text = ($assoc['type'] == 'text') ? sb_utf8_encode($assoc['content']) : '';
			// rss/iframe/logs réutilisent également location/value_column,
			// chacun avec son propre sens (voir la validation ci-dessus).
			$rss_url      = ($assoc['type'] == 'rss') ? sb_utf8_encode($assoc['location']) : '';
			$rss_count    = ($assoc['type'] == 'rss') ? $assoc['value_column'] : '';
			$iframe_url   = ($assoc['type'] == 'iframe') ? sb_utf8_encode($assoc['location']) : '';
			$log_filename    = ($assoc['type'] == 'logs') ? sb_utf8_encode($assoc['location']) : '';
			$log_lines       = ($assoc['type'] == 'logs') ? $assoc['value_column'] : '';
			$logaccess_count = ($assoc['type'] == 'logaccess') ? $assoc['value_column'] : '';
			$module_widget   = ($assoc['type'] == 'module') ? $assoc['widget_key'] : '';
			$mw_params       = ($assoc['type'] == 'module') ? (array) json_decode((string) $assoc['content'], true) : array();
			$mw_width        = ($assoc['type'] == 'module') ? $assoc['value_column'] : '';
			$widget_title    = sb_utf8_encode($assoc['title']);
			$link         = sb_utf8_encode($assoc['link']);
			$icon         = sb_utf8_encode($assoc['icon']);
			$color        = $assoc['color'];
			$show_chart   = $assoc['show_chart'];
			$active       = $assoc['active'];
			$sbsmarty->assign('assoc', $query_1);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_1 . "\n" . 'Form Type = '.$formType);
		}
		// --------------------------------
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));

		// ----------------------------
		// --- Type de widget - conditionne les groupes de champs affichés
		// ci-dessous (voir dashboard-widget-form.js, aucun des 3 groupes
		// n'est "required" en HTML : seul celui du type choisi doit l'être,
		// géré uniquement côté serveur pour ne pas bloquer la soumission
		// à cause d'un champ caché resté "required").
		// ----------------------------
		// Boutons cliquables (composant "pills" du thème Adminator, jusque
		// là inutilisé) plutôt qu'un <select> - 9 choix, plus lisible et
		// plus rapide à choisir en un clic. Le vrai champ soumis reste un
		// input hidden #type, piloté par les boutons en JS (voir
		// dashboard-widget-form.js) - aucune logique de validation ne
		// change côté serveur.
		$sb_type_buttons = '';
		foreach ($sb_type_options as $sb_type_key => $sb_type_label) {
			$sb_type_active = ($sb_type_key == $type) ? ' is-active' : '';
			$sb_type_icon   = isset($sb_type_icons[$sb_type_key]) ? $sb_type_icons[$sb_type_key] : 'circle-o';
			$sb_type_buttons .= '<button type="button" class="tab' . $sb_type_active . '" data-type-value="' . $sb_type_key . '"><i class="' . sbFaClass($sb_type_icon) . '"></i> ' . $sb_type_label . '</button>';
		}
		$sbform->addAnything(
			'<div class="field"><label class="field-label form_required">Type de widget <span class="req">*</span></label>'
			. '<div class="tabs pills sb-type-picker" data-widget-type-picker>' . $sb_type_buttons . '</div>'
			. '<input type="hidden" id="type" name="type" value="' . $type . '"></div><p></p>'
		);

		// ----------------------------
		// --- Groupe "table" (visible par défaut, masqué en JS si un autre
		// type est sélectionné au chargement)
		// ----------------------------
		$sbform->addAnything('<div data-widget-type-fields="table">');

		$sbform->openSelect("Table", array("id" => "table_name", "name" => "table_name"));
		$sb_opt_empty = array("value" => "");
		if ($table_name == '') $sb_opt_empty['selected'] = '';
		$sbform->addOption('Choisissez une table', $sb_opt_empty);
		foreach ($sb_schema as $sb_table_short => $sb_cols) {
			$sb_opt_args = array("value" => $sb_table_short);
			if ($sb_table_short == $table_name) $sb_opt_args['selected'] = '';
			$sb_opt_label = isset($sb_table_titles[$sb_table_short]) ? $sb_table_titles[$sb_table_short] : $sb_table_short;
			$sbform->addOption($sb_opt_label, $sb_opt_args);
		}
		$sbform->closeSelect();

		$sb_current_cols = isset($sb_schema[$table_name]) ? array_keys($sb_schema[$table_name]) : array();

		$sbform->openSelect("Colonne à afficher", array("id" => "value_column", "name" => "value_column"));
		foreach ($sb_current_cols as $sb_col) {
			$sb_opt_args = array("value" => $sb_col);
			if ($sb_col == $value_column) $sb_opt_args['selected'] = '';
			$sbform->addOption($sb_col, $sb_opt_args);
		}
		$sbform->closeSelect('Colonne montrée pour chaque élément dans la liste "Récent" du dashboard.');

		$sbform->openSelect("Colonne date (optionnel)", array("id" => "date_column", "name" => "date_column"));
		$sb_opt_none = array("value" => "");
		if ($date_column == '') $sb_opt_none['selected'] = '';
		$sbform->addOption('Aucune', $sb_opt_none);
		foreach ($sb_current_cols as $sb_col) {
			$sb_opt_args = array("value" => $sb_col);
			if ($sb_col == $date_column) $sb_opt_args['selected'] = '';
			$sbform->addOption($sb_col, $sb_opt_args);
		}
		$sbform->closeSelect('Si renseignée : active la tendance (7 derniers jours vs 7 précédents) et le graphique.');

		$show_chart = ($show_chart) ? '1' : '0';
		$sbform->addRadioYN('Afficher un graphique', false, array('id' => 'show_chart', 'name' => 'show_chart', 'checked' => "$show_chart"), 'oui', 'non', 'Nécessite une colonne date choisie ci-dessus.');

		// ----------------------------
		// --- Groupe "system" (masqué par défaut)
		// ----------------------------
		$sbform->addAnything('</div><div data-widget-type-fields="system" style="display:none">');

		$sbform->openSelect("Métrique", array("id" => "widget_key", "name" => "widget_key"));
		foreach ($sb_system_options as $sb_sys_key => $sb_sys_label) {
			$sb_opt_args = array("value" => $sb_sys_key);
			if ($sb_sys_key == $widget_key) $sb_opt_args['selected'] = '';
			$sbform->addOption($sb_sys_label, $sb_opt_args);
		}
		$sbform->closeSelect();

		// ----------------------------
		// --- Groupe "weather" (masqué par défaut)
		// ----------------------------
		$sbform->addAnything('</div><div data-widget-type-fields="weather" style="display:none">');

		$sbform->addInput('text', 'Ville', array('id' => 'city', 'name' => 'city', 'value' => "$city_input"), false, false, 'Résolue une seule fois à l\'enregistrement (géocodage Open-Meteo) - pas de nouvel appel à chaque affichage du dashboard.');

		$sbform->addAnything('</div><div data-widget-type-fields="html" style="display:none">');

		$sbform->addTextarea('Code HTML', "$content_html", array('id' => 'content_html', 'name' => 'content_html', 'style' => 'height:200px;font-family:monospace'), false, 'Collé tel quel dans la page - à vos risques (aucun filtrage).');

		$sbform->addAnything('</div><div data-widget-type-fields="text" style="display:none">');

		$sbform->addTextareaHtml('Texte', "$content_text", array('id' => 'content_text', 'name' => 'content_text', 'style' => 'height:200px'), false);

		// ----------------------------
		// --- Groupe "rss" (masqué par défaut)
		// ----------------------------
		$sbform->addAnything('</div><div data-widget-type-fields="rss" style="display:none">');

		$sbform->addInput('text', 'URL du flux RSS', array('id' => 'rss_url', 'name' => 'rss_url', 'value' => "$rss_url"), false, false, 'RSS 2.0 ou Atom - lu à chaque affichage du dashboard, sans mise en cache.');
		$sbform->addInput('text', "Nombre d'articles", array('id' => 'rss_count', 'name' => 'rss_count', 'value' => "$rss_count"), false, false, 'Entre 1 et 20 - 5 par défaut si laissé vide.');

		// ----------------------------
		// --- Groupe "iframe" (masqué par défaut)
		// ----------------------------
		$sbform->addAnything('</div><div data-widget-type-fields="iframe" style="display:none">');

		$sbform->addInput('text', 'URL à intégrer', array('id' => 'iframe_url', 'name' => 'iframe_url', 'value' => "$iframe_url"), false, false, 'http(s) uniquement - le site distant doit autoriser son intégration en iframe.');

		// ----------------------------
		// --- Groupe "logs" (masqué par défaut)
		// ----------------------------
		$sbform->addAnything('</div><div data-widget-type-fields="logs" style="display:none">');

		$sbform->addInput('text', 'Nom du fichier', array('id' => 'log_filename', 'name' => 'log_filename', 'value' => "$log_filename"), false, false, 'Doit exister dans le dossier backdoor/logs/ (protégé, non accessible directement depuis le web).');
		$sbform->addInput('text', 'Nombre de lignes', array('id' => 'log_lines', 'name' => 'log_lines', 'value' => "$log_lines"), false, false, 'Entre 1 et 100 - 15 par défaut si laissé vide.');

		// ----------------------------
		// --- Groupe "logaccess" (masqué par défaut)
		// ----------------------------
		$sbform->addAnything('</div><div data-widget-type-fields="logaccess" style="display:none">');

		$sbform->addInput('text', 'Nombre de connexions', array('id' => 'logaccess_count', 'name' => 'logaccess_count', 'value' => "$logaccess_count"), false, false, 'Entre 1 et 50 - 10 par défaut si laissé vide. Ne montre que les connexions réussies (module Journaux).');

		// ----------------------------
		// --- Groupe "module" (masqué par défaut) : un jeu de paramètres par
		// widget, seul celui du widget choisi est affiché (et retenu)
		// ----------------------------
		$sbform->addAnything('</div><div data-widget-type-fields="module" style="display:none">');

		if ($sb_module_widgets) {
			$sbform->openSelect("Widget", array("id" => "module_widget", "name" => "module_widget"));
			foreach ($sb_module_widgets as $sb_mw_id => $sb_mw) {
				$sb_opt_args = array("value" => $sb_mw_id);
				if ($sb_mw_id == $module_widget) $sb_opt_args['selected'] = '';
				$sbform->addOption($sb_mw['module_title'] . ' › ' . $sb_mw['title'] . ($sb_mw['format'] == 'card' ? ' (carte)' : ' (tuile)'), $sb_opt_args);
			}
			$sbform->closeSelect('Widgets fournis par les modules installés (dossier widgets/ du module).');
			$sb_mw_first = ($module_widget !== '' && isset($sb_module_widgets[$module_widget])) ? $module_widget : array_key_first($sb_module_widgets);
			foreach ($sb_module_widgets as $sb_mw_id => $sb_mw) {
				$sbform->addAnything('<div data-module-widget-params="' . htmlspecialchars($sb_mw_id) . '"' . ($sb_mw_id == $sb_mw_first ? '' : ' style="display:none"') . '>');
				if ($sb_mw['description'] !== '') $sbform->addAnything('<p style="color:var(--t-muted);font-size:13px">' . $sb_mw['description'] . '</p>');
				foreach ($sb_mw['params'] as $sb_p => $sb_pd) {
					$sb_pname = 'mwp[' . $sb_mw_id . '][' . $sb_p . ']';
					$sb_pid   = 'mwp_' . str_replace(':', '_', $sb_mw_id) . '_' . $sb_p;
					$sb_pval  = ($sb_mw_id == $module_widget && isset($mw_params[$sb_p]) && is_scalar($mw_params[$sb_p])) ? (string) $mw_params[$sb_p] : (string) $sb_pd['default'];
					$sb_phelp = $sb_pd['help'];
					if ($sb_phelp === '' && $sb_pd['type'] == 'number' && $sb_pd['min'] !== null && $sb_pd['max'] !== null) $sb_phelp = 'Entre ' . $sb_pd['min'] . ' et ' . $sb_pd['max'] . '.';
					if ($sb_pd['type'] == 'select' || $sb_pd['type'] == 'yesno') {
						$sb_popts = ($sb_pd['type'] == 'yesno') ? array('1' => 'Oui', '0' => 'Non') : $sb_pd['options'];
						$sbform->openSelect($sb_pd['label'], array('id' => $sb_pid, 'name' => $sb_pname));
						foreach ($sb_popts as $sb_ov => $sb_ol) {
							$sb_opt_args = array('value' => htmlspecialchars((string) $sb_ov));
							if ((string) $sb_ov === $sb_pval) $sb_opt_args['selected'] = '';
							$sbform->addOption($sb_ol, $sb_opt_args);
						}
						$sbform->closeSelect($sb_phelp);
					} else {
						// Champ "required" contrôlé côté serveur seulement (groupe masqué)
						$sbform->addInput('text', $sb_pd['label'] . ($sb_pd['required'] ? ' *' : ''), array('id' => $sb_pid, 'name' => $sb_pname, 'value' => htmlspecialchars($sb_pval)), false, false, $sb_phelp);
					}
				}
				if (!$sb_mw['params']) $sbform->addAnything('<p style="color:var(--t-light);font-size:13px">Ce widget n\'a pas de paramètre.</p>');
				$sbform->addAnything('</div>');
			}
			$sbform->openSelect("Largeur de la carte", array("id" => "mw_width", "name" => "mw_width"));
			foreach (array('' => 'Celle du widget', '4' => '1/3 de la largeur (4 colonnes)', '6' => '1/2 (6 colonnes)', '8' => '2/3 (8 colonnes)', '12' => 'Toute la largeur (12 colonnes)') as $sb_wv => $sb_wl) {
				$sb_opt_args = array("value" => $sb_wv);
				if ((string) $sb_wv === (string) $mw_width) $sb_opt_args['selected'] = '';
				$sbform->addOption($sb_wl, $sb_opt_args);
			}
			$sbform->closeSelect('Widgets en carte seulement (une tuile garde sa taille).');
		} else {
			$sbform->addAnything('<p style="color:var(--t-muted)">Aucun module installé ne propose de widget (fichiers datas/modules/&lt;nom&gt;/widgets/&lt;clé&gt;.php).</p>');
		}

		$sbform->addAnything('</div>');

		// ----------------------------
		// --- Titre / Lien / Icône (Icône ignorée pour la météo, le texte et
		// le HTML)
		// ----------------------------
		$sbform->addInput('text', 'Titre', array('name' => 'widget_title', 'value' => "$widget_title"), true);
		$sbform->addInput('text', 'Lien (relatif de préférence)', array('id' => 'link', 'name' => 'link', 'value' => "$link"), false, false, 'Préremplit automatiquement au choix de la table ci-dessus - modifiable ensuite.');
		$sbform->addIconFA('Icône', array('id' => 'icon', 'name' => 'icon', 'value' => "$icon"), false, "Cliquez sur \"Choisir\" pour parcourir les icônes disponibles. Ignorée pour les widgets météo, HTML et texte.");

		// ----------------------------
		// --- Couleur / Actif
		// ----------------------------
		$sbform->openSelect("Couleur", array("id" => "color", "name" => "color"));
		foreach ($sb_color_options as $sb_color_key => $sb_color_label) {
			$sb_opt_args = array("value" => $sb_color_key);
			if ($sb_color_key == $color) $sb_opt_args['selected'] = '';
			$sbform->addOption($sb_color_label, $sb_opt_args);
		}
		$sbform->closeSelect();

		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id' => 'active', 'name' => 'active', 'checked' => "$active"), 'activé', 'désactivé');

		// --- Hiddens / Buttons
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'edit') {
			$sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		}
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --- Close Form
		$sbform->closeForm();
		// --- Bouton "Actions" de la colonne droite (shared-panel-actions.tpl)
		$sbsmarty->assign('sb_form_id', $formName);
		$sbsmarty->assign('sb_form_submit_value', $btn_add_edit);
		// Titre du formulaire (jamais transmis jusqu'ici : carte sans titre).
		// Le gabarit le décode (unescape) : aucune balise venue du titre saisi
		$sbsmarty->assign('legend_add_edit', ($action == 'add') ? $legend_add_edit : sprintf($legend_add_edit, strip_tags(html_entity_decode((string) ($widget_title ?? ''), ENT_QUOTES, 'UTF-8'))));

		// --- Mapping table -> colonnes pour la cascade des <select> côté JS
		// (assets/adminator/dashboard-widget-form.js), pas d'aller-retour AJAX.
		$sbsmarty->assign('sb_dashboard_schema_json', json_encode($sb_schema));
		$sbsmarty->assign('sb_dashboard_table_links_json', json_encode($sb_table_links));
		// Titre, icône et lien proposés par chaque widget de module (préremplissage JS)
		$sb_mw_defaults = array();
		foreach ($sb_module_widgets as $sb_mw_id => $sb_mw) $sb_mw_defaults[$sb_mw_id] = array('title' => $sb_mw['title'], 'icon' => $sb_mw['icon'], 'iconClass' => sbFaClass($sb_mw['icon']), 'link' => $sb_mw['link']);
		$sbsmarty->assign('sb_dashboard_module_widgets_json', json_encode((object) $sb_mw_defaults, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT));

	break;
}

// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Dashboard');

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);
