<?php
//------------------------------------------------------------------------------ 
//*** French (fr)
//------------------------------------------------------------------------------ 

$arrLang = array();

$arrLang['alert_admin_email_wrong'] = "Email Admin n'a pas le bon format! Recommencer.";
$arrLang['alert_min_version_db'] = "SBUIADMIN nécessite au moins la version _DB_VERSION_ de _DB_ installé (version actuelle _DB_CURR_VERSION_). Vous ne pouvez pas continuer l'installation.";
$arrLang['alert_min_version_php'] = "SBUIADMIN nécessite au moins la version _PHP_VERSION_ de PHP (version actuelle _PHP_CURR_VERSION_). Vous ne pouvez pas continuer l'installation.";
$arrLang['alert_directory_not_writable'] = "Le répertoire <b>_FILE_DIRECTORY_</b> n'est pas ouvert en écriture ! <br />Vous devez accorder les permissions d'écriture (Droit d'accès 0755 or 777, dépends de votre configuration système) à ce répertoire avant de continuer l'installation !";
$arrLang['alert_extension_not_installed'] = "Extension pdo_".EI_DATABASE_TYPE." n'est pas installée sur votre serveur ! Vous ne pouvez pas continuer l'installation.";
$arrLang['alert_settings_path_upload_wrong'] = "Répertoire d'upload de vos médias obligatoire.";
$arrLang['alert_settings_recaptcha_public_wrong'] = "Clé publique Google reCaptcha obligatoire.";
$arrLang['alert_settings_recaptcha_private_wrong'] = "Clé privée Google reCaptcha obligatoire.";
$arrLang['alert_unable_to_install'] = "Unable to install this application because an application with the same identity is already installed. <br>You may only <b>Update</b> or <b>Uninstall</b> it. Make sure you have a backup of your database before proceeding.";
$arrLang['alert_required_fields'] = "Les champs marqués d'un astérisque sont obligatoires";
$arrLang['alert_db_host_empty'] = "Hôte de la Database ne peut pas être vide ! Recommencer.";
$arrLang['alert_db_name_empty'] = "Nom de la Database ne peut pas être vide ! Recommencer.";
$arrLang['alert_db_username_empty'] = "Utilisateur de la Database ne peut pas être vide ! Recommencer.";
$arrLang['alert_db_password_empty'] = "Database password ne peut pas être vide ! Recommencer.";
$arrLang['alert_admin_username_empty'] = "Utilisateur Admin ne peut pas être vide ! Recommencer.";
$arrLang['alert_admin_password_empty'] = "Mot de passe Admin ne peut pas être vide ! Recommencer.";
$arrLang['alert_wrong_testing_parameters'] = "Paramètres de test incorrect! Entrer des paramètres valides.";
$arrLang['alert_remove_files'] = "Pour des raisons de sécurité, supprimer le répertoire <b>install/</b> de votre serveur !";
$arrLang['alert_wrong_parameter_passed'] = "Paramètres erronés ! Revenez à l'étape précédente puis réessayer.";

$arrLang['error_asp_tags'] = "This installation requires <b>ASP tags</b> settings turned ON.";
$arrLang['error_can_not_open_config_file'] = "la Database a été créé ! Cannot open configuration file _CONFIG_FILE_PATH_ to save info.";
$arrLang['error_can_not_read_file'] = "Could not read file <b>_SQL_DUMP_FILE_</b>! Please check if a file exists.";
$arrLang['error_check_db_exists'] = "Database connection error! Please check if your database exists and access allowed for user <b>_DATABASE_USERNAME_</b>._ERROR_<br />";
$arrLang['error_check_db_connection'] = "Database connecting error! Please check your connection parameters._ERROR_<br />";
$arrLang['error_mysqli_support'] = "Cette installation requiert <b>l'extension MYSQLi</b>.";
$arrLang['error_mysqlnd_support'] = "Cette installation requiert <b>l'extension MYSQLnd</b>.";
$arrLang['error_pdo_support'] = "Cette installation requiert <b>l'extension PDO</b>.";
$arrLang['error_sql_executing'] = "Erreur d'execution SQL ! Veuillez activer le mode debug et vérifier soigneusement la syntaxe de votre fichier dump SQL.";
$arrLang['error_server_requirements'] = "Cette installation requiert que le paramètre _SETTINGS_NAME_ soit activé (ON) ou installé.";
$arrLang['error_vd_support'] = "Cette installation requiert que 'Virtual Directory support' soit à ON.";

$arrLang['admin_access_data'] = "Infos configuration";
$arrLang['admin_access_data_descr'] = "(you need this to enter the protected admin area)";
$arrLang['administrator_account'] = "Configuration";
$arrLang['administrator_account_skipping'] = "Ignorer (Le compte Admin n'est pas obligatoire)";
$arrLang['asp_tags'] = "Tags Asp";
$arrLang['back'] = "Retour";
$arrLang['build_date'] = "Build Date";
$arrLang['cancel_installation'] = "Arrêter l'installation";
$arrLang['click_start_button'] = "Cliquer sur le bouton Start button pour continuer";
$arrLang['click_to_start_installation'] = "Cliquer pour démarrer l'installation";
$arrLang['checked'] = "Vérifié";
$arrLang['complete'] = "Terminé";
$arrLang['complete_installation'] = "Installation terminée";
$arrLang['completed'] = "Terminé";
$arrLang['continue'] = "Continuer";
$arrLang['continue_installation'] = "Continuer l'installation";
$arrLang['database_extension'] = "Extension Database";
$arrLang['database_host'] = "Hôte Database";
$arrLang['database_host_info'] = "Nom d'hôte ou adresse IP du serveur de base de données. Le serveur de base de données peut prendre la forme d'un nom d'hôte (et/ou d'une adresse de port), tel que db1.myserver.com, ou localhost, ou comme une adresse IP, tel que 192.168.0.1";
$arrLang['database_import'] = "Importation de la Database";
$arrLang['database_import_error'] = "Import Database (erreur)";
$arrLang['database_name'] = "Nom de la Database";
$arrLang['database_name_info'] = "Nom de la  Database. La base de données utilisée pour contenir les données. Un exemple de nom de base de données est 'test'.";
$arrLang['database_username'] = "Utilisateur de la Database";
$arrLang['database_username_info'] = "Utilisateur de la Database. Le nom d'utilisateur utilisé pour se connecter au serveur de base de données. Un exemple de nom d'utilisateur est 'test_db'.";
$arrLang['database_password'] = "Mot de passe de la Database";
$arrLang['database_password_info'] = "Mot de passe de la Database. Le mot de passe est utilisé avec le nom d'utilisateur, qui forme le compte utilisateur de la base de données.";
$arrLang['database_prefix'] = "Préfixe de la Database";
$arrLang['database_prefix_info'] = "Préfixe de la Database. Utilisé pour définir le préfixe unique pour les tables de base de données et empêcher un type de données d'interférer avec un autre. Un exemple de préfixe de base de données est '6Tg4z_'.";
$arrLang['database_settings'] = "Paramètres database";
$arrLang['directories_and_files'] = "Répertoires et fichiers";
$arrLang['disabled'] = "désactivé";
$arrLang['enabled'] = "activé";
$arrLang['error'] = "Erreur";
$arrLang['extensions'] = "Extensions";
$arrLang['getting_system_info'] = "Informations système";
$arrLang['file_successfully_rewritten'] = "Le fichier de configuration a été ré-écrit et la Database mise à jour.";
$arrLang['file_successfully_deleted'] = "Le fichier de configuration a été supprimé et la Database supprimée.";
$arrLang['file_successfully_created'] = "Le fichier de configuration a été créé.<br>La base de données a été créé.";
$arrLang['failed'] = "échec ";
$arrLang['folder_paths'] = "Folder Paths";
$arrLang['follow_the_wizard'] = "Suivre l'<b>assistant</b> pour l'installation";
$arrLang['installed'] = "installé";
$arrLang['installation_completed'] = "Installation réussie !";
$arrLang['installation_in_progress'] = "Installation en cours";
$arrLang['installation_guide'] = "SBUIADMIN installation";
$arrLang['installation_type'] = "Type d'installation";
$arrLang['language'] = "Langage";
$arrLang['license'] = "Licence";
$arrLang['loading'] = "chargement";
$arrLang['mbstring_support'] = "Multibyte String Support";
$arrLang['magic_quotes_gpc'] = "Magic Quotes for GPC (Get/Post/Cookie)";
$arrLang['magic_quotes_runtime'] = "Magic Quotes Runtime";
$arrLang['magic_quotes_sybase'] = "Magic Quotes are in Sybase-style";
$arrLang['mode'] = "Mode";
$arrLang['modes'] = "Modes";
$arrLang['mysqli_support'] = "MySQLi Support (CMS)";
$arrLang['mysqlnd_support'] = "MySQLnd Support";
$arrLang['new_installation_of'] = "Installation du CMS";
$arrLang['new'] = "Nouveau";
$arrLang['no'] = "Non";
$arrLang['no_writable'] = "pas ouvert en écriture";
$arrLang['not_installed'] = "pas installé";
$arrLang['off'] = "Off";
$arrLang['ok'] = "OK";
$arrLang['on'] = "On";
$arrLang['passed'] = "réussi";
$arrLang['password_encryption'] = "Password Encryption";
$arrLang['perform_manual_installation'] = "Effectuer une installation <b>Manuelle</b>";
$arrLang['pdo_support'] = "PDO Support (Installeur)";
$arrLang['php_version'] = "PHP Version";
$arrLang['proceed_to_login_page'] = "Aller sur l'administration";
$arrLang['ready_to_install'] = "Prêt pour l'installation";
$arrLang['remove_configuration_button'] = "Remove Configuration and Start Over";
$arrLang['required_php_settings'] = "Paramètres PHP requis";
$arrLang['safe_mode'] = "Safe Mode";
$arrLang['select_installation_language'] = "Choisissez la langue";
$arrLang['select_installation_type'] = "Choisissez le type d'installation";
$arrLang['sendmail_from'] = "Sendmail From";
$arrLang['sendmail_path'] = "Sendmail Path";
$arrLang['server_api'] = "Server API";
$arrLang['server_requirements'] = "Requis Serveur";
$arrLang['session_support'] = "Session Support";
$arrLang['settings_customer_name'] = "Nom de votre client (Admin Login)";
$arrLang['settings_customer_name_info'] = "Nom de votre client utilisé comme lien dans votre administration pour aller sur votre site et utilisé également dans la balise 'TITLE' en html (référencement).";
$arrLang['settings_customer_url'] = "Url du site client (http<span style='color: red; font-weight: bold;'>s</span>://...) avec <span style='color: red; font-weight: bold;'>/</span> final";
$arrLang['settings_customer_url_info'] = "L'url de votre site client avec obligatoirement un <span style='color: red; font-weight: bold;'>/</span> à la fin de votre url.";
$arrLang['settings_path_upload'] = "Répertoire d'uploads des médias";
$arrLang['settings_path_upload_info'] = "Utiliser pour le répertoire d'uploads de vos médias, un chemin relatif tel que '../upload'. Vous pouvez modifier ce chemin à tout moment dans l'administration.";
$arrLang['settings_recaptcha_public'] = "Clé <a href='https://www.google.com/recaptcha/admin' target='_blank'>Google INVISIBLE reCAPTCHA</a> : Clé publique";
$arrLang['settings_recaptcha_public_info'] = "Clé Google INVISIBLE reCAPTCHA publique a indiqué. Créer cette clé depuis la console admin de votre compte Google Recaptcha.";
$arrLang['settings_recaptcha_private'] = "Clé <a href='https://www.google.com/recaptcha/admin' target='_blank'>Google INVISIBLE reCAPTCHA</a> : Clé secrète";
$arrLang['settings_recaptcha_private_info'] = "Clé Google INVISIBLE reCAPTCHA privée a indiqué. Créer cette clé depuis la console admin de votre compte Google Recaptcha.";
$arrLang['settings_url_upload'] = "Url d'uploads des médias (http<span style='color: red; font-weight: bold;'>s</span>://..../upload)";
$arrLang['settings_url_upload_info'] = "Url d'uploads de vos médias. Peut être modifié à tout instant depuis l'administration également.";
$arrLang['short_open_tag'] = "Short Open Tag";
$arrLang['smtp'] = "SMTP";
$arrLang['smtp_port'] = "SMTP Port";
$arrLang['start'] = "Démarrer";
$arrLang['start_all_over'] = "Start All Over";
$arrLang['start_all_over_text'] = "If you want to remove this installation for some reason, you can force the Installer to remove current configuration and start all over again. <br><b>WARNING</b>: You have to undo the database installation manually to remove all changes that were done.";
$arrLang['step_1_of'] = "Etape 1 de 6";
$arrLang['step_2_of'] = "Etape 2 de 6";
$arrLang['step_3_of'] = "Etape 3 de 6";
$arrLang['step_4_of'] = "Etape 4 de 6";
$arrLang['step_5_of'] = "Etape 5 de 6";
$arrLang['step_6_of'] = "Etape 6 de 6";
$arrLang['sub_title_message'] = "Cet assistant vous guidera tout au long du processus d'installation";
$arrLang['system'] = "System";
$arrLang['system_architecture'] = "Architecture système";
$arrLang['test_connection'] = "Test de connexion";
$arrLang['test_database_connection'] = "Test de connexion à la base de données";
$arrLang['unknown'] = "Inconnu";
$arrLang['uninstall'] = "Uninstall";
$arrLang['uninstallation_completed'] = "Uninstallation Completed!";
$arrLang['update'] = "Mise à jour";
$arrLang['updating_completed'] = "Mise à jour effectuée !";
$arrLang['virtual_directory_support'] = "Virtual Directory Support";
$arrLang['we_are_ready_to_installation'] = "Nous sommes prêt pour l'installation";
$arrLang['we_are_ready_to_installation_text'] = "A cette étape, l'assistant de configuration tente de créer toutes les tables de base de données requises et de les remplir avec des données. <br>Si quelque chose ne va pas, retournez à l'étape 'Paramètres database' et assurez-vous que toutes les informations que vous avez saisies sont correctes.";
$arrLang['writable'] = "ouvert en écriture";
    
?>