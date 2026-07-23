<?php
/* *********************************
 * @link https://informatux.com/   *
 * @package SBUIADMIN              *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ©SBUIADMIN.FR                   *
 * ******************************* */

// ––––––––––––––––––––––––––––––––––––––––––––––––––
// CONSTANTS - URL
// ––––––––––––––––––––––––––––––––––––––––––––––––––
$actual_link  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$redirect_url = pathinfo($actual_link, PATHINFO_DIRNAME);
define('SBUIADMIN_DEV_URL',              'https://client.informatux.com/');
define('SBUIADMIN_NET_INSTALL_DIR',      'master/net_install/');
define('SBUIADMIN_NET_INSTALL_URL',      SBUIADMIN_DEV_URL . SBUIADMIN_NET_INSTALL_DIR);
define('SBUIADMIN_NET_INSTALL_LATEST_URL', SBUIADMIN_DEV_URL . SBUIADMIN_NET_INSTALL_DIR . 'latest/');
define('SBUIADMIN_NET_INSTALL_STABLE_URL', SBUIADMIN_DEV_URL . SBUIADMIN_NET_INSTALL_DIR . 'stable/');

// Timeouts cURL (secondes)
define('SBUIADMIN_CURL_CONNECT_TIMEOUT', 15);   // connexion TCP
define('SBUIADMIN_CURL_TIMEOUT',         180);  // transfert total (zip peut être lourd)

// ––––––––––––––––––––––––––––––––––––––––––––––––––
// CONSTANTS - LANG FR
// ––––––––––––––––––––––––––––––––––––––––––––––––––
define('SBUIADMIN_TITLE_FR',               'Net Installation SBUIADMIN');
define('SBUIADMIN_INSTALL_SUCCESSFULLY_FR','Installation réussie ✓<br>Pensez à supprimer ce fichier netinstall.');
define('SBUIADMIN_DOWNLOAD_BUTTON_FR',     'Installer SBUIADMIN');
define('SBUIADMIN_GO_TO_ADMIN_FR',         'Accéder à l\'administration');
define('SBUIADMIN_SLOGAN_1_FR',            'Pensé pour vos mobiles');
define('SBUIADMIN_SLOGAN_2_FR',            'Styles conçus avec Bootstrap');
define('SBUIADMIN_SLOGAN_3_FR',            'Opérationnel immédiatement');

// ––––––––––––––––––––––––––––––––––––––––––––––––––
// CONSTANTS - LANG EN
// ––––––––––––––––––––––––––––––––––––––––––––––––––
define('SBUIADMIN_TITLE_EN',               'SBUIADMIN Net Installation');
define('SBUIADMIN_INSTALL_SUCCESSFULLY_EN','Installation complete ✓<br>Remember to delete this netinstall file.');
define('SBUIADMIN_DOWNLOAD_BUTTON_EN',     'Install SBUIADMIN');
define('SBUIADMIN_GO_TO_ADMIN_EN',         'Go to Administration');
define('SBUIADMIN_SLOGAN_1_EN',            'Built with mobile in mind');
define('SBUIADMIN_SLOGAN_2_EN',            'Styles designed with Bootstrap');
define('SBUIADMIN_SLOGAN_3_EN',            'Quick to start immediately');

// ––––––––––––––––––––––––––––––––––––––––––––––––––
// LANGUAGE DETECTION
// ––––––––––––––––––––––––––––––––––––––––––––––––––
$available_languages = ['en', 'fr'];
$browser_language    = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
$language            = in_array($browser_language, $available_languages) ? strtoupper($browser_language) : 'EN';

// ––––––––––––––––––––––––––––––––––––––––––––––––––
// HELPERS
// ––––––––––––––––––––––––––––––––––––––––––––––––––

/**
 * Suppression récursive d'un dossier, avec vérification d'existence.
 */
function rrmdir(string $src): void
{
    if (!is_dir($src)) return;
    $dir = opendir($src);
    while (false !== ($file = readdir($dir))) {
        if ($file === '.' || $file === '..') continue;
        $full = $src . '/' . $file;
        is_dir($full) ? rrmdir($full) : unlink($full);
    }
    closedir($dir);
    rmdir($src);
}

/**
 * Vérifie si cURL est disponible.
 */
function _is_curl_installed(): bool
{
    return function_exists('curl_version');
}

// ––––––––––––––––––––––––––––––––––––––––––––––––––
// INSTALLATION
// ––––––––––––––––––––––––––––––––––––––––––––––––––
$errors      = '';
$fatal_error = 0;
$do_install  = isset($_GET['a']) && $_GET['a'] === 'install';

if ($do_install) {

    /* --- Prérequis --- */
    if (!class_exists('ZipArchive')) {
        $errors      .= 'FATAL ERROR :: [CLASS] ZipArchive n\'est pas installé sur ce serveur.';
        $fatal_error++;
    }
    if (!_is_curl_installed()) {
        $errors      .= 'FATAL ERROR :: [FUNCTION] cURL n\'est pas installé sur ce serveur.';
        $fatal_error++;
    }

    if ($fatal_error === 0) {

        // Laisser le temps au script de terminer
        set_time_limit(SBUIADMIN_CURL_TIMEOUT + 30);

        $zipFile     = 'sbuiadmin_latest.zip';
        $url         = SBUIADMIN_NET_INSTALL_LATEST_URL . $zipFile;
        $zipResource = fopen($zipFile, 'w');

        if ($zipResource === false) {
            $errors .= '[FILE] Impossible de créer le fichier temporaire ' . $zipFile . '. Vérifiez les permissions d\'écriture.';
        } else {

            // --- Téléchargement cURL ---
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_FAILONERROR    => true,
                CURLOPT_HEADER         => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => SBUIADMIN_CURL_CONNECT_TIMEOUT,
                CURLOPT_TIMEOUT        => SBUIADMIN_CURL_TIMEOUT,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FILE           => $zipResource,
            ]);

            $success = curl_exec($ch);
            $curlErr = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            fclose($zipResource);

            if (!$success || $curlErr) {
                $errors .= '[CURL] Erreur de téléchargement' . "\n"
                         . 'URL    : ' . $url . "\n"
                         . 'HTTP   : ' . $httpCode . "\n"
                         . 'Détail : ' . ($curlErr ?: 'inconnue');
                @unlink($zipFile);
            } elseif (!file_exists($zipFile) || filesize($zipFile) < 1024) {
                $errors .= '[CURL] Le fichier téléchargé est vide ou corrompu' . "\n"
                         . 'URL    : ' . $url . "\n"
                         . 'Taille : ' . (file_exists($zipFile) ? filesize($zipFile) : 0) . ' octets';
                @unlink($zipFile);
            } else {

                // --- Extraction (sans le dossier racine de l'archive ex: sbuiadmin-master/) ---
                $zip        = new ZipArchive();
                $openResult = $zip->open($zipFile);

                if ($openResult !== true) {
                    $errors .= '[UNZIP] Impossible d\'ouvrir le fichier ZIP (code : ' . $openResult . ').';
                } else {
                    $destPath = dirname(__FILE__) . DIRECTORY_SEPARATOR;

                    // Déterminer le préfixe du dossier racine (ex: "sbuiadmin-master/")
                    $rootPrefix = '';
                    if ($zip->numFiles > 0) {
                        $firstEntry = $zip->getNameIndex(0);
                        // Si la première entrée est un dossier, c'est notre préfixe
                        if (substr($firstEntry, -1) === '/') {
                            $rootPrefix = $firstEntry;
                        } else {
                            $parts = explode('/', $firstEntry);
                            if (count($parts) > 1) {
                                $rootPrefix = $parts[0] . '/';
                            }
                        }
                    }

                    // Extraire fichier par fichier en retirant le préfixe racine
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $entryName = $zip->getNameIndex($i);

                        // Ignorer le dossier racine lui-même et __MACOSX
                        if ($entryName === $rootPrefix) continue;
                        if (strpos($entryName, '__MACOSX') !== false) continue;

                        // Retirer le préfixe racine
                        $relativePath = $rootPrefix !== ''
                            ? substr($entryName, strlen($rootPrefix))
                            : $entryName;

                        if ($relativePath === '' || $relativePath === false) continue;

                        $targetPath = $destPath . $relativePath;

                        if (substr($entryName, -1) === '/') {
                            // C'est un dossier
                            if (!is_dir($targetPath)) {
                                mkdir($targetPath, 0755, true);
                            }
                            chmod($targetPath, 0755);
                        } else {
                            // C'est un fichier — créer le dossier parent si besoin
                            $targetDir = dirname($targetPath);
                            if (!is_dir($targetDir)) {
                                mkdir($targetDir, 0755, true);
                            }
                            $stream = $zip->getStream($entryName);
                            if ($stream !== false) {
                                file_put_contents($targetPath, stream_get_contents($stream));
                                fclose($stream);
                                chmod($targetPath, 0644);
                            }
                        }
                    }

                    $zip->close();
                }

                @unlink($zipFile);
                rrmdir('__MACOSX');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo strtolower($language); ?>">
<head>
    <meta charset="utf-8">
    <title>SBUIADMIN Net Install</title>
    <meta name="description" content="SBUIADMIN, le CMS By INFORMATUX">
    <meta name="author" content="INFORMATUX">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        /* ── Reset minimal ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 62.5%; }

        /* ── Tokens ── */
        :root {
            --c-bg:        #0d1117;
            --c-surface:   #161b22;
            --c-border:    #30363d;
            --c-accent:    #4f9cf9;
            --c-accent-dk: #1f6feb;
            --c-success:   #3fb950;
            --c-error:     #f85149;
            --c-text:      #e6edf3;
            --c-muted:     #8b949e;
            --r:           12px;
            --font-display: 'Space Grotesk', sans-serif;
            --font-body:    'Inter', sans-serif;
        }

        body {
            background: var(--c-bg);
            color: var(--c-text);
            font-family: var(--font-body);
            font-size: 1.5em;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* ── Card ── */
        .card {
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            border-radius: var(--r);
            width: 100%;
            max-width: 520px;
            padding: 4rem 4rem 3.5rem;
            text-align: center;
        }

        /* ── Logo / Badge ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: rgba(79,156,249,.12);
            border: 1px solid rgba(79,156,249,.3);
            border-radius: 99px;
            padding: 0.4rem 1.2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--c-accent);
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 2.4rem;
        }
        .badge::before {
            content: '';
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--c-accent);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: .3; }
        }

        /* ── Title ── */
        h1 {
            font-family: var(--font-display);
            font-size: 3.2rem;
            font-weight: 700;
            letter-spacing: -.03em;
            color: var(--c-text);
            margin-bottom: .8rem;
        }
        .subtitle {
            font-size: 1.4rem;
            color: var(--c-muted);
            margin-bottom: 3.2rem;
        }

        /* ── Button principal ── */
        .btn-install {
            display: inline-flex;
            align-items: center;
            gap: .8rem;
            background: var(--c-accent);
            color: #fff;
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 600;
            padding: 1.2rem 3rem;
            border-radius: var(--r);
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background .2s, transform .15s;
            margin-bottom: 3.2rem;
        }
        .btn-install:hover  { background: var(--c-accent-dk); transform: translateY(-1px); }
        .btn-install:active { transform: translateY(0); }

        /* ── Progress ── */
        .progress-wrap {
            margin-bottom: 3.2rem;
        }
        .progress-label {
            font-size: 1.3rem;
            color: var(--c-muted);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
        }
        .progress-bar {
            height: 6px;
            background: var(--c-border);
            border-radius: 99px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--c-accent), #a5d8ff);
            border-radius: 99px;
            animation: progress-anim 3s ease-in-out infinite;
        }
        @keyframes progress-anim {
            0%   { width: 5%;   opacity: 1; }
            70%  { width: 85%;  opacity: 1; }
            90%  { width: 92%;  opacity: .8; }
            100% { width: 95%;  opacity: 1; }
        }
        .spinner {
            width: 22px; height: 22px;
            border: 3px solid rgba(79,156,249,.2);
            border-top-color: var(--c-accent);
            border-radius: 50%;
            animation: spin .7s linear infinite;
            display: inline-block;
            vertical-align: middle;
            margin-right: .6rem;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── État succès ── */
        .state-success {
            margin-bottom: 3.2rem;
        }
        .checkmark {
            width: 56px; height: 56px;
            background: rgba(63,185,80,.12);
            border: 2px solid var(--c-success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.6rem;
            font-size: 2.4rem;
        }
        .state-success h2 {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 600;
            color: var(--c-success);
            margin-bottom: .6rem;
        }
        .state-success p {
            font-size: 1.3rem;
            color: var(--c-muted);
        }
        .btn-admin {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            background: var(--c-success);
            color: #fff;
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 600;
            padding: 1rem 2.4rem;
            border-radius: var(--r);
            text-decoration: none;
            transition: opacity .2s;
            margin-top: 1.6rem;
        }
        .btn-admin:hover { opacity: .85; }

        /* ── État erreur ── */
        .state-error {
            background: rgba(248,81,73,.08);
            border: 1px solid rgba(248,81,73,.3);
            border-radius: var(--r);
            padding: 1.6rem 2rem;
            text-align: left;
            margin-bottom: 2.4rem;
        }
        .state-error .error-title {
            font-family: var(--font-display);
            font-weight: 600;
            color: var(--c-error);
            font-size: 1.4rem;
            margin-bottom: .6rem;
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .state-error .error-body {
            font-size: 1.25rem;
            color: var(--c-muted);
            font-family: 'Courier New', monospace;
            word-break: break-word;
            white-space: pre-wrap;
        }

        /* ── Feature pills ── */
        .features {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            padding-top: 2.4rem;
            border-top: 1px solid var(--c-border);
            margin-top: .4rem;
        }
        .feature {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255,255,255,.04);
            border: 1px solid var(--c-border);
            border-radius: 99px;
            padding: .5rem 1.2rem;
            font-size: 1.2rem;
            color: var(--c-muted);
        }
        .feature span { font-size: 1.4rem; }

        /* ── Visibility helpers ── */
        .hidden { display: none !important; }
    </style>
</head>

<body>

    <div class="card">

        <!-- Badge -->
        <div class="badge">SBUIADMIN</div>

        <!-- Titre -->
        <h1><?php echo constant('SBUIADMIN_TITLE_' . $language); ?></h1>
        <p class="subtitle">Version latest &mdash; <?php echo date('Y'); ?></p>

        <?php if ($do_install && $errors === ''): ?>
        <!-- ══ ÉTAT : SUCCÈS ══ -->
        <div class="state-success">
            <div class="checkmark">✓</div>
            <h2><?php $language === 'FR' ? print('Installation réussie') : print('Installation complete'); ?></h2>
            <p><?php echo constant('SBUIADMIN_INSTALL_SUCCESSFULLY_' . $language); ?></p>
            <a class="btn-admin" href="./backdoor/index.php">
                <?php echo constant('SBUIADMIN_GO_TO_ADMIN_' . $language); ?> →
            </a>
        </div>

        <?php elseif ($do_install && $errors !== ''): ?>
        <!-- ══ ÉTAT : ERREUR ══ -->
        <div class="state-error">
            <div class="error-title">⚠ <?php $language === 'FR' ? print('Erreur d\'installation') : print('Installation error'); ?></div>
            <div class="error-body"><?php echo htmlspecialchars($errors); ?></div>
        </div>
        <a class="btn-install" href="<?php echo basename(__FILE__); ?>?a=install">
            ↺ <?php $language === 'FR' ? print('Réessayer') : print('Retry'); ?>
        </a>

        <?php else: ?>
        <!-- ══ ÉTAT : PRÊT ══ -->
        <a id="btn-install"
           class="btn-install"
           href="<?php echo basename(__FILE__); ?>?a=install"
           onclick="startInstall(event)">
            ⬇ <?php echo constant('SBUIADMIN_DOWNLOAD_BUTTON_' . $language); ?>
        </a>

        <!-- Progress (masqué jusqu'au clic) -->
        <div id="progress-wrap" class="progress-wrap hidden">
            <div class="progress-label">
                <span><span class="spinner"></span><?php $language === 'FR' ? print('Téléchargement en cours…') : print('Downloading…'); ?></span>
                <span id="progress-timeout"></span>
            </div>
            <div class="progress-bar"><div class="progress-bar-fill"></div></div>
        </div>

        <?php endif; ?>

        <!-- Feature pills -->
        <div class="features">
            <div class="feature"><span>📱</span> <?php echo constant('SBUIADMIN_SLOGAN_1_' . $language); ?></div>
            <div class="feature"><span>🎨</span> <?php echo constant('SBUIADMIN_SLOGAN_2_' . $language); ?></div>
            <div class="feature"><span>⚡</span> <?php echo constant('SBUIADMIN_SLOGAN_3_' . $language); ?></div>
        </div>

    </div>

    <script>
    (function () {
        var TIMEOUT_SEC = <?php echo SBUIADMIN_CURL_TIMEOUT; ?>;

        function startInstall(e) {
            var btn  = document.getElementById('btn-install');
            var wrap = document.getElementById('progress-wrap');
            var tick = document.getElementById('progress-timeout');

            if (!btn || !wrap) return; // laisse le lien fonctionner normalement

            btn.classList.add('hidden');
            wrap.classList.remove('hidden');

            // Compte à rebours indicatif
            var elapsed = 0;
            var timer = setInterval(function () {
                elapsed++;
                var remaining = TIMEOUT_SEC - elapsed;
                if (remaining <= 0) {
                    clearInterval(timer);
                    tick.textContent = '';
                } else {
                    tick.textContent = remaining + 's';
                }
            }, 1000);
        }

        // Expose globalement pour le onclick inline
        window.startInstall = startInstall;
    })();
    </script>

</body>
</html>
