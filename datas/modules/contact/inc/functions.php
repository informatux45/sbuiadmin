<?php
/**
 * Plugin Name: SBUIADMIN CONTACT
 * Description: Gestionnaire de formulaire de contact
 * Version: 0.1.2
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 * File: functions.php
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

/**
 * Get A Contact AJAX Form with ID
 * id		int			$param id
 * name		string		$param name (function name after 'shortcode_')
 * return HTML
 */
function shortcode_sbcontactajax($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$id        = (isset($param['id']) && intval($param['id']) > 0) ? intval($param['id']) : intval($_GET['id']);
	$class     = $param['class'];
	$form_html = '';
	$sendmail  = false;
	// --- Tables
	$table = _AM_DB_PREFIX . 'sb_contact';
	// --- SQL Contact form
	$query_form   = "SELECT * FROM $table WHERE id = '$id'";
	$request_form = $sbsql->query($query_form);
	$form_info    = $sbsql->assoc($request_form);
	
	// --- Check if form exists
	if ($form_info) {
		// --- Check if form is active
		if ($form_info['active']) {
			// --- --- --- --- --- --- ---
			// --- Include Process Contact
			// --- --- --- --- --- --- ---
			$module['tables']['config']  = _AM_DB_PREFIX . "sb_config";
			# Include Globals
			global $sbsmarty, $sbsanitize, $sbsql;
			# ################################################
			# Settings for Recaptcha AND Global email settings
			# SQL Request (all config)
			$query   = "SELECT config, content FROM {$module['tables']['config']} WHERE config = 'email_to' OR config = 'email_subject' OR config = 'email_publickey' OR config = 'email_privatekey'";
			$request = $sbsql->query($query);
			$result  = $sbsql->toarray($request);
			foreach($result as $val) {
				switch($val['config']) {
					case "email_to": $email_to = $sbsanitize->sTrim($val['content']); break;
					case "email_subject": $subject = $sbsanitize->displayLang(utf8_encode($val['content'])); break;
					case "email_publickey": $publickey = $sbsanitize->sTrim($val['content']); break;
					case "email_privatekey": $privatekey = $sbsanitize->sTrim($val['content']); break;
				}
			}
			// --- --- --- --- --- --- ---
			// --- --- --- --- --- --- ---
			// --- --- --- --- --- --- ---
			
			// --- Initialization
			$form_html .= '<style>';
			$form_html .= ' .success-ajax-msg {
							  padding: 1em;
							  margin-bottom: 0.75rem;
							  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
							  color: #468847;
							  background-color: #dff0d8;
							  border: 1px solid #d6e9c6;
							  -webkit-border-radius: 4px;
								 -moz-border-radius: 4px;
									  border-radius: 4px;
							}
							
							.error-ajax-msg {
							  padding: 1em;
							  margin-bottom: 0.75rem;
							  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
							  color: #b94a48;
							  background-color: #f2dede;
							  border: 1px solid rgba(185, 74, 72, 0.3);
							  -webkit-border-radius: 4px;
								 -moz-border-radius: 4px;
									  border-radius: 4px;
							}';
			$form_html .= '</style>';
			// --- Error / Success Message
			$form_t     = time();
			$form_html .= '<div id="form-messages-' . $form_t . '"></div>';

			// --- Form
			$form_html .= '<form id="ajax-contact-' . $form_t . '" action="' . SB_MODULES_URL . 'contact/contact_ajax.php?id='.$id.'" method="post" class="'.$class.'">';

			// --- Construct Form
			$form_html .= sbGetContactFormElements($form_info['form'], $publickey, $sendmail);
			
			// --- Insert HIDDEN INPUT
			$form_html .= '<input type="hidden" name="submitform" value="ok">';
			// --- Close Form
			$form_html .= '</form>';
			
			$form_html .= '<script>';
			$form_html .= "
			$(function() {
				// Get the form.
				var form = $('#ajax-contact-$form_t');
			
				// Get the messages div.
				var formMessages = $('#form-messages-$form_t');
			
				// Set up an event listener for the contact form.
				$(form).submit(function(e) {
					// Stop the browser from submitting the form.
					e.preventDefault();
			
					// Serialize the form data.
					var formData = $(form).serialize();
			
					// Submit the form using AJAX.
					$.ajax({
						type: 'POST',
						url: $(form).attr('action'),
						data: formData
					})
					.done(function(response) {
						// Make sure that the formMessages div has the 'success' class.
						$(formMessages).removeClass('error');
						$(formMessages).addClass('success');
			
						// Set the message text.
						$(formMessages).html(response);
			
						// Clear the form.
						$('#name, #email, #message').val('');
					})
					.fail(function(data) {
						// Make sure that the formMessages div has the 'error' class.
						$(formMessages).removeClass('success');
						$(formMessages).addClass('error');
			
						// Set the message text.
						if (data.responseText !== '') {
							$(formMessages).html(data.responseText);
						} else {
							$(formMessages).html('Oops! An error occured and your message could not be sent.');
						}
					});
			
				});
			
			});";
			$form_html .= '</script>';
			
			// --- Return Form
			return $sbsanitize->displayText($form_html);
		
		} else {
			// --- Form inactive
			return _CMS_CONTACT_FORM_INACTIVE;
		}
		
	} else {
		// --- Form not found
		return _CMS_CONTACT_FORM_NOTFOUND;
	}
}

function shortcode_sbcontact($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$id        = intval($param['id']);
	$class     = $param['class'];
	$formname  = (isset($param['form'])) ? trim($param['form']) : false;
	$form_html = '';
	$sendmail  = false;
	// --- Tables
	$table = _AM_DB_PREFIX . 'sb_contact';
	// --- SQL Slider
	$query_form   = "SELECT * FROM $table WHERE id = '$id'";
	$request_form = $sbsql->query($query_form);
	$form_info    = $sbsql->assoc($request_form);
	
	// --- Check if form exists
	if ($form_info) {
		// --- Check if form is active
		if ($form_info['active']) {
			// --- --- --- --- --- --- ---
			// --- Include Process Contact
			// --- --- --- --- --- --- ---
			$module['tables']['config']  = _AM_DB_PREFIX . "sb_config";
			# Include Globals
			global $sbsmarty, $sbsanitize, $sbsql;
			# ################################################
			# Settings for Recaptcha AND Global email settings
			# SQL Request (all config)
			$query   = "SELECT config, content FROM {$module['tables']['config']} WHERE config = 'email_to' OR config = 'email_subject' OR config = 'email_publickey' OR config = 'email_privatekey'";
			$request = $sbsql->query($query);
			$result  = $sbsql->toarray($request);
			foreach($result as $val) {
				switch($val['config']) {
					case "email_to": $email_to = $sbsanitize->sTrim($val['content']); break;
					case "email_subject": $subject = $sbsanitize->displayLang(utf8_encode($val['content'])); break;
					case "email_publickey": $publickey = $sbsanitize->sTrim($val['content']); break;
					case "email_privatekey": $privatekey = $sbsanitize->sTrim($val['content']); break;
				}
			}
			# ################################################
			# Settings for email settings (if exists)
			# SQL Request	
			$query_contact   = "SELECT recipients, subject FROM $table WHERE id = '$id'";
			$request_contact = $sbsql->query($query_contact);
			$result_contact  = $sbsql->assoc($request_contact);
			if ($result_contact['recipients'] != '') $email_to = $sbsanitize->sTrim($result_contact['recipients']);
			if ($sbsanitize->displayLang(utf8_encode($result_contact['subject'])) != '') $subject = $sbsanitize->displayLang(utf8_encode($result_contact['subject']));
			# ################################################
			# Check if form is submitted
			if(isset($_POST['submitform']) && !empty($_POST['submitform'])) {
				// --- Check Google Recaptcha
				if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
					function getCurlData($url) {
						$curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $url);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curl, CURLOPT_TIMEOUT, 10);
						curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
						$curlData = curl_exec($curl);
						curl_close($curl);
						return $curlData;
					}
					
					// --- Get verify response data
					$google_url = "https://www.google.com/recaptcha/api/siteverify";
					$ip         = $_SERVER['REMOTE_ADDR'];
					$url        = $google_url . "?secret=" . $privatekey . "&response=" . $_POST['g-recaptcha-response'] . "&remoteip=" . $ip;
					$response   = getCurlData($url);
					$response   = json_decode($response); // Don't add TRUE setting in json_decode
					
					if ($response->success === true) {
						// --- Initialization
						$htmlContent = '<h1>' . $subject . ' (' . _AM_SITE_TITLE . ')</h1>';
						// --- Get Contact form submission $_POST
						foreach($_POST as $k => $v) {
							if ($k == 'name')  $name  = $v;
							if ($k == 'email') $email = $v;
							// --- Increase html content
							if ($k != 'g-recaptcha-response' && $k != 'submitform' && $k != 'go')
								$htmlContent .= '<p><b>'.$k.' :</b> '.$sbsanitize->nl2Br($v).'</p>';
						}
	
						// --- Always set content-type when sending HTML email
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						// --- More headers
						$headers .= 'From:'.$name.' <'.$email.'>' . "\r\n";
						// --- Send email
						@mail($email_to, $subject, $htmlContent, $headers);
						
						$succMsg = _CMS_CONTACT_FORM_SUCCESS;
						// --- Empty form fields
						$sendmail = true;
						
					} else {
						//$errMsg = _CMS_CONTACT_FORM_ERROR_CAPTCHA . '(error-codes: '.$responseData->error.')';
						$errMsg = _CMS_CONTACT_FORM_ERROR_CAPTCHA;
					}
				} else {
					$errMsg = _CMS_CONTACT_FORM_ERROR_CAPTCHA_EMPTY;
				}
			} else {
				$errMsg = '';
				$succMsg = '';
			}
			// --- --- --- --- --- --- ---
			// --- --- --- --- --- --- ---
			// --- --- --- --- --- --- ---
			
			// --- Initialization
			$form_name  = ($formname) ? 'name="'.$formname.'" id="'.$formname.'" ' : '';
			$form_class = ($class) ? ' class="'.$class.'"' : '';
			$form_html .= '<a name="form_'.$id.'"></a>';
			$form_html .= '<form '.$form_name.'action="http' . (($_SERVER['HTTPS'] == 'on') ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'#form_' . $id . '" method="post"'.$form_class.'>';
			// --- Error / Success Message
			//$form_html .= '<div class="usermessagea">';
			if ($errMsg) {
				$form_html .= '<div class="box error-box">';
				$form_html .= $errMsg;
				$form_html .= '</div>';
			}
			if ($succMsg) {
				$form_html .= '<div class="box success-box">';
				$form_html .= $succMsg;
				$form_html .= '</div>';
			}
			//$form_html .= '</div>';
			// --- Form
			$form_html .= sbGetContactFormElements($form_info['form'], $publickey, $sendmail);
			
			// --- Insert HIDDEN INPUT
			$form_html .= '<input type="hidden" name="submitform" value="ok">'; 
			// --- Close Form
			$form_html .= '</form>';
			
			// --- Return Form
			return $sbsanitize->displayText($form_html);
		
		} else {
			// --- Form inactive
			return _CMS_CONTACT_FORM_INACTIVE;
		}
		
	} else {
		// --- Form not found
		return _CMS_CONTACT_FORM_NOTFOUND;
	}
}

/**
 * Replace all Shortcodes in string
 * form code	string	$string
 * public key	string	$publickey (reCAPTCHA Google Public Key)
 * return HTML
 */
function sbGetContactFormElements($string, $publickey = '', $sendmail = false) {
	global $sbsanitize, $sbsql;
	
	// --- Types
	$type_elements = ['TEXT','CHECKBOX','TXTAREA','SELECT','RECAPTCHA','SUBMIT'];
	
	// ------------------------
	// --- Get INPUTs
	// ------------------------
	foreach($type_elements as $type_element) {
		// --- Preg Match all type element in array
		preg_match_all( '/\['.$type_element.'(.*?)\]/', $string, $matches );
		// --- Get if is one more FORM ELEMENTS
		$result = $input = $param = array();
		for($i = 0; $i < count($matches[1]); $i++) {
			$result[] = explode("/", trim($matches[1][$i]));
			$input[]  = trim($matches[0][$i]);
		}
	
		// --- Check if result exist ;-)
		if (!empty($result)) {
			// --- Get the result	
			for($i = 0; $i < count($result); $i++) {
				for($j = 0; $j < count($result[$i]); $j++) {
					// --- List all keys => values
					list($key, $value) = explode("=", $result[$i][$j]);
					// --- Assign keys
					$param[$key] = $value;
				}
				// ------------------------------------------------
				// Do function if FORM ELEMENT exist
				// ------------------------------------------------
				$string = str_replace($input[$i], sbContactFormConstructElement($param, $type_element, $publickey, $sendmail), $string);
			}
		}
	}
	
	return $string;
}

/**
 * Construct Element Form
 * key		string		$param key
 * value	string		$param value
 * type		string		$type of element (TEXT, TXTAREA, SELECT, SUBMIT, RECAPTCHA)
 * return HTML
 */
function sbContactFormConstructElement($param, $type = '', $publickey = '', $sendmail = false) {
	// --- Initialization
	$input_html  = '';
	$required    = false;
	$keyname     = false;
	$recaptcha_t = time();
	
	switch($type) {
		default:
		case "TEXT":
			$type = 'text';
			$input_html .= '<input';
			foreach($param as $key => $val) {
				// --- Check if required
				if ($key == 'type')
					$type = $val;
				elseif ($key == 'required')
					$required = true;
				else {
					if ($key == 'name') $keyname = $val;
					$input_html .= ' ' . $key . '="' . $val . '"';
				}
			}
			$input_html .= ($keyname && !$sendmail) ? 'value="' . $_POST[$keyname] . '"' : 'value=""';
			$input_html .= ' type="'.$type.'"';
			$input_html .= ($required) ? ' required>' : '>';
		break;
	
		case "CHECKBOX":
			$type = 'checkbox';
			$input_html .= '<input';
			foreach($param as $key => $val) {
				// --- Check if required
				if ($key == 'type')
					$type = $val;
				elseif ($key == 'required')
					$required = true;
				else {
					if ($key == 'name') $keyname = $val;
					$input_html .= ' ' . $key . '="' . $val . '"';
				}
			}
			$input_html .= ($keyname && !$sendmail) ? 'value="' . $_POST[$keyname] . '"' : 'value=""';
			$input_html .= ' type="'.$type.'"';
			$input_html .= ($required) ? ' required>' : '>';
		break;
	
		case "TXTAREA":
			$input_html .= '<textarea';
			foreach($param as $key => $val) {
				// --- Check if required
				if ($key == 'required')
					$required = true;
				else {
					if ($key == 'name') $keyname = $val;
					$input_html .= ' ' . $key . '="' . $val . '"';
				}
			}
			$input_html .= ($required) ? ' required>' : '>';
			$input_html .= ($keyname && !$sendmail) ? $_POST[$keyname] : '';
			$input_html .= '</textarea>';
		break;
	
		case "SELECT":
			$input_html .= '<select';
			// --- Construct SELECT
			foreach($param as $key => $val) {
				// --- Get OPTIONS / VALUE
				if ($key == 'options' ) {
					$options_array = explode("|", $val);
				} elseif ($key == 'value') {
					$value_array = explode("|", $val);
				} else
					$input_html .= ' ' . $key . '="' . $val . '"';
			}
			$input_html .= '>';
			// --- Construct OPTIONS / VALUE
			for($o = 0; $o < count($options_array); $o++) {
				$input_html .= '<option value="' . $value_array[$o] . '">';
				$input_html .= $options_array[$o];
				$input_html .= '</option>';
			}
			// --- Close SELECT
			$input_html .= '</select>';
		break;
	
		case "SUBMIT":
			$input_html .= '<input type="submit"';
			foreach($param as $key => $val) {
				$input_html .= ' ' . $key . '="' . $val . '"';
			}
			$input_html .= '>';
		break;
	
		case "RECAPTCHA_INVISIBLE":
			$input_html .= '<script src="https://www.google.com/recaptcha/api.js?hl=';
			$input_html .= ($_SESSION['lang'] == 'en') ? 'en' : 'fr';
			$input_html .= '&remoteip=' . $_SERVER['REMOTE_ADDR'] . '" async defer></script>';
			$input_html .= '<input type="submit"';
			foreach($param as $key => $val) {
				// If Id inserted
				if ($key == 'id') {
					$input_html .= ' ' . $key . '="' . $val . '"';
					$input_id    = $val;
				}
				// If class inserted
				if ($key == 'class') {
					$input_html .= ' ' . $key . '="' . $val . ' g-recaptcha"';
					$input_class = true;
				}
				$input_html .= ' ' . $key . '="' . $val . '"';
			}
			// If no class in keys
			if (!$input_class) $input_html .= ' class="g-recaptcha"';
			if (!$input_id) {
				$input_id    = 'contactform_' . $recaptcha_t;
				$input_html .= ' id="' . $input_id . '"';
			}
			// Recaptcha settings
			$input_html .= ' data-sitekey = "' . $publickey . '"';
			$input_html .= ' data-callback = "sbLoginOnSubmit_' . $recaptcha_t . '"';
			$input_html .= '>';
			$input_html .= '<script type="text/javascript">';
			$input_html .= 'function sbLoginOnSubmit_' . $recaptcha_t . '(token) {
								document.getElementById("' . $input_id . '").submit();
							}';
			$input_html .= '</script>';
		break;
	
		case "RECAPTCHA":
			$recaptcha_v2 = time();
			$input_html  .= '<div id="grecaptcha_' . $recaptcha_v2 . '"></div>';
			$input_html  .= '<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=';
			$input_html  .= ($_SESSION['lang'] == 'en') ? 'en' : 'fr';
			$input_html  .= '&remoteip=' . $_SERVER['REMOTE_ADDR'] . '" async defer></script>';
			$input_html  .= '<script type="text/javascript">';
			$input_html  .= "var onloadCallback = function() {
								grecaptcha.render('grecaptcha_$recaptcha_v2', {
									'sitekey' : '$publickey',
									'theme' : 'light', // light, dark
									'type' : 'image', // image, audio
									'size' : 'normal', // normal, compact
									'tabindex' : 0
								});
							};";
			$input_html .= '</script>';
		break;

	}

	return $input_html;
}


?>
