<?php
/* *********************************
 * @link https://informatux.com/   *
 * @package SBUIADMIN              *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ©SBUIADMIN.FR                   *
 * ******************************* */

/*
 * Installation de SBUIADMIN depuis GitHub (dernière version publiée).
 *
 * À déposer SEUL dans le dossier (vide) du futur site, puis à ouvrir dans le
 * navigateur. Rien de ce qui vient du réseau n'est accepté sans preuve :
 *  - HTTPS avec certificat vérifié, vers GitHub uniquement (redirections
 *    comprises) ;
 *  - le manifeste de la version (version, nom, taille et SHA-256 de
 *    l'archive) doit porter une signature Ed25519 valide pour la clé publique
 *    écrite ci-dessous (la clé privée n'est jamais sur GitHub) ;
 *  - l'archive doit avoir exactement la taille et l'empreinte annoncées ;
 *  - chaque entrée de l'archive est contrôlée avant toute écriture (pas de
 *    chemin absolu, de « .. », de lien symbolique, ni de fichier hors du
 *    dossier) ; taille totale et nombre de fichiers bornés ;
 *  - refus si SBUIADMIN est déjà installé ici ; envoi par formulaire avec
 *    jeton ; ce fichier se supprime après une installation réussie.
 */

// ––––––––––––––––––––––––––––––––––––––––––––––––––
// CONSTANTES
// ––––––––––––––––––––––––––––––––––––––––––––––––––
const SBNI_REPO        = 'informatux45/sbuiadmin';
// Clé publique de signature des versions (Ed25519, base64)
const SBNI_PUBLIC_KEY  = 'MFcedhT1D+i3Ej3abNnOs6Mz4EjIIPo7hwc0azE/uoY=';
// Version minimale acceptée (empêche de se faire servir une ancienne version signée)
const SBNI_MIN_VERSION = '4.12';
const SBNI_MIN_PHP     = '8.4.0';
const SBNI_MANIFEST    = 'sbuiadmin-release.json';
const SBNI_ROOT        = 'sbuiadmin/';           // dossier racine dans l'archive
const SBNI_MAX_ZIP     = 64 * 1024 * 1024;       // taille maximale de l'archive
const SBNI_MAX_UNZIP   = 512 * 1024 * 1024;      // taille décompressée maximale
const SBNI_MAX_FILES   = 20000;
const SBNI_HOSTS       = array('api.github.com', 'github.com', 'objects.githubusercontent.com', 'release-assets.githubusercontent.com');
const SBNI_CONNECT_TIMEOUT = 15;
const SBNI_TIMEOUT     = 180;

// ––––––––––––––––––––––––––––––––––––––––––––––––––
// FONCTIONS
// ––––––––––––––––––––––––––––––––––––––––––––––––––

/** Téléchargement HTTPS vérifié, limité à GitHub et à $max octets */
function sbniGet(string $url, int $max, ?string $toFile = null): string
{
	$fh = null;
	if ($toFile !== null && ($fh = fopen($toFile, 'wb')) === false) throw new RuntimeException('[FILE] Fichier temporaire impossible à créer');
	$ch = curl_init($url);
	$body = '';
	$size = 0;
	curl_setopt_array($ch, array(
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_MAXREDIRS      => 5,
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_SSL_VERIFYHOST => 2,
		CURLOPT_FAILONERROR    => true,
		CURLOPT_CONNECTTIMEOUT => SBNI_CONNECT_TIMEOUT,
		CURLOPT_TIMEOUT        => SBNI_TIMEOUT,
		CURLOPT_USERAGENT      => 'SBUIADMIN-netinstall',
		CURLOPT_HTTPHEADER     => array('Accept: application/vnd.github+json, application/octet-stream;q=0.9, */*;q=0.1'),
		CURLOPT_WRITEFUNCTION  => function ($ch, $chunk) use (&$body, &$size, $fh, $max) {
			$size += strlen($chunk);
			if ($size > $max) return 0; // interrompt le transfert
			if ($fh) return fwrite($fh, $chunk);
			$body .= $chunk;
			return strlen($chunk);
		},
	));
	// HTTPS seulement, y compris pour les redirections
	if (defined('CURLOPT_PROTOCOLS_STR')) {
		curl_setopt($ch, CURLOPT_PROTOCOLS_STR, 'https');
		curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS_STR, 'https');
	} else {
		curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
		curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS);
	}
	$ok    = curl_exec($ch);
	$err   = curl_error($ch);
	$final = (string) curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	if ($fh) fclose($fh);
	if ($size > $max) throw new RuntimeException('[HTTP] Réponse trop volumineuse : ' . $url);
	if (!$ok) throw new RuntimeException('[HTTP] Téléchargement impossible : ' . $url . "\n" . $err);
	if (!sbniAllowedUrl($final)) throw new RuntimeException('[HTTP] Redirection hors de GitHub refusée : ' . $final);
	return $body;
}

/** URL https vers un hôte GitHub autorisé ? */
function sbniAllowedUrl(string $url): bool
{
	$p = parse_url($url);
	return is_array($p) && ($p['scheme'] ?? '') === 'https' && in_array(strtolower($p['host'] ?? ''), SBNI_HOSTS, true);
}

/** Dernière version publiée : adresses du manifeste, de sa signature et des archives */
function sbniLatestRelease(): array
{
	$json = json_decode(sbniGet('https://api.github.com/repos/' . SBNI_REPO . '/releases/latest', 1024 * 1024), true);
	if (!is_array($json) || empty($json['assets']) || !is_array($json['assets'])) throw new RuntimeException('[GITHUB] Aucune version publiée trouvée');
	$assets = array();
	foreach ($json['assets'] as $a) {
		if (isset($a['name'], $a['browser_download_url']) && sbniAllowedUrl($a['browser_download_url'])) $assets[$a['name']] = $a['browser_download_url'];
	}
	if (!isset($assets[SBNI_MANIFEST], $assets[SBNI_MANIFEST . '.sig'])) throw new RuntimeException('[GITHUB] La version publiée n\'a pas de manifeste signé : installation refusée');
	return $assets;
}

/**
 * Vérifie la signature du manifeste puis son contenu.
 * @return array manifeste décodé
 */
function sbniVerifyManifest(string $manifest, string $sigB64, string $pubB64 = SBNI_PUBLIC_KEY, string $minVersion = SBNI_MIN_VERSION): array
{
	$sig = base64_decode(trim($sigB64), true);
	$pub = base64_decode($pubB64, true);
	if ($sig === false || strlen($sig) !== SODIUM_CRYPTO_SIGN_BYTES || $pub === false || strlen($pub) !== SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES
		|| !sodium_crypto_sign_verify_detached($sig, $manifest, $pub)) {
		throw new RuntimeException('[SIGNATURE] Signature du manifeste invalide : archive non authentique, installation refusée');
	}
	$m = json_decode($manifest, true);
	if (!is_array($m) || ($m['name'] ?? '') !== 'sbuiadmin'
		|| !preg_match('/^\d+\.\d+(\.\d+)?$/', (string) ($m['version'] ?? ''))
		|| !preg_match('/^sbuiadmin-\d+\.\d+(\.\d+)?\.zip$/', (string) ($m['file'] ?? ''))
		|| !is_int($m['size'] ?? null) || $m['size'] <= 0 || $m['size'] > SBNI_MAX_ZIP
		|| !preg_match('/^[0-9a-f]{64}$/', (string) ($m['sha256'] ?? ''))) {
		throw new RuntimeException('[MANIFESTE] Manifeste signé mais incomplet ou invalide');
	}
	if (version_compare($m['version'], $minVersion, '<')) {
		throw new RuntimeException('[MANIFESTE] Version ' . $m['version'] . ' plus ancienne que la version minimale ' . $minVersion . ' : refusée');
	}
	return $m;
}

/** L'archive a-t-elle exactement la taille et l'empreinte du manifeste ? */
function sbniCheckArchive(string $file, array $m): void
{
	clearstatcache(true, $file);
	if (!is_file($file) || filesize($file) !== $m['size'] || !hash_equals($m['sha256'], hash_file('sha256', $file))) {
		throw new RuntimeException('[ARCHIVE] L\'archive ne correspond pas au manifeste signé (taille ou SHA-256) : installation refusée');
	}
}

/**
 * Contrôle toutes les entrées avant d'écrire quoi que ce soit.
 * @return array index => chemin relatif (dossiers terminés par /)
 */
function sbniPlan(ZipArchive $zip): array
{
	if ($zip->numFiles < 1 || $zip->numFiles > SBNI_MAX_FILES) throw new RuntimeException('[ARCHIVE] Nombre de fichiers anormal : ' . $zip->numFiles);
	$plan  = array();
	$total = 0;
	for ($i = 0; $i < $zip->numFiles; $i++) {
		$st = $zip->statIndex($i);
		$name = is_array($st) ? (string) $st['name'] : '';
		if ($name === SBNI_ROOT) continue;
		if (strpos($name, SBNI_ROOT) !== 0) throw new RuntimeException('[ARCHIVE] Entrée hors du dossier ' . SBNI_ROOT . ' : ' . $name);
		$rel = substr($name, strlen(SBNI_ROOT));
		if ($rel === '' || strpos($rel, "\0") !== false || strpos($rel, '\\') !== false || strpos($rel, ':') !== false || $rel[0] === '/') {
			throw new RuntimeException('[ARCHIVE] Nom d\'entrée refusé : ' . $name);
		}
		foreach (explode('/', rtrim($rel, '/')) as $seg) {
			if ($seg === '' || $seg === '.' || $seg === '..') throw new RuntimeException('[ARCHIVE] Chemin refusé : ' . $name);
		}
		// Liens symboliques (attributs Unix) refusés
		if ($zip->getExternalAttributesIndex($i, $opsys, $attr) && $opsys === ZipArchive::OPSYS_UNIX && ((($attr >> 16) & 0170000) === 0120000)) {
			throw new RuntimeException('[ARCHIVE] Lien symbolique refusé : ' . $name);
		}
		$total += (int) $st['size'];
		if ($total > SBNI_MAX_UNZIP) throw new RuntimeException('[ARCHIVE] Taille décompressée trop importante');
		$plan[$i] = $rel;
	}
	return $plan;
}

/** Écrit les entrées contrôlées dans $dest (jamais ce fichier lui-même) */
function sbniExtract(ZipArchive $zip, array $plan, string $dest): int
{
	$dest = rtrim($dest, '/') . '/';
	$self = realpath(__FILE__);
	$n = 0;
	foreach ($plan as $i => $rel) {
		$target = $dest . $rel;
		if (substr($rel, -1) === '/') {
			if (!is_dir($target) && !mkdir($target, 0755, true)) throw new RuntimeException('[FICHIER] Dossier impossible à créer : ' . $rel);
			continue;
		}
		$dir = dirname($target);
		if (!is_dir($dir) && !mkdir($dir, 0755, true)) throw new RuntimeException('[FICHIER] Dossier impossible à créer : ' . dirname($rel));
		if (is_link($target) || ($self && realpath($target) === $self)) continue;
		$in = $zip->getStream($zip->getNameIndex($i));
		if ($in === false) throw new RuntimeException('[ARCHIVE] Entrée illisible : ' . $rel);
		$out = fopen($target, 'wb');
		if ($out === false) { fclose($in); throw new RuntimeException('[FICHIER] Écriture impossible : ' . $rel); }
		stream_copy_to_stream($in, $out);
		fclose($in);
		fclose($out);
		chmod($target, 0644);
		$n++;
	}
	return $n;
}

/** SBUIADMIN déjà présent dans ce dossier ? */
function sbniAlreadyInstalled(string $dir): bool
{
	return is_file($dir . '/sbconfig.php') || is_file($dir . '/header.php') || is_dir($dir . '/backdoor');
}

// Chargé par un harnais de tests : fonctions seules
if (defined('SBNI_LIBRARY')) return;

// ––––––––––––––––––––––––––––––––––––––––––––––––––
// LANGUE
// ––––––––––––––––––––––––––––––––––––––––––––––––––
$available_languages = ['en', 'fr'];
$browser_language    = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
$language            = in_array($browser_language, $available_languages) ? strtoupper($browser_language) : 'EN';
$t = function (string $fr, string $en) use ($language) { return $language === 'FR' ? $fr : $en; };

// ––––––––––––––––––––––––––––––––––––––––––––––––––
// INSTALLATION
// ––––––––––––––––––––––––––––––––––––––––––––––––––
session_name('sbnetinstall');
session_start();
if (empty($_SESSION['sbni_token'])) $_SESSION['sbni_token'] = bin2hex(random_bytes(16));

$dest       = __DIR__;
$errors     = '';
$installed  = null; // manifeste de la version installée
$do_install = ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
$already    = sbniAlreadyInstalled($dest);

if ($do_install) {
	$tmpZip = null;
	try {
		if (!hash_equals($_SESSION['sbni_token'], (string) ($_POST['token'] ?? ''))) throw new RuntimeException('[FORMULAIRE] Jeton invalide : rechargez la page');
		if ($already) throw new RuntimeException('[SITE] SBUIADMIN est déjà installé dans ce dossier : supprimez ce fichier netinstall');
		if (version_compare(PHP_VERSION, SBNI_MIN_PHP, '<')) throw new RuntimeException('[PHP] PHP ' . SBNI_MIN_PHP . ' minimum requis (version actuelle : ' . PHP_VERSION . ')');
		foreach (array('ZipArchive' => class_exists('ZipArchive'), 'cURL' => function_exists('curl_init'), 'sodium' => function_exists('sodium_crypto_sign_verify_detached')) as $ext => $present) {
			if (!$present) throw new RuntimeException('[PHP] Extension ' . $ext . ' manquante sur ce serveur');
		}
		if (!is_writable($dest)) throw new RuntimeException('[FICHIER] Le dossier ' . $dest . ' n\'est pas inscriptible');
		set_time_limit(SBNI_TIMEOUT * 2);

		// 1. Manifeste signé de la dernière version
		$assets   = sbniLatestRelease();
		$manifest = sbniGet($assets[SBNI_MANIFEST], 64 * 1024);
		$sig      = sbniGet($assets[SBNI_MANIFEST . '.sig'], 4 * 1024);
		$m        = sbniVerifyManifest($manifest, $sig);
		if (!isset($assets[$m['file']])) throw new RuntimeException('[GITHUB] Archive ' . $m['file'] . ' absente de la version publiée');

		// 2. Archive : taille et empreinte du manifeste
		$tmpZip = tempnam(sys_get_temp_dir(), 'sbni');
		sbniGet($assets[$m['file']], $m['size'], $tmpZip);
		sbniCheckArchive($tmpZip, $m);

		// 3. Contrôle de toutes les entrées, puis écriture
		$zip = new ZipArchive();
		if (($rc = $zip->open($tmpZip, ZipArchive::RDONLY)) !== true) throw new RuntimeException('[ARCHIVE] Ouverture impossible (code ' . $rc . ')');
		$plan = sbniPlan($zip);
		sbniExtract($zip, $plan, $dest);
		$zip->close();
		$installed = $m;
	} catch (Throwable $e) {
		$errors = $e->getMessage();
	}
	if ($tmpZip) @unlink($tmpZip);
	if ($installed) {
		unset($_SESSION['sbni_token']);
		@unlink(__FILE__); // plus rien à faire ici : ce fichier ne doit pas rester sur le site
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
        .verified {
            margin-top: 1.6rem;
            text-align: left;
            font-size: 1.2rem;
            color: var(--c-muted);
            background: rgba(255,255,255,.03);
            border: 1px solid var(--c-border);
            border-radius: var(--r);
            padding: 1.2rem 1.6rem;
            word-break: break-all;
        }
        .verified b { color: var(--c-text); font-weight: 600; }
        .notice { font-size: 1.25rem; color: var(--c-muted); margin-bottom: 2.4rem; }
        form { display: inline; }
    </style>
</head>

<body>

    <div class="card">

        <!-- Badge -->
        <div class="badge">SBUIADMIN</div>

        <!-- Titre -->
        <h1><?php echo $t('Net Installation SBUIADMIN', 'SBUIADMIN Net Installation'); ?></h1>
        <p class="subtitle"><?php echo $t('Dernière version publiée sur GitHub, signature vérifiée', 'Latest release from GitHub, signature verified'); ?></p>

        <?php if ($installed): ?>
        <!-- ══ ÉTAT : SUCCÈS ══ -->
        <div class="state-success">
            <div class="checkmark">✓</div>
            <h2><?php echo $t('Installation réussie', 'Installation complete'); ?></h2>
            <p><?php echo $t('Ce fichier netinstall a été supprimé. Étape suivante : l\'assistant d\'installation vous demandera une <strong>clé d\'installation</strong>. Ouvrez sur votre serveur (FTP, SSH ou gestionnaire de fichiers de l\'hébergeur) le fichier <code>backdoor/install/installer/install-key.php</code> et copiez la clé écrite après <code>KEY:</code>. Elle prouve que c\'est bien vous qui installez : personne d\'autre ne peut prendre la main sur votre site pendant l\'installation.', 'This netinstall file has been deleted. Next step: the installation wizard will ask for an <strong>installation key</strong>. Open on your server (FTP, SSH or your host\'s file manager) the file <code>backdoor/install/installer/install-key.php</code> and copy the key written after <code>KEY:</code>. It proves you are the one installing: nobody else can take over your site during installation.'); ?></p>
            <div class="verified">
                <b>SBUIADMIN <?php echo htmlspecialchars($installed['version']); ?></b> (<?php echo htmlspecialchars($installed['tag'] ?? ''); ?>, commit <?php echo htmlspecialchars(substr((string) ($installed['commit'] ?? ''), 0, 7)); ?>)<br>
                <?php echo $t('Signature Ed25519 vérifiée', 'Ed25519 signature verified'); ?> ✓<br>
                SHA-256 : <?php echo htmlspecialchars($installed['sha256']); ?>
            </div>
            <a class="btn-admin" href="./backdoor/index.php">
                <?php echo $t('Lancer l\'installation', 'Start the installation'); ?> →
            </a>
        </div>

        <?php elseif ($already): ?>
        <!-- ══ ÉTAT : DÉJÀ INSTALLÉ ══ -->
        <div class="state-error">
            <div class="error-title">⚠ <?php echo $t('SBUIADMIN est déjà installé ici', 'SBUIADMIN is already installed here'); ?></div>
            <div class="error-body"><?php echo $t('Supprimez ce fichier netinstall du serveur : il ne doit pas rester sur un site en service.', 'Delete this netinstall file from the server: it must not stay on a live site.'); ?></div>
        </div>

        <?php else: ?>
            <?php if ($errors !== ''): ?>
            <!-- ══ ÉTAT : ERREUR ══ -->
            <div class="state-error">
                <div class="error-title">⚠ <?php echo $t('Erreur d\'installation', 'Installation error'); ?></div>
                <div class="error-body"><?php echo htmlspecialchars($errors); ?></div>
            </div>
            <?php endif; ?>

            <!-- ══ ÉTAT : PRÊT ══ -->
            <p class="notice"><?php echo $t('Rien n\'est installé sans vérification : HTTPS vers GitHub, signature de la version et empreinte de l\'archive.', 'Nothing is installed unverified: HTTPS to GitHub, release signature and archive checksum.'); ?></p>
            <form method="post" action="<?php echo htmlspecialchars(basename(__FILE__)); ?>" onsubmit="startInstall()">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['sbni_token']); ?>">
                <button id="btn-install" class="btn-install" type="submit">
                    <?php echo $errors !== '' ? '↺ ' . $t('Réessayer', 'Retry') : '⬇ ' . $t('Installer SBUIADMIN', 'Install SBUIADMIN'); ?>
                </button>
            </form>

            <!-- Progress (masqué jusqu'au clic) -->
            <div id="progress-wrap" class="progress-wrap hidden">
                <div class="progress-label">
                    <span><span class="spinner"></span><?php echo $t('Téléchargement et vérification…', 'Downloading and verifying…'); ?></span>
                    <span id="progress-timeout"></span>
                </div>
                <div class="progress-bar"><div class="progress-bar-fill"></div></div>
            </div>
        <?php endif; ?>

        <!-- Feature pills -->
        <div class="features">
            <div class="feature"><span>🔒</span> <?php echo $t('Version signée', 'Signed release'); ?></div>
            <div class="feature"><span>🎨</span> <?php echo $t('Styles conçus avec Bootstrap', 'Styles designed with Bootstrap'); ?></div>
            <div class="feature"><span>⚡</span> <?php echo $t('Opérationnel immédiatement', 'Quick to start immediately'); ?></div>
        </div>

    </div>

    <script>
    (function () {
        var TIMEOUT_SEC = <?php echo SBNI_TIMEOUT; ?>;

        function startInstall() {
            var btn  = document.getElementById('btn-install');
            var wrap = document.getElementById('progress-wrap');
            var tick = document.getElementById('progress-timeout');
            if (!btn || !wrap) return;

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

        window.startInstall = startInstall;
    })();
    </script>

</body>
</html>
