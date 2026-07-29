<?php
/**
 * Admin Startbootstrap
 * Manage Boutique (products, categories)
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
// Include Config CMS
// -----------------------
include_once('../sbconfig.php');

// -----------------------
// Module URL
// -----------------------
$module_page = 'boutique';
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
$table         = _AM_DB_PREFIX . "sb_shop_product";
$table_cat     = _AM_DB_PREFIX . "sb_shop_category";
$table_config  = _AM_DB_PREFIX . "sb_shop_config";
$text          = "Produit";
$text_s        = "Produits";

// Taux de TVA courants proposés dans les listes déroulantes (France) - un
// taux hors de cette liste reste saisissable à la main (champ texte).
$sb_tva_taux_courants = ['20', '10', '5.5', '2.1', '0'];

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			$query_2 = "DELETE FROM $table WHERE id = '$get_id'";
			$request  = $sbsql->query($query_2);

			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = ['Tri', 'Photo', 'Référence', 'Titre', 'Catégorie', 'Prix TTC', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);

		// Contents table
		$query_0 = "SELECT t1.*, t2.title AS category_title
		            FROM $table AS t1
					LEFT JOIN $table_cat AS t2 ON (t1.catid = t2.id)
					ORDER BY t1.sort ASC";
		$request0  = $sbsql->query($query_0);
		$result0   = $sbsql->toarray($request0);

		$sbsmarty->assign('all', true);
		$sbsmarty->assign('allproducts', $result0);

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
		$formName        = ($action == 'add') ? "add_form" : "edit_form";
		$formType        = ($action == 'add' || $_POST['form_submit'] == 'add_form') ? "add" : "edit";
		$btn_add_edit    = ($action == 'add') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'add') ? "Ajouter un produit" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id                  = intval($_POST['id']);
			$catid               = intval($_POST['catid']);
			$reference           = $sbsanitize->displayText($_POST['reference'], 'UTF-8', 1, 0);
			$title               = $sbsanitize->displayText($_POST['title'], 'UTF-8', 1, 0);
			$description_short   = $sbsanitize->displayText($_POST['description_short'], 'UTF-8', 1, 0);
			$description         = $sbsanitize->displayText($_POST['description'], 'UTF-8', 1, 0);
			$photo               = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$price               = str_replace(',', '.', $sbsanitize->displayText($_POST['price'], 'UTF-8', 1, 0));
			$priceht             = str_replace(',', '.', $sbsanitize->displayText($_POST['priceht'], 'UTF-8', 1, 0));
			$poids               = str_replace(',', '.', $sbsanitize->displayText($_POST['poids'], 'UTF-8', 1, 0));
			$allow_physical      = $sbsanitize->displayText($_POST['allow_physical'], 'UTF-8', 1, 0);
			$allow_dematerialise = $sbsanitize->displayText($_POST['allow_dematerialise'], 'UTF-8', 1, 0);
			$tva_assujetti       = $sbsanitize->displayText($_POST['tva_assujetti'], 'UTF-8', 1, 0);
			$active              = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

			// --- Ventilation TVA (0 à 3 taux : certaines formes de société -
			// franchise en base - sont dispensées de TVA, le produit peut donc
			// n'avoir AUCUNE ligne). Reconstruction du JSON stocké depuis les
			// champs à plat du formulaire (même convention que le projet de
			// référence cabaret/web). Si non assujetti, on ignore toute valeur
			// saisie par erreur dans les champs (cohérence en base).
			$tva_data   = [];
			$tva_sum_ht = 0.0;
			if ($tva_assujetti) {
				foreach (['tva1', 'tva2', 'tva3'] as $k) {
					$libelle    = $sbsanitize->displayText($_POST[$k . '_libelle'], 'UTF-8', 1, 0);
					$nom        = $sbsanitize->displayText($_POST[$k . '_nom'], 'UTF-8', 1, 0);
					$taux       = $sbsanitize->displayText($_POST[$k . '_taux'], 'UTF-8', 1, 0);
					$montant_ht = str_replace(',', '.', $sbsanitize->displayText($_POST[$k . '_montant_ht'], 'UTF-8', 1, 0));
					$compte     = $sbsanitize->displayText($_POST[$k . '_compte'], 'UTF-8', 1, 0);
					if (trim($libelle) == '' || trim($taux) == '') continue;
					$tva_data[$k] = ['libelle' => $libelle, 'nom' => $nom, 'taux' => $taux, 'montant_ht' => $montant_ht, 'compte' => $compte];
					$tva_sum_ht  += floatval($montant_ht);
				}
			}
			$tva = json_encode($tva_data);

			// --- Contrôle de cohérence (uniquement si assujetti) : somme des
			// montant_ht ventilés = prix HT. Aucune ligne renseignée alors
			// qu'assujetti = simple rappel, pas bloquant (ex: ventilation à
			// compléter plus tard).
			$tva_check_msg = '';
			if ($tva_assujetti && $tva_sum_ht > 0 && abs($tva_sum_ht - floatval($priceht)) > 0.02) {
				$tva_check_msg = ' ⚠ Attention : la somme des montants HT ventilés (' . number_format($tva_sum_ht, 2, ',', ' ') . ' €) ne correspond pas au prix HT (' . number_format(floatval($priceht), 2, ',', ' ') . ' €) - vérifiez la ventilation TVA.';
			} elseif ($tva_assujetti && $tva_sum_ht == 0) {
				$tva_check_msg = ' ⚠ Produit assujetti à la TVA mais aucune ligne de ventilation renseignée.';
			}

			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (catid,reference,title,description,description_short,price,priceht,tva_assujetti,tva,photo,poids,allow_physical,allow_dematerialise,active)
						  VALUES ('$catid','$reference','$title','$description','$description_short','$price','$priceht','$tva_assujetti','$tva','$photo','$poids','$allow_physical','$allow_dematerialise','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$catid = $reference = $title = $description = $description_short = $photo = $price = $priceht = $poids = $allow_physical = $allow_dematerialise = $tva_assujetti = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès' . $tva_check_msg;
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET catid = '$catid'
											,reference = '$reference'
											,title = '$title'
											,description = '$description'
											,description_short = '$description_short'
											,price = '$price'
											,priceht = '$priceht'
											,tva_assujetti = '$tva_assujetti'
											,tva = '$tva'
											,photo = '$photo'
											,poids = '$poids'
											,allow_physical = '$allow_physical'
											,allow_dematerialise = '$allow_dematerialise'
											,active = '$active'
											WHERE id = '$id'";

				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = $text . ' modifié avec succès' . $tva_check_msg;
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);

		} else {
			// Si AJOUT (First time)
			// --- Vider les champs du formulaire
			$catid = $reference = $title = $description = $description_short = $photo = $price = $priceht = $poids = $active = '';
			$allow_physical      = '1';
			$allow_dematerialise = '0';
			$tva_assujetti       = '1';
			$tva_data = [];
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id                  = intval($_GET['id']);
			$query_1             = "SELECT * FROM $table WHERE id = $id";
			$requestQ            = $sbsql->query($query_1);
			$assoc               = $sbsql->assoc($requestQ);
			$catid               = $assoc['catid'];
			$reference           = $assoc['reference'];
			$title               = $sbsanitize->displayLang(sb_utf8_encode($assoc['title']));
			$description         = $sbsanitize->displayLang(sb_utf8_encode($assoc['description']));
			$description_short   = $sbsanitize->displayLang(sb_utf8_encode($assoc['description_short']));
			$photo               = $assoc['photo'];
			$price               = $assoc['price'];
			$priceht             = $assoc['priceht'];
			$poids               = $assoc['poids'];
			$allow_physical      = $assoc['allow_physical'];
			$allow_dematerialise = $assoc['allow_dematerialise'];
			$tva_assujetti       = $assoc['tva_assujetti'];
			$active              = $assoc['active'];
			$tva_data            = ($assoc['tva']) ? json_decode($assoc['tva'], true) : [];

			$sbsmarty->assign('assoc', $query_1);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_1 . "\n" . 'Form Type = '.$formType);
		}

		// --------------------------------
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Référence / Titre
		// ----------------------------
		$sbform->addInput('text', "Référence", array ('name' => 'reference', 'value' => "$reference", 'placeholder' => "Référence produit"), true);
		$sbform->addInput('text', "Titre", array ('name' => 'title', 'value' => "$title", 'placeholder' => "Titre du produit"), true);
		// ----------------------------
		// --- Catégorie
		// ----------------------------
		$query_category   = "SELECT * FROM $table_cat WHERE active = '1' ORDER BY title ASC";
		$request_category = $sbsql->query($query_category);
		$categories       = $sbsql->toarray($request_category);
		$sbform->openSelect("Catégorie", array("id"=>"catid", "name"=>"catid", "style" => "width: 500px;"), true);
		if ($catid == '' || $catid > 0) $sbform->addOption('Choisissez une catégorie', array ("value"=>"", "selected"=>""));
		foreach($categories as $row) {
			if ($row['id'] == $catid)
				$sbform->addOption($row['title'], array ("value"=>$row['id'], "selected"=>""));
			else
				$sbform->addOption($row['title'], array ("value"=>$row['id']));
		}
		// --- Close Select
		$sbform->closeSelect();
		// ----------------------------
		// --- Photo
		// ----------------------------
		$sbform->addInput('text', 'Photo', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo", "medias"=>"", 'icon' => 'photo', 'style' => 'width: 100% !important'), false);
		// ----------------------------
		// --- Descriptions
		// ----------------------------
		$sbform->addTextareaHtml("Description courte", $description_short, array('id' => 'description_short', 'name' => 'description_short'), false);
		$sbform->addTextareaHtml("Description", $description, array('id' => 'description', 'name' => 'description'), true);
		// ----------------------------
		// --- Prix / Poids
		// ----------------------------
		$sbform->addBreak('Prix');
		$sbform->addInput('text', "Prix TTC (€)", array ('name' => 'price', 'value' => "$price", 'placeholder' => "0.00"), true);
		$sbform->addInput('text', "Prix HT (€)", array ('name' => 'priceht', 'value' => "$priceht", 'placeholder' => "0.00"), true, false, "Doit correspondre à la somme des montants HT ventilés ci-dessous.");
		$sbform->addInput('text', "Poids (kg)", array ('name' => 'poids', 'value' => "$poids", 'placeholder' => "0.000"), false, false, "Utilisé par le calcul de frais de transport au poids (Phase 3).");
		// ----------------------------
		// --- Assujettissement à la TVA
		// ----------------------------
		$sbform->addBreak('TVA');
		$tva_assujetti = ($tva_assujetti === '' || $tva_assujetti === null) ? '1' : (($tva_assujetti) ? '1' : '0');
		$sbform->addRadioYN('Assujetti à la TVA', false, array('id'=>'tva_assujetti', 'name'=>'tva_assujetti', 'checked'=>"$tva_assujetti"), 'oui', 'non', "Certaines formes de société (franchise en base) en sont dispensées - sur 'non', aucune ligne de TVA n'est requise ni enregistrée ci-dessous.");
		// ----------------------------
		// --- Ventilation TVA (0 à 3 taux, uniquement si assujetti)
		// ----------------------------
		foreach ([1, 2, 3] as $n) {
			$k = 'tva' . $n;
			$t = $tva_data[$k] ?? [];
			$sbform->addBreak('Ligne TVA ' . $n . ' (optionnel)');
			$sbform->addInput('text', "Libellé", array ('name' => $k . '_libelle', 'value' => ($t['libelle'] ?? ''), 'placeholder' => "Ex: Billet spectacle"), false);
			$sbform->addInput('text', "Nom du compte", array ('name' => $k . '_nom', 'value' => ($t['nom'] ?? ''), 'placeholder' => "Ex: Ventes spectacles"), false);
			$sbform->addInput('text', "Taux (%)", array ('name' => $k . '_taux', 'value' => ($t['taux'] ?? ''), 'placeholder' => "20"), false);
			$sbform->addInput('text', "Montant HT (€)", array ('name' => $k . '_montant_ht', 'value' => ($t['montant_ht'] ?? ''), 'placeholder' => "0.00"), false);
			$sbform->addInput('text', "Compte comptable", array ('name' => $k . '_compte', 'value' => ($t['compte'] ?? ''), 'placeholder' => "Ex: 706100"), false, false, "Utilisé par l'export comptable (Phase 7).");
		}
		// ----------------------------
		// --- Physique / Dématérialisé
		// ----------------------------
		$sbform->addBreak('Livraison');
		$allow_physical = ($allow_physical) ? '1' : '0';
		$sbform->addRadioYN('Vendu en version physique', false, array('id'=>'allow_physical', 'name'=>'allow_physical', 'checked'=>"$allow_physical"), 'oui', 'non');
		$allow_dematerialise = ($allow_dematerialise) ? '1' : '0';
		$sbform->addRadioYN('Vendu en version dématérialisée', false, array('id'=>'allow_dematerialise', 'name'=>'allow_dematerialise', 'checked'=>"$allow_dematerialise"), 'oui', 'non', "Le choix du plugin de livraison dématérialisée sera ajouté en Phase 3.");
		// --------------------------------
		// --- Hiddens / Buttons
		// --------------------------------
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'edit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --------------------------------
		// --- Close Form
		// --------------------------------
		$sbform->closeForm ();
		// --------------------------------
	break;

	case "sort":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$formName        = "sort_form";
		$formType        = "sort";
		$btn_add_edit    = "Valider";
		$legend_add_edit = "Trier les produits";
		// --------------------------------
		if ($_POST['drag']) {
			// --------------------------------
			// --- Control form submit --------
			// --------------------------------
			$sb_toSort = $_POST['drag'];

			// reorganizes the order of elements
			$sql_error = 0;
			for ($i = 0; $i < count($sb_toSort); $i++) {
				$tri = $i + 1;
				$query_sort  = "UPDATE $table SET sort = $tri WHERE id = " . $sb_toSort[$i];
				$result_sort = $sbsql->query($query_sort);
				if (!$result_sort) {
					// --- Error Database
					$sql_error++;
				}
				if (_AM_SITE_DEBUG) $sbsmarty->append('sbdebugsql', $query_sort);
			}
			// Check result
			if ($sql_error < 1) {
				// --- Message SUCCES
				$sb_msg_valid = "Les produits ont été triés avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}

		// --- Recuperation des donnees
		$query_3      = "SELECT * FROM $table ORDER BY sort ASC";
		$requestQ      = $sbsql->query($query_3);
		$sort_array    = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$active = ($sort['active']) ? $sbsanitize->displayLang(sb_utf8_encode($sort['title'])) : "<span style='color: red;'>".$sbsanitize->displayLang(sb_utf8_encode($sort['title']))."</span>";
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = $active;
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_3 . "\n" . 'Form Type = '.$formType);

		// --------------------------------
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les produits <span style='color: red;'>en rouge</span> sont des produits en statut non visible");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------
		// --- Close Form
		// --------------------------------
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('sort', true);
	break;

	case "categorydel":
	case "category":
		// Action DELETE category
		if ($action == 'categorydel') {
			$get_id   = intval($_GET['id']);
			// -------------------------------------
			// --- Check if product uses this category
			$query_prod   = "SELECT id FROM $table WHERE catid = $get_id";
			$request_prod = $sbsql->query($query_prod);
			$numrows_prod = $sbsql->numrows($request_prod);
			// -------------------------------------
			if ($numrows_prod > 0) {
				$sb_msg_error = 'Cette catégorie contient des produits !!<br>Vous devez supprimer ou déplacer les produits de cette catégorie avant !!';
			} else {
				$query_5 = "DELETE FROM $table_cat WHERE id = '$get_id'";
				$request  = $sbsql->query($query_5);

				if ($request)
					$sb_msg_valid = 'Catégorie supprimée avec succès';
				else
					$sb_msg_error = 'Error: Write Error (DEL)!';
			}
		}

		// Initialisation
		$sb_table_header = ['Photo', 'Titre', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);

		// Contents table
		$query_4  = "SELECT * FROM $table_cat ORDER BY sort ASC";
		$request2 = $sbsql->query($query_4);
		$result2  = $sbsql->toarray($request2);

		$sbsmarty->assign('allcat', true);
		$sbsmarty->assign('allcategory', $result2);

		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query_4;
			if (isset($action) && $action == 'categorydel') {
				$alldel_debug .= "\n" . 'DEL: ' . $query_5;
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}

	break;

	case "categoryadd":
	case "categoryedit":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = ($action == 'categoryadd') ? "add_form" : "edit_form";
		$formType        = ($action == 'categoryadd' || $_POST['form_submit'] == 'add_form') ? "categoryadd" : "categoryedit";
		$btn_add_edit    = ($action == 'categoryadd') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'categoryadd') ? "Ajouter une catégorie" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id          = intval($_POST['id']);
			$title       = $sbsanitize->displayText($_POST['title'], 'UTF-8', 1, 0);
			$description = $sbsanitize->displayText($_POST['description'], 'UTF-8', 1, 0);
			$photo       = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$active      = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

			// ADD or EDIT
			if ($formType == 'categoryadd') {
				// INSERT DATAS
				$query = "INSERT INTO $table_cat (title,description,photo,active)
						  VALUES ('$title','$description','$photo','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$title = $description = $photo = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Catégorie ajoutée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'categoryedit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_cat SET title = '$title'
											    ,description = '$description'
											    ,photo = '$photo'
											    ,active = '$active'
											    WHERE id = '$id'";

				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- Message SUCCES
					$sb_msg_valid = 'Catégorie modifiée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);

		} else {
			// Si AJOUT (First time)
			// --- Vider les champs du formulaire
			$title = $description = $photo = $active = '';
		}
		// --------------------------------
		if ($formType == 'categoryedit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id          = intval($_GET['id']);
			$query_1     = "SELECT * FROM $table_cat WHERE id = $id";
			$requestQ    = $sbsql->query($query_1);
			$assoc       = $sbsql->assoc($requestQ);
			$title       = $sbsanitize->displayLang(sb_utf8_encode($assoc['title']));
			$description = $sbsanitize->displayLang(sb_utf8_encode($assoc['description']));
			$photo       = $assoc['photo'];
			$active      = $assoc['active'];

			$sbsmarty->assign('assoc', $query_1);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_1 . "\n" . 'Form Type = '.$formType);
		}

		// --------------------------------
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		$sbform->addInput('text', "Titre", array ('name' => 'title', 'value' => "$title", 'placeholder' => "Titre de la catégorie"), true);
		$sbform->addTextareaHtml("Description", $description, array('id' => 'description', 'name' => 'description'), false);
		$sbform->addInput('text', 'Photo', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo", "medias"=>"", 'icon' => 'photo', 'style' => 'width: 100% !important'), false);
		// --------------------------------
		// --- Hiddens / Buttons
		// --------------------------------
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'categoryedit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --------------------------------
		// --- Close Form
		// --------------------------------
		$sbform->closeForm ();
		// --------------------------------
	break;

	case "settings":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = "settings_form";
		$formType        = "settings";
		$btn_add_edit    = "Modifier";
		$legend_add_edit = "Paramètres généraux de la boutique";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$is_tva               = $sbsanitize->displayText($_POST['is_tva'], 'UTF-8', 1, 0);
			$currency              = $sbsanitize->displayText($_POST['currency'], 'UTF-8', 1, 0);
			$currency_text         = $sbsanitize->displayText($_POST['currency_text'], 'UTF-8', 1, 0);
			$currency_position     = $sbsanitize->displayText($_POST['currency_position'], 'UTF-8', 1, 0);
			$n_decimals            = intval($_POST['n_decimals']);
			$per_page              = intval($_POST['per_page']);
			$invoice_prefix        = $sbsanitize->displayText($_POST['invoice_prefix'], 'UTF-8', 1, 0);
			$unique_code_root      = $sbsanitize->displayText($_POST['unique_code_root'], 'UTF-8', 1, 0);
			$unique_code_key       = $sbsanitize->displayText($_POST['unique_code_key'], 'UTF-8', 1, 0);
			$unique_code_pattern   = $sbsanitize->displayText($_POST['unique_code_pattern'], 'UTF-8', 1, 0);
			$unique_code_length    = intval($_POST['unique_code_length']);

			// UPDATE DATAS (ligne unique, id=1)
			$query = "UPDATE $table_config SET is_tva = '$is_tva'
										       ,currency = '$currency'
										       ,currency_text = '$currency_text'
										       ,currency_position = '$currency_position'
										       ,n_decimals = '$n_decimals'
										       ,per_page = '$per_page'
										       ,invoice_prefix = '$invoice_prefix'
										       ,unique_code_root = '$unique_code_root'
										       ,unique_code_key = '$unique_code_key'
										       ,unique_code_pattern = '$unique_code_pattern'
										       ,unique_code_length = '$unique_code_length'
										       WHERE id = '1'";

			$result_edit = $sbsql->query($query);
			if ($result_edit) {
				// --- Message SUCCES
				$sb_msg_valid = 'Paramètres modifiés avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);

		} else {
			// --- Recuperation des donnees
			$query_1  = "SELECT * FROM $table_config WHERE id = 1";
			$requestQ = $sbsql->query($query_1);
			$assoc    = $sbsql->assoc($requestQ);

			$is_tva               = $assoc['is_tva'];
			$currency              = $assoc['currency'];
			$currency_text         = $assoc['currency_text'];
			$currency_position     = $assoc['currency_position'];
			$n_decimals            = $assoc['n_decimals'];
			$per_page              = $assoc['per_page'];
			$invoice_prefix        = $assoc['invoice_prefix'];
			$unique_code_root      = $assoc['unique_code_root'];
			$unique_code_key       = $assoc['unique_code_key'];
			$unique_code_pattern   = $assoc['unique_code_pattern'];
			$unique_code_length    = $assoc['unique_code_length'];

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_1 . "\n" . 'Form Type = '.$formType);
		}

		// --------------------------------
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$is_tva = ($is_tva) ? '1' : '0';
		$sbform->addRadioYN('Boutique soumise à la TVA', false, array('id'=>'is_tva', 'name'=>'is_tva', 'checked'=>"$is_tva"), 'oui', 'non', "Bascule générale - le choix détaillé se fait ensuite produit par produit (voir la fiche de chaque produit).");
		$sbform->addBreak('Devise et affichage');
		$sbform->addInput('text', "Code devise", array ('name' => 'currency', 'value' => "$currency", 'placeholder' => "EUR"), true);
		$sbform->addInput('text', "Symbole devise", array ('name' => 'currency_text', 'value' => "$currency_text", 'placeholder' => "€"), true);
		$currency_position = ($currency_position) ? '1' : '0';
		$sbform->addRadioYN('Symbole après le prix', false, array('id'=>'currency_position', 'name'=>'currency_position', 'checked'=>"$currency_position"), 'oui (ex: 12,00 €)', 'non (ex: € 12,00)');
		$sbform->addInput('text', "Décimales", array ('name' => 'n_decimals', 'value' => "$n_decimals", 'placeholder' => "2"), true);
		$sbform->addInput('text', "Produits par page (catalogue)", array ('name' => 'per_page', 'value' => "$per_page", 'placeholder' => "12"), true);
		$sbform->addBreak('Facturation');
		$sbform->addInput('text', "Préfixe des numéros de facture", array ('name' => 'invoice_prefix', 'value' => "$invoice_prefix", 'placeholder' => "FAC"), true);
		$sbform->addBreak('Génération des codes uniques (livraison dématérialisée)');
		$sbform->addInput('text', "Racine", array ('name' => 'unique_code_root', 'value' => "$unique_code_root", 'placeholder' => "Ex: BTQ"), false, false, "Préfixe fixe ajouté devant chaque code généré.");
		$sbform->addInput('text', "Clé secrète", array ('name' => 'unique_code_key', 'value' => "$unique_code_key", 'placeholder' => "Chaîne secrète, non devinable"), false, false, "Utilisée pour dériver le code - à ne jamais communiquer.");
		$sbform->openSelect("Motif de la partie générée", array("id"=>"unique_code_pattern", "name"=>"unique_code_pattern"), true);
		foreach (['alphanumeric' => 'Alphanumérique', 'numeric' => 'Numérique uniquement'] as $val => $label) {
			if ($val == $unique_code_pattern)
				$sbform->addOption($label, array ("value"=>$val, "selected"=>""));
			else
				$sbform->addOption($label, array ("value"=>$val));
		}
		$sbform->closeSelect();
		$sbform->addInput('text', "Longueur totale du code", array ('name' => 'unique_code_length', 'value' => "$unique_code_length", 'placeholder' => "16"), true, false, "Racine comprise.");
		// --------------------------------
		// --- Hiddens / Buttons
		// --------------------------------
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------
		// --- Close Form
		// --------------------------------
		$sbform->closeForm ();
		// --------------------------------
	break;

}


// ---------------------------------------------------
// ---------------------------------------------------
// IMPORTANT: Don't remove these lines
// ---------------------------------------------------
// ---------------------------------------------------
// ----------------------------------------
// ASSIGN Page TITLE - Modify this |
// ----------------------------------------
$sbsmarty->assign('page_title', $text_s);
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($sbsanitize->displayLang(isset($title) ? $title : ''), 'UTF-8', 0, 1)));

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);
// --- Second submit Button
$sbsmarty->assign('sb_form_id', $formName);
$sbsmarty->assign('sb_form_submit_value', $btn_add_edit);

// ----------------------
// CLOSE SQL
// ----------------------
$sbsql->close();

?>
