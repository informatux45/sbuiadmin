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
	$id        = (isset($param['id']) && intval($param['id']) > 0) ? intval($param['id']) : intval($_GET['id'] ?? 0);
	$class     = $param['class'] ?? '';
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
			# Global email settings
			# SQL Request (all config)
			$email_to = $subject = '';
			$query   = "SELECT config, content FROM {$module['tables']['config']} WHERE config = 'email_to' OR config = 'email_subject'";
			$request = $sbsql->query($query);
			$result  = $sbsql->toarray($request);
			foreach($result as $val) {
				switch($val['config']) {
					case "email_to": $email_to = $sbsanitize->sTrim($val['content']); break;
					case "email_subject": $subject = $sbsanitize->displayLang(sb_utf8_encode($val['content'])); break;
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
			$form_html .= sbGetContactFormElements($form_info['form'], '', $sendmail);
			
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
					.always(function() {
						// Un défi ALTCHA ne sert qu'une fois : nouveau défi pour un nouvel envoi
						$(form).find('altcha-widget').each(function() { if (this.reset) this.reset(); });
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
	$id        = intval($param['id'] ?? 0);
	$class     = $param['class'] ?? '';
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
			# Global email settings
			# SQL Request (all config)
			$email_to = $subject = '';
			$query   = "SELECT config, content FROM {$module['tables']['config']} WHERE config = 'email_to' OR config = 'email_subject'";
			$request = $sbsql->query($query);
			$result  = $sbsql->toarray($request);
			foreach($result as $val) {
				switch($val['config']) {
					case "email_to": $email_to = $sbsanitize->sTrim($val['content']); break;
					case "email_subject": $subject = $sbsanitize->displayLang(sb_utf8_encode($val['content'])); break;
				}
			}
			# ################################################
			# Settings for email settings (if exists)
			# SQL Request	
			$query_contact   = "SELECT recipients, subject FROM $table WHERE id = '$id'";
			$request_contact = $sbsql->query($query_contact);
			$result_contact  = $sbsql->assoc($request_contact);
			if ($result_contact['recipients'] != '') $email_to = $sbsanitize->sTrim($result_contact['recipients']);
			if ($sbsanitize->displayLang(sb_utf8_encode($result_contact['subject'])) != '') $subject = $sbsanitize->displayLang(sb_utf8_encode($result_contact['subject']));
			# ################################################
			# Check if form is submitted
			$errMsg = $succMsg = '';
			if(isset($_POST['submitform']) && !empty($_POST['submitform'])) {
				// --- Anti-robot ALTCHA (une seule API : inc/sbuiadmin-altcha.php)
				if (sbAltchaVerify()) {
					$errMsg = sbContactSend($id, $email_to, $subject, $subject . ' (' . _AM_SITE_TITLE . ')', array('submitform', 'go'));
					if ($errMsg === '') {
						$succMsg = _CMS_CONTACT_FORM_SUCCESS;
						// --- Empty form fields
						$sendmail = true;
					}
				} else {
					$errMsg = ($GLOBALS['sb_altcha_error'] == 'missing') ? _CMS_CONTACT_FORM_ERROR_CAPTCHA_EMPTY : _CMS_CONTACT_FORM_ERROR_CAPTCHA;
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
			$form_html .= '<form '.$form_name.'action="http' . ((($_SERVER['HTTPS'] ?? '') == 'on') ? 's' : '') . '://' . htmlspecialchars($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') . '#form_' . $id . '" method="post"'.$form_class.'>';
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
			$form_html .= sbGetContactFormElements($form_info['form'], '', $sendmail);
			
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
 * public key	string	$publickey (inutilisé : ancienne clé Google reCAPTCHA)
 * return HTML
 */
function sbGetContactFormElements($string, $publickey = '', $sendmail = false) {
	global $sbsanitize, $sbsql;
	
	// --- Types
	$type_elements = ['TEXT','CHECKBOX','TXTAREA','SELECT','ALTCHA','SUBMIT'];
	$GLOBALS['sb_contact_altcha_widgets'] = 0;
	
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
				// --- Attributs propres à chaque champ (ceux du précédent ne sont pas repris)
				$param = array();
				for($j = 0; $j < count($result[$i]); $j++) {
					// --- List all keys => values
					list($key, $value) = array_pad(explode("=", $result[$i][$j], 2), 2, '');
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

	// --- L'envoi exige toujours ALTCHA : un formulaire sans [ALTCHA]
	// --- reçoit le widget avant son premier bouton d'envoi
	if (!$GLOBALS['sb_contact_altcha_widgets']) {
		$widget = sbAltchaWidget();
		$submit = stripos($string, '<input type="submit"');
		$string = ($submit !== false) ? substr_replace($string, $widget, $submit, 0) : $string . $widget;
	}
	
	return $string;
}

/**
 * Construct Element Form
 * key		string		$param key
 * value	string		$param value
 * type		string		$type of element (TEXT, TXTAREA, SELECT, SUBMIT, ALTCHA)
 * return HTML
 */
function sbContactFormConstructElement($param, $type = '', $publickey = '', $sendmail = false) {
	// --- Initialization
	$input_html  = '';
	$required    = false;
	$keyname     = false;
	
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
			$input_html .= ' value="' . (($keyname && !$sendmail) ? sbContactPosted($keyname) : '') . '"';
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
			$input_html .= ' value="' . (($keyname && !$sendmail) ? sbContactPosted($keyname) : '') . '"';
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
			$input_html .= ($keyname && !$sendmail) ? sbContactPosted($keyname) : '';
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
			$options_array = $options_array ?? array();
			for($o = 0; $o < count($options_array); $o++) {
				$input_html .= '<option value="' . ($value_array[$o] ?? '') . '">';
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
	
		case "ALTCHA":
			// --- Anti-robot ALTCHA (auto-hébergé, une seule API : inc/sbuiadmin-altcha.php)
			$input_html .= '<div class="sb-altcha">' . sbAltchaWidget() . '</div>';
			$GLOBALS['sb_contact_altcha_widgets']++;
		break;

	}

	return $input_html;
}


/**
 * Valeur saisie par le visiteur, échappée pour être réaffichée dans le formulaire
 * @param string $name nom du champ
 * @return string
 */
function sbContactPosted($name) {
	$v = $_POST[$name] ?? '';
	return htmlspecialchars(is_array($v) ? '' : (string) $v, ENT_QUOTES, 'UTF-8');
}

/**
 * Adresses d'une liste « Email(s) » (séparées par , ou ;), les invalides
 * écartées (et notées dans le journal d'erreurs PHP)
 * @param string $list
 * @return array adresses valides, sans doublon
 */
function sbContactRecipients($list) {
	$valid = array();
	foreach (preg_split('/[,;]+/', html_entity_decode((string) $list, ENT_QUOTES, 'UTF-8')) as $address) {
		$address = trim($address);
		if ($address === '') continue;
		if (filter_var($address, FILTER_VALIDATE_EMAIL)) $valid[strtolower($address)] ??= $address;
		else error_log('Contact : destinataire invalide écarté : ' . $address);
	}
	return array_values($valid);
}

/**
 * Envoi d'un formulaire de contact : le message est enregistré
 * (sb_contact_messages, même si le mail échoue) puis envoyé aux destinataires.
 * @param int    $form_id  formulaire (0 = page Contact du module)
 * @param string $email_to destinataires (séparés par , ou ;)
 * @param string $subject  sujet du mail
 * @param string $title    titre en tête du mail
 * @param array  $skip     champs techniques à ne pas transmettre
 * @return string '' si envoyé, sinon le message d'erreur pour le visiteur
 */
function sbContactSend($form_id, $email_to, $subject, $title, array $skip = array()) {
	global $sbsanitize;

	$skip[] = SB_ALTCHA_FIELD;
	$fields = array();
	foreach ($_POST as $k => $v) {
		if (in_array((string) $k, $skip, true)) continue;
		$fields[(string) $k] = is_array($v) ? implode(', ', array_map('strval', $v)) : trim((string) $v);
	}
	$name  = trim(str_replace(array("\r", "\n"), ' ', $fields['name'] ?? ''));
	$email = $fields['email'] ?? '';
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return _CMS_CONTACT_FORM_ERROR_EMAIL;

	$subject    = $sbsanitize->displayText($subject, 'UTF-8');
	$recipients = sbContactRecipients($email_to);
	$mail       = sbMailer();
	$error      = '';
	if (!$recipients) {
		$error = 'aucun destinataire valide';
	} elseif (!$mail) {
		$error = 'PHPMailer absent (vendor/)';
	} else {
		$e    = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8', false); };
		$html = '<h1>' . $e($sbsanitize->displayText($title, 'UTF-8')) . "</h1>\n";
		foreach ($fields as $k => $v) $html .= '<p><b>' . $e(str_replace('-', ' ', $k)) . ' :</b> ' . nl2br($e($v)) . "</p>\n";
		// --- Expéditeur = le site (le SMTP n'accepte que son domaine vérifié),
		// --- réponse = le visiteur
		if (!$mail->setFrom(SBFROMEMAIL, $name)) {
			$error = $mail->ErrorInfo;
		} else {
			$mail->addReplyTo($email, $name);
			foreach ($recipients as $to) $mail->addAddress($to);
			$mail->Subject = $subject;
			$mail->msgHTML($html);
			if (!$mail->send()) $error = $mail->ErrorInfo;
		}
	}

	sbContactSaveMessage($form_id, $name, $email, $fields, implode(', ', $recipients), $error);
	if ($error === '') return '';
	error_log('Contact : envoi en échec : ' . $error);
	return _CMS_CONTACT_FORM_ERROR_SEND;
}

/** Durée de conservation des messages, en mois (0 = sans limite) */
function sbContactRetention() {
	global $sbsql;
	$row = $sbsql->assoc($sbsql->query("SELECT content FROM " . _AM_DB_PREFIX . "sb_config WHERE config = 'contact_retention'"));
	return $row ? max(0, (int) $row['content']) : 12;
}

/** Supprime les messages plus anciens que la durée de conservation */
function sbContactPurgeMessages() {
	global $sbsql;
	$months = sbContactRetention();
	if ($months > 0) $sbsql->query("DELETE FROM " . _AM_DB_PREFIX . "sb_contact_messages WHERE created_at < DATE_SUB(NOW(), INTERVAL $months MONTH)");
}

/** Enregistre un message reçu ($error : '' si le mail est parti) */
function sbContactSaveMessage($form_id, $name, $email, array $fields, $recipients, $error) {
	global $sbsql;
	sbContactPurgeMessages();
	$q = function ($v, $max = 0) use ($sbsql) {
		$v = (string) $v;
		if ($max) $v = mb_substr($v, 0, $max, 'UTF-8');
		return "'" . $sbsql->escape_string($v) . "'";
	};
	$json = json_encode($fields, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
	$sql  = "INSERT INTO " . _AM_DB_PREFIX . "sb_contact_messages (form_id, created_at, name, email, fields, recipients, sent, error)"
		. " VALUES (" . (int) $form_id . ", NOW(), " . $q($name, 255) . ", " . $q($email, 255) . ", " . $q($json) . ", " . $q($recipients) . ", "
		. ($error === '' ? 1 : 0) . ", " . $q($error, 255) . ")";
	if (!$sbsql->query($sql)) error_log('Contact : message non enregistré : ' . $sbsql->error);
}


?>