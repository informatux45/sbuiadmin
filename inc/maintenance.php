<?php
/**
 * Page de maintenance du site public
 *
 * Mode Maintenance actif (Configuration > Générale) : index.php affiche
 * cette page À L'ADRESSE DEMANDÉE, sans redirection, en HTTP 503 (Retry-After,
 * noindex, sans cache). À la réouverture, un simple rafraîchissement rend la
 * page demandée. Contenu : CMS Config > Maintenance (réglages coming-soon-*).
 *
 * Passent quand même : l'URL d'accès ?d=<code> (pour la session) et les
 * utilisateurs connectés au back-office (double authentification validée).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SB_PATH') or die('Are you crazy!');

/**
 * Le visiteur peut-il voir le site malgré la maintenance ?
 * @param string $code code d'accès (réglage coming-soon-url)
 */
function sbMaintenanceBypass($code) {
	if ($code !== '' && isset($_GET['d']) && hash_equals($code, (string) $_GET['d'])) {
		$_SESSION['dev_in_progress'] = 'SBuiadminCMS';
	}
	if (($_SESSION['dev_in_progress'] ?? '') === 'SBuiadminCMS') return true;
	return sbMaintenanceBackofficeUser();
}

/** Utilisateur du back-office connecté (mot de passe inchangé, 2FA validée) */
function sbMaintenanceBackofficeUser() {
	global $sbusers;
	$user = (string) ($_SESSION['sbuiadmin_user_name'] ?? '');
	if ($user === '' || !$sbusers || !$sbusers->checkSessionHash($user, (string) ($_SESSION['sbuiadmin_user_password'] ?? ''))) return false;
	if (file_exists(SB_ADMIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . '2fa-disabled')) return true;
	return !empty($_SESSION['sb2fa_ok']) && hash_equals((string) $_SESSION['sb2fa_ok'], $user);
}

/** Réglages de la page (CMS Config > Maintenance) */
function sbMaintenanceSettings() {
	global $sbsql;
	$cs = array();
	$res = $sbsql->query("SELECT config, content FROM " . _AM_DB_PREFIX . "sb_config WHERE config LIKE 'coming-soon%'");
	foreach ((array) $sbsql->toarray($res) as $row) $cs[$row['config']] = (string) $row['content'];
	return $cs;
}

/** Date de lancement (jj/mm/aaaa) en timestamp, ou 0 */
function sbMaintenanceDate($value) {
	if (!preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', trim((string) $value), $m) || !checkdate((int) $m[2], (int) $m[1], (int) $m[3])) return 0;
	return mktime(0, 0, 0, (int) $m[2], (int) $m[1], (int) $m[3]);
}

/**
 * Affiche la page de maintenance (503) et termine le script
 */
function sbMaintenancePage() {
	$cs = sbMaintenanceSettings();
	$g = function ($k) use ($cs) { return trim((string) ($cs[$k] ?? '')); };
	// Valeurs enregistrées en entités HTML (sanitize) : décodées puis échappées
	$e = function ($v) { return htmlspecialchars(html_entity_decode((string) $v, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8'); };
	$url = function ($v) { $v = html_entity_decode(trim((string) $v), ENT_QUOTES, 'UTF-8'); return preg_match('#^https?://#i', $v) ? $v : ''; };

	$assets = SB_URL . 'assets/maintenance/';
	$title  = $g('coming-soon-title') !== '' ? $g('coming-soon-title') : (defined('_AM_SITE_TITLE') ? _AM_SITE_TITLE : 'Maintenance');
	$type   = $g('coming-soon-type') === 'video' ? 'video' : 'image';
	$image  = $g('coming-soon-image') !== '' ? rtrim(_AM_MEDIAS_URL, '/') . '/' . rawurlencode(basename(html_entity_decode($g('coming-soon-image'), ENT_QUOTES, 'UTF-8'))) : $assets . 'images/bg-image.jpg';
	$video  = preg_match('/^[A-Za-z0-9_-]{6,20}$/', $g('coming-soon-video')) ? $g('coming-soon-video') : 'PF0L3gvSVcg';
	$launch = ($g('coming-soon-countdown') === '1') ? sbMaintenanceDate($g('coming-soon-date')) : 0;
	if ($launch && $launch <= time()) $launch = 0;
	$email  = filter_var(html_entity_decode($g('coming-soon-email'), ENT_QUOTES, 'UTF-8'), FILTER_VALIDATE_EMAIL) ?: '';
	$tel    = preg_replace('/[^0-9+]/', '', html_entity_decode($g('coming-soon-tel'), ENT_QUOTES, 'UTF-8'));
	$social = array();
	foreach (array('coming-soon-facebook' => array('facebook', 'Facebook'), 'coming-soon-twitter' => array('twitter', 'X (Twitter)'), 'coming-soon-youtube' => array('youtube', 'YouTube')) as $k => $info) {
		if ($url($g($k)) !== '') $social[] = array($url($g($k)), $info[0], $info[1]);
	}
	$has_about   = trim(strip_tags(html_entity_decode($g('coming-soon-text'), ENT_QUOTES, 'UTF-8'))) !== '';
	$has_contact = $email !== '' || $tel !== '' || $g('coming-soon-address') !== '';

	// 503 : indisponibilité temporaire (les moteurs gardent les pages et
	// reviennent), jamais mise en cache : un rafraîchissement suffit à la
	// réouverture
	http_response_code(503);
	header('Retry-After: ' . ($launch ? max(3600, min($launch - time(), 7 * 86400)) : 3600));
	header('X-Robots-Tag: noindex, nofollow');
	header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
	header('Content-Type: text/html; charset=utf-8');

	$social_html = '';
	foreach ($social as $s) {
		$social_html .= '<li><a href="' . htmlspecialchars($s[0], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener" title="' . $s[2] . '"><i class="fa fa-' . $s[1] . '" aria-hidden="true"></i></a></li>';
	}
	$footer = '&copy; ' . $e($title) . ' ' . date('Y') . '. Tous droits r&eacute;serv&eacute;s.';
	?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?php echo $e($title); ?></title>
<link rel="icon" href="<?php echo $assets; ?>images/favicon.ico" type="image/x-icon">
<link href="<?php echo $assets; ?>css/style.css" rel="stylesheet">
<link href="<?php echo $assets; ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $assets; ?>css/responsive.css" rel="stylesheet">
<link href="<?php echo $assets; ?>css/font-awesome.min.css" rel="stylesheet">
<style>
main[role="main-wrapper-iamge"],main[role="main-wrapper-iamge"] > .over-bg-color{background-image:url(<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>);background-size:cover;background-position:50% 0;background-repeat:no-repeat;width:100%;height:100%;position:fixed}
main[role="main-wrapper-iamge"] > .over-bg-color{background-color:rgba(255,255,255,0.5);background-image:none;padding:0}
.sbm-video{position:fixed;inset:0;overflow:hidden;z-index:-1;background:#000 url(<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>) 50% 0/cover no-repeat}
.sbm-video iframe{position:absolute;top:50%;left:50%;width:100vw;height:56.25vw;min-height:100vh;min-width:177.78vh;transform:translate(-50%,-50%);border:0;pointer-events:none}
.sbm-video-overlay{position:fixed;inset:0;background-color:rgba(255,255,255,0.6);z-index:-1}
main[role="video-container"]{position:relative;z-index:6}
#sbm-countdown{display:flex;justify-content:center;gap:24px;margin-bottom:10px}
#sbm-countdown div{text-align:center}
#sbm-countdown span{display:block;font-family:'Oranienbaum',serif;font-size:56px;line-height:60px}
#sbm-countdown small{text-transform:uppercase;letter-spacing:2px;font-size:12px}
@media (max-width:767px){.sbm-video iframe{display:none}#sbm-countdown span{font-size:36px;line-height:40px}}
</style>
</head>
<body>
<?php if ($type == 'video') { ?>
<div class="sbm-video"><iframe src="https://www.youtube-nocookie.com/embed/<?php echo $video; ?>?autoplay=1&amp;mute=1&amp;loop=1&amp;playlist=<?php echo $video; ?>&amp;controls=0&amp;disablekb=1&amp;modestbranding=1&amp;playsinline=1&amp;rel=0" title="Vidéo" allow="autoplay; encrypted-media" tabindex="-1" aria-hidden="true"></iframe></div>
<div class="sbm-video-overlay"></div>
<main role="video-container">
<?php } else { ?>
<main id="main" role="main-wrapper-iamge">
<div class="over-bg-color">
<?php } ?>
  <div class="container">
	<div class="tab-content text-center">
		<a target="_blank" rel="noopener" href="https://github.com/informatux45/sbuiadmin"><img style="position: absolute; top: 0; right: 0; border: 0; width: 115px; height: 115px;" src="<?php echo $assets; ?>images/sbuiadmin-fork-me-on-github-blue.png" alt="Fork me on GitHub - SBUIADMIN - INFORMATUX"></a>
	  <section id="home" class="tab-pane fade in active">
		<article role="countdown" class="countdown-pan">
		  <?php if ($launch) { ?>
		  <div id="sbm-countdown" data-launch="<?php echo $launch * 1000; ?>" aria-live="polite"></div>
		  <?php } ?>
		  <p><?php echo html_entity_decode($g('coming-soon-title2'), ENT_QUOTES, 'UTF-8'); ?></p>
		</article>
	  </section>
	  <?php if ($has_about) { ?>
	  <section id="menu1" class="tab-pane fade">
		<article role="introduction" class="introduction-pan">
		  <header class="page-title"><h2>A propos de nous</h2></header>
		  <?php echo html_entity_decode($g('coming-soon-text'), ENT_QUOTES, 'UTF-8'); ?>
		</article>
	  </section>
	  <?php } ?>
	  <?php if ($has_contact) { ?>
	  <section id="menu3" class="tab-pane fade">
		<article role="contact" class="contact-pan">
		  <header class="page-title"><h2>Contactez nous</h2></header>
		  <?php if ($email !== '') { ?><h3><a href="mailto:<?php echo $e($email); ?>"><?php echo $e($email); ?></a></h3><?php } ?>
		  <ul class="contact-infos">
			<?php if ($g('coming-soon-address') !== '') { ?><li><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $e($g('coming-soon-address')); ?></li><?php } ?>
			<?php if ($tel !== '') { ?><li><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:<?php echo $e($tel); ?>"><?php echo $e($g('coming-soon-tel')); ?></a></li><?php } ?>
		  </ul>
		</article>
	  </section>
	  <?php } ?>
	</div>
  </div>
  <header role="header">
	<hgroup>
	  <h1><a href="<?php echo $e(SB_URL); ?>" title="<?php echo $e($title); ?>"><?php echo $e($title); ?></a></h1>
	  <nav role="nav" id="header-nav" class="nav navy">
		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#home">Maintenance</a></li>
		  <?php if ($has_about) { ?><li><a data-toggle="tab" href="#menu1">A propos de nous</a></li><?php } ?>
		  <?php if ($has_contact) { ?><li><a data-toggle="tab" href="#menu3">Contact</a></li><?php } ?>
		</ul>
		<?php if ($social_html) { ?><ul role="socil-icons" class="mobile-social"><?php echo $social_html; ?></ul><?php } ?>
	  </nav>
	  <?php if ($social_html) { ?><ul role="socil-icons" class="desk-social"><?php echo $social_html; ?></ul><?php } ?>
	</hgroup>
	<footer class="desk"><p><?php echo $footer; ?></p></footer>
  </header>
  <footer class="mobile"><p><?php echo $footer; ?></p></footer>
<?php if ($type == 'image') { ?></div><?php } ?>
</main>
<script src="<?php echo $assets; ?>js/jquery.min.js"></script>
<script src="<?php echo $assets; ?>js/nav-custom.js"></script>
<script src="<?php echo $assets; ?>js/bootstrap.min.js"></script>
<?php if ($launch) { ?>
<script>
(function () {
	var el = document.getElementById('sbm-countdown');
	var end = parseInt(el.getAttribute('data-launch'), 10);
	function pad(n) { return (n < 10 ? '0' : '') + n; }
	function tick() {
		var s = Math.max(0, Math.floor((end - Date.now()) / 1000));
		var d = Math.floor(s / 86400), h = Math.floor(s % 86400 / 3600), m = Math.floor(s % 3600 / 60);
		el.innerHTML = '<div><span>' + d + '</span><small>jour' + (d > 1 ? 's' : '') + '</small></div>'
			+ '<div><span>' + pad(h) + '</span><small>heures</small></div>'
			+ '<div><span>' + pad(m) + '</span><small>minutes</small></div>'
			+ '<div><span>' + pad(s % 60) + '</span><small>secondes</small></div>';
		if (s === 0) { clearInterval(timer); setTimeout(function () { location.reload(); }, 5000); }
	}
	var timer = setInterval(tick, 1000);
	tick();
})();
</script>
<?php } ?>
</body>
</html>
<?php
	exit;
}
