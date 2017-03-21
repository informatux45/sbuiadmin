<?php

/**
 * 
 * Please read the LICENSE file
 * @author Vadim V. Gabriel <vadimg88@gmail.com> http://www.vadimg.co.il/
 * @package Class Installer
 * @version 1.0.0a
 * @license GNU Lesser General Public License
 * 
 */

/** Load the template class **/
require_once(INSTALLER_PATH . '/Installer_Template.php');


/**
 * Class installer
 *
 */
class Installer extends sanitize
{
    /**
     * Options property
     *
     * @var array
     */
    protected $_options = array();

    /**
     * View object
     *
     * @var object
     */
    protected $view;

    /**
     * Language array
     *
     * @var array
     */
    protected $lang = array();

    protected $default_lang = 'french';

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        # Do we have a cookie
        if(isset($_COOKIE['lang']) && $_COOKIE['lang'] != '')
        {
            $this->default_lang = $_COOKIE['lang'];
        }
        
        # Change language
        if(isset($_POST['lang']) && $_POST['lang'] != '' && $this->default_lang != $_POST['lang'])
        {
            $path = INSTALLER_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $_POST['lang'] . '.php';
            if(file_exists($path))
            {
                $this->default_lang = $_POST['lang'];
                @setcookie('lang', $this->default_lang, time() + 60 * 60 * 24);
                $_POST['lang'] = 0;
                $this->nextStep('index');
            }
        }

        # Load the language file
        require_once( INSTALLER_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $this->default_lang . '.php' );
        $this->lang = $lang;

        # Load the template class
        $this->view = new Installer_Template($this->lang);

        # Did we run it again?
        if(file_exists(INSTALLER_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'installer.lock'))
        {
            $this->view->error($this->lang['L-01']);
        }

        $allwed_steps = array('index' => 'indexAction', 'check' => 'checkAction', 'db' => 'dbAction', 'cfg' => 'configAction', 'database' => 'dbTables', 'config' => 'configWrite', 'finish' => 'finishInstaller');
        if(!in_array($_POST['step'], array_keys($allwed_steps)))
        {
            $this->nextStep('index');
        }

        # Display the right step
        $this->$allwed_steps[$_POST['step']]($_POST);
    }

    /**
     * Show welcome message
     *
     */
    public function indexAction()
    {

        $options = '';
        foreach($this->buildLangSelect() as $lang)
        {
            if ($lang == 'french')
                $options .= "<option value='{$lang}' selected=\"\">".ucfirst($lang)."</option>";
            else            
                $options .= "<option value='{$lang}'>".ucfirst($lang)."</option>";
        }

        $this->view->vars = array('options' => $options);
        $this->view->render('index');
    }

    /**
     * Show check server configuration
     *
     */
    public function checkAction()
    {
        // PHP Version
        $version_php_min     = '7.0.1';
        $version_php_current = explode('-',PHP_VERSION);
        $phpversion_color    = (version_compare(phpversion(), $version_php_min, ">=")) ? 'green' : 'red';

        // Directories / Files permission
        $cachecore   = dirname(ADMIN_PATH . 'datas/core/');
        $cachemedias = dirname(ADMIN_PATH . 'datas/medias/');
        $cachetplsc  = dirname(ADMIN_PATH . 'datas/tpls_c/');
        //$incadmin_h  = ADMIN_PATH . 'inc/admin/.htpasswd';
        $incadmin_d  = ADMIN_PATH . 'inc/admin/dashboard.txt';
        $incadmin_s  = ADMIN_PATH . 'inc/admin/settings.txt';
        // Yes
        $writable_true  = "<strong style='color: green'>OUI</strong>";
        // No
        $writable_false = "<strong style='color: red'>NON</strong>";

        $this->view->vars = array('phpversion_server'   => $version_php_min,
                                  'phpversion_current'  => $version_php_current[0],
                                  'phpversion_color'    => $phpversion_color,
                                  'cachecore'           => (is_writable($cachecore)) ? $writable_true : $writable_false,
                                  'cachemedias'         => (is_writable($cachemedias)) ? $writable_true : $writable_false,
                                  'cachetplsc'          => (is_writable($cachetplsc)) ? $writable_true : $writable_false,
                                  //'incadmin_htpasswd'   => (is_writable($incadmin_h)) ? $writable_true : $writable_false,
                                  'incadmin_dashboard'  => (is_writable($incadmin_d)) ? $writable_true : $writable_false,
                                  'incadmin_settings'   => (is_writable($incadmin_s)) ? $writable_true : $writable_false,
                                 );
        $this->view->render('check');
    }
    
    /**
     * Show database setup stuff
     *
     */
    public function dbAction()
    {
        $this->view->render('db');
    }
    

    public function configAction(array $options)
    {
        # Pass in to the options property
        $this->_options = $options;
        
        $current_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $sbmagic_url = str_replace('administration/install/install.php', '', $current_url);
        
        $this->view->vars = array('dbhost' => $this->_options['dbhost'],
                                  'dbuser' => $this->_options['dbuser'],
                                  'dbpass' => $this->_options['dbpass'],
                                  'dbname' => $this->_options['dbname'],
                                  'dbprefix' => $this->_options['dbprefix'],
                                  'urlcustomer' => $sbmagic_url,
                                  'urluploads' => $sbmagic_url . 'upload/',
                                 );
        $this->view->render('config');
    }

    /**
     * Handle DB table quries
     *
     * @param array $options
     */
    public function dbTables(array $options)
    {
        # Make sure we have an array of options
        if(!is_array($options))
        {
            $this->view->error($this->lang['L-02']);
        }

        # Make sure we have everything we need!
        $required_db_options = array('dbname', 'dbuser', 'dbpass', 'dbhost', 'dbprefix');

        foreach ($required_db_options as $required_db_option)
        {
            if(!isset($options[$required_db_option]))
            {
                $this->view->error($this->lang['L-03']);
            }
        }

        # Pass in to the options property
        $this->_options = $options;

        # First test the connection
        $link = mysql_connect($this->_options['dbhost'], $this->_options['dbuser'], $this->_options['dbpass']);
        if(!$link)
        {
            $this->view->error($this->lang['L-04']);
        }

        # Select the DB
        $db_selected = mysql_select_db($this->_options['dbname'], $link);
        if(!$db_selected && !$this->_options['create_database'])
        {
            $this->view->error($this->lang['L-05']);
        }
        elseif (!$db_selected && $this->_options['create_database'])
        {
            # Create the DB
            $result = mysql_query("CREATE DATABASE {$this->_options['dbname']}", $link);
            if (!$result)
            {
                $this->view->error(sprintf($this->lang['L-06'], $this->_options['dbname'], htmlspecialchars(mysql_error())));
            }
        }

        # Load the database stuff
        define('_AM_CREATE_DB_PREFIX', $this->_options['dbprefix']);
        $path = INSTALLER_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'database.php' ;
        if(!file_exists($path))
        {
            $this->view->error(sprintf($this->lang['L-07'], $path));
        }

        $SQL = array();
        require_once($path);

        $count = 0;
        $errors_count = 0;

        # Loop if we have any
        if(count($SQL))
        {
            # Start the count

            $errors = array();
            foreach ($SQL as $query)
            {
                $result = mysql_query($query, $link);
                if (!$result)
                {
                    $errors[] = sprintf($this->lang['L-08'], htmlspecialchars(mysql_error()));
                    $errors_count++;
                    continue;
                }

                # Increase it
                $count++;
            }
        }

        $error_string = '';

        # Did we had any errors?
        if(count($errors))
        {
            $error_string = "<br /><br />".sprintf($this->lang['I-14'], implode("<br /><br />", $errors));
        }

        # Redirect
        $this->view->vars = array('message'  => sprintf($this->lang['L-09'], $count, $errors_count, $error_string),
                                  'button'   => $error_string ? $this->lang['I-16'] : $this->lang['I-03'],
                                  'dbhost'   => $this->_options['dbhost'],
                                  'dbuser'   => $this->_options['dbuser'],
                                  'dbpass'   => $this->_options['dbpass'],
                                  'dbname'   => $this->_options['dbname'],
                                  'dbprefix' => $this->_options['dbprefix'],
                                  );
        $this->view->render('dbdone');

    }

    /**
     * Display everything
     *
     */
    public function display()
    {
        $this->view->display();
    }

    /**
     * Write the configuration File
     *
     */
    public function configWrite()
    {
        # One level up
        $settings_file = ADMIN_PATH . 'inc/admin/settings.txt';
        $htaccess_file = ADMIN_PATH . 'htaccess';
        $install_file  = ADMIN_PATH . 'install.php';
        
        // Injection des données
        $output_file  = $this->displayText($_POST['customer_name'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('admin', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText($_POST['dbhost'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText($_POST['dbname'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText($_POST['dbuser'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText($_POST['dbpass'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText($_POST['diruploads'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('2MB', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText(' ', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('0', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('0', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('0', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('jpg,jpg,png,pdf,xml,txt,mp4', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText($_POST['urluploads'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('20', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText($_POST['url_customer'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('1', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('0', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('1024', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText($_POST['recaptcha_public'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText($_POST['recaptcha_secret'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText($_POST['dbprefix'], 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('1', 'UTF-8', 1, 0) . "\n";
        $output_file .= $this->displayText('1', 'UTF-8', 1, 0) . "\n";
        
        // Locker le fichier pour qu'une seule personne a la fois ecrive dedans
        $result_edit = file_put_contents($settings_file, $output_file, FILE_USE_INCLUDE_PATH | LOCK_EX);
        
        // Ecrire le fichier htacess
        $output_htaccess  = 'AuthUserFile ' . ADMIN_PATH . 'inc/admin/.htpasswd' . "\n";
        $output_htaccess .= 'AuthName "ADMINISTRATION"' . "\n";
        $output_htaccess .= 'AuthType Basic' . "\n\n";
        $output_htaccess .= '<limit GET POST>' . "\n";
        $output_htaccess .= 'require valid-user' . "\n";
        $output_htaccess .= '</limit>' . "\n\n";
        // -------------------------
        $output_htaccess  = '# Prevent viewing of .htaccess file' . "\n";
        $output_htaccess .= '<Files .htaccess>' . "\n";
        $output_htaccess .= 'order allow,deny' . "\n";
        $output_htaccess .= 'deny from all' . "\n";
        $output_htaccess .= '</Files>' . "\n\n";
        $output_htaccess .= '# Prevent directory listings' . "\n";
        $output_htaccess .= 'Options All -Indexes' . "\n";

        // Locker le fichier pour qu'une seule personne a la fois ecrive dedans
        $result_edit = file_put_contents($htaccess_file, $output_htaccess, FILE_USE_INCLUDE_PATH | LOCK_EX);
        
        // Supprimer le fichier INSTALL.PHP
        @unlink($install_file);
        
        $this->finishInstaller($_POST);
    }

    /**
     * Finish the installer proccess
     *
     */
    public function finishInstaller($posts)
    {
        # Pass in to the options property
        $this->_posts = $posts;

        # Lock the installer
        $path = INSTALLER_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'installer.lock';
        file_put_contents($path, "installer lock file");
        
        $this->view->vars = array('link_admin' => str_replace('/install/install.php' , '', BASE_URL),
                                  'Link_website' => $this->_posts['url_customer'],
                                  );

        $this->view->render('finish');
        
        clearstatcache();
    }

    /**
     * Redirect to the next step
     *
     * @param string $step
     */
    public function nextStep($step)
    {
        $url = BASE_URL . '?step='.$step;
        if(!headers_sent())
        {
            header('Location: '. $url);
            exit;
        }

        print "<html><body><meta http-equiv='refresh' content='1;url={$url}'></body></html>";
        exit;
    }

    /**
     * Redirect screen with a message
     *
     * @param string $message
     */
    public function redirectScreen($message, $step)
    {
        $this->view->redirect($message, $step);
    }

    /**
     * Build the language select box
     *
     */
    public function buildLangSelect()
    {
        $path = INSTALLER_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'lang';
        $dirs = scandir($path);
        $files = array();
        foreach($dirs as $file)
        {
            if ($file == '.' || $file == '..')
            {
                continue;
            }
            elseif (is_dir($path.'/'.$file))
            {
                continue;
            }
            else
            {
                $files[] = str_replace('.php', '', $file);
            }
        }

        return $files;
    }

    /**
     * Destructor
     *
     */
    public function __destruct()
    {


        @mysql_close($this->db);
        # Clear
        unset($this);
    }

}