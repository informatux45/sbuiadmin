<?php
/** *****************************************************************************
*                      INFORMATUX sanitize class (UTF8)                         *
/** *****************************************************************************
* @author     Patrice BOUTHIER <contact[at]informatux.com>                      *
* @copyright  1996-2016 INFORMATUX                                              *
* @link       http://www.informatux.com/                                        *
* @since      1.0                                                               *
* @version    CVS: 1.8                                                          *
* ----------------------------------------------------------------------------- *
* Copyright (c) 2011, INFORMATUX Solutions and web development                  *
* All rights reserved.                                                          *
***************************************************************************** **/

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBMAGIC_PATH') or die('Are you crazy!');


class form extends sanitize {

	/**
	* Class form's attributes
	* private properties
	* All the elements and attributes usables
	* Some values are entered by default
	*/
	private $eventArr = array ('onfocus' => ''
							  ,'onblur' => ''
							  ,'onselect' => ''
							  ,'onchange' => ''
							  ,'onclick' => ''
							  ,'ondblclick' => ''
							  ,'onmousedown' => ''
							  ,'onmouseup' => ''
							  ,'onmouseover' => ''
							  ,'onmousemove' => ''
							  ,'onmouseout' => ''
							  ,'onkeypress' => ''
							  ,'onkeydown' => ''
							  ,'onkeyup' => ''
							  );
	private $commonArr = array ('id' => ''
							   ,'class' => ''
							   ,'title' => ''
							   ,'style' => ''
							   ,'dir' => ''
							   ,'lang' => ''
							   ,'xml:lang' => ''
							   );
	private $formArr = array ('name' => 'formNew'
							 ,'id' => 'formNew'
							 ,'method' => 'post'
							 ,'action' => ''
							 ,'enctype' => 'application/x-www-form-urlencoded'
							 ,'accept' => ''
							 ,'onsubmit' => ''
							 ,'onclick' => ''
							 ,'onreset' => ''
							 ,'accept-charset' => 'unknown'
							 ,'style' => ''
							 ,'postid' => ''
							 ,'reloadpage' => ''
							 ,'submitpage' => ''
							 ,'reloadatas' => ''
							 ,'class' => ''
							 );
	private $inputArr = array ('text' => array ('value' => ''
											   ,'name' => ''
											   ,'alt' => ''
											   ,'tabindex' => ''
											   ,'accesskey' => ''
											   ,'readonly' => ''
											   ,'disabled' => ''
											   ,'width' => ''
											   ,'maxlength' => ''
											   ,'required' => ''
											   ,'size ' => ''
											   ,'valid' => ''
											   ,'class' => ''
											   ,'bname' => ''
											   ,'mask' => ''
											   ,'placeholder' => ''
											   ,'icon' => ''
											   ,'icon2' => ''
											   ,'medias' => ''
											   ,'dir' => ''
											   ,'custom' => ''
											   ,'extension' => ''
											   ),
							   'button' => array ('name' => ''
												 ,'value' => ''
												 ,'alt' => ''
												 ,'tabindex' => ''
												 ,'accesskey' => ''
												 ,'disabled' => ''
												 ),
							   'hidden' => array ('name' => ''
												 ,'value' => ''
												 ,'alt' => ''
												 ,'disabled' => ''
												 ,'size ' => ''
												 ),
							   'password' => array ('name' => ''
												   ,'value' => ''
												   ,'alt' => ''
												   ,'tabindex' => ''
												   ,'accesskey' => ''
												   ,'readonly' => ''
												   ,'disabled' => ''
												   ,'width' => ''
												   ,'maxlength' => ''
												   ,'size ' => ''
												   ,'valid' => ''
												   ,'bname' => ''
												   ,'mask' => ''
												   ),
							   'submit' => array ('name' => ''
												 ,'value' => 'Valider'
												 ,'alt' => ''
												 ,'tabindex' => ''
												 ,'accesskey' => ''
												 ,'disabled' => ''
												 ),
							   'checkbox' => array ('name' => ''
												   ,'value' => ''
												   ,'alt' => ''
												   ,'tabindex' => ''
												   ,'accesskey' => ''
												   ,'disabled' => ''
												   ,'checked' => ''
												   ,'valid' => ''
												   ,'class' => ''
												   ),
							   'radio' => array ('name' => ''
												,'value' => ''
												,'alt' => ''
												,'tabindex' => ''
												,'accesskey' => ''
												,'disabled' => ''
												,'checked' => ''
												,'title' => ''
												,'valid' => ''
												,'class' => ''
												,'bname' => ''
												),
							   'reset' => array ('name' => ''
												,'class' => ''
												,'value' => ''
												,'alt' => ''
												,'tabindex' => ''
												,'accesskey' => ''
												,'disabled' => ''
												,'title' => ''
												),
							   'file' => array ('name' => ''
											   ,'value' => ''
											   ,'alt' => ''
											   ,'tabindex' => ''
											   ,'accesskey' => ''
											   ,'disabled' => ''
											   ,'accept' => ''
											   ,'size ' => ''
											   ,'max_file_size' => ''
											   ,'bname' => ''
											   ,'mask' => ''
											   ),
							   'image' => array ('name' => ''
												,'value' => ''
												,'alt' => ''
												,'tabindex' => ''
												,'accesskey' => ''
												,'disabled' => ''
												,'src' => ''
												,'usemap' => ''
												,'ismap' => ''
												,'valid' => ''
												)
							);
	private $textareaArr = array ('rows' => ''
								 ,'cols' => ''
								 ,'disabled' => ''
								 ,'readonly' => ''
								 ,'accesskey' => ''
								 ,'tabindex' => ''
								 ,'name' => ''
								 ,'valid' => ''
								 ,'class' => ''
								 ,'bname' => ''
								 ,'mask' => ''
								 );
	private $selectArr = array ('disabled' => ''
							   ,'multiple' => ''
							   ,'selected' => ''
							   ,'size' => ''
							   ,'name' => ''
							   ,'class' => ''
							   ,'bname' => ''
							   ,'mask' => ''
							   );
	private $optionArr = array ('disabled' => ''
							   ,'label' => ''
							   ,'selected' => ''
							   ,'value' => ''
							   );
	private $optgroupArr      = array ('label' => ''
									  ,'disabled' => ''
									  );
	private $formBuffer       = array ();
	private $formElementArr   = array ();
	private $formAttributeArr = array ();
	
	//Constructeur
	public function __construct () {
	
	}
	
	// débuter effectivement le formulaire
	public function openForm ($arrArgs = array ()) {
		foreach ($this -> formArr as $clef => $val) {
			if (array_key_exists ($clef, $arrArgs)) {
				$this -> formAttributeArr[$clef] = $arrArgs[$clef];
			} else if (!empty ($val)) {
				$this -> formAttributeArr[$clef] = $val;
			}
		}

		// Form initialisation
		//$showInDiv  = ($this -> formAttributeArr['postid']) ? $this -> formAttributeArr['postid'] : 'central_home';
		//$reloadData = ($this -> formAttributeArr['reloadatas']) ? $this -> formAttributeArr['reloadatas'] : "0";
		
		$this -> formBuffer['open'] = ' <link rel="stylesheet" href="inc/js/jquery/theme/1.11.4.custom/jquery-ui.min.css">
										<link rel="stylesheet" href="inc/js/jquery/colorpicker/css/colorpicker.css">
										<script type="text/javascript" src="inc/js/jquery/theme/1.11.4.custom/jquery-ui.min.js"></script>
										<script type="text/javascript" src="inc/js/jquery/colorpicker/js/colorpicker.js"></script>
										<script type="text/javascript" src="inc/js/jquery/ui/i18n/ui.datepicker-fr.js"></script>
										<script type="text/javascript" src="inc/js/editor/ckeditor/ckeditor.js"></script>
									  ';
		$this -> formBuffer['open'] .= "\n" . '<form ';
		
		foreach ($this -> formAttributeArr as $clef => $val) {
			if ($clef == 'id')
				$this -> formBuffer['formId'] = $val;
			if ($clef == 'name')
				$this -> formBuffer['formName'] = $val;
			if ($clef != 'reloadpage' && $clef != 'submitpage')
				$this -> formBuffer['open'] .= $clef.'="'.$val.'" ';
		}
		$this -> formBuffer['open'] .= '>' . "\n";
		
		// Define open & close input
		$this -> formBuffer['open_div'] .= '<div class="form-group">';
		$this -> formBuffer['close_div'] .= '</div>';
		$this -> formBuffer['close_div'] .= '</div>';		
	}
	
	// fermer le formulaire
	public function closeForm () {
		$this -> formBuffer['close'] = '</form>';
	}


	/**
	* Construct form (body)
	* add a form element (input)
	* @return html code
	*/
	public function addInput ($elem, $label = '', $arrArgs = array (), $isRequired = false, $options = false, $helpDsc = '') {
		if (!array_key_exists ($elem, $this -> inputArr)) {
			throw new Exception ($elem . ' n\'est pas un élément valide');
		}
		
		if (!array_key_exists ('name', $arrArgs) && $elem !== 'submit' && $elem !== 'reset') {
			$arrArgs['name'] = 'default'.time();
		}
		
		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt][$elem] = array ();
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr, $this -> inputArr[$elem]);

		foreach ($arrTemp as $clef => $val) {
			if (array_key_exists ($clef, $arrArgs)) {
				if ($clef == 'id')
					$labelFor = $arrArgs[$clef];
				if ($clef == 'icon')
					$labelInputGroup = $arrArgs[$clef];
				if ($clef == 'medias')
					$onclickFunction = true;
				
				$this -> formElementArr[$cpt][$elem][$clef] = $arrArgs[$clef];
			}
		}
		
		// Show the label for the element
		// If $elem = hidden, so do not create a table row
		if ($elem == 'hidden') {
			$chaineTemp = '<input type="'.$elem.'" ';

			foreach ($this -> formElementArr[$cpt][$elem] as $clef => $val) {
				$chaineTemp .= $clef.'="'.$val.'" ';
			}

			$chaineTemp .= '/>';
		} elseif ($elem == 'submit' || $elem == 'reset') {

			// Show the form element
			$chaineTemp = '&nbsp;&nbsp;<input type="'.$elem.'" ';

			foreach ($this -> formElementArr[$cpt][$elem] as $clef => $val) {
				if ($clef == 'class')
					$chaineClass = $val;
				$chaineTemp .= $clef.'="'.$val.'" ';
			}

			if (!isset($chaineClass)) {
				$submitClass = ($elem == 'submit') ? ' btn-submit' : '';
				$chaineTemp .= 'class="btn btn-default' . $submitClass . '" ';
			}

			$chaineTemp .= '/>&nbsp;&nbsp;';
				
		} else {

			// Show label (required fields)
			$chaineTemp .= $this -> isRequired ($isRequired, $label, $labelFor, 'red', $labelInputGroup);

			// Show the form element
			if ($labelInputGroup != '') {
				if (substr($labelInputGroup, 0 ,1) == '0')
					$chaineTemp .= '<span class="input-group-addon">' . substr($labelInputGroup, 1) . '</span>';				
				else
					$chaineTemp .= '<span class="input-group-addon"><i class="fa fa-' . $labelInputGroup . '"></i></span>';
			}

			$chaineTemp .= '<input class="form-control" type="'.$elem.'" ';

			// BNAME Obligatoire pour le script de validation
			// Meme si le champs n'est pas obligatoire ou
			// Avec un formatage particulier
			$chaineTemp .= 'bname="' . $label . '" ';

			foreach ($this -> formElementArr[$cpt][$elem] as $clef => $val) {
				if ($clef == 'max_file_size')
					$fileSize = $val;
				if ($clef == 'icon2')
					$labelInputGroup2 = $val;
				if ($clef == 'value')
					$photo = $val;
				if ($clef == 'id')
					$id = $val;
				if ($clef == 'custom')
					$transfert = $val;
				if ($clef == 'dir')
					$dir = $val;
				if ($clef == 'extension')
					$exts = $val;
				
				$chaineTemp .= $clef.'="'.$val.'" ';
			}

			$chaineRequired = ($isRequired == true) ? 'required="true" ' : '';
			$chaineTemp    .= $chaineRequired;
			$transfertFunction = ($transfert != '') ? $transfert : '';

			if ($onclickFunction && $id) {
				if ($exts) {
					$chaineTemp .= " onclick=\"sbOpenPopup('$id', '$exts', '$transfertFunction')\"";
				} else {
					$chaineTemp .= " onclick=\"sbOpenPopup('$id', '', '$transfertFunction')\"";
				}
			}
			
			$chaineTemp .= '/>';
			
			// Si deuxieme icon (icon2)
			if ($labelInputGroup2 != '')
				$chaineTemp .= '<span class="input-group-addon">' . $labelInputGroup2 . '</span>';
			
			$chaineTemp .= '</div>';
			
			$media_dir = (isset($dir) && $dir != '') ? $dir : _AM_MEDIAS_DIR;
			
			// Si icon1 present et si icon = photo
			if ($labelInputGroup === 'photo') {				
				$media_class = ($photo || $photo != "") ? 'transfert-media-img-pictures-without' : 'transfert-media-img-pictures';
				$media_photo = '<div id="' . $id . 'Thumb" class="' . $media_class . ' icon-transfert">';
				if ($photo || $photo != "") {
					if (file_exists($media_dir.DIRECTORY_SEPARATOR.$photo)) {
						$media_photo .= '<img class="transfert-media-img" src="'.$media_dir.DIRECTORY_SEPARATOR.$photo.'" alt="'.$photo.'" title="'.$photo.'" />';
					} else {
						$media_photo .= '<img class="transfert-media-img-remove" src="img/broken-image-100.svg" alt="'.$photo.'" title="'.$photo.'" />';
					}
				}
				$media_photo .= '</div>';				
				
				
				// --- Affichage photo
				$chaineTemp  .= '<label for="showPhoto" class="control-label"></label>'.$media_photo.'<p></p>';
			}
			
			// If help
			if ($helpDsc != '')
				$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
				
		}

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	
	/**
	* Construct form (body)
	* add a form element (input radio)
	* @return html code
	*/
	public function addRadio ($label = '', $tabRadio, $arrArgs = array (), $isRequired = false, $separator = '&nbsp;', $helpDsc = '') {
		$elem = 'radio';
		if (!array_key_exists ($elem, $this -> inputArr)) {
			throw new Exception ($elem . ' n\'est pas un élément valide');
		}

		if (!array_key_exists ('name', $arrArgs) && $elem !== 'submit' && $elem !== 'reset') {
			$arrArgs['name'] = 'default';
		}

		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt][$elem] = array ();
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr, $this -> inputArr[$elem]);

		foreach ($arrTemp as $clef => $val) {
			if (array_key_exists ($clef, $arrArgs)) {
				$this -> formElementArr[$cpt][$elem][$clef] = $arrArgs[$clef];
			}
		}

		// Show the label for the element ()
		$chaineTemp .= $this -> isRequired ($isRequired, $label);

		// Show the form element
		for ($i = 0; $i < count($tabRadio); $i++) {
			$chaineTemp .= '<input type="'.$elem.'" ';

			$chaineTemp .= 'value="'.$tabRadio[$i]['value'].'" ';

			foreach ($this -> formElementArr[$cpt][$elem] as $clef => $val) {
				if ($clef == 'checked')
					$chaineValue = $val;
				else
					$chaineTemp .= $clef.'="'.$val.'" ';
			}

			if ($tabRadio[$i]['value'] == $chaineValue)
				$chaineTemp .= 'checked ';

			if ($isRequired == true && $i == count($tabRadio)-1)
				$chaineTemp .= ' required="true" bname="' . $label . '" ';

			$chaineTemp .= '/>&nbsp;&nbsp;';
			$chaineTemp .= $tabRadio[$i]['text'].'&nbsp;';
			$chaineTemp .= $separator;
		}
		
		$chaineTemp .= '</div>';
		
		// If help
		if ($helpDsc != '')
			$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
		else
			$chaineTemp .= '<p></p>';

		// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		// To debug view (show the entries)    -=
		// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		for ($i = 0; $i < count($tabRadio); $i++) {
			$this -> formElementArr[$cpt][$elem]['value ('.$i.')'] = $tabRadio[$i]['value'];
		}
		// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}


	/**
	* Construct form (body)
	* add a form element (input radio)
	* @return html code
	*/
	public function addRadioYN ($label = '', $isRequired = false, $arrArgs = array (), $yes = 'OUI', $no = 'NON', $helpDsc = '', $separator = '&nbsp;&nbsp;&nbsp;') {
		$elem = 'radio';
		if (!array_key_exists ($elem, $this -> inputArr)) {
			throw new Exception ($elem . ' n\'est pas un élément valide');
		}

		if (!array_key_exists ('name', $arrArgs) && $elem !== 'submit' && $elem !== 'reset') {
			$arrArgs['name'] = 'default';
		}

		$cpt = count ($this ->formElementArr);
		$this -> formElementArr[$cpt][$elem] = array ();
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr, $this -> inputArr[$elem]);

		foreach ($arrTemp as $clef => $val) {
			if (array_key_exists ($clef, $arrArgs)) {
				$this -> formElementArr[$cpt][$elem][$clef] = $arrArgs[$clef];
			}
		}

		// Show the label for the element
		//$chaineTemp  = $this -> formBuffer['open_td1'];
		$chaineTemp .= $this -> isRequired ($isRequired, $label);

		// Show the form element radio YES
		// if checked => value="1"
		//$chaineTemp .= $this -> formBuffer['open_td2'];
		$chaineTemp .= '<input type="'.$elem.'" ';

		foreach ($this -> formElementArr[$cpt][$elem] as $clef => $val) {
			if ($clef === 'checked' && $val == 1) {
				$chaineTemp .= 'checked="checked" ';
				
			} elseif ($clef !== 'checked') {
				$chaineTemp .= $clef.'="'.$val.'" ';
			}
			$chaineTemp .= 'value="1" ';
		}

		$chaineRequired = ($isRequired == true) ? ' required="true" bname="' . $label . '" ' : '';
		$chaineTemp    .= $chaineRequired;

		$chaineTemp .= '/>&nbsp;'.$yes;

		// Show the form element radio NO
		// if checked => value="0"
		$chaineTemp .= $separator;
		$chaineTemp .= '<input type="'.$elem.'" ';

		foreach ($this -> formElementArr[$cpt][$elem] as $clef => $val) {
			if ($clef === 'checked' && $val === '0')
				$chaineTemp .= 'checked="checked" ';
			elseif ($clef !== 'checked')
				$chaineTemp .= $clef.'="'.$val.'" ';
		}
		$chaineTemp .= 'value="0" ';

		$chaineTemp .= '/>&nbsp;'.$no;

		$chaineTemp .= '</div>';
		
		// If help
		if ($helpDsc != '')
			$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
		else
			$chaineTemp .= '<p></p>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}

	
	/**
	* Construct form (body)
	* add a form element (checkbox)
	* @return html code
	*/
	public function addCheckbox ($label = '', $tabCheck, $arrArgs = array (), $isRequired = false, $separator = '&nbsp;', $helpDsc = '') {
		$elem = 'checkbox';
		if (!array_key_exists ($elem, $this -> inputArr)) {
			throw new Exception ($elem . ' ' . UIADMIN_SYS_DEBUG_FORM_ERROR_INPUT);
		}

		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt][$elem] = array ();
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr);

		// Show the label for the element ()
		$chaineTemp .= $this -> isRequired ($isRequired, $label);

		// Show the tab form elements
		for ($i = 0; $i < count($tabCheck); $i++) {
			$chaineTemp .= '<input type="'.$elem.'" ';
			$chaineTemp .= 'name="'.$tabCheck[$i]['name'].'" ';

			foreach ($this -> formElementArr[$cpt][$elem] as $clef => $val) {
				$chaineTemp .= $clef.'="'.$val.'" ';
			}
			
			if ($tabCheck[$i]['value'])
				$chaineTemp .= 'value="' .$tabCheck[$i]['value'].'" ';

			if ($i == count($tabCheck)-1 && $isRequired == true)
				$chaineTemp .= 'required="true" bname="' . $label . '"';

			if ($tabCheck[$i]['checked'] && $tabCheck[$i]['checked'] == '1')
				$chaineTemp .= 'checked="checked" ';

			$chaineTemp .= '/>&nbsp;&nbsp;';
			$chaineTemp .= $tabCheck[$i]['text'].'&nbsp;';
			$chaineTemp .= $separator;
			//$this -> formElementArr[$cpt][$elem]['name ('.($i+1).')'] = $tabCheck[$i]['name'];
			//$this -> formElementArr[$cpt][$elem]['text ('.($i+1).')'] = $tabCheck[$i]['text'];
		}

		$chaineTemp .= '</div>';
		
		// If help
		if ($helpDsc != '')
			$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
		else
			$chaineTemp .= '<p></p>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	
	/**
	* Construct form (body)
	* add a form element (textarea)
	* @return html code
	*/
	public function addTextarea ($label = '', $txt, $arrArgs = array (), $isRequired = false, $helpDsc = '') {
		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt]['textarea']['innerHTML'] = $txt;
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr, $this -> textareaArr);

		foreach ($arrTemp as $clef => $val) {
			if (array_key_exists ($clef, $arrArgs)) {
				$this -> formElementArr[$cpt]['textarea'][$clef] = $arrArgs[$clef];
			}
		}

		// Show the label for the element
		$chaineTemp .= $this -> isRequired ($isRequired, $label);

		// Show the form element
		$chaineTemp    .= '<textarea ';

		foreach ($this -> formElementArr[$cpt]['textarea'] as $clef => $val) {
			if ($clef !== 'innerHTML') {
				if ($clef == 'valid')
					$valid = ' '.$val;
				else
					$chaineTemp .= $clef.'="'.$val.'" ';
			}
		}

		$chaineRequired = ($isRequired == true) ? ' required="true" bname="' . $label . '" ' : '';
		$chaineTemp    .= $chaineRequired;
		
		$chaineValid    = ($valid != '') ? $valid : '';
		$chaineTemp    .= 'class="form-control'.$chaineRequired.$chaineValid.'"';

		$chaineTemp .= '>'.$txt.'</textarea>';

		$chaineTemp .= '</div>';
		
		// If help
		if ($helpDsc != '')
			$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
		else
			$chaineTemp .= '<p></p>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	
	/**
	* Construct form (body)
	* add a form element (sortable)
	* @return html code
	*/
	public function addSortable ($options_sort, $helpDsc = '', $extra = false, $lang = 'fr', $encode = "UTF-8") {		
		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt]['sortable'] = $options_sort;
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr, $this -> optionArr);

		// Show the label for the element
		$chaineTemp .= '<div class="form-group">';
		
		// If help
		if ($helpDsc != '')
			$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
		else
			$chaineTemp .= '<p></p>';
		
		if ($extra)
			$chaineTemp .= '<ul id="sortable-grid">';
		else
			$chaineTemp .= '<ul id="sortable">';
		
		foreach ($this -> formElementArr[$cpt]['sortable'] as $clef => $val) {
			if ($extra)
				$chaineTemp .= '<li id="drag_' . $clef . '"  class="ui-state-default sortable-extra" > ' . $this->displayLang($val, $lang, $encode);
			else
				$chaineTemp .= '<li id="drag_' . $clef . '"  class="ui-state-default" ><i class="fa fa-sort sb-sort-i"></i> ' . $this->displayLang($val, $lang, $encode);

			$chaineTemp .= '<input type="hidden" value="' . $clef . '" name="drag[]">';
			$chaineTemp .= '</li>';
		}

		$chaineTemp .= '</ul>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;

		$chaineTemp .= '</div>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	
	/**
	* Construct form (body)
	* add a form element (select)
	* @return html code
	*/
	public function openSelect ($label = '', $arrArgs = array (), $isRequired = false) {
		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt]['select'] = array ();
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr, $this -> selectArr);

		foreach ($arrTemp as $clef => $val) {
			if (array_key_exists ($clef, $arrArgs)) {
				$this -> formElementArr[$cpt]['select'][$clef] = $arrArgs[$clef];
			}
		}

		// Show the label for the element
		$chaineTemp .= $this -> isRequired ($isRequired, $label);

		// Show the form element
		$chaineTemp .= '<select ';

		foreach ($this -> formElementArr[$cpt]['select'] as $clef => $val) {
			if ($clef == 'class')
				$chaineClasses = $val;
			else 
				$chaineTemp .= $clef.'="'.$val.'" ';
		}
		
		// Classes
		$chaineTemp .= ($chaineClasses != '') ? ' class="form-control ' . $chaineClasses . '"' : ' class="form-control"';

		$chaineTemp .= ($isRequired == true) ? ' required="true" bname="' . $label . '"' : '';

		$chaineTemp .= '>';
		
		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}


	/**
	* Construct form (body)
	* add a form element (close select)
	* @return html code
	*/
	public function closeSelect ($helpDsc = '') {
		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt]['/select'] = array ();
		$chaineTemp = '</select>';

		$chaineTemp .= '</div>';
		
		// If help
		if ($helpDsc != '')
			$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
		else
			$chaineTemp .= '<p></p>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}


	/**
	* Construct form (body)
	* add a option to a form element (select)
	* @return html code
	*/
	public function addOption ($txt, $arrArgs = array ()) {
		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt]['option']['innerHTML'] = $txt;
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr, $this -> optionArr);

		foreach ($arrTemp as $clef => $val) {
			if (array_key_exists ($clef, $arrArgs)) {
				$this -> formElementArr[$cpt]['option'][$clef] = $arrArgs[$clef];
			}
		}

		$chaineTemp = '<option ';

		foreach ($this -> formElementArr[$cpt]['option'] as $clef => $val) {
			if ($clef !== 'innerHTML') {
				$chaineTemp .= $clef.'="'.$val.'" ';
			}
		}

		$chaineTemp .= '>'.$txt.'</option>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	
	/**
	* Construct form (body)
	* add a form element (input date select)
	* @return html code
	*/
	public function addDate ($label = '', $arrArgs = array (), $isRequired = false, $localization = 'fr', $helpDsc = '') {
		$elem = 'text';
		if (!array_key_exists ($elem, $this -> inputArr)) {
			throw new Exception ($elem . ' n\'est pas un élément valide');
		}

		if (!array_key_exists ('name', $arrArgs) && $elem !== 'submit' && $elem !== 'reset') {
			$arrArgs['name'] = 'default';
		}

		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt][$elem] = array ();
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr, $this -> inputArr[$elem]);

		foreach ($arrTemp as $clef => $val) {
			if (array_key_exists ($clef, $arrArgs)) {
				$this -> formElementArr[$cpt][$elem][$clef] = $arrArgs[$clef];
			}
		}
		
		// Show the label for the element
		$chaineTemp .= $this -> isRequired ($isRequired, $label, '', 'red', 'calendar');

		// Show the form element
		$chaineTemp .= '<span class="input-group-addon group-calendar"><i class="fa fa-calendar"></i></span>';
		$chaineTemp .= '<input type="'.$elem.'" ';

		foreach ($this -> formElementArr[$cpt][$elem] as $clef => $val) {
			if ($clef === 'id')
				$dateId = $val;
			$chaineTemp .= $clef.'="'.$val.'" ';
		}

		if ($dateId != '') {
			$datepickerId = $dateId;
		} else {
			$dateDefault  = 'calendar1';
			$chaineTemp  .= 'id="'.$dateDefault.'" ';
			$datepickerId = $dateDefault;
		}

		$chaineRequired = ($isRequired == true) ? ' required="true" bname="' . $label . '" ' : '';
		$chaineTemp    .= $chaineRequired;

		$chaineTemp .= '/>&nbsp;';
		$chaineTemp .= '<script type="text/javascript">';
		// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		// Possible options for the calendar date select -=
		// -----------------------------------------------=
		// minDate: -20, maxDate: '+1M +10D'             -=
		// numberOfMonths: 3                             -=
		// showButtonPanel: true                         -=
		// showOn: 'button',                             -=
		// ...buttonImage: 'images/calendar.gif',        -=
		// ...buttonImageOnly: true                      -=
		// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$chaineTemp .= '$(function() {
							$("#'.$datepickerId.'").datepicker({
								showWeek: true,
								firstDay: 1,
								changeMonth: true,
								changeYear: true,
							});	
						});';
		$chaineTemp .= '</script>';

		//if ($localization != 'en')
		//	$chaineTemp .= '<script type="text/javascript" src="inc/js/jquery/ui/i18n/ui.datepicker-'.$localization.'.js"></script>';

		$chaineTemp .= '</div>';
		
		// If help
		if ($helpDsc != '')
			$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
		else
			$chaineTemp .= '<p></p>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	
	/**
	* Construct form (body)
	* add a form element (textarea with editor wysiwyg)
	* @return html code (editor HTMLBOX - Remiya)
	*/
	public function addTextareaHtml ($label = '', $txt, $arrArgs = array (), $isRequired = false, $toolbar = 'full', $width = '100%', $height = '200px', $helpDsc = '') {
		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt]['textarea']['innerHTML'] = $txt;
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr, $this -> textareaArr);

		foreach ($arrTemp as $clef => $val) {
			if (array_key_exists ($clef, $arrArgs)) {
				$this -> formElementArr[$cpt]['textarea'][$clef] = $arrArgs[$clef];
			}
		}
										
		// Show the label for the element
		$chaineTemp .= $this -> isRequired ($isRequired, $label);
		
		// Show the form element
		$chaineTemp .= '<textarea ';

		foreach ($this -> formElementArr[$cpt]['textarea'] as $clef => $val) {
			if ($clef !== 'innerHTML') {
				if ($clef == 'id')
					$nameEditor = $val;
				$chaineTemp .= $clef.'="'.$val.'" ';
			}
		}

		if (!isset($nameEditor)) {
			$validNameEditor = 'HTMLdefault'.time();
			$chaineTemp     .= ' id="' . $validNameEditor . '"';
		} else {
			$validNameEditor = $nameEditor;
		}

		if ($isRequired == true)
			$chaineTemp .= ' required="true" bname="' . $label . '"';
		
		$chaineTemp .= '>'.$txt.'</textarea>';
		
		$chaineTemp .= '<script language="Javascript" type="text/javascript">
						$(document).ready(function() {
					   ';

		switch($toolbar) {
			default: // full toolbar
			$chaineTemp .= "CKEDITOR.replace( '$validNameEditor', {
							filebrowserBrowseUrl: 'index.php?p=transfert&editor=ck&type=Files'
							});
							CKEDITOR.config.allowedContent = true;
						   ";
			break;

			case 'basic': // basic toolbar
			$chaineTemp .= "CKEDITOR.replace( '$validNameEditor', {
							filebrowserBrowseUrl: 'index.php?p=transfert&editor=ck&type=Files',
							toolbarGroups: [
								{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
								{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
								{ name: 'links', groups: [ 'links' ] },
								{ name: 'insert', groups: [ 'insert' ] },
								{ name: 'forms', groups: [ 'forms' ] },
								{ name: 'tools', groups: [ 'tools' ] },
								{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
								{ name: 'others', groups: [ 'others' ] },
								{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
								{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
								{ name: 'styles', groups: [ 'styles' ] },
								{ name: 'colors', groups: [ 'colors' ] },
								{ name: 'about', groups: [ 'about' ] }
							],
							removeButtons: 'Underline,Subscript,Superscript,Styles,Format,About,Scayt,Anchor,Table,SpecialChar,Maximize,Cut,Copy,Outdent,Indent,Blockquote'
							});
							CKEDITOR.config.allowedContent = true;
						   ";
			break;

			case 'simple': // simple toolbar
			$chaineTemp .= "CKEDITOR.replace( '$validNameEditor', {
							filebrowserBrowseUrl: 'index.php?p=transfert&editor=ck&type=Files',
							toolbarGroups: [
								{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
								{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
								{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
								{ name: 'insert', groups: [ 'insert' ] },
								{ name: 'links', groups: [ 'links' ] },
								{ name: 'forms', groups: [ 'forms' ] },
								{ name: 'tools', groups: [ 'tools' ] },
								{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
								{ name: 'others', groups: [ 'others' ] },
								{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
								{ name: 'styles', groups: [ 'styles' ] },
								{ name: 'colors', groups: [ 'colors' ] },
								{ name: 'about', groups: [ 'about' ] }
							],
							removeButtons: 'Underline,Subscript,Superscript,Styles,Format,About,Scayt,Anchor,Table,SpecialChar,Maximize,Cut,Copy,Outdent,Indent,Blockquote,Undo,Redo,Paste,PasteText,PasteFromWord,NumberedList,BulletedList'
							});
							CKEDITOR.config.allowedContent = true;
						   ";
			break;
		
			case 'custom': // Custome toolbar by developer
				require(SBMAGIC_PATH . '/inc/admin/ckeditor.php');
			break;
		}
		
		$chaineTemp .= '});
						</script>';
						
		$chaineTemp .= '</div>';
		
		// If help
		if ($helpDsc != '')
			$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
		else
			$chaineTemp .= '<p></p>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	
	/**
	* Construct form (body)
	* add a form element (input)
	* @return html code
	*/
	public function addColor ($label = '', $arrArgs = array (), $isRequired = false, $helpDsc = '') {
		$elem = 'text';
		if (!array_key_exists ($elem, $this -> inputArr)) {
			throw new Exception ($elem . ' n\'est pas un élément valide');
		}

		if (!array_key_exists ('name', $arrArgs) && $elem !== 'submit' && $elem !== 'reset') {
			$arrArgs['name'] = 'default';
		}

		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt][$elem] = array ();
		$arrTemp = array_merge ($this -> eventArr, $this -> commonArr, $this -> inputArr[$elem]);

		foreach ($arrTemp as $clef => $val) {
			if (array_key_exists ($clef, $arrArgs)) {
				$this -> formElementArr[$cpt][$elem][$clef] = $arrArgs[$clef];
			}
		}

		// Show the label for the element
		$chaineTemp .= $this -> isRequired ($isRequired, $label);

		// Show the form element
		$chaineTemp .= '<input type="'.$elem.'" ';

		foreach ($this -> formElementArr[$cpt][$elem] as $clef => $val) {
			if ($clef == 'id')
				$nameColorP = $val;
			$chaineTemp .= $clef.'="'.$val.'" ';
		}
		
		if (!isset($nameColorP)) {
			$validNameColorP = 'Color' . time();
			$chaineTemp     .= 'id = "' . $validNameColorP . '" ';
		} else {
			$validNameColorP = $nameColorP;
		}

		$chaineRequired = ($isRequired == true) ? ' required="true" bname="' . $label . '" ' : '';
		$chaineTemp    .= $chaineRequired;
		//$chaineTemp .= ($isRequired == true) ? 'class="required"' : '';

		$chaineTemp .= '/>';
		
		$chaineTemp .= '<script type="text/javascript">
				$(\'#' . $validNameColorP . '\').ColorPicker({
					onSubmit: function(hsb, hex, rgb) {
						$(\'#' . $validNameColorP . '\').val(\'#\' + hex);
					},
					onBeforeShow: function () {
						$(this).ColorPickerSetColor(this.value);
					},
					onHide: function (colpkr) {
						$(colpkr).fadeOut(500);
						return false;
					},
					onShow: function (colpkr) {
						$(colpkr).fadeIn(500);
						return false;
					}
				})
				.bind(\'keyup\', function(){
					$(this).ColorPickerSetColor(this.value);
					});
				</script>';

		$chaineTemp .= '</div>';
		
		// If help
		if ($helpDsc != '')
			$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
		else
			$chaineTemp .= '<p></p>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}

	
	
	/**
	* Construct form (body)
	* add a break to the form (line with title)
	* @return html code (string)
	*/
	public function addBreak ($title, $align = 'left') {
		$cpt = count ($this -> formElementArr);
		$chaineTemp .= '<div class="well form-break-info">';
		$chaineTemp .= '<h4>' . $title . '</h4>';
		$chaineTemp .= '</div>';
		$this -> formBuffer['anything'][$cpt] = $chaineTemp;
	}


	/**
	* Construct form (body)
	* add a * for form fields required
	* @return html code (string)
	*/
	public function isRequired ($fieldRequired, $label, $labelFor = '', $colorRequired = 'red', $labelInputGroup = '') {
		
		if ($labelInputGroup != '') {
			
			if ($fieldRequired === true) {
				$chaineTemp .= '<label for="' . $labelFor . '" class="form_required">';
				$chaineTemp .= $label . '&nbsp;<span style="color: ' . $colorRequired . '">*</span>';
			} else {
				$chaineTemp .= '<label for="' . $labelFor . '">';
				$chaineTemp .= $label;
			}
	
			$chaineTemp .= '</label><br>';

			$chaineTemp .= '<div class="form-group input-group input-group-icon">';		
		
		} else {
			
			$chaineTemp .= '<div class="form-group">';
		
			if ($fieldRequired === true) {
				$chaineTemp .= '<label for="' . $labelFor . '" class="form_required">';
				$chaineTemp .= $label . '&nbsp;<span style="color: ' . $colorRequired . '">*</span>';
			} else {
				$chaineTemp .= '<label for="' . $labelFor . '">';
				$chaineTemp .= $label;
			}
	
			$chaineTemp .= '</label><br>';
		}

		return $chaineTemp;
	}
	
	
	/**
	* Construct form (body)
	* show the entire form
	* @return html code
	*/
	public function __toString () {
		$chaineTemp = $this -> formBuffer['open'];
		
		foreach ($this -> formBuffer['elements'] as $clef => $val) {
			if (isset ($this -> formBuffer['anything'][$clef])) {
				$chaineTemp .= $this -> formBuffer['anything'][$clef];
			}
			$chaineTemp .= $val;
		}
		$chaineTemp .= $this -> formBuffer['close'];
		
		return $chaineTemp;
	}
	
	/**
	* Construct form (method)
	* free up resources and create a new form
	* form any previously created and not displayed will be lost
	* @return empty array
	*/
	public function freeForm () {
		$this -> formBuffer       = array ();
		$this -> formElementArr   = array ();
		$this -> formAttributeArr = array ();
	}


	/**
	* Construct form (method)
	* Destructor
	* @return bool false
	*/
	public function __destruct () {
		unset ($this);
	}
	
	
	// ouvrir un fieldset
	public function openFieldset ($arrArgs = array ()) {
	  $cpt = count ($this -> formElementArr);
	  $this -> formElementArr[$cpt]['fieldset'] = array ();
	  $arrTemp = array_merge ($this -> eventArr, $this -> commonArr,
							  $this -> fieldsetArr);
	  foreach ($arrTemp as $clef => $val) {
		if (array_key_exists ($clef, $arrArgs)) {
		  $this -> formElementArr[$cpt]['fieldset'][$clef] = $arrArgs[$clef];
		}
	  }
	  $chaineTemp = '<fieldset ';
	  foreach ($this -> formElementArr[$cpt]['fieldset'] as $clef => $val) {
		$chaineTemp .= $clef.'="'.$val.'" ';
	  }
	  $chaineTemp .= '>';
	  $this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	// fermer un fieldset
	public function closeFieldset () {
		$cpt = count ($this -> formElementArr);
		$this -> formElementArr[$cpt]['/fieldset'] = array ();
		$chaineTemp = '</fieldset>';

		$chaineTemp .= '</div>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	// ajouter une légende
	public function addLegend ($legend, $arrArgs = array ()) {
	  $cpt = count ($this -> formElementArr);
	  $this -> formElementArr[$cpt]['legend']['innerHTML'] = $legend;
	  $arrTemp = array_merge ($this -> eventArr, $this -> commonArr,
							  $this -> legendArr);
	  foreach ($arrTemp as $clef => $val) {
		if (array_key_exists ($clef, $arrArgs)) {
		  $this -> formElementArr[$cpt]['legend'][$clef] = $arrArgs[$clef];
		}
	  }
	  $chaineTemp = '<legend ';
	  foreach ($this -> formElementArr[$cpt]['legend'] as $clef => $val) {
	   if ($clef !== 'innerHTML') {
		  $chaineTemp .= $clef.'="'.$val.'" ';
	  }
	  }
	  $chaineTemp .= '>'.$legend.'</legend>';
	  $this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	 // ouvrir une balise p
	 public function openP ($arrArgs = array ()) {
	   $cpt = count ($this -> formElementArr);
	   $this -> formElementArr[$cpt]['p'] = array ();
	   $arrTemp = array_merge ($this -> eventArr, $this -> commonArr,
							   $this -> pArr);
	   foreach ($arrTemp as $clef => $val) {
		 if (array_key_exists ($clef, $arrArgs)) {
		   $this -> formElementArr[$cpt]['p'][$clef] = $arrArgs[$clef];
		 }
	   }
	   $chaineTemp = '<p ';
	   foreach ($this -> formElementArr[$cpt]['p'] as $clef => $val) {
		 $chaineTemp .= $clef.'="'.$val.'" ';
	   }
	   $chaineTemp .= '>';
	   $this -> formBuffer['elements'][$cpt] = $chaineTemp;
	 }
	
	 // fermer une balise p
	 public function closeP () {
	   $cpt = count ($this -> formElementArr);
	   $this -> formElementArr[$cpt]['/p'] = array ();
	   $chaineTemp = '</p>';
	   $this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	// ajouter un label
	public function addLabel ($label, $arrArgs = array ()) {
	  $cpt = count ($this -> formElementArr);
	 $this -> formElementArr[$cpt]['label']['innerHTML'] = $label;
	  $arrTemp = array_merge ($this -> eventArr, $this -> commonArr,
							  $this -> labelArr);
	  foreach ($arrTemp as $clef => $val) {
		if (array_key_exists ($clef, $arrArgs)) {
		  $this -> formElementArr[$cpt]['label'][$clef] = $arrArgs[$clef];
		}
	  }
	  $chaineTemp = '<label ';
	  foreach ($this -> formElementArr[$cpt]['label'] as $clef => $val) {
	   if ($clef !== 'innerHTML') {
		  $chaineTemp .= $clef.'="'.$val.'" ';
	  }
	  }
	  $chaineTemp .= '>'.$label.'</label>';
	  $this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}

		
	// ouvrir un optgroup
	public function openOptgroup ($label, $arrArgs = array ()) {
	  $cpt = count ($this -> formElementArr);
	  $this -> formElementArr[$cpt]['optgroup']['label'] = $label;
	  $arrTemp = array_merge ($this -> eventArr, $this -> commonArr,
							  $this -> optgroupArr);
	  foreach ($arrTemp as $clef => $val) {
		if (array_key_exists ($clef, $arrArgs)) {
		  $this -> formElementArr[$cpt]['select'][$clef] = $arrArgs[$clef];
		}
	  }
	  $chaineTemp = '<optgroup ';
	  foreach ($this -> formElementArr[$cpt]['optgroup'] as $clef => $val) {
		$chaineTemp .= $clef.'="'.$val.'" ';
	  }
	  $chaineTemp .= '>';
	  $this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	// fermer un optgroup
	public function closeOptgroup () {
	  $cpt = count ($this -> formElementArr);
	  $this -> formElementArr[$cpt]['/optgroup'] = array ();
	  $chaineTemp = '</optgroup>';
	  $this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	// ajouter n'importe quoi
	public function addAnything ($any) {
	  $cpt = count ($this -> formElementArr);
	  $this -> formBuffer['anything'][$cpt] = $any;
	}
	
	

	
	/***************************
	 ***METHODS FOR DEBUGGING***
	 ***************************/
	
	/**
	* Construct form (DEBUGGING METHOD)
	* Show all elements in a form
	* @return element array
	*/
	public function showElems () {
	  $chaineTemp = '';
		foreach ($this -> formElementArr as $clef => $val) {
			foreach ($val as $elem => $attrArr) {
				if (strpos ($elem, '/') !== false) {
					$chaineTemp .= '<ul><li style="color: blue;">end '.substr ($elem, 1, strlen ($elem)).'</li></ul>';
				} else {
					$chaineTemp .= '<ul><li style="color: blue;">'.$elem.'</li><ul>';
					foreach ($attrArr as $attr => $value) {
						$chaineTemp .= '<li style="color: red;">'.$attr.' = <span style="color: green; font-style: italic;">'.$value.'</span></li>';
					}
					$chaineTemp .= '</ul></ul>';
				}
			}
		}
		return $chaineTemp;
	}
	

	/**
	* Construct form (DEBUGGING METHOD)
	* Method with the elements contained in the form (count)
	* Detail : The overall total, and total per item
	* @return element array
	*/
	public function countElems () {
		foreach ($this -> formElementArr as $clef => $val) {
			foreach ($val as $elem => $attrArr) {
				if (strpos ($elem, '/') === false) {
					$arrTemp[] = $elem;
				}
			}
		}

		$cptElem = count ($arrTemp);
		$arrEachElem = array_count_values ($arrTemp);
		$chaineTemp = '<span style="color: black; font-weight: bold;">Total Eléments <span style="color: red;">'.$cptElem.'</span><br />dont : </span><br />';
		ksort ($arrEachElem, SORT_STRING);
		foreach ($arrEachElem as $elem => $nbr) {
			$chaineTemp .= '<span style="color: blue; margin-left: 20px;">'.$elem.' : </span><span style="color: red;">'.$nbr.'</span><br />';
		}

		return $chaineTemp;
	}
	

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

} // End class form