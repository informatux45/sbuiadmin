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
session_start();

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
// métadonnée en base reliant une table SQL à sa page d'admin, uniquement
// les modules du cœur SBUIADMIN sont couverts ici ; une table inconnue
// (module tiers, table "Tableaux" custom...) laisse simplement le champ vide.
$sb_table_links = array(
	'sb_sandbox'      => 'index.php?p=sandbox',
	'sb_faq'          => 'index.php?p=faq',
	'sb_faq_category' => 'index.php?p=faq&a=category',
	'sb_news'         => 'index.php?p=news',
	'sb_news_category'=> 'index.php?p=news&a=category',
	'sb_slider'       => 'index.php?p=slider',
	'sb_slider_photos'=> 'index.php?p=slider',
	'sb_tabbs'        => 'index.php?p=tabbs',
	'sb_tabbs_tab'    => 'index.php?p=tabbs&a=alltabs',
	'sb_table'        => 'index.php?p=table',
	'sb_pages'        => 'index.php?p=pages',
	'sb_blocs'        => 'index.php?p=blocs',
	'sb_contact'      => 'index.php?p=contact',
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
	'sb_faq'                => 'FAQ',
	'sb_faq_category'       => 'Catégories FAQ',
	'sb_news'               => 'Actualités',
	'sb_news_category'      => 'Catégories actualités',
	'sb_news_settings'      => 'Paramètres actualités',
	'sb_slider'             => 'Sliders',
	'sb_slider_photos'      => 'Photos des sliders',
	'sb_tabbs'              => 'Tabbs',
	'sb_tabbs_tab'          => 'Onglets',
	'sb_table'              => 'Tableaux',
	'sb_table_datas'        => 'Données des tableaux',
	'sb_table_structure'    => 'Structure des tableaux',
	'sb_pages'              => 'Pages',
	'sb_blocs'              => 'Blocs',
	'sb_blocs_sort'         => 'Ordre des blocs',
	'sb_contact'            => 'Formulaires de contact',
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

$action = $_GET['a'];
switch ($action) {
	case "del":
	case "up":
	case "down":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id  = intval($_GET['id']);
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
			$get_id  = intval($_GET['id']);
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
		$formType        = ($action == 'add' || $_POST['form_submit'] == 'add_form') ? "add" : "edit";
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
		if ($_POST['form_submit']) {

			// Injection des données
			$id           = intval($_POST['id']);
			$type         = in_array($_POST['type'], array('table', 'system', 'weather', 'html', 'text')) ? $_POST['type'] : 'table';
			$table_name   = $sbsanitize->displayText($_POST['table_name'], 'UTF-8', 1, 0);
			$value_column = $sbsanitize->displayText($_POST['value_column'], 'UTF-8', 1, 0);
			$date_column  = $sbsanitize->displayText($_POST['date_column'], 'UTF-8', 1, 0);
			$widget_key   = $sbsanitize->displayText($_POST['widget_key'], 'UTF-8', 1, 0);
			$city_input   = $sbsanitize->displayText($_POST['city'], 'UTF-8', 1, 0);
			// content_html (widget "html") et content_text (widget "text",
			// CKEditor) alimentent la même colonne "content" en base - seul
			// le champ pertinent pour $type est non vide côté formulaire.
			$content_html = $sbsanitize->displayText($_POST['content_html'], 'UTF-8', 1, 0);
			$content_text = $sbsanitize->displayText($_POST['content_text'], 'UTF-8', 1, 0);
			$widget_title = $sbsanitize->displayText($_POST['widget_title'], 'UTF-8', 1, 0);
			$link         = $sbsanitize->displayText($_POST['link'], 'UTF-8', 1, 0);
			$icon         = $sbsanitize->displayText($_POST['icon'], 'UTF-8', 1, 0);
			$color        = $sbsanitize->displayText($_POST['color'], 'UTF-8', 1, 0);
			$show_chart   = $sbsanitize->displayText($_POST['show_chart'], 'UTF-8', 1, 0);
			$active       = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

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
						$table_name = $value_column = $date_column = $widget_key = $city_input = $content_html = $content_text = $widget_title = $link = $icon = '';
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
			$table_name = $value_column = $date_column = $widget_key = $city_input = $content_html = $content_text = $widget_title = $link = $icon = '';
			$color = 'primary'; $show_chart = '0'; $active = '1';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id']);
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
			$content_html = ($assoc['type'] == 'html') ? utf8_encode($assoc['content']) : '';
			$content_text = ($assoc['type'] == 'text') ? utf8_encode($assoc['content']) : '';
			$widget_title = utf8_encode($assoc['title']);
			$link         = utf8_encode($assoc['link']);
			$icon         = utf8_encode($assoc['icon']);
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
		$sbform->openSelect("Type de widget", array("id" => "type", "name" => "type"), true);
		foreach ($sb_type_options as $sb_type_key => $sb_type_label) {
			$sb_opt_args = array("value" => $sb_type_key);
			if ($sb_type_key == $type) $sb_opt_args['selected'] = '';
			$sbform->addOption($sb_type_label, $sb_opt_args);
		}
		$sbform->closeSelect();

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

		// --- Mapping table -> colonnes pour la cascade des <select> côté JS
		// (assets/adminator/dashboard-widget-form.js), pas d'aller-retour AJAX.
		$sbsmarty->assign('sb_dashboard_schema_json', json_encode($sb_schema));
		$sbsmarty->assign('sb_dashboard_table_links_json', json_encode($sb_table_links));

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
