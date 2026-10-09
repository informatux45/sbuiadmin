<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP EasyInstaller Free version                                           #
##  Developed by:  ApPHP <info@apphp.com>                                      #
##  License:       GNU LGPL v.3                                                #
##  Site:          http://www.apphp.com/php-easyinstaller/                     #
##  Copyright:     ApPHP EasyInstaller (c) 2009-2013. All rights reserved.     #
##                                                                             #
##  Additional modules (embedded):                                             #
##  -- jQuery (JavaScript Library)                           http://jquery.com #
##                                                                             #
################################################################################
   
    // Meme nom de session que l'administration (inc/sbsession.php) : sinon la
    // session admin n'est jamais reconnue par inc-auth-guard.php.
    require_once(__DIR__ . '/../../inc/sbsession.php');
    session_start();
    require_once('inc-auth-guard.php');

	require_once('include/shared.inc.php');    
    require_once('include/settings.inc.php');
	require_once('include/functions.inc.php');
	require_once('include/languages.inc.php');	

	$task = isset($_POST['task']) ? prepare_input($_POST['task']) : '';
	$passed_step = isset($_SESSION['passed_step']) ? (int)$_SESSION['passed_step'] : 0;
	$installation_type = isset($_SESSION['installation_type']) ? $_SESSION['installation_type'] : 'wizard';
	$program_already_installed = false;
	
	// handle previous installation
	// -------------------------------------------------
    //if(file_exists(EI_CONFIG_FILE_PATH)){ 
	//	$program_already_installed = true;
		//header('location: '.EI_APPLICATION_START_FILE);
        //exit;
	//}
	
	// handle previous steps
	// -------------------------------------------------
	if($passed_step >= 1){
		// OK
	}else{
		header('location: start.php');
		exit;				
	}

	// handle form submission
	// -------------------------------------------------
	if($task == 'send'){
		$_SESSION['passed_step'] = 2;
		header('location: database_settings.php');
		exit;
	}

    ob_start();    
	if(function_exists('phpinfo')) @phpinfo(-1);
	$phpinfo = array('phpinfo' => array());
	if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
	foreach($matches as $match){
		$array_keys = array_keys($phpinfo);
		$end_array_keys = end($array_keys);
		if(strlen($match[1])){
			$phpinfo[$match[1]] = array();
		}else if(isset($match[3])){
			$phpinfo[$end_array_keys][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
		}else{
			$phpinfo[$end_array_keys][] = $match[2];
		}
	}
	
	//echo '<pre>';
	//var_dump($phpinfo['mysqlnd']['mysqlnd']);
	//echo '</pre>';
	//exit;
	
	$is_error = false;
	$error_mg = array();
	if(EI_CHECK_PHP_MINIMUM_VERSION && (version_compare(phpversion(), EI_PHP_MINIMUM_VERSION, '<'))){	
		$is_error = true;
		$alert_min_version_php = lang_key('alert_min_version_php');
		$alert_min_version_php = str_replace('_PHP_VERSION_', EI_PHP_MINIMUM_VERSION, $alert_min_version_php);
		$alert_min_version_php = str_replace('_PHP_CURR_VERSION_', phpversion(), $alert_min_version_php);
		$error_mg[] = $alert_min_version_php;
	}
	if(EI_CHECK_CONFIG_DIR_WRITABILITY && !is_writable(EI_CONFIG_FILE_DIRECTORY)){
		$is_error = true;
		$error_mg[] = str_replace('_FILE_DIRECTORY_', EI_CONFIG_FILE_DIRECTORY, lang_key('alert_directory_not_writable'));
	}
	
	$php_core_index = ((version_compare(phpversion(), '5.3.0', '<'))) ? 'PHP Core' : 'Core';
	// [0] requred
	// [1] title
	// [2] condition
	// [3] true value
	// [4] false value
	// [5] error message
	$validations = array(
		'divider_system_info' => array('title'=>lang_key('getting_system_info'), 'description'=>''),
		
		'phpversion'   => array(true, lang_key('php_version'), function_exists('phpversion'), phpversion(), lang_key('unknown')),
		'system'       => array(true, lang_key('system'), isset($phpinfo['phpinfo']['System']), (isset($phpinfo['phpinfo']['System']) ? $phpinfo['phpinfo']['System'] : ''), lang_key('disabled')),
		//'architecture' => array(false, lang_key('system_architecture'), (isset($phpinfo['phpinfo']['Architecture'])), (isset($phpinfo['phpinfo']['Architecture']) ? $phpinfo['phpinfo']['Architecture'] : ''), lang_key('disabled')),
		//'build_date'   => array(false, lang_key('build_date'), isset($phpinfo['phpinfo']['Build Date']), (isset($phpinfo['phpinfo']['Build Date']) ? $phpinfo['phpinfo']['Build Date'] : ''), lang_key('disabled')),
		'server_api'   => array(false, lang_key('server_api'), isset($phpinfo['phpinfo']['Server API']), (isset($phpinfo['phpinfo']['Server API']) ? $phpinfo['phpinfo']['Server API'] : ''), lang_key('unknown')),
		
		'divider_php_settings' => array('title'=>lang_key('required_php_settings')),
		// --- MySQLi
		// ----------
		//'vd_support'   => array(false, lang_key('virtual_directory_support'), (isset($phpinfo['phpinfo']['Virtual Directory Support']) && $phpinfo['phpinfo']['Virtual Directory Support'] == 'enabled'), lang_key('enabled'), lang_key('disabled'), lang_key('error_vd_support')),
		//'asp_tags'     => array(false, lang_key('asp_tags'), (isset($phpinfo[$php_core_index]) && $phpinfo[$php_core_index]['asp_tags'][0] == 'On'), lang_key('on'), lang_key('off'), lang_key('error_asp_tags')),
		//'safe_mode'    => array(false, lang_key('safe_mode'), (isset($phpinfo[$php_core_index]) && $phpinfo[$php_core_index]['safe_mode'][0] == 'On'), lang_key('on'), lang_key('off')),
		// Off attendu (les gabarits ne s'en servent pas ; On peut casser du XML)
		'short_open_tag'  => array(false, lang_key('short_open_tag'), !(isset($phpinfo[$php_core_index]) && $phpinfo[$php_core_index]['short_open_tag'][0] == 'On'), lang_key('off'), lang_key('on')),
	);
	/// $database_system_version = isset($phpinfo['mysql']) ? $phpinfo['mysql']['Client API version'] : "unknown";

	if(EI_CHECK_MBSTRING_SUPPORT){
		$validations['mbstring_support'] = array(false, lang_key('mbstring_support'), function_exists('mb_detect_encoding'), lang_key('enabled'), lang_key('disabled'));
	}
	
	if(EI_CHECK_MAGIC_QUOTES){
		$validations['divider_magic_quotes'] = array('title'=>'', 'description'=>'');
		$validations['magic_quotes_gpc'] = array(false, lang_key('magic_quotes_gpc'), ini_get('magic_quotes_gpc'), lang_key('on'), lang_key('off'));
		$validations['magic_quotes_runtime'] = array(false, lang_key('magic_quotes_runtime'), ini_get('magic_quotes_runtime'), lang_key('on'), lang_key('off'));
		$validations['magic_quotes_sybase'] = array(false, lang_key('magic_quotes_sybase'), ini_get('magic_quotes_sybase'), lang_key('on'), lang_key('off'));
	}

	if(EI_CHECK_MAIL_SETTINGS){
		$validations['divider_smtp'] = array('title'=>'', 'description'=>'');
		$validations['smtp'] = array(false, lang_key('smtp'), ini_get('SMTP'), ini_get('SMTP'), lang_key('unknown'));
		$validations['smtp_port'] = array(false, lang_key('smtp_port'), ini_get('smtp_port'), ini_get('smtp_port'), lang_key('unknown'));
		$validations['sendmail_from'] = array(false, lang_key('sendmail_from'), ini_get('sendmail_from'), ini_get('sendmail_from'), lang_key('unknown'));
		$validations['sendmail_path'] = array(false, lang_key('sendmail_path'), ini_get('sendmail_path'), ini_get('sendmail_path'), lang_key('unknown'));
	}
	
	// -------------------------------------------------------------------
	// Prérequis SBUIADMIN (état des lieux 2026-10, CMS 4.11).
	// [0] requis (bloque l'installation) / false = recommandé
	// [2] true = OK, false = absent, null = non vérifiable (n'empêche rien)
	// -------------------------------------------------------------------
	$sb_ext = function ($name) { return extension_loaded($name); };
	$sb_bytes = function ($v) {
		$v = trim((string) $v); if ($v === '' || $v === '-1') return -1;
		$n = (float) $v; $u = strtolower(substr($v, -1));
		return (int) ($u === 'g' ? $n * 1073741824 : ($u === 'm' ? $n * 1048576 : ($u === 'k' ? $n * 1024 : $n)));
	};

	if(EI_CHECK_EXTENSIONS){
		$validations['php_recommended'] = array(false, 'Version PHP recommandée (8.5, version testée)', version_compare(PHP_VERSION, '8.5.0', '>='), PHP_VERSION, PHP_VERSION . ' (fonctionne, 8.5 conseillé)');

		$validations['divider_extensions'] = array('title' => 'Extensions PHP requises', 'description' => '');
		foreach (array(
			'mysqli'   => 'MySQLi (base de données du CMS)',
			'pdo_mysql'=> 'PDO MySQL (assistant d\'installation)',
			'session'  => 'Session',
			'json'     => 'JSON',
			'mbstring' => 'mbstring (texte UTF-8)',
			'sodium'   => 'sodium (chiffrement des secrets en base)',
			'openssl'  => 'OpenSSL (e-mails SMTP chiffrés, HTTPS sortant)',
			'curl'     => 'cURL (mises à jour, sitemap)',
			'gd'       => 'GD (vignettes et redimensionnement des images)',
			'fileinfo' => 'fileinfo (type réel des fichiers envoyés)',
			'filter'   => 'filter',
			'hash'     => 'hash',
		) as $sb_name => $sb_label) {
			$validations['ext_' . $sb_name] = array(true, $sb_label, $sb_ext($sb_name), lang_key('installed'), lang_key('not_installed'));
		}

		$validations['divider_extensions_reco'] = array('title' => 'Extensions PHP recommandées', 'description' => 'Facultatives : seule la fonction indiquée est privée si elles manquent.');
		foreach (array(
			'mysqlnd'  => 'mysqlnd (pilote MySQL natif)',
			'exif'     => 'EXIF (orientation des photos)',
			'zip'      => 'Zip (mise à jour automatique)',
			'intl'     => 'intl (dates et tri localisés)',
			'iconv'    => 'iconv (conversions de jeux de caractères)',
			'dom'      => 'DOM (sitemap, Page Builder)',
			'simplexml'=> 'SimpleXML',
		) as $sb_name => $sb_label) {
			$validations['ext_' . $sb_name] = array(false, $sb_label, $sb_ext($sb_name), lang_key('installed'), lang_key('not_installed'));
		}
		$validations['ext_memcache'] = array(false, 'Memcache ou Memcached (anti-flood de la connexion)', $sb_ext('memcache') || $sb_ext('memcached'), lang_key('installed'), lang_key('not_installed') . ' (anti-flood indisponible)');

		$validations['divider_php_ini'] = array('title' => 'Réglages PHP', 'description' => '');
		$sb_upload = $sb_bytes(ini_get('upload_max_filesize'));
		$sb_post   = $sb_bytes(ini_get('post_max_size'));
		$sb_mem    = $sb_bytes(ini_get('memory_limit'));
		$validations['ini_file_uploads'] = array(true, 'file_uploads (envoi de fichiers)', (bool) ini_get('file_uploads'), lang_key('on'), lang_key('off'));
		$validations['ini_upload_max']   = array(false, 'upload_max_filesize (8 Mo ou plus conseillé)', $sb_upload < 0 || $sb_upload >= 8388608, ini_get('upload_max_filesize'), ini_get('upload_max_filesize'));
		$validations['ini_post_max']     = array(false, 'post_max_size (au moins upload_max_filesize)', $sb_post < 0 || ($sb_upload >= 0 && $sb_post >= $sb_upload), ini_get('post_max_size'), ini_get('post_max_size'));
		$validations['ini_memory']       = array(false, 'memory_limit (128 Mo ou plus conseillé, traitement des images)', $sb_mem < 0 || $sb_mem >= 134217728, ini_get('memory_limit'), ini_get('memory_limit'));
		$validations['ini_display']      = array(false, 'display_errors désactivé (production)', !in_array(strtolower((string) ini_get('display_errors')), array('1', 'on', 'stdout'), true), lang_key('off'), lang_key('on'));
	}

	if(EI_CHECK_MODES){
		// Les modules Apache ne sont listables qu'avec mod_php : sinon on
		// vérifie leur effet en interrogeant le site lui-même (en reprenant
		// l'éventuelle authentification HTTP de la requête en cours).
		$validations['divider_modes'] = array('title' => 'Serveur web', 'description' => 'Requis par les .htaccess du site : mod_rewrite (sans lui, Apache répond par une erreur 500) et mod_headers (en-têtes de sécurité). Apache 2.4 conseillé, mod_access_compat inutile.');
		$sb_software = isset($_SERVER['SERVER_SOFTWARE']) ? (string) $_SERVER['SERVER_SOFTWARE'] : '';
		$sb_is_apache = stripos($sb_software, 'apache') !== false || function_exists('apache_get_modules');
		$validations['web_server'] = array(false, 'Apache (.htaccess)', $sb_is_apache ? true : ($sb_software === '' ? null : false), $sb_software !== '' ? htmlspecialchars($sb_software) : 'Apache', ($sb_software !== '' ? htmlspecialchars($sb_software) : lang_key('unknown')) . ' : protections .htaccess à reproduire');

		$sb_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
		$sb_site_url = $sb_https . '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') . rtrim(str_replace('\\', '/', dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])))), '/') . '/';
		$sb_admin_dir = basename(dirname(__DIR__));
		$sb_probe = function ($path) use ($sb_site_url) {
			if (!function_exists('curl_init')) return null;
			$ch = curl_init($sb_site_url . $path);
			curl_setopt_array($ch, array(CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => true, CURLOPT_NOBODY => false, CURLOPT_TIMEOUT => 5, CURLOPT_FOLLOWLOCATION => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => 0));
			if (isset($_SERVER['PHP_AUTH_USER'])) {
				curl_setopt($ch, CURLOPT_USERPWD, $_SERVER['PHP_AUTH_USER'] . ':' . (isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : ''));
			} elseif (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $_SERVER['HTTP_AUTHORIZATION']));
			}
			$raw = curl_exec($ch);
			$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$hsize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			if ($raw === false || $code === 0 || $code === 401) return null;
			return array('code' => $code, 'headers' => strtolower(substr($raw, 0, $hsize)));
		};
		$sb_modules = function_exists('apache_get_modules') ? apache_get_modules() : null;

		// .htaccess pris en compte (AllowOverride) : un fichier interdit doit répondre 403
		$sb_r = $sb_probe($sb_admin_dir . '/inc/admin/theme.txt');
		$validations['web_htaccess'] = array(true, '.htaccess pris en compte (AllowOverride All)', $sb_r === null ? null : ($sb_r['code'] === 403), lang_key('enabled'), $sb_r === null ? 'non vérifiable (accès HTTP impossible depuis le serveur)' : 'ignoré : fichiers protégés lisibles (code ' . $sb_r['code'] . ')');

		// mod_rewrite : la règle [F] sur .git répond 403 même si le dossier n'existe pas
		$sb_rw = $sb_modules !== null ? in_array('mod_rewrite', $sb_modules) : null;
		if ($sb_rw === null) { $sb_r = $sb_probe('.git/sbuiadmin-test'); $sb_rw = $sb_r === null ? null : ($sb_r['code'] === 403); }
		$validations['mod_rewrite'] = array(true, 'mod_rewrite', $sb_rw, lang_key('installed'), $sb_rw === null ? 'non vérifiable' : lang_key('not_installed'));

		// mod_headers : en-têtes de sécurité posés par le .htaccess racine
		$sb_hd = $sb_modules !== null ? in_array('mod_headers', $sb_modules) : null;
		if ($sb_hd === null) { $sb_r = $sb_probe(''); $sb_hd = $sb_r === null ? null : (strpos($sb_r['headers'], 'x-content-type-options') !== false); }
		$validations['mod_headers'] = array(true, 'mod_headers (en-têtes de sécurité)', $sb_hd, lang_key('installed'), $sb_hd === null ? 'non vérifiable' : lang_key('not_installed'));

		$validations['db_version_note'] = array(false, 'MySQL 5.7+ ou MariaDB 10.3+ (vérifié à l\'étape suivante)', null, '', 'vérifié à l\'étape « Paramètres database »');
	}

	if(EI_CHECK_DIRECTORIES_AND_FILES){
		$validations['divider_dirs_and_files'] = array('title'=>lang_key('directories_and_files'), 'description'=>'');
		//$validations['config_file_dir_1'] = array(true, EI_CONFIG_FILE_DIRECTORY, is_writable(EI_CONFIG_FILE_DIRECTORY), lang_key('writable'), lang_key('no_writable'));	
		// sbdbconfig.php (accès base) : premier emplacement inscriptible, dans
		// l'ordre exact de l'installation (fonctions du chargeur, sans migration)
		defined('SBUIADMIN_SETTINGS_NO_AUTOLOAD') or define('SBUIADMIN_SETTINGS_NO_AUTOLOAD', true);
		require_once(dirname(__DIR__) . '/inc/sbuiadmin-settings.php');
		$sb_req_where = false;
		foreach (sbDbConfigDirs() as $sb_req_dir) {
			if ($sb_req_dir[2] && @is_dir($sb_req_dir[0]) && @is_writable($sb_req_dir[0])) { $sb_req_where = $sb_req_dir[0]; break; }
		}
		$validations['config_file_dir_2'] = array(true, 'sbdbconfig.php (' . ($sb_req_where ? htmlspecialchars($sb_req_where) . (strpos($sb_req_where . '/', sbDbConfigSiteId() . '/') === 0 ? ', dans le site' : ', hors du site') : 'aucun dossier inscriptible') . ')', $sb_req_where !== false, lang_key('writable'), lang_key('no_writable'));
		$validations['config_file_dir_3'] = array(true, '../inc/admin/dashboard.txt', is_writable('../inc/admin/dashboard.txt'), lang_key('writable'), lang_key('no_writable'));
		$validations['config_file_dir_4'] = array(true, '../inc/admin/theme.txt', is_writable('../inc/admin/theme.txt'), lang_key('writable'), lang_key('no_writable'));
		$validations['config_file_dir_5'] = array(true, '../install.php', is_writable('../install.php'), lang_key('writable'), lang_key('no_writable'));
		$validations['cache_file_dir_1'] = array(true, '../datas/cache/core/', is_writable('../datas/cache/core/'), lang_key('writable'), lang_key('no_writable'));
		$validations['cache_file_dir_2'] = array(true, '../datas/cache/medias/', is_writable('../datas/cache/medias/'), lang_key('writable'), lang_key('no_writable'));
		$validations['cache_file_dir_3'] = array(true, '../datas/cache/tpls_c/', is_writable('../datas/cache/tpls_c/'), lang_key('writable'), lang_key('no_writable'));
		$validations['cache_file_dir_4'] = array(true, './installer/', is_writable('./installer/'), lang_key('writable'), lang_key('no_writable'));
	}

	// --- Header INC
	include_once('include/header.inc.php');
	
?>
<body>
<div id="main">
	<h1><?php echo lang_key('new_installation_of'); ?> <?php echo EI_APPLICATION_NAME.' '.EI_APPLICATION_VERSION;?>!</h1>
	<h2 class="sub-title"><?php echo lang_key('sub_title_message'); ?></h2>

	<div id="content">
		<?php if($installation_type == 'wizard'){ ?>
			<?php
				draw_side_navigation(2);		
			?>
			<div class="central-part">
			<h2><?php echo lang_key('step_2_of'); ?> - <?php echo lang_key('server_requirements'); ?></h2>

			<form action="server_requirements.php" method="post">
			<input type="hidden" name="task" value="send" />
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

				<?php
					$content = '';
					foreach($validations as $key => $val){
						$content .= '<tr>';							
						if(preg_match('/divider\_/i', $key)){
							if($val['title'] != ''){
								$content .= '<td colspan="2">';
								$content .= '<h3>'.$val['title'].'</h3>';
								if(!empty($val['description'])) $content .= '<p>'.$val['description'].'</p>';
								$content .= '</td>';	
							}else{
								$content .= '<td colspan="2" nowrap height="9px"></td>';
							}
						}else{
							$content .= '<td>&#8226; '.$val[1].': <i>'.(($val[2]) ? '<span class="found">'.$val[3].'</span>' : '<span class="disabled">'.$val[4].'</span>').'</i></td>';
							if($val[2] === null){
								// Non vérifiable : signalé, sans bloquer
								$content .= '<td><span style="color: #c67605;">à vérifier</span></td>';
							}elseif($val[0] == true && !$val[2]){
								$is_error = true;
								$error_mg[$key] = isset($val[5]) ? $val[5] : str_ireplace('_SETTINGS_NAME_', '<b>'.$key.'</b>', lang_key('error_server_requirements'));
								$content .= '<td><span class="failed">'.lang_key('failed').'!</span></td>';
							}elseif(!$val[2]){
								// Recommandé mais absent
								$content .= '<td><span style="color: #c67605;">recommandé</span></td>';
							}else{
								$content .= '<td><span class="passed">'.lang_key('passed').'</span></td>';	
							}
						}
						$content .= '</tr>'."\n";
					}				
				?>
				
				<?php
					if($is_error){
						echo '<div class="alert alert-error">';
						foreach($error_mg as $msg){
							echo $msg.'<br>';
						}
						echo '</div>';
					}
					if(!$is_error && $program_already_installed){
						echo '<div class="alert alert-warning">'.lang_key('alert_unable_to_install').'</div>';									
					}
				?>
				<table width="99%" cellspacing="2" cellpadding="0" border="0">
				<tbody>
				<?php echo $content; ?>
				</tbody>
				</table>
				
				<div class="buttons-wrapper">
					<a href="start.php" class="form_button" /><?php echo lang_key('back'); ?></a>
					<?php if(!$is_error){ ?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" class="form_button" name="btnSubmit" value="<?php echo lang_key('continue'); ?>" />
					<?php } ?>
				</div>
			</form>
			</div>
		<?php
			}else{			
				if(EI_ALLOW_MANUAL_INSTALLATION){
					echo '<div id="divManually">';
					echo '<div class="content">';
					include_once(EI_MANUAL_INSTALLATION_DIR.$arr_manual_installations[$curr_lang]);
					echo '</div>';
					echo '<div class="footer"><a href="start.php" class="form_button" title="'.lang_key('cancel_installation').'" />'.lang_key('back').'</a></div>';
					echo '</div>';
				}
			} 	
		?>			
		<div class="clear"></div>
	</div>
	
	<?php include_once('include/footer.inc.php'); ?>

</div>
</body>
</html>
