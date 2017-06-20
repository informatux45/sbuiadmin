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
											   ,'into' => ''
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
							   ,'rel' => ''
							   );
	private $optgroupArr      = array ('label' => ''
									  ,'disabled' => ''
									  );
	private $formBuffer       = array ();
	private $formElementArr   = array ();
	private $formAttributeArr = array ();
	
	/**
	* Class form's operations
	* Constructor
	*/
	public function __construct () {
	
	}
	
	/**
	* Construct form (header)
	* @return html code
	*/
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
				if ($clef == 'addany')
					$addany = true;
				
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
			
			// Check in DIV into
			if ($addany)
				$chaineTemp .= '<div style="width: 200px; height: 200px; border: 1px solid red;">';

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
				
			// Check if DIV into
			if ($addany)
				$chaineTemp .= '</div>';
				
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
	* add a form element (country select)
	* @return html code
	*/
	public function addCountry ($label = '', $arrArgs = array (), $isRequired = false, $helpDsc = '') {
		$chaineTemp .= $this -> openSelect ($label, $arrArgs, $isRequired, $helpDsc);

		// Extract all the countries (options html)
		$query   = "SELECT * FROM " . _AM_DB_PREFIX . "sb_country ORDER BY country_printable_name ASC";
		$request = $this->query($query);
		$result  = $this->toarray($request);
		$chaineTemp .= $this -> addOption ('Choisissez un pays', array('value'=>''));
		if ($result) {
			foreach ($result as $row) {
				$country_name  = $row['country_printable_name'];
				$country_value = ($row['country_iso3']) ? $row['country_iso3'] : $row['country_iso'];
				if ($arrArgs['value'] && $arrArgs['value'] == $country_value)
					$chaineTemp .= $this -> addOption ($country_name, array('value'=>$country_value, 'selected'=>''));
				else
					$chaineTemp .= $this -> addOption ($country_name, array('value'=>$country_value));
			}
		} else {
			$chaineTemp .= $this -> addOption ('Pas de pays disponible', array('value'=>''));	
		}

		$chaineTemp .= $this -> closeSelect();
		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	
	/**
	* Construct form (body)
	* add a form element (textarea with editor wysiwyg)
	* @return html code (editor CKEditor)
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

		if (SBMAGIC_CKEDITOR_BEHAVIOR) $toolbar = 'custom';
					   
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
		
			case 'custom': // Custom toolbar by developer
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
	* add a form element (Html Editor - Page Builder)
	* @return Page Builder
	*/
	public function addPageBuilder ($label = '', $src = '', $model = '', $arrArgs = array (), $isRequired = false, $toolbar = 'full', $helpDsc = '') {
		// Load CSS
		$chaineTemp .= '<link rel="stylesheet" href="inc/plugins/pagebuilder/css/pagebuilder.css">
						<link rel="stylesheet" href="inc/plugins/pagebuilder/css/colorselector.css">';
		// Load JS
		$chaineTemp .= '<script src="inc/plugins/pagebuilder/js/jquery.ui.touch-punch.min.js"></script>
						<script src="inc/plugins/pagebuilder/js/colorselector.js"></script>
						<!--<script type="text/javascript">
							var path = "";
						</script>-->
						<script src="inc/plugins/pagebuilder/js/pagebuilder.js"></script>';
				
		// Show the label for the element
		$chaineTemp .= $this -> isRequired ($isRequired, $label);
		
		// Navbar
		$chaineTemp .= '<div class="navbar-page-builder navbar-inverse navbar-htmleditor">
							<div class="navbar-header">
								<button data-target="navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button"> <span class="glyphicon-bar"></span> <span class="glyphicon-bar"></span> <span class="glyphicon-bar"></span> </button> <a class="navbar-brand"><i class="fa fa-magic"></i>&nbsp;&nbsp;Page Builder</a> </div>
							<div class="collapse navbar-collapse">
								<ul class="nav" id="menu-htmleditor">
									<li>
										<div class="btn-group" data-toggle="buttons-radio">
											<button type="button" id="edit" class="active btn btn-primary"><i class="glyphicon glyphicon-edit "></i> Edit</button>
											<button type="button" id="save" class="btn btn-warning float-right"><i class="fa fa-save"></i>&nbsp;save</button>
										</div>
									</li>
								</ul>
							</div>
						</div>';
		
		// Page Builder NavBar (Column Elements)
		$chaineTemp .= '<div class="container">
							<div class="row">
								<div class="">';
								
		// Page Builder NavBar (Column Elements)
		$chaineTemp .= '<div id="pagebuilder-sidebar-nav" class="sidebar-nav">
							<div class="lyrow-block">
									<div class="lyrow">';
		$chaineTemp .= $this->addPageBuilderTags('lyrow-button');
		$chaineTemp .= $this->addPageBuilderTags('lyrow-column-12');
		$chaineTemp .= '			</div>';
		$chaineTemp .= '	</div>';
		$chaineTemp .= '	<div class="lyrow-block">';
		$chaineTemp .= '			<div class="lyrow">';
		$chaineTemp .= $this->addPageBuilderTags('lyrow-button');
		$chaineTemp .= $this->addPageBuilderTags('lyrow-column-6-6');
		$chaineTemp .= '			</div>';
		$chaineTemp .= '	</div>';
		$chaineTemp .= '	<div class="lyrow-block">';
		$chaineTemp .= '			<div class="lyrow">';
		$chaineTemp .= $this->addPageBuilderTags('lyrow-button');
		$chaineTemp .= $this->addPageBuilderTags('lyrow-column-8-4');
		$chaineTemp .= '			</div>';
		$chaineTemp .= '	</div>';
		$chaineTemp .= '	<div class="lyrow-block">';
		$chaineTemp .= '			<div class="lyrow">';
		$chaineTemp .= $this->addPageBuilderTags('lyrow-button');
		$chaineTemp .= $this->addPageBuilderTags('lyrow-column-4-8');
		$chaineTemp .= '			</div>';
		$chaineTemp .= '	</div>';
		$chaineTemp .= '	<div class="lyrow-block">';
		$chaineTemp .= '			<div class="lyrow">';
		$chaineTemp .= $this->addPageBuilderTags('lyrow-button');
		$chaineTemp .= $this->addPageBuilderTags('lyrow-column-3-9');
		$chaineTemp .= '			</div>';
		$chaineTemp .= '	</div>';
		$chaineTemp .= '	<div class="lyrow-block">';
		$chaineTemp .= '			<div class="lyrow">';
		$chaineTemp .= $this->addPageBuilderTags('lyrow-button');
		$chaineTemp .= $this->addPageBuilderTags('lyrow-column-9-3');
		$chaineTemp .= '			</div>';
		$chaineTemp .= '	</div>';
		$chaineTemp .= '	<div class="lyrow-block">';
		$chaineTemp .= '	<div class="lyrow">';
		$chaineTemp .= $this->addPageBuilderTags('lyrow-button');
		$chaineTemp .= $this->addPageBuilderTags('lyrow-column-4-4-4');
		$chaineTemp .= '			</div>';
		$chaineTemp .= '	</div>';
		$chaineTemp .= '	<div class="lyrow-block">';
		$chaineTemp .= '			<div class="lyrow">';
		$chaineTemp .= $this->addPageBuilderTags('lyrow-button');
		$chaineTemp .= $this->addPageBuilderTags('lyrow-column-3-3-3-3');
		$chaineTemp .= '			</div>
							</div>';
		// Page Builder NavBar (Box Elements)
		$chaineTemp .= '<ul class="nav nav-list pagebuilder">
							<li class="boxes" id="elmBase">
								<div class="box box-element" data-type="paragraph"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings"  href="#" ><i class="fa fa-gear"></i></a> </span>
									<div class="preview"> <i class="fa fa-font fa-2x"></i>
										<div class="element-desc">Texte</div>
									</div>
									<div class="view">
										<p>Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
									</div>
								</div>
								<div class="box box-element" data-type="image"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings"  href="#" ><i class="fa fa-gear"></i></a> </span>
									<div class="preview"> <i class="fa fa-picture-o fa-2x"></i>
										<div class="element-desc">Image</div>
									</div>
									<div class="view"> <img id="" class="" title="Votre image" src="img/add-image.svg" width="130" height="130"> </div>
								</div>
								<div class="box box-element" data-type="button"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings"  href="#" ><i class="fa fa-gear"></i></a> </span>
									<div class="preview"> <i class="fa  fa-hand-o-up fa-2x"></i>
										<div class="element-desc">Bouton</div>
									</div>
									<div class="view"> <a class="btn btn-default" href="#">Click Me !</a> </div>
								</div>
								<div class="box box-element" data-type="youtube"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings"  href="#" ><i class="fa fa-gear"></i></a> </span>
									<div class="preview"> <i class="fa  fa fa-youtube-play  fa-2x"></i>
										<div class="element-desc">Youtube</div>
									</div>
									<div class="view">
										<iframe class="img-responsive" src="https://www.youtube.com/embed/_pVCS8HbrmI" frameborder="0" allowfullscreen data-url=""></iframe>
									</div>
								</div>
								<!-- Vimeo -->
								<div class="box box-element" data-type="youtube"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings"  href="#" ><i class="fa fa-gear"></i></a> </span>
									<div class="preview"> <i class="fa  fa-vimeo-square fa-2x"></i>
										<div class="element-desc">Vimeo</div>
									</div>
									<div class="view">
										<iframe class="img-responsive" src="https://player.vimeo.com/video/20016963?byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
									</div>
								</div>
								<div class="box box-element" data-type="map"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings"  href="#" ><i class="fa fa-gear"></i></a> </span>
									<div class="preview"> <i class="fa fa-map-marker fa-2x"></i>
										<div class="element-desc">Carte</div>
									</div>
									<div class="view">
										<iframe class="img-responsive" src="http://maps.google.com/maps?q=12.927923,77.627108&z=15&output=embed" frameborder="0" allowfullscreen data-url=""></iframe>
									</div>
								</div>
								<div class="box box-element" data-type="code"> <a href="#close" class="remove btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove"></i></a> <a class="drag btn btn-default btn-xs"><i class="glyphicon glyphicon-move"></i></a> <span class="configuration"> <a class="btn btn-xs btn-warning settings" href="#" ><i class="fa fa-gear"></i></a> </span>
									<div class="preview"> <i class="fa fa-code fa-2x"></i>
										<div class="element-desc">Code</div>
									</div>
									<div class="view"> Put your html code here </div>
								</div>
							</li>
						</ul>
						</div>';
		
		// Page Builder End of Row
		$chaineTemp .= '</div>
						</div>
						<div class="row">
						<div class="htmlpage">' . $model . '</div>
						</div>
						</div>'; // Row
						
		// Page Builder Modal / JS
		$chaineTemp .= '<div class="modal fade" id="download" tabindex="-1" role="dialog" aria-labelledby="download" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title"><i class="fa fa-save"></i>&nbsp;Save as </h4> </div>
									<div class="modal-body" id="sourceCode">
										<textarea id="src" rows="10"></textarea>
										<textarea id="model" rows="10" class="form-control"></textarea>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
										<button type="button" class="btn btn-success" id="srcSave" onclick="alert(\'OK\'); return false;"><i class="fa fa-save"></i>&nbsp;Save</button>
									</div>
								</div>
							</div>
						</div>';
		$chaineTemp .= '<div class="modal fade" id="preferences" tabindex="-1" role="dialog" aria-labelledby="preferences">
							<div class="modal-dialog" role="document">
								<div class="modal-content">';
		$chaineTemp .= $this->addPageBuilderTags('modal-content', $toolbar);
		$chaineTemp .= '		</div>
							</div>';
		$chaineTemp .= '	<div id="download-layout">
								<div class="container"></div>
							</div>
						</div>';
						
		// Page Builder End of Container
		$chaineTemp .= '</div>'; // Container
		
		// If help
		if ($helpDsc != '')
			$chaineTemp .= '<p class="help-block">' . $helpDsc . '</p>';
		else
			$chaineTemp .= '<p></p>';

		$this -> formBuffer['elements'][$cpt] = $chaineTemp;
	}
	
	
	public function addPageBuilderTags($option = false, $toolbar = '') {
		if (!$option) {
			return '';
		} else {
			switch($option) {
				// -----------------------------
				// ---------- BUTTONS ----------
				// -----------------------------
				case "lyrow-button":
					$htmlpagebuilder = '<a href="#close" class="remove btn btn-danger btn-xs">
											<i class="glyphicon-remove glyphicon" title="Remove"></i>
										</a>
										<a class="drag btn btn-default btn-xs">
											<i class="glyphicon glyphicon-move" title="Move"></i>
										</a>
										<a href="#" class="btn btn-info btn-xs clone" title="Clone">
											<i class="fa fa-copy"></i>
										</a>'; 
				break;
			
				// -----------------------------
				// ---------- COLMUNS ----------
				// -----------------------------
				case "lyrow-column-12":
					$htmlpagebuilder = '<div class="preview">
											<input type="text" value="12" class="form-control">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-12 column"></div>
											</div>
										</div>';
				break;
				case "lyrow-column-6-6":
					$htmlpagebuilder = '<div class="preview">
											<input type="text" value="6 6" class="form-control">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-6 column"></div>
												<div class="col-md-6 column"></div>
											</div>
										</div>';
				break;
				case "lyrow-column-8-4":
					$htmlpagebuilder = '<div class="preview">
											<input type="text" value="8 4" class="form-control">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-8 column"></div>
												<div class="col-md-4 column"></div>
											</div>
										</div>';
				break;
				case "lyrow-column-4-8":
					$htmlpagebuilder = '<div class="preview">
											<input type="text" value="4 8" class="form-control">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-4 column"></div>
												<div class="col-md-8 column"></div>
											</div>
										</div>';
				break;
				case "lyrow-column-3-9":
					$htmlpagebuilder = '<div class="preview">
											<input type="text" value="3 9" class="form-control">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-3 column"></div>
												<div class="col-md-9 column"></div>
											</div>
										</div>';
				break;
				case "lyrow-column-9-3":
					$htmlpagebuilder = '<div class="preview">
											<input type="text" value="9 3" class="form-control">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-9 column"></div>
												<div class="col-md-3 column"></div>
											</div>
										</div>';
				break;
				case "lyrow-column-4-4-4":
					$htmlpagebuilder = '<div class="preview">
											<input type="text" value="4 4 4" class="form-control">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-4 column"></div>
												<div class="col-md-4 column"></div>
												<div class="col-md-4 column"></div>
											</div>
										</div>';
				break;
				case "lyrow-column-3-3-3-3":
					$htmlpagebuilder = '<div class="preview">
											<input type="text" value="3 3 3 3" class="form-control">
										</div>
										<div class="view">
											<div class="row clearfix">
												<div class="col-md-3 column"></div>
												<div class="col-md-3 column"></div>
												<div class="col-md-3 column"></div>
												<div class="col-md-3 column"></div>
											</div>
										</div>';
				break;
				
				case "modal-content":
$htmlpagebuilder = <<<EOT
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="preferencesTitle"></h4> </div>
<div class="modal-body" id="preferencesContent">
	<div id="mediagallery" style="overflow: auto; height: 400px; display: none">
		<!--<div id="contenutoimmagini"></div>-->
		<!--<form enctype="multipart/form-data" id="form-id">
			<input name="nomefile" type="file" />
			<input class="button" type="button" value="Upload" /> </form>-->
		<!--<progress value="0"></progress>-->
		<br>
		<script type="text/javascript">
			$(document).ready(function() {
				$('.button').click(function() {
					var formx = document.getElementById('form-id');
					var formData = new FormData(formx);
					$.ajax({
						url: 'media-popup.php?op=newfile',
						type: 'POST',
						xhr: function() {
							var myXhr = $.ajaxSettings.xhr();
							if (myXhr.upload) {
								myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
							}
							return myXhr;
						},
						success: completeHandler,
						error: errorHandler,
						data: formData,
						cache: false,
						contentType: false,
						processData: false
					});

					function completeHandler() {
						loadimages();
					}

					function errorHandler() {
						alert('errore caricamento');
					}

					function progressHandlingFunction(e) {
						if (e.lengthComputable) {
							$('progress').attr({
								value: e.loaded,
								max: e.total
							});
						}
					}
				});
				//loadimages();
			});

			function inserisci(elemento) {
				var link = $(elemento);
				var image = link.data('image');
				$('#img-url').val(image);
				$('#imgContent').children('img').attr('src', image);
				$('#mediagallery').slideUp();
				$('#thepref').slideDown();
			}

			function loadimages() {
				var request = $.ajax({
					url: "media-popup.php?immagini=1",
					method: "POST",
					data: {
						nome: ''
					},
					dataType: "html"
				});
				request.done(function(msg) {
					$("#contenutoimmagini").html(msg);
				});
			}
		</script> <a class="btn btn-info" href="javascript:;" onclick="$('#mediagallery').hide();$('#thepref').show();">Return to image settings</a> </div>
	<div id="thepref">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#Settings" aria-controls="Settings" role="tab" data-toggle="tab">Edition</a></li>
			<li role="presentation"><a href="#CellSettings" aria-controls="profile" role="tab" data-toggle="tab">Cell (paramètres)</a></li>
			<li role="presentation"><a href="#RowSettings" aria-controls="messages" role="tab" data-toggle="tab">Row (paramètres)</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="Settings">
				<div class="panel panel-body">
					<div id="text" style="display: none;">
						<textarea id="html5editor"></textarea>
						<input type="hidden" id="editor_toolbar" name="editor_toolbar" value="$toolbar" />
					</div>
					<div id="image" style="display:none">
						<div class="row">
							<div class="col-md-5">
								<div id="imgContent"> </div> <a class="btn btn-default form-control" href="#" id="gallery"><i class="icon-upload-alt"></i>&nbsp;Browse ...</a> </div>
							<div class="col-md-7">
								<div class="form-group">
									<label for="img-url">Url :</label>
									<input type="text" value="" id="img-url" class="form-control" /> </div>
								<!-- <div class="form-group"> <label for="img-url">Click Url:</label> <input type="text" value="" id="img-clickurl" class="form-control" /> </div> -->
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="img-width">Width :</label>
											<input type="text" value="" id="img-width" class="form-control" /> </div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="img-height">Height :</label>
											<input type="text" value="" id="img-height" class="form-control" /> </div>
									</div>
								</div>
								<div class="form-group">
									<label for="img-title">Title : </label>
									<input type="text" value="" id="img-title" class="form-control" /> </div>
								<div class="form-group">
									<label for="img-rel">Rel :</label>
									<input type="text" value="" id="img-rel" class="form-control" /> </div>
							</div>
						</div>
					</div>
					<!-- fine settaggi immagine -->
					<div id="youtube" style="display:none">
						<div class="row">
							<div class="col-md-12">
								<div id="youtube-video"> </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<form>
									<div class="form-group">
										<label for="video-url">Video id :</label>
										<input type="text" value="" id="video-url" class="form-control" /> </div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="video-width">Width :</label>
												<input type="text" value="" id="video-width" class="form-control" /> </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="video-height">Height :</label>
												<input type="text" value="" id="video-height" class="form-control" /> </div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- fine settagio youtube -->
					<div id="map" style="display:none">
						<div class="row">
							<div class="col-md-12">
								<div id="map-content"> </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<form>
									<div class="form-group">
										<label for="address">Latitude :</label>
										<input type="text" value="" id="latitude" class="form-control" /> </div>
									<div class="form-group">
										<label for="address">Longitude :</label>
										<input type="text" value="" id="longitude" class="form-control" /> </div>
									<div class="form-group">
										<label for="address">Zoom :</label>
										<input type="text" value="" id="zoom" class="form-control" /> </div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="img-width">Width :</label>
												<input type="text" value="" id="map-width" class="form-control" /> </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="img-height">Height :</label>
												<input type="text" value="" id="map-height" class="form-control" /> </div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div id="buttons" style="display:none">
						<div id="buttonContainer"></div>
						<br>
						<div class="form-group">
							<label> Label : </label>
							<input type="text" class="form-control" id="buttonLabel" /> </div>
						<div class="form-group">
							<label> Href : </label>
							<input type="text" class="form-control" id="buttonHref" /> </div> <span class="btn-group btn-group-xs"> <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">Styles <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li class=""><a href="#" class="btnpropa" rel="btn-default">Default</a></li>
							<li class=""><a href="#" class="btnpropa" rel="btn-primary">Primary</a></li>
							<li class=""><a href="#" class="btnpropa" rel="btn-success">Success</a></li>
							<li class=""><a href="#" class="btnpropa" rel="btn-info">Info</a></li>
							<li class=""><a href="#" class="btnpropa" rel="btn-warning">Warning</a></li>
							<li class=""><a href="#" class="btnpropa" rel="btn-danger">Danger</a></li>
							<li class=""><a href="#" class="btnpropa" rel="btn-link">Link</a></li>
						</ul>
						</span> <span class="btn-group btn-group-xs"> <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">Size <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li class=""><a href="#" class="btnpropb" rel="btn-lg">Large</a></li>
							<li class=""><a href="#" class="btnpropb" rel="btn-default">Default</a></li>
							<li class=""><a href="#" class="btnpropb" rel="btn-sm">Small</a></li>
							<li class=""><a href="#" class="btnpropb" rel="btn-xs">Mini</a></li>
						</ul>
						</span> <span class="btn-group btn-group-xs"> <a class="btn btn-xs btn-default btnprop" href="#" rel="btn-block">Block</a> <a class="btn btn-xs btn-default btnprop" href="#" rel="active">Active</a> <a class="btn btn-xs btn-default btnprop" href="#" rel="disabled">Disabled</a> </span>
						<br>
						<br>
						<div class="form-group">
							<label> Custom width / height / font-size / padding top : </label>
							<br> <span class="btn-group"> <input type="text"  id="custombtnwidth" style="width:20%"/> <input type="text"  id="custombtnheight" style="width:20%"/> <input type="text"  id="custombtnfont" style="width:20%"/> <input type="text"  id="custombtnpaddingtop" style="width:20%"/> </span> </div>
						<!-- <div class="form-group"> <label> Align:  </label> <br> <span class="btn-group"> <select id="btnalign"> <option value="center">center</option> <option value="left">left</option> <option value="right">right</option> </select> </span> </div> -->
						<div class="form-group">
							<label>Custom background color :</label>
							<input type="text" class="form-control" id="colbtn" />
							<select id="colorselectorbtn">
								<option value="1" data-value="1" data-color="#A0522D">sienna</option>
								<option value="2" data-value="2" data-color="#CD5C5C">indianred</option>
								<option value="3" data-value="3" data-color="#FF4500">orangered</option>
								<option value="4" data-value="4" data-color="#008B8B">darkcyan</option>
								<option value="5" data-value="5" data-color="#B8860B">darkgoldenrod</option>
								<option value="6" data-value="6" data-color="#32CD32">limegreen</option>
								<option value="7" data-value="7" data-color="#FFD700">gold</option>
								<option value="8" data-value="8" data-color="#48D1CC">mediumturquoise</option>
								<option value="9" data-value="9" data-color="#87CEEB">skyblue</option>
								<option value="10" data-value="10" data-color="#FF69B4">hotpink</option>
								<option value="11" data-value="11" data-color="#87CEFA">lightskyblue</option>
								<option value="12" data-value="12" data-color="#6495ED">cornflowerblue</option>
								<option value="13" data-value="13" data-color="#DC143C">crimson</option>
								<option value="14" data-value="14" data-color="#FF8C00">darkorange</option>
								<option value="15" data-value="15" data-color="#C71585">mediumvioletred</option>
								<option value="16" data-value="16" data-color="#000000">black</option>
								<option value="17" data-value="17" data-color="#575757">grigio scuro</option>
								<option value="18" data-value="18" data-color="#f2f2f2">grigio chiaro</option>
								<option value="19" data-value="19" data-color="#efefef">marroncino</option>
								<option value="20" data-value="20" data-color="#e7e0d8">marrone</option>
								<option value="21" data-value="21" data-color="#d7d0c6">marrone scuro</option>
								<option value="22" data-value="22" data-color="#263459">blu scuro</option>
								<option value="23" data-value="23" data-color="#ffffff">bianco</option>
							</select>
							<script type="text/javascript">
								$('#colorselectorbtn').colorselector({
									callback: function(value, color, title) {
										$("#colbtn").val(color);
									}
								});
							</script>
						</div>
						<div class="form-group">
							<label>Custom text color :</label>
							<input type="text" class="form-control" id="colbtncol" />
							<select id="colorselectorbtncol">
								<option value="1" data-value="1" data-color="#A0522D">sienna</option>
								<option value="2" data-value="2" data-color="#CD5C5C">indianred</option>
								<option value="3" data-value="3" data-color="#FF4500">orangered</option>
								<option value="4" data-value="4" data-color="#008B8B">darkcyan</option>
								<option value="5" data-value="5" data-color="#B8860B">darkgoldenrod</option>
								<option value="6" data-value="6" data-color="#32CD32">limegreen</option>
								<option value="7" data-value="7" data-color="#FFD700">gold</option>
								<option value="8" data-value="8" data-color="#48D1CC">mediumturquoise</option>
								<option value="9" data-value="9" data-color="#87CEEB">skyblue</option>
								<option value="10" data-value="10" data-color="#FF69B4">hotpink</option>
								<option value="11" data-value="11" data-color="#87CEFA">lightskyblue</option>
								<option value="12" data-value="12" data-color="#6495ED">cornflowerblue</option>
								<option value="13" data-value="13" data-color="#DC143C">crimson</option>
								<option value="14" data-value="14" data-color="#FF8C00">darkorange</option>
								<option value="15" data-value="15" data-color="#C71585">mediumvioletred</option>
								<option value="16" data-value="16" data-color="#000000">black</option>
								<option value="17" data-value="17" data-color="#575757">grigio scuro</option>
								<option value="18" data-value="18" data-color="#f2f2f2">grigio chiaro</option>
								<option value="19" data-value="19" data-color="#efefef">marroncino</option>
								<option value="20" data-value="20" data-color="#e7e0d8">marrone</option>
								<option value="21" data-value="21" data-color="#d7d0c6">marrone scuro</option>
								<option value="22" data-value="22" data-color="#263459">blu scuro</option>
								<option value="23" data-value="23" data-color="#ffffff">bianco</option>
							</select>
							<script type="text/javascript">
								$('#colorselectorbtncol').colorselector({
									callback: function(value, color, title) {
										$("#colbtncol").val(color);
									}
								});
							</script>
						</div>
					</div>
					<!-- fine bottone-->
					<div id="code" style="display:none"> </div>
					<!-- fine code -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label> ID : </label>
								<input type="text" class="form-control" id="id" /> </div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="class"> Css class : </label>
								<input type="text" name="class" id="class" class="form-control" /> </div>
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="CellSettings">
				<div class="panel panel-body">
					<table width="100%" cellpadding="5" cellspacing="0" style="border:1px solid #cccccc" id="tabCol">
						<tr>
							<td>&nbsp;Margin</td>
							<td></td>
							<td>
								<input type="text" size="4" class="form-control text-center" data-ref="margin-top" />
							</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td bgcolor="#f2f2f2">Padding</td>
							<td bgcolor="#f2f2f2">
								<input type="text" size="4" class="form-control text-center" data-ref="padding-top" />
							</td>
							<td bgcolor="#f2f2f2"></td>
							<td></td>
						</tr>
						<tr>
							<td>
								<input type="text" size="4" class="form-control text-center" data-ref="margin-left">
							</td>
							<td bgcolor="#f2f2f2">
								<input type="text" size="4" class="form-control text-center" data-ref="padding-left">
							</td>
							<td bgcolor="#f2f2f2"></td>
							<td bgcolor="#f2f2f2">
								<input type="text" size="4" class="form-control text-center" data-ref="padding-right">
							</td>
							<td>
								<input type="text" size="4" class="form-control text-center" data-ref="margin-right">
							</td>
						</tr>
						<tr>
							<td></td>
							<td bgcolor="#f2f2f2"></td>
							<td bgcolor="#f2f2f2">
								<input type="text" size="4" class="form-control text-center" data-ref="padding-bottom">
							</td>
							<td bgcolor="#f2f2f2"></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>
								<input type="text" size="4" class="form-control text-center" data-ref="margin-bottom">
							</td>
							<td></td>
							<td></td>
						</tr>
					</table>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Background color :</label>
								<input type="text" class="form-control" id="colbg" />
								<select id="colorselectorbg">
									<option value="1" data-value="1" data-color="#A0522D">sienna</option>
									<option value="2" data-value="2" data-color="#CD5C5C">indianred</option>
									<option value="3" data-value="3" data-color="#FF4500">orangered</option>
									<option value="4" data-value="4" data-color="#008B8B">darkcyan</option>
									<option value="5" data-value="5" data-color="#B8860B">darkgoldenrod</option>
									<option value="6" data-value="6" data-color="#32CD32">limegreen</option>
									<option value="7" data-value="7" data-color="#FFD700">gold</option>
									<option value="8" data-value="8" data-color="#48D1CC">mediumturquoise</option>
									<option value="9" data-value="9" data-color="#87CEEB">skyblue</option>
									<option value="10" data-value="10" data-color="#FF69B4">hotpink</option>
									<option value="11" data-value="11" data-color="#87CEFA">lightskyblue</option>
									<option value="12" data-value="12" data-color="#6495ED">cornflowerblue</option>
									<option value="13" data-value="13" data-color="#DC143C">crimson</option>
									<option value="14" data-value="14" data-color="#FF8C00">darkorange</option>
									<option value="15" data-value="15" data-color="#C71585">mediumvioletred</option>
									<option value="16" data-value="16" data-color="#000000">black</option>
									<option value="17" data-value="17" data-color="#575757">grigio scuro</option>
									<option value="18" data-value="18" data-color="#f2f2f2">grigio chiaro</option>
									<option value="19" data-value="19" data-color="#efefef">marroncino</option>
									<option value="20" data-value="20" data-color="#e7e0d8">marrone</option>
									<option value="21" data-value="21" data-color="#d7d0c6">marrone scuro</option>
									<option value="22" data-value="22" data-color="#263459">blu scuro</option>
									<option value="23" data-value="23" data-color="#ffffff">bianco</option>
								</select>
								<script type="text/javascript">
									$('#colorselectorbg').colorselector({
										callback: function(value, color, title) {
											$("#colbg").val(color);
										}
									});
								</script>
							</div>
						</div>
						<div class="col-md-6">
							<!-- <div class="form-group"> <label>Css class :</label> <input type="text" class="form-control" id="colcss" /> </div> --></div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="RowSettings">
				<div class="panel panel-body">
					<table width="100%" cellpadding="5" cellspacing="0" style="border:1px solid #cccccc" id="tabRow">
						<tr>
							<td>&nbsp;Margin</td>
							<td></td>
							<td>
								<input type="text" size="4" class="form-control text-center" data-ref="margin-top" />
							</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td bgcolor="#f2f2f2">Padding</td>
							<td bgcolor="#f2f2f2">
								<input type="text" size="4" class="form-control text-center" data-ref="padding-top" />
							</td>
							<td bgcolor="#f2f2f2"></td>
							<td></td>
						</tr>
						<tr>
							<td>
								<input type="text" size="4" class="form-control text-center" data-ref="margin-left">
							</td>
							<td bgcolor="#f2f2f2">
								<input type="text" size="4" class="form-control text-center" data-ref="padding-left">
							</td>
							<td bgcolor="#f2f2f2"></td>
							<td bgcolor="#f2f2f2">
								<input type="text" size="4" class="form-control text-center" data-ref="padding-right">
							</td>
							<td>
								<input type="text" size="4" class="form-control text-center" data-ref="margin-right">
							</td>
						</tr>
						<tr>
							<td></td>
							<td bgcolor="#f2f2f2"></td>
							<td bgcolor="#f2f2f2">
								<input type="text" size="4" class="form-control text-center" data-ref="padding-bottom">
							</td>
							<td bgcolor="#f2f2f2"></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>
								<input type="text" size="4" class="form-control text-center" data-ref="margin-bottom">
							</td>
							<td></td>
							<td></td>
						</tr>
					</table>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Background color :</label>
								<input type="text" class="form-control" id="rowbg" />
								<select id="colorselectorrowbg">
									<option value="1" data-value="1" data-color="#A0522D">sienna</option>
									<option value="2" data-value="2" data-color="#CD5C5C">indianred</option>
									<option value="3" data-value="3" data-color="#FF4500">orangered</option>
									<option value="4" data-value="4" data-color="#008B8B">darkcyan</option>
									<option value="5" data-value="5" data-color="#B8860B">darkgoldenrod</option>
									<option value="6" data-value="6" data-color="#32CD32">limegreen</option>
									<option value="7" data-value="7" data-color="#FFD700">gold</option>
									<option value="8" data-value="8" data-color="#48D1CC">mediumturquoise</option>
									<option value="9" data-value="9" data-color="#87CEEB">skyblue</option>
									<option value="10" data-value="10" data-color="#FF69B4">hotpink</option>
									<option value="11" data-value="11" data-color="#87CEFA">lightskyblue</option>
									<option value="12" data-value="12" data-color="#6495ED">cornflowerblue</option>
									<option value="13" data-value="13" data-color="#DC143C">crimson</option>
									<option value="14" data-value="14" data-color="#FF8C00">darkorange</option>
									<option value="15" data-value="15" data-color="#C71585">mediumvioletred</option>
									<option value="16" data-value="16" data-color="#000000">black</option>
									<option value="17" data-value="17" data-color="#575757">grigio scuro</option>
									<option value="18" data-value="18" data-color="#f2f2f2">grigio chiaro</option>
									<option value="19" data-value="19" data-color="#efefef">marroncino</option>
									<option value="20" data-value="20" data-color="#e7e0d8">marrone</option>
									<option value="21" data-value="21" data-color="#d7d0c6">marrone scuro</option>
									<option value="22" data-value="22" data-color="#263459">blu scuro</option>
									<option value="23" data-value="23" data-color="#ffffff">bianco</option>
								</select>
								<script type="text/javascript">
									$('#colorselectorrowbg').colorselector({
										callback: function(value, color, title) {
											$("#rowbg").val(color);
										}
									});
								</script>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Css class :</label>
								<input type="text" class="form-control" id="rowcss" /> </div>
						</div>
					</div>
					<div class="form-group">
						<label>Background image :</label>
						<input type="text" class="form-control" id="rowbgimage" /> </div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><i class='fa fa-close'></i>&nbsp;Fermer</button>
		<button type="button" class="btn btn-success" id="applyChanges"><i class='fa fa-check'></i>&nbsp;Sauvegarder</button>
	</div>
</div>
EOT;
				break;
			
			}

			return $htmlpagebuilder;
		}
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

		
	/**
	* Construct form (body)
	* add a option to a form element (optgroup)
	* @return html code
	*/
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
	
	/**
	* Construct form (body)
	* add a option to a form element (close optgroup)
	* @return html code
	*/
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