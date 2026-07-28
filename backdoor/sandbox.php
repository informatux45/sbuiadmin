<?php
/**
 * Admin Startbootstrap
 * SANDBOX
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
// Module URL
// -----------------------
$module_page = 'sandbox';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

$page_builder_content  = <<<EOT
<div class="lyrow ui-draggable" style="position: relative; width: 400px; right: auto; height: 22px; bottom: auto; left: 0px; top: 0px; opacity: 1;"><a href="#close" class="remove btn btn-danger btn-xs">
											<i class="glyphicon-remove glyphicon" title="Remove"></i>
										</a>
										<a class="drag btn btn-default btn-xs ui-draggable-handle">
											<i class="glyphicon glyphicon-move" title="Move"></i>
										</a>
										<a href="#" class="btn btn-info btn-xs clone" title="Clone">
											<i class="fa fa-copy"></i>
										</a><div class="preview">
											<input value="9 3" class="form-control" type="text">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-9 column ui-sortable"><div class="lyrow ui-draggable" style="position: relative; width: 400px; right: auto; height: 22px; bottom: auto; left: 0px; top: 0px; opacity: 1;"><a href="#close" class="remove btn btn-danger btn-xs">
											<i class="glyphicon-remove glyphicon" title="Remove"></i>
										</a>
										<a class="drag btn btn-default btn-xs ui-draggable-handle">
											<i class="glyphicon glyphicon-move" title="Move"></i>
										</a>
										<a href="#" class="btn btn-info btn-xs clone" title="Clone">
											<i class="fa fa-copy"></i>
										</a><div class="preview">
											<input value="4 4 4" class="form-control" type="text">
										</div>
										<div class="view">
											<div class="row clearfix" style="padding: 25px 14px 0px; margin: 15px 0px; background-color: rgb(245, 245, 245);">
												<div class="col-md-4 column ui-sortable"><div class="box box-element ui-draggable" data-type="image" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 1;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa fa-picture-o fa-2x"></i>
										<div class="element-desc">Image</div>
									</div>
									<div class="view"> <img id="" class="" title="Votre image" src="img/add-image.svg" width="130" height="130"> </div>
								</div></div>
												<div class="col-md-4 column ui-sortable"><div class="box box-element ui-draggable" data-type="button" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 1;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa  fa-hand-o-up fa-2x"></i>
										<div class="element-desc">Bouton</div>
									</div>
									<div class="view"> <a class="btn btn-default" href="#">Click Me !</a> </div>
								</div></div>
												<div class="col-md-4 column ui-sortable" style="padding: 39px 19px 24px; margin: 15px 0px; background-color: rgb(255, 255, 255);"><div class="box box-element ui-draggable active" data-type="paragraph" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 1;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa fa-font fa-2x"></i>
										<div class="element-desc">Texte</div>
									</div>
									<div class="view" id=""><p>Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua.</p>
</div>
								</div></div>
											</div>
										</div>			</div></div>
												<div class="col-md-3 column ui-sortable"><div class="box box-element ui-draggable" data-type="code" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 1;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa fa-code fa-2x"></i>
										<div class="element-desc">Code</div>
									</div>
									<div class="view"> Put your html code here </div>
								</div></div>
											</div>
										</div>			</div><div class="lyrow ui-draggable" style="position: relative; width: 400px; right: auto; height: 22px; bottom: auto; left: 0px; top: 0px; opacity: 1;"><a href="#close" class="remove btn btn-danger btn-xs">
											<i class="glyphicon-remove glyphicon" title="Remove"></i>
										</a>
										<a class="drag btn btn-default btn-xs ui-draggable-handle ui-sortable-handle">
											<i class="glyphicon glyphicon-move" title="Move"></i>
										</a>
										<a href="#" class="btn btn-info btn-xs clone" title="Clone">
											<i class="fa fa-copy"></i>
										</a><div class="preview">
											<input value="12" class="form-control" type="text">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-12 column ui-sortable"><div class="box box-element ui-draggable" data-type="paragraph" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 1;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs ui-sortable-handle"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa fa-font fa-2x"></i>
										<div class="element-desc">Texte</div>
									</div>
									<div class="view">
										<p>Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
									</div>
								</div></div>
											</div>
										</div>			</div><div class="lyrow ui-draggable" style="position: relative; width: 400px; right: auto; height: 22px; bottom: auto; left: 0px; top: 0px; opacity: 1;"><a href="#close" class="remove btn btn-danger btn-xs">
											<i class="glyphicon-remove glyphicon" title="Remove"></i>
										</a>
										<a class="drag btn btn-default btn-xs ui-draggable-handle ui-sortable-handle">
											<i class="glyphicon glyphicon-move" title="Move"></i>
										</a>
										<a href="#" class="btn btn-info btn-xs clone" title="Clone">
											<i class="fa fa-copy"></i>
										</a><div class="preview">
											<input value="6 6" class="form-control" type="text">
										</div>
										<div class="view">
											<div class="row clearfix" style="padding: 25px 14px 0px; margin: 15px 0px; background-color: rgb(245, 245, 245);">
												<div class="col-md-6 column ui-sortable" style="padding: 39px 19px 24px; margin: 15px 0px; background-color: rgb(255, 255, 255);"><div class="box box-element ui-draggable" data-type="youtube" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 1;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs ui-sortable-handle"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa  fa fa-youtube-play  fa-2x"></i>
										<div class="element-desc">Youtube</div>
									</div>
									<div class="view">
										<iframe class="" src="https://www.youtube.com/embed/5k4Y9FGKFTU" allowfullscreen="" data-url="" id="" style="width: 100%; height: 150px;" frameborder="0"></iframe>
									</div>
								</div></div>
												<div class="col-md-6 column ui-sortable"><div class="box box-element ui-draggable" data-type="youtube" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 1;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs ui-sortable-handle"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa  fa-vimeo-square fa-2x"></i>
										<div class="element-desc">Vimeo</div>
									</div>
									<div class="view">
										<iframe class="img-responsive" src="https://player.vimeo.com/video/20016963?byline=0&amp;portrait=0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" frameborder="0"></iframe>
									</div>
								</div></div>
											</div>
										</div>			</div><div class="lyrow ui-draggable" style="position: relative; width: 400px; right: auto; height: 22px; bottom: auto; left: 0px; top: 0px; opacity: 1;"><a href="#close" class="remove btn btn-danger btn-xs">
											<i class="glyphicon-remove glyphicon" title="Remove"></i>
										</a>
										<a class="drag btn btn-default btn-xs ui-draggable-handle ui-sortable-handle">
											<i class="glyphicon glyphicon-move" title="Move"></i>
										</a>
										<a href="#" class="btn btn-info btn-xs clone" title="Clone">
											<i class="fa fa-copy"></i>
										</a><div class="preview">
											<input value="3 3 3 3" class="form-control" type="text">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-3 column ui-sortable"><div class="box box-element ui-draggable" data-type="image" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 1;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs ui-sortable-handle"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa fa-picture-o fa-2x"></i>
										<div class="element-desc">Image</div>
									</div>
									<div class="view"> <img id="" class="" title="Votre image" src="img/add-image.svg" width="130" height="130"> </div>
								</div></div>
												<div class="col-md-3 column ui-sortable"><div class="box box-element ui-draggable" data-type="image" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 1;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs ui-sortable-handle"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa fa-picture-o fa-2x"></i>
										<div class="element-desc">Image</div>
									</div>
									<div class="view"> <img id="" class="" title="Votre image" src="img/add-image.svg" width="130" height="130"> </div>
								</div></div>
												<div class="col-md-3 column ui-sortable"><div class="box box-element ui-draggable" data-type="image" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 1;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs ui-sortable-handle"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa fa-picture-o fa-2x"></i>
										<div class="element-desc">Image</div>
									</div>
									<div class="view"> <img id="" class="" title="Votre image" src="img/add-image.svg" width="130" height="130"> </div>
								</div></div>
												<div class="col-md-3 column ui-sortable"><div class="box box-element ui-draggable" data-type="image" style="position: relative; width: 404px; right: auto; height: 50px; bottom: auto; left: 0px; top: 0px; opacity: 0.35; z-index: 1000;"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs ui-sortable-handle"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#"><i class="fa fa-gear"></i></a> </span>
									<div class="preview ui-draggable-handle"> <i class="fa fa-picture-o fa-2x"></i>
										<div class="element-desc">Image</div>
									</div>
									<div class="view"> <img id="" class="" title="Votre image" src="img/add-image.svg" width="130" height="130"> </div>
								</div></div>
											</div>
										</div>			</div>

EOT;

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------

$table = _AM_DB_PREFIX ."sb_sandbox";

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			$query_2  = "DELETE FROM $table WHERE id = '$get_id'";
			$request  = $sbsql->query($query_2);
			
			if ($request)
				$sb_msg_valid = 'Enregistrement supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = array('Nom', 'Pays', 'Type', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query     = "SELECT * FROM $table";
		$request2  = $sbsql->query($query);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('allsandbox', $result2);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query;
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
		$legend_add_edit = ($action == 'add') ? "Ajouter un enregistrement" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données - un champ par type de widget démontré
			// sur cette page (voir le formulaire plus bas), aligné sur les
			// vraies colonnes de sb_sandbox (le bloc précédent lisait des
			// clés POST - horsename/sire_dam_info/perf_1.../video_1... -
			// qu'aucun champ du formulaire n'envoie jamais, et écrivait
			// dans des colonnes qui n'existaient plus dans la table :
			// toute soumission plantait avant d'atteindre la base).
			$id              = intval($_POST['id']);
			$active          = ($_POST['active'] == '1') ? '1' : '0';
			$yourname        = $sbsanitize->displayText($_POST['yourname'], 'UTF-8', 1, 0);
			$montant         = $sbsanitize->displayText($_POST['montant'], 'UTF-8', 1, 0);
			$seo_url         = $sbsanitize->displayText($_POST['seo_url'], 'UTF-8', 1, 0);
			$country         = $sbsanitize->displayText($_POST['country'], 'UTF-8', 1, 0);
			$dob             = $sbsanitize->displayText($_POST['dob'], 'UTF-8', 1, 0);
			$color           = $sbsanitize->displayText($_POST['color'], 'UTF-8', 1, 0);
			$tags            = sbGetTagifyDatas($_POST['tags']); // Tags
			$pdf             = $sbsanitize->displayText($_POST['pdf'], 'UTF-8', 1, 0);
			$photo           = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$video           = $sbsanitize->displayText($_POST['video'], 'UTF-8', 1, 0);
			$option_one      = isset($_POST['option_one']) ? '1' : '0';
			$option_two      = isset($_POST['option_two']) ? '1' : '0';
			$option_three    = isset($_POST['option_three']) ? '1' : '0';
			$type            = in_array($_POST['type'], array('1', '2', '3')) ? $_POST['type'] : '';
			$selection_table       = $sbsanitize->displayText($_POST['selection'], 'UTF-8', 1, 0);
			$comment         = $sbsanitize->displayText($_POST['comment'], 'UTF-8', 1, 0);
			$comment_ckeditor1 = $sbsanitize->displayText($_POST['comment_editor1'], 'UTF-8', 1, 0);
			$comment_ckeditor2 = $sbsanitize->displayText($_POST['comment_editor2'], 'UTF-8', 1, 0);
			$comment_ckeditor3 = $sbsanitize->displayText($_POST['comment_editor3'], 'UTF-8', 1, 0);
			// Page Builder : voir addPageBuilder()/pagebuilder.js - le
			// champ réel synchronisé à la soumission (data-pagebuilder-
			// target), pas la version "démo" codée en dur.
			$page_builder_content = $sbsanitize->displayText($_POST['page_builder_content'], 'UTF-8', 1, 0);

			if (!$tags) $tags = '';

			// Échappement SQL - absent jusqu'ici sur tout ce bloc (aucun
			// escape_string() nulle part), risque d'injection et requête
			// cassée dès qu'une valeur contient une apostrophe.
			$yourname_esc          = $sbsql->escape_string($yourname);
			$montant_esc           = $sbsql->escape_string($montant);
			$seo_url_esc           = $sbsql->escape_string($seo_url);
			$country_esc           = $sbsql->escape_string($country);
			$dob_esc               = $sbsql->escape_string($dob);
			$color_esc             = $sbsql->escape_string($color);
			$tags_esc              = $sbsql->escape_string($tags);
			$pdf_esc                = $sbsql->escape_string($pdf);
			$photo_esc              = $sbsql->escape_string($photo);
			$video_esc              = $sbsql->escape_string($video);
			$type_esc               = $sbsql->escape_string($type);
			$selection_table_esc    = $sbsql->escape_string($selection_table);
			$comment_esc            = $sbsql->escape_string($comment);
			$comment_ckeditor1_esc  = $sbsql->escape_string($comment_ckeditor1);
			$comment_ckeditor2_esc  = $sbsql->escape_string($comment_ckeditor2);
			$comment_ckeditor3_esc  = $sbsql->escape_string($comment_ckeditor3);
			$page_builder_content_esc = $sbsql->escape_string($page_builder_content);

			// Réaffichage du formulaire dans la même requête après soumission
			// (pas de redirection HTTP : "add" échoué et "edit" ne vident
			// jamais les champs, voir plus bas) : $page_builder_content
			// ci-dessus est encore la version encodée en entités pour le
			// stockage (displayText()), déjà capturée dans
			// $page_builder_content_esc pour la requête SQL - on peut donc
			// la re-décoder sans risque pour redonner à addPageBuilder() du
			// HTML brut, exactement comme le fait le bloc GET plus bas
			// (html_entity_decode(utf8_encode(...))). Sans ce re-décodage,
			// addPageBuilder() recevait la version entités et affichait la
			// "soupe de code" immédiatement après Ajouter/Modifier, sans
			// même attendre un rechargement (pas d'utf8_encode() ici :
			// contrairement au bloc GET, cette chaîne ne vient pas de la
			// base, elle est déjà de l'UTF-8 valide dans cette même requête).
			$page_builder_content = html_entity_decode($page_builder_content, ENT_QUOTES, 'UTF-8');

			// ADD or EDIT
			if ($formType == 'add') {
				$query = "INSERT INTO $table (active, yourname, montant, seo_url, country, dob, color, tags, pdf, photo, video, option_one, option_two, option_three, type, selection, comment, comment_editor1, comment_editor2, comment_editor3, page_builder_content, sort)
						  VALUES ('$active','$yourname_esc','$montant_esc','$seo_url_esc','$country_esc','$dob_esc','$color_esc','$tags_esc','$pdf_esc','$photo_esc','$video_esc','$option_one','$option_two','$option_three','$type_esc','$selection_table_esc','$comment_esc','$comment_ckeditor1_esc','$comment_ckeditor2_esc','$comment_ckeditor3_esc','$page_builder_content_esc','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$yourname = $montant = $seo_url = $country = $dob = $color = $tags = $pdf = $photo = $video = $selection_table = $comment = $comment_ckeditor1 = $comment_ckeditor2 = $comment_ckeditor3 = $page_builder_content = $type = '';
					$active = $option_one = $option_two = $option_three = '0';
					// --- Message SUCCESS
					$sb_msg_valid = 'Enregistrement ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {

				// UPDATE DATAS
				$query = "UPDATE $table SET active = '$active'
											 ,yourname = '$yourname_esc'
											 ,montant = '$montant_esc'
											 ,seo_url = '$seo_url_esc'
											 ,country = '$country_esc'
											 ,dob = '$dob_esc'
											 ,color = '$color_esc'
											 ,tags = '$tags_esc'
											 ,pdf = '$pdf_esc'
											 ,photo = '$photo_esc'
											 ,video = '$video_esc'
											 ,option_one = '$option_one'
											 ,option_two = '$option_two'
											 ,option_three = '$option_three'
											 ,type = '$type_esc'
											 ,selection = '$selection_table_esc'
											 ,comment = '$comment_esc'
											 ,comment_editor1 = '$comment_ckeditor1_esc'
											 ,comment_editor2 = '$comment_ckeditor2_esc'
											 ,comment_editor3 = '$comment_ckeditor3_esc'
											 ,page_builder_content = '$page_builder_content_esc'
											 WHERE id = '$id'";

				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Enregistrement modifié avec succès';
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
			$yourname = $montant = $seo_url = $country = $dob = $color = $tags = $pdf = $photo = $video = $selection_table = $comment = $comment_ckeditor1 = $comment_ckeditor2 = $comment_ckeditor3 = $page_builder_content = $type = '';
			$active = $option_one = $option_two = $option_three = '0';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id                = intval($_GET['id']);
			$query_1           = "SELECT * FROM $table WHERE id = $id";
			$requestQ          = $sbsql->query($query_1);
			$assoc             = $sbsql->assoc($requestQ);
			$active            = $assoc['active'];
			$yourname          = utf8_encode($assoc['yourname']);
			$montant           = utf8_encode($assoc['montant']);
			$seo_url           = utf8_encode($assoc['seo_url']);
			$country           = utf8_encode($assoc['country']);
			$dob               = utf8_encode($assoc['dob']);
			$color             = utf8_encode($assoc['color']);
			// Tagify attend son "value" initial au format JSON
			// ([{"value":"demo"}, ...]) pour re-précharger les tags - une
			// simple chaîne "demo,exemple" fait planter son initialisation
			// (loadOriginalValues/addTags, erreur "focusNode" - i is null),
			// ce qui bloquait au passage toute la file $(document).ready()
			// suivante (dont la synchronisation du Page Builder).
			$tags = '';
			if ($assoc['tags'] != '') {
				$tags_arr = array();
				foreach (explode(',', $assoc['tags']) as $tags_item) {
					$tags_item = trim($tags_item);
					if ($tags_item !== '') $tags_arr[] = array('value' => $tags_item);
				}
				$tags = json_encode($tags_arr, JSON_UNESCAPED_UNICODE);
			}
			$pdf               = utf8_encode($assoc['pdf']);
			$photo             = utf8_encode($assoc['photo']);
			$video             = utf8_encode($assoc['video']);
			$option_one        = $assoc['option_one'];
			$option_two        = $assoc['option_two'];
			$option_three      = $assoc['option_three'];
			$type_checked      = $assoc['type'];
			$selection_table   = $assoc['selection'];
			$comment           = utf8_encode($assoc['comment']);
			$comment_ckeditor1 = utf8_encode($assoc['comment_editor1']);
			$comment_ckeditor2 = utf8_encode($assoc['comment_editor2']);
			$comment_ckeditor3 = utf8_encode($assoc['comment_editor3']);
			// Page Builder : contenu réel (pas la version "démo") - décodé
			// pour être réinjecté tel quel dans ".htmlpage" par
			// addPageBuilder() (voir sbuiadmin-form.php), pas affiché en
			// texte échappé.
			$page_builder_content = html_entity_decode(utf8_encode($assoc['page_builder_content']), ENT_QUOTES, 'UTF-8');

			$sbsmarty->assign('assoc', $query_1);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_1 . "\n" . 'Form Type = '.$formType);						
		}
		// --------------------------------
		// --- Define variables
		// $id manquait ici (bug préexistant) - l'action du formulaire ne
		// portait jamais l'id en édition, la cible réelle ne venait que du
		// champ caché "id" en POST (fonctionnait par accident, mais l'URL
		// affichée pendant l'édition était trompeuse).
		$formAction = $module_url . "&a=" . $formType . ($formType == 'edit' ? "&id=" . $id : "");
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		// ----------------------------
		// --- Radio Y - N
		// ----------------------------
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif (Radio YN)', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Input TEXT
		// ----------------------------
		$sbform->addInput('text', 'Nom (Input TEXT)', array ('name' => 'yourname', 'value' => "$yourname", 'placeholder' => "Votre nom"), true);
		// ----------------------------
		// --- Input TEXT with icon / icon2
		// ----------------------------
		$sbform->addInput('text', 'Nom (2 icônes)', array ('name' => 'montant', 'value' => "$montant", 'placeholder' => "Votre montant", 'icon' => 'euro', 'icon2' => '.00', 'style' => 'width: 200px;'), true);
		// ----------------------------
		// --- Input TEXT with text instead of icon
		// ----------------------------
		$sbform->addInput('text', "Texte à la place de l'icône", array ('name' => 'seo_url', 'value' => "$seo_url", 'placeholder' => "Votre url", 'icon' => '0Url du site'), false);
		// ----------------------------
		// --- BREAK
		// ----------------------------
		$sbform->addBreak('Separateur');
		// ----------------------------
		// --- Input COUNTRY
		// ----------------------------		
		$sbform->addCountry('Pays', array('id' => 'country', 'name' => 'country', 'value' => $country, 'style' => 'width: auto;'), true, 'Choisissez un pays');
		// ----------------------------
		// --- Input DATE (calendar)
		// ----------------------------
		$sbform->addDate('Date de naissance (Calendar)', array('id'=>'dob', 'name'=>'dob', 'value'=>$dob), true);
		// ----------------------------
		// --- Input COLOR PICKER
		// ----------------------------		
		$sbform->addColor ('Couleur (Color PICKER)', array('id' => 'color', 'name' => 'color', 'value' => $color), false);
		// -----------------------------------
		// --- Caisses
		// -----------------------------------
		$sbform->addTagify ('Tags', array('name' => 'tags', 'value' => $tags, 'placeholder' => 'Indiquez le nom des caisses', 'style' => 'width: 500px;'), false, 'Ajouter des noms et valider par la touche Entrée de votre clavier.<br>Vous pouvez trier les noms en drag & drop.<br>Ne pas utilisez le caractère "<strong>,</strong>" (Virgule).');
		// ----------------------------
		// --- Pdf only (width popup medias)
		// You can add more exts separate by coma
		// ex: "extension" => "pdf"
		// ex: "extension" => "pdf,xml,gif"
		// ----------------------------
		$sbform->addInput('text', 'Pdf only (With popup MEDIAS)', array ('id'=>'inputPdf', 'name' => 'pdf', 'value' => "$photo", 'placeholder' => "Votre pdf", "medias"=>"", "extension" => "pdf", 'icon' => 'file-pdf-o'), false);
		// ----------------------------
		// --- Photo (width popup medias)
		// ----------------------------
		$sbform->addInput('text', 'Photo (With popup MEDIAS)', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Votre photo", "medias"=>"", 'icon' => 'photo'), false);
		// ----------------------------
		// --- Photo (width popup medias in SUBDIR)
		// ----------------------------
		$sbform->addInput('text', 'Photo (With popup MEDIAS in SUBDIR)', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Votre photo", "medias"=>"", 'icon' => 'photo', "dir" => _AM_MEDIAS_DIR . "/new", "subdir" => "new"), false);
		// ----------------------------
		// --- Photo (width popup medias in SUBDIR AND Limit files to display)
		// ----------------------------
		$sbform->addInput('text', 'Photo (With popup MEDIAS in SUBDIR AND Limit files display to 10)', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Votre photo", "medias"=>"", 'icon' => 'photo', "dir" => _AM_MEDIAS_DIR . "/new", "subdir" => "new", "limitfiles" => 10), false);
		// ----------------------------
		// -- Video (without popup media)
		// ----------------------------
		$sbform->addInput('text', 'Vidéo (URL) - (Without popup MEDIAS)', array ('id' => 'video', 'name' => 'video', 'value' => "$video", 'placeholder' => "URL de votre vidéo ( http:// )", 'icon' => 'video-camera'), true);
		// ----------------------------
		// -- Input CHECKBOX
		// ----------------------------
		$tab_check = array();
		$tab_check[0]['text']    = 'Option 1';
		$tab_check[0]['name']    = 'option_one';
		$tab_check[0]['checked'] = ($option_one == 1) ? '1' : '0';
		$tab_check[1]['text']    = 'Option 2';
		$tab_check[1]['name']    = 'option_two';
		$tab_check[1]['checked'] = ($option_two == 1) ? '1' : '0';
		$tab_check[2]['text']    = 'Option 3';
		$tab_check[2]['name']    = 'option_three';
		$tab_check[2]['checked'] = ($option_three == 1) ? '1' : '0';
		$sbform->addCheckbox('Toutes vos options', $tab_check, '', false, '<br />');
		// ----------------------------
		// -- Input RADIO
		// ----------------------------
		$tab_type  = [];
		$tab_types = array( ['id' => '1', 'title' => 'Option 1'],
						    ['id' => '2', 'title' => 'Option 2'],
						    ['id' => '3', 'title' => 'Option 3'],
						   );
		foreach($tab_types as $key => $val) {
			if ($type == $val['id'])
				$type_checked = $val['id'];
			$tab_type[$key]['text']  = $val['title'];
			$tab_type[$key]['value'] = $val['id'];
		}
		$sbform->addRadio ('Choisissez un type', $tab_type, array('id'=>'type', 'name'=>'type', 'checked'=>"$type_checked"), true, '<br>');
		// -----------------------------------
		// --- Affichage du Select - STATUS 2
		// -----------------------------------
		$sb_tables = array('Selection 1','Selection 2','Selection 3');
		$sbform->openSelect("Votre selection", array("id"=>"selection", "name"=>"selection"));
		if ($selection_table == '') $sbform->addOption('Choisissez une table', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($sb_tables); $i++) {
			if ($sb_tables[$i] == $selection_table)
				$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i], "selected"=>""));
		else
				$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i]));
		}
		// --- Close Select
		$sbform->closeSelect();
		// ----------------------------
		// -- Input TEXTAREA
		// ----------------------------		
		$sbform->addTextarea('Editeur (TEXTAREA)', $comment, array('id' => 'comment', 'name' => 'comment', 'style' => 'height: 150px !important;'), false);
		// ----------------------------
		// -- Input TEXTAREA HTML (Ckeditor FULL)
		// ----------------------------		
		$sbform->addTextareaHTML('Editeur HTML (CKEDITOR Full)', $comment_ckeditor1, array('id' => 'comment_editor1', 'name' => 'comment_editor1'), false);
		// --- Basic
		$sbform->addTextareaHTML('Editeur HTML (CKEDITOR Basic)', $comment_ckeditor2, array('id' => 'comment_editor2', 'name' => 'comment_editor2'), false, 'basic');
		// --- Simple
		$sbform->addTextareaHTML('Editeur HTML (CKEDITOR Simple)', $comment_ckeditor3, array('id' => 'comment_editor3', 'name' => 'comment_editor3'), false, 'simple');
		// ----------------------------
		// -- Page BUILDER
		// ----------------------------				
		$sbform->addPageBuilder('Page BUILDER HTML', $page_builder_content, array('id' => 'page_builder_content', 'name' => 'page_builder_content'), false, 'full', '');
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
		$legend_add_edit = "Trier les enregistrements";
		// --------------------------------
		if ($_POST['drag']) {
			// --------------------------------
			// --- Control form submit --------
			// --------------------------------
			$sb_toSort = $_POST['drag'];
			
			// reorganizes the order of elements
			$sql_error = 0;
			for ($i = 0; $i < count($sb_toSort); $i++) {  
				$query_sort  = "UPDATE $table SET sort = $i WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = 'Les enregistrements ont été trié avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$id            = intval($_GET['id']);
		$query_3       = "SELECT * FROM $table WHERE active = '1' ORDER BY sort ASC";
		$requestQ      = $sbsql->query($query_3);
		$sort_array    = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = utf8_encode($sort['yourname']);
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_3 . "\n" . 'Form Type = '.$formType);
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('sort', true);
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
$sbsmarty->assign('page_title', 'SANDBOX');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($horsename, 'UTF-8', 0, 1)));

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

// ----------------------
// CLOSE SQL (if open)
// ----------------------
$sbsql->close();

?>