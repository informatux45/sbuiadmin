<?php
/** *****************************************************************************
*                        INFORMATUX user class (UTF8)                           *
/** *****************************************************************************
* @author     Patrice BOUTHIER <contact[at]informatux.com>                      *
* @copyright  2007-2021 INFORMATUX                                              *
* @link       https://www.informatux.com/                                       *
* @since      1.0                                                               *
* @version    CVS: 1.8                                                          *
* ----------------------------------------------------------------------------- *
* Copyright (c) 2020, INFORMATUX Solutions and web development                  *
* All rights reserved.                                                          *
***************************************************************************** **/

/**
 * SQL Table ACCOUNT
 * 
 * uid bigint(20) NOT NULL COMMENT 'ID User',
 * gid bigint(20) NOT NULL COMMENT 'ID Groupe',
 * rpx_connect varchar(255) DEFAULT NULL COMMENT 'Connect identifier',
 * rpx_social varchar(50) DEFAULT NULL COMMENT 'Social network (name)',
 * viewed bigint(20) NOT NULL DEFAULT '0' COMMENT 'Nombre de consultation du user',
 * username varchar(255) NOT NULL,
 * email varchar(255) NOT NULL,
 * password varchar(100) DEFAULT NULL,
 * sex varchar(1) DEFAULT NULL COMMENT 'h: homme, f: femme',
 * civility varchar(5) DEFAULT NULL COMMENT 'mr: Monsieur, mme: Madame',
 * firstname varchar(50) NOT NULL,
 * lastname varchar(50) NOT NULL,
 * address varchar(150) DEFAULT NULL,
 * address_2 varchar(150) DEFAULT NULL,
 * zip varchar(10) DEFAULT NULL,
 * city varchar(150) DEFAULT NULL,
 * country varchar(50) DEFAULT NULL,
 * shipping_same tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: pas la meme, 1: la meme que facturation',
 * shipping_address varchar(150) DEFAULT NULL,
 * shipping_address_2 varchar(150) DEFAULT NULL,
 * shipping_zip varchar(10) DEFAULT NULL,
 * shipping_city varchar(150) DEFAULT NULL,
 * shipping_country varchar(50) DEFAULT NULL,
 * rate float NOT NULL DEFAULT '0' COMMENT 'Note cumulee des votants (a diviser par le nombre de votants)',
 * rateby bigint(20) NOT NULL DEFAULT '0' COMMENT 'Nombre de votants',
 * slogan varchar(255) DEFAULT NULL,
 * logo varchar(100) DEFAULT NULL COMMENT 'Photo du user',
 * email_verified tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: Email invalid, 1: Email verified',
 * key_activation varchar(100) NOT NULL,
 * created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Compte cree le ...',
 * lastlogin datetime DEFAULT NULL COMMENT 'Date / Heure de la derniere connexion',
 * businessname varchar(255) DEFAULT NULL,
 * siret varchar(100) DEFAULT NULL,
 * activity varchar(255) DEFAULT NULL,
 * telephone varchar(20) DEFAULT NULL,
 * birthday datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 * newsletter tinyint(4) NOT NULL DEFAULT '1' COMMENT '0: non abonné, 1: abonné',
 * status tinyint(4) NOT NULL DEFAULT '0',
 * active tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: inactive, 1: active'
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');


class account extends sql {
	
    /**
     * Holds various infos
     *
     * @var string
     */
    public $tblaccount   = _AM_DB_PREFIX . "sb_account";
    public $tblgroup     = _AM_DB_PREFIX . "sb_shop_group";
	public $tblemail     = _AM_DB_PREFIX . "sb_shop_email";
	public $tblcurrency  = _AM_DB_PREFIX . "sb_shop_currency_country";
	public $tblorder     = _AM_DB_PREFIX . 'sb_shop_order';
	public $tblaccess    = _AM_DB_PREFIX . "sb_logaccess";
	public $tblconfig    = _AM_DB_PREFIX . "sb_config";
	
	/**
	 * Login form user
	 *
	 * @param	string	$email 		User email
	 * @param	string	$password 	User password
	 *
	 * @return bool
	 */
    function login($email, $password) {
		// Initialisation
		$query  = "SELECT email, password FROM " . $this->tblaccount . " WHERE email = '$email'";
        $result = $this->query($query);
		$infos  = $this->assoc($result);
		// Check if user exists
		if ($result) {
			// Passwords
			$password_db    = $this->decrypt($infos['password']);
			$password_login = $this->decrypt($password);
			// Check passwords
			if ($password_db !== $password_login)
				return false;
			else
				return true;

		} else {
			// User unknown
			return false;
		}
    }
	
	/**
	 * Check if user captcha is correct if is activated
	 *
	 * @param	string	$email 		User email
	 * @param	string	$captcha 	Captcha result
	 *
	 * @return bool
	 */
    function checkUser($password, $captcha) {
        if (isset($_SESSION['sbaccount_user_email']) || $_SESSION['sbaccount_user_email'] != '') {
            if (!$this->login($_SESSION['sbaccount_user_email'], $password, $crypt)) {
                return false;
            } elseif (_AM_CAPTCHA_MODE == 0) {
                return true;
            } else {
                if ($_SESSION['captchaResult'] == $captcha) return true;
                else return false;
            }
        } else {
            return false;
        }
    }
	
	/**
	 * Get the homepage url (Site closed OR not)
	 *
	 * @return string (url)
	 */
	function getUrlHomepage() {
		// --- Link CUSTOMER WEBSITE HOME
		$sb_link_settings = file( SB_ADMIN_DIR . 'inc/admin/settings.txt' );
		if (trim($sb_link_settings[24]) == '1') {
			// Site closed / Get the URL to display the homepage
			$query            = "SELECT config, content FROM " . $this->tblconfig . " WHERE config = 'coming-soon-url'";
			$request          = $this->query($query);
			$assoc            = $this->object($request);
			// Link homepage
			$sb_url_customer  = trim($sb_link_settings[15]) . '?d=' . trim($assoc->content);
		} else {
			// Link homepage without cookie (Session)
			$sb_url_customer = trim($sb_link_settings[15]);
		}
		return $sb_url_customer;
	}
	
	/**
	 * User logout
	 *
	 * @return void
	 */
	function userLogout() {
		// Update LastLogin
		$this->updateAccessUserLogin($_SESSION['sbaccount_user_email']);
		// --- Link CUSTOMER WEBSITE HOME
		$sb_url_customer = $this->getUrlHomepage();
		// --- Start SESSION
		@session_start();
		// --- Set sessions to NULL
		$_SESSION = array();
		@session_unset();
		@session_destroy();
		@session_write_close();
		@setcookie(session_name(),'',0,'/');
		@session_regenerate_id(true);
		header("Location: " . $sb_url_customer);
		exit();
	}
	
	/**
	 * User redirect
	 *
	 * @return void
	 */
	function redirect($redirect_url) {
		header("Location: " . $redirect_url);
		exit();
	}
	
	/**
	 * Check if user is active
	 *
	 * @param	string	$email 	User email
	 *
	 * @return bool
	 */
    function checkUserIsActive($email) {
        $query_user  = "SELECT active FROM " . $this->tblaccount . " WHERE email = '$email'";
        $result_user = $this->query($query_user);
        $user_infos  = $this->assoc($result_user);
        if ($user_infos['active'] == '0') {
            return false;
        } else {
            return true;
        }
    }
	
	/**
	 * Get all groups
	 *
	 * @return array
	 */
	function getGroups() {
        $query_group  = "SELECT gid, title FROM " . $this->tblgroup . " WHERE active = '1' ORDER BY sort ASC";
        $result_group = $this->query($query_group);
        $group_infos  = $this->toarray($result_group);
		
		return ($group_infos) ? $group_infos : false;		
	}
	
	/**
	 * Get User Infos
	 *
	 * @param	string	$email 	User email
	 * @param	string	$field	Field from SQL table
	 *
	 * @return array / string
	 */
	function getGroupInfo($gid, $field = '') {
		global $sbsanitize;
		// --- Initialization
		$field = ($field != '') ? $sbsanitize->stopXSS($field) : "*";
		$gid   = intval($gid);
		
        $sql        = "SELECT $field FROM " . $this->tblgroup . " WHERE gid = '$gid'";
        $result     = $this->query($sql);
        $group_info = $this->assoc($result);
		
		// --- Check if exists
		if ($group_info) {
			if ($field == "*") {
				// All infos about GROUP
				return $group_info;
			} elseif ($group_info[$field]) {
				// Field info about GROUP
				return $group_info[$field];
			} else {
				// No info
				return false;
			}
		} else {
			// Group don't exists
			return false;
		}
	}
	
	/**
	 * TODO: group SQL TBL to construct
	 */
    function checkGroup() {
        if (isset($_SESSION['sbaccount_user_email']) || $_SESSION['sbaccount_user_email'] != '') return true;
        else return false;
    }
	
	/**
	* Check if email already exists
	*
	* @param	string 	$email 	User Email
	* 
	* @return bool
	*/
	function checkEmailExist($email) {
		global $sbsanitize;
		// check if email already exist
		// Escape strings
		$email = $sbsanitize->stopXSS($email);
	
		// check if email is unique
		$query   = "SELECT * FROM " . $this->tblaccount . " WHERE email = '$email'";
		$request = $this->query($query);
		$result  = $this->assoc($request);
	
		// check SQL request
		if ($result)
			return true; // Deja existant
		else
			return false; // Valide
	}
	
	/**
	* Check if Social User exists
	*
	* @param	string	$identifier		ID connect (social network)
	* @param	string	$provider		name of social network (Ex: facebook, google)
	* 
	* @return bool / array
	*/
	function isUserSocialExist($identifier, $provider) {
		// --- Initialize
		$identifier = trim($identifier);
		$provider   = strtolower(trim($provider));
		
		// --- Get Infos from user DB
		$query   = "SELECT rpx_connect, rpx_social, email, password FROM " . $this->tblaccount . " WHERE rpx_connect = '$identifier' AND rpx_social = '$provider'";
		$request = $this->query($query);
		$result  = $this->object($request);
		
		if (!$result) {
			return false;
		} else {
			return ['email' => $result->email, 'password' => $result->password];
		}
	}
	
	/**
	* Show error response (popup social network login / register)
	*
	* @param	array	$session	Session informations (error, ...)
	* @param	string	$type		Type of error
	* @param	bool	$success	Success
	* 
	* @return HTML
	*/
	function socialGetErrorSession($session, $type, $success = false) {
		// Initialization
		$html_return = "";
		$html_style  = ($success) ? 'success' : 'error';
		// --------------
		$html_return .= '<style>';
		if ($html_style == 'error') {
			$html_return .= 'div {display: flex; min-height: 100vh;}
							blockquote {margin: auto;}
							body {margin: 0; font-family: "Century Gothic", helvetica, arial, sans-serif;}
							div {background: white; padding: 40px;}
							blockquote {border: 1px solid blue; color: blue; background-color: white; padding: 15px; font-size: 2em; border-radius: 7px;}';
		} else {
			$html_return .= 'div {display: flex; min-height: 100vh;}
							blockquote {margin: auto;}
							body {margin: 0; font-family: "Century Gothic", helvetica, arial, sans-serif;}
							div {background: white; padding: 40px;}
							blockquote {border: 1px solid green; color: green; background-color: white; padding: 15px; font-size: 2em; border-radius: 7px;}';
		}
		$html_return .= '</style>';
		$html_return .= '<div class="container">';	//echo 'Referer: '.$_SERVER['HTTP_REFERER'].'<br>';	
		$html_return .= '<blockquote>';
		foreach($session as $key => $value) {
			$html_return .= $value . '<br>';
		}
		$html_return .= '<a style="text-decoration: none;" href="' . SB_URL . 'datas/modules/' . strtolower(MODULENAME) . 'account_setsession.php?type=' . $type . '">Retour au site</a>';
		$html_return .= '</blockquote>';
		$html_return .= '</div>';
		// --------------
		echo $html_return;
	}

	/**
	* Update Session
	*
	* @param	string	$sbuiadmin_type		Error Type
	* @param	string	$sbuiadmin_event	Error message
	* @param	string	$sbuiadmin_user		client OR email
	* 
	* @return bool
	*/
	function updateUserSession($email, $password) {
		$_SESSION['sbaccount_user_email']    = $email;
		$_SESSION['sbaccount_user_password'] = $password;
	}	
	
	/**
	* Update Access Log
	*
	* @param	string	$sbuiadmin_type		Error Type
	* @param	string	$sbuiadmin_event	Error message
	* @param	string	$sbuiadmin_user		client OR email
	* 
	* @return bool
	*/
	function updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user = 'client') {
		// --- Update the Access Log file if exist
		$sql = "INSERT INTO " . $this->tblaccess . " (`logaccess_type`, `logaccess_date`, `logaccess_user`, `logaccess_event`)
				VALUES ('$sbuiadmin_type', UNIX_TIMESTAMP(), '$sbuiadmin_user', '$sbuiadmin_event')";
		$result = $this->query($sql);
		if (!$result)
			return false;
		else
			return true;
	}


	/**
	* Update Acces Login / Last login Time User
	*
	* @param	string	$email 		User email
	* @param	date	$lastlogin 	Last login date
	* 
	* @return bool
	*/
	function updateAccessUserLogin($email, $lastlogin = false, $time = false) {
		// --- Update the Access User logintime
		if ($email != '' && $lastlogin == false) {
			$sql = "UPDATE " . $this->tblaccount . " SET logintime = NOW() WHERE email = '$email'";
			$result = $this->query($sql);
			if (!$result)
				return false;
			else
				return true;
		} elseif ($email != '' && $lastlogin) {
			$sql = "UPDATE " . $this->tblaccount . " SET lastlogin = logintime WHERE email = '$email'";
			$result = $this->query($sql);
			if (!$result)
				return false;
			else
				return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Get User Infos
	 *
	 * @param	string	$email 	User email
	 * @param	string	$field	Field from SQL table
	 *
	 * @return array / string
	 */
	function getUserInfo($email, $field = '') {
		global $sbsanitize;
		// --- Initialization
		$field = ($field != '') ? $sbsanitize->stopXSS($field) : "*";
		$email = $sbsanitize->stopXSS($email);
		
        $sql       = "SELECT $field FROM " . $this->tblaccount . " WHERE email = '$email'";
        $result    = $this->query($sql);
        $user_info = $this->assoc($result);
		
		// --- Check if exists
		if ($user_info) {
			if ($field == "*") {
				// All infos about USER
				return $user_info;
			} elseif ($user_info[$field]) {
				// Field info about USER
				return $user_info[$field];
			} else {
				// No info
				return false;
			}
		} else {
			// User don't exists
			return false;
		}
	}
	
	/**
	 * Get Shop Orders
	 *
	 * @param	integer		$client_uid		Client UID
	 * @param	string		$field			Field name
	 * 
	 * @return array
	 */
	function sbGetOrders($client_uid = '', $field = '') {
		global $sbsanitize;
		// --- Initialization
		$field = ($field != '') ? $sbsanitize->stopXSS($field) : "*";
		if ($client_uid != '') {
			$uid   = intval($client_uid);
			$where = "WHERE client_uid = '$uid' ORDER BY date DESC";
		} else {
			$where = "ORDER BY date DESC";
		}
		
		$query_order   = "SELECT $field FROM " . $this->tblorder . " $where";
		$request_order = $this->query($query_order);
		$orders        = $this->toarray($request_order);

		return $orders;
	}

	/**
	 * Get Shop Order Infos
	 *
	 * @param	integer		$order_id		Order ID
	 * @param	string		$field			Field name
	 * 
	 * @return array
	 */
	function getOrderInfo($order_id, $field = '') {
		global $sbsanitize;
		// --- Initialization
		$field = ($field != '') ? $sbsanitize->stopXSS($field) : "*";
		if (!$order_id)
			return false;
		
		$order_id = intval($order_id);
		
		$query_order   = "SELECT $field FROM " . $this->tblorder . " WHERE id = '$order_id'";
		$request_order = $this->query($query_order);
		$order         = $this->assoc($request_order);

		return $order;
	}
	
	/**
	 * Get Shop Order Status
	 *
	 * @param	integer		$status_id		Status ID
	 * 
	 * @return string Status Name
	 */
	function sbGetOrderStatus($status_id = '') {		
		switch($status_id) {
			case "6": // 3: Déposé - BURO Club
				return "Déposé - BURO Club";
			break;
			case "5": // 3: Expédié - La poste
				return "Expédié - La poste";
			break;
			case "4": // 3: Autres
				return "Autres";
			break;
			case "3": // 3: En attente
				return "En attente";
			break;
			case "2": // 2: paiement erreur
				return "Erreur de paiement";
			break;
			case "1": // 1: payé
				return "Payé";
			break;
			case "0":
			default: // 0: en cours
				return "En cours";
			break;
		}

		return $orders;
	}
	
	/**
	 * Get Country / Currency / Dial code / Symbol
	 *
	 * @return array
	 */
	function getCountry() {
		$this->free();
		$query_currency    = "SELECT * FROM " . $this->tblcurrency . " ORDER BY country ASC";
		$request_currency  = $this->query($query_currency);
		$result_currencies = $this->toarray($request_currency);
		
		return $result_currencies;
	}
	
	/**
	 * Get Country Name
	 *
	 * @param	string 	$code Two characters (Country CODE)	
	 *
	 * @return string
	 */
	function getCountryName($code) {
		$this->free();
		$query_currency   = "SELECT country FROM " . $this->tblcurrency . " WHERE country_code = '$code'";
		$request_currency = $this->query($query_currency);
		$result_currency  = $this->object($request_currency);
		
		return $result_currency->country;
	}
	
	/**
	 * CURL: Get response from url
	 *
	 * @param	string	$url	Url to access
	 *
	 * @return	string
	 */
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
	
	/**
	* Get Google Recaptcha Response
	* @return bool
	*/
	function checkRecaptcha($privatekey) {
		global $_POST;
		if ($privatekey) {
			// --- Check Google Recaptcha
			if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
				// --- Get verify response data
				$google_url = "https://www.google.com/recaptcha/api/siteverify";
				$ip         = $_SERVER['REMOTE_ADDR'];
				$url        = $google_url . "?secret=" . $privatekey . "&response=" . $_POST['g-recaptcha-response'] . "&remoteip=" . $ip;
				$response   = $this->getCurlData($url);
				$response   = json_decode($response); // Don't add TRUE setting in json_decode
				
				if ($response->success === false) {
					// --- Error Google Recaptcha
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Generate a password
	 *
	 * @param	integer		$length		length of password
	 *
	 * return string 
	 */
	function generatePassword($length = 64) {
		$salt = '';
		$base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$microtime = function_exists('microtime') ? microtime() : time();
		srand((double)$microtime * 1000000);
		for($i=0; $i<=$length; $i++)
		$salt.= substr($base, rand() % strlen($base), 1);
		return $salt;
	}
	
	/**
	* Send Email Automatic
	*
	* @param	string 	$emailname	email name (Ex: review-accepted - More infos in DB)
	* 
	* @return bool
	*/
	function sendEmail($emailname, $datas = '') {
		// -----------------------
		// Get email name infos
		// -----------------------
		$query   = "SELECT * FROM " . $this->tblemail . " WHERE name = '$emailname'";
		$request = $this->query($query);
		$result  = $this->assoc($request);
		// -----------------------
		// Check if exists
		// -----------------------
		if (!$result) {
			// -----------------------
			// Error name
			// -----------------------
			return false;
		} else {
			// -----------------------
			// Initialization
			// -----------------------
			$return = false;
			// -----------------------
			// Get general infos
			// -----------------------
			$sender_email = SBFROMEMAIL;  // sbconfig.php
			$admin_email  = sbGetConfig('theme_infos_email');
			$admin_name   = 'ADMIN ' . $admin_email;
			$email_tpls   = SB_PATH . 'datas/modules/account/tpls/emails/';
			
			// -----------------------
			// Include PHPMAILER
			// -----------------------
			if (!class_exists("PHPMailer")) require SB_PATH . 'vendor/phpmailer/PHPMailerAutoload.php'; // sbconfig.php
			
			// -----------------------
			// DATAS
			// -----------------------
			$address_to   = $datas['email'];
			$address_name = $datas['firstname'] . ' ' . $datas['lastname'];
			// -----------------------
			// CLIENT EMAIL
			// -----------------------
			$subject_customer = $this->formatEmail( $result['subject_customer'], $datas );
			$body_customer    = @file_get_contents( $email_tpls . 'default_client.php' );
			$client_content   = $this->formatEmail( $result['body_customer'], $datas );
			# --- Format template (CLIENT)
			$body_customer    = @preg_replace("/#EMAILCONTENT#/", $client_content, $body_customer);
			// -----------------------
			// ADMIN EMAIL
			// -----------------------
			$subject_admin = $this->formatEmail( $result['subject_admin'], $datas );
			$body_admin    = @file_get_contents( $email_tpls . 'default_admin.php' );
			$admin_content = $this->formatEmail( $result['body_admin'], $datas );
			# --- Format template (ADMIN)
			$body_admin    = @preg_replace("/#EMAILCONTENT#/", $admin_content, $body_admin);
			// -----------------------	
			// Send email customer
			// -----------------------
			$attachment = ($datas['attachment']) ? $datas['attachment'] : false;
			if (isset($address_to) && $address_to != '' && $subject_customer != '') {
				$customer_send_email = $this->sendMailer($address_to, $address_name, $subject_customer, $body_customer, $admin_name, $sender_email, $attachment);
				if ($customer_send_email) $return = true;
			}
			// -----------------------
			// Send email admin
			// -----------------------
			if (isset($subject_admin) && $subject_admin != '') {
				$this->sendMailer($admin_email, $admin_name, $subject_admin, $body_admin, $admin_name, $sender_email, $attachment);
			}
			
			return $return;
		}
	}
	
	/**
	* Send Email by PHPMailer
	* @return bool
	*/
	function sendMailer($address_to, $address_name, $subject, $htmlcontent, $sender_name, $sender_email, $attachment = false) {
		global $sbsanitize;
		
		$PHPMailer = new PHPMailer;
		
		@$PHPMailer->SetFrom($sender_email, "$admin_name");
		@$PHPMailer->ClearAllRecipients();
		@$PHPMailer->AddAddress($address_to, "$address_name");
		@$PHPMailer->Subject  = $sbsanitize->displayText($subject, 'ISO-8859-15');
		@$PHPMailer->AltBody  = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		@$PHPMailer->MsgHTML($sbsanitize->displayText($htmlcontent, 'ISO-8859-15'));
		//@$PHPMailer->MsgHTML  = $htmlcontent;
		//@$PHPMailer->WordWrap = 80;
		@$PHPMailer->IsHTML(true);
        
        // -----------------------------------
        // -------------- SMTP ---------------
        // -----------------------------------
        if (sbGetConfig('email_smtp') == '1') {
            @$PHPMailer->isSMTP();
            @$PHPMailer->Host = sbGetConfig('email_smtp_host');
            @$PHPMailer->SMTPAuth = (sbGetConfig('email_smtp_auth') == '1') ? true : false;
            if (sbGetConfig('email_smtp_port') != '') @$PHPMailer->Port = sbGetConfig('email_smtp_port');
            @$PHPMailer->Username = sbGetConfig('email_smtp_username');
            @$PHPMailer->Password = sbGetConfig('email_smtp_password');
            if (sbGetConfig('email_smtp_secure') != '') @$PHPMailer->SMTPSecure = sbGetConfig('email_smtp_secure') > 0; // optionnal (tls | starttls)
            if (sbGetConfig('email_smtp_debug') == '1') @$PHPMailer->SMTPDebug = SMTP::DEBUG_SERVER;
        }
	
		if ($attachment) {
			@$PHPMailer->AddAttachment($attachment);
		}
		//if ($attachment) {
		//	while(list($k,$v) = each($attachment)) {
		//		@$PHPMailer->AddAttachment($v['file'],$v['nickname']);
		//	}
		//}
	
		@$status = $PHPMailer->Send();
		@$PHPMailer->ClearAddresses();
		@$PHPMailer->ClearAttachments();
	
		if ($status) {
			return true;
		} else {
			//if (SBDEBUG) echo 'Email Error: ' . $PHPMailer->ErrorInfo . "\n";
			//die('Email Error: ' . $PHPMailer->ErrorInfo);
			return false;
		}
	}
	
	/**
	* Format email Default Value
	* $datas : Array for replace in body email
	* @return Formatted email
	*/
	function formatEmail($return_text, $datas) {
		# Url confirm email
		$confirmemail = SB_URL . 'account/activation/' . $datas['key_activation'] . '/confirmation';
		# Format email
		$return_text = @preg_replace("/#DATE#/", $datas['date'], $return_text);
		$return_text = @preg_replace("/#USERNAME#/", $datas['firstname'] . ' ' . $datas['lastname'], $return_text);
		$return_text = @preg_replace("/#FIRSTNAME#/", $datas['firstname'], $return_text);
		$return_text = @preg_replace("/#LASTNAME#/", $datas['lastname'], $return_text);
		$return_text = @preg_replace("/#NICKNAME#/", $datas['nickname'], $return_text);
		$return_text = @preg_replace("/#TELEPHONE#/", $datas['telephone'], $return_text);
		$return_text = @preg_replace("/#GROUP#/", $datas['group'], $return_text);
		$return_text = @preg_replace("/#EMAIL#/", $datas['email'], $return_text);
		$return_text = @preg_replace("/#ADDRESS#/", $datas['address'], $return_text);
		$return_text = @preg_replace("/#ADDRESS2#/", $datas['address2'], $return_text);
		$return_text = @preg_replace("/#CITY#/", $datas['city'], $return_text);
		$return_text = @preg_replace("/#ZIP#/", $datas['zip'], $return_text);
		$return_text = @preg_replace("/#COUNTRY#/", $datas['country'], $return_text);
		$return_text = @preg_replace("/#BUSINESSNAME#/", $datas['businessname'], $return_text);
		$return_text = @preg_replace("/#SIRET#/", $datas['siret'], $return_text);
		$return_text = @preg_replace("/#ACTIVITY#/", $datas['activity'], $return_text);
		$return_text = @preg_replace("/#TITLE#/", $datas['title'], $return_text);
		$return_text = @preg_replace("/#PRODUCT#/", $datas['product'], $return_text);
		$return_text = @preg_replace("/#CONFIRMEMAIL#/", "<a href='$confirmemail'>" . sprintf( _CMS_ACCOUNT_REGISTER_LINK_ACTIVATION, _AM_SITE_TITLE ) . "</a>", $return_text);
		$return_text = @preg_replace("/#PASSWORD#/", $datas['password'], $return_text);
		$return_text = @preg_replace("/#SITENAME#/", _AM_SITE_TITLE, $return_text);
		// --------------------------------------------------------------------------
		$return_text = @preg_replace("/#ORDER_ID#/", $datas['order_id'], $return_text);
		$return_text = @preg_replace("/#ORDER_DETAIL#/", $datas['order_detail'], $return_text);
		$return_text = @preg_replace("/#ORDER_PERSONALIZE_GIFT#/", $datas['order_gift_package'], $return_text);
		$return_text = @preg_replace("/#ORDER_PERSONALIZE_MESSAGE#/", $datas['order_write_message'], $return_text);
		$return_text = @preg_replace("/#ORDER_CLIENTNAME#/", $datas['order_clientname'], $return_text);
		$return_text = @preg_replace("/#ORDER_TOTAL#/", $datas['order_total'], $return_text);
		$return_text = @preg_replace("/#ORDER_TOTAL_CURRENCY#/", $datas['order_total_currency'], $return_text);
		$return_text = @preg_replace("/#ORDER_FACT#/", $datas['order_fact'], $return_text);
		$return_text = @preg_replace("/#ORDER_LIVR#/", $datas['order_livr'], $return_text);
		$return_text = @preg_replace("/#ORDER_PAYMENT#/", $datas['order_payment'], $return_text);
		$return_text = @preg_replace("/#ORDER_STATUS#/", $datas['order_status'], $return_text);
		$return_text = @preg_replace("/#ORDER_TEXT#/", $datas['order_text'], $return_text);
		// --------------------------------------------------------------------------
		$return_text = @preg_replace("/#ORDER_LINK_ACTIVITIES#/", $datas['order_link_activities'], $return_text);
		$return_text = @preg_replace("/#ORDER_UNIQUE_LINK#/", $datas['order_unique_link'], $return_text);
		$return_text = @preg_replace("/#ORDER_UNIQUE_ID_EBOX#/", $datas['order_unique_id_ebox'], $return_text);
		// --------------------------------------------------------------------------
		$return_text = @preg_replace("/#ORDER_DEBUG#/", $datas['order_debug'], $return_text);
		// --------------------------------------------------------------------------
		$return_text = @preg_replace("/#EURO#/", "&euro;", $return_text);
		// --------------------------------------------------------------------------
		# Return Formatted Email
		return $return_text;
	}
	
	/**
	 * Returns an encrypted & utf8-encoded
	 *
	 * @param	string	$text		encrypted string
	 * @param	string	$key 		decrypt key
	 * @param	string	$iv 		IV
	 * @param	integer	$bit_check	Bit
	 * @param	string	$tag		Salt key (DEPRECATED)
	 *
	 * @return string
	 */	
	function encrypt($text, $key = '(D$9=h!S2info$rS3+huY!NX', $iv = "fYAjHeXm", $bit_check = 32, $tag = "informatux") {
		// Check if php version smaller than 7.1.0
		if (version_compare(phpversion(), '7.1.0', '<')) {
			// All method
			$text_num = str_split($text, $bit_check);
			$text_num = $bit_check-strlen($text_num[count($text_num)-1]);
			
			for ($i=0; $i<$text_num; $i++) {
				$text = $text . chr($text_num);
			}
			
			$cipher = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');
			mcrypt_generic_init($cipher, $key, $iv);
			
			$decrypted = mcrypt_generic($cipher, $text);
			mcrypt_generic_deinit($cipher);
			
			return base64_encode($decrypted);
		} else {
			/* New method for php version 7.1 minimum
			 * $cipher     = "aes-128-gcm";
			 * $ivlen      = openssl_cipher_iv_length($cipher);
			 * $iv2        = openssl_random_pseudo_bytes($ivlen);
			 * $ciphertext = openssl_encrypt($text, $cipher, $key, $options=0, $iv2, $tag);
			 */
			// --- Remove the base64 encoding from our key
			$encryption_key = base64_decode($key);
			// --- Generate an initialization vector
			$iv2 = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
			// --- Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
			$encrypted = openssl_encrypt($text, 'aes-256-cbc', $encryption_key, 0, $iv2);
			// --- The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
			$encrypted_text = base64_encode($encrypted . '::' . $iv2);

			return $encrypted_text;		
		}


	}
	
	/**
	 * Returns decrypted original string
	 *
	 * @param	string	$encrypted_text		encrypted string
	 * @param	string	$key 				decrypt key
	 * @param	string	$iv 				IV
	 * @param	integer	$bit_check			Bit
	 * @param	string	$tag				Salt key (DEPRECATED)
	 *
	 * @return string
	 */	
	function decrypt($encrypted_text, $key = '(D$9=h!S2info$rS3+huY!NX', $iv = "fYAjHeXm", $bit_check = 32, $tag = "informatux") {
		// Check if php version smaller than 7.1.0
		if (version_compare(phpversion(), '7.1.0', '<')) {
			$cipher = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');
			mcrypt_generic_init($cipher, $key, $iv);
	
			$decrypted = mdecrypt_generic($cipher,base64_decode($encrypted_text));
			mcrypt_generic_deinit($cipher);
	
			$last_char = substr($decrypted,-1);
	
			for($i=0; $i<$bit_check-1; $i++) {
				if(chr($i) == $last_char) {
					$decrypted = substr($decrypted, 0, strlen($decrypted)-$i);
					break;
				}
			}
			return $decrypted;
		} else {
			/* New method for php version 7.2 minimum
			 * $cipher    = "aes-128-gcm"; // Or "AES-256-CFB"
			 * $ivlen     = openssl_cipher_iv_length($cipher);
			 * $iv2       = openssl_random_pseudo_bytes($ivlen);
			 * Store $cipher, $iv, and $tag for decryption later
			 * $decrypted = openssl_decrypt ($encrypted_text, $cipher, $key, $options=0, $iv2, $tag);
			 */
			// --- Remove the base64 encoding from our key
			$encryption_key = base64_decode($key);
			// --- To decrypt, split the encrypted data from our IV - our unique separator used was "::"
			list($encrypted_data, $iv2) = explode('::', base64_decode($encrypted_text), 2);
			$decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv2);
			return $decrypted;
		}
	}
	
}

?>
