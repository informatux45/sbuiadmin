<?php
/**
 * Module stats : collecte côté serveur (lot 1)
 *
 * Chaque page publique servie par index.php (200 ou 404, requête GET d'un
 * navigateur) est notée en fin de requête : la page en cache Smarty compte
 * aussi (header.php charge ce fichier avant l'affichage).
 *
 * Vie privée : aucun cookie, aucune adresse IP enregistrée. Le visiteur est
 * une empreinte HMAC (IP tronquée + navigateur + langue) dont la clé change
 * chaque jour : deux jours différents ne peuvent pas être reliés.
 *
 * Une erreur ici n'empêche jamais une page de s'afficher.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

// -----------------------------------------------------------------------
// Réglages (table sb_stats_setting : nom => valeur)
// -----------------------------------------------------------------------

/** Réglages par défaut */
function stats_setting_defaults() {
	return array(
		'enabled'         => '1',  // collecte active
		'exclude_admin'   => '1',  // ignorer les utilisateurs connectés à l'administration
		'excluded_ips'    => '',   // adresses ou plages CIDR, une par ligne
		'respect_dnt'     => '0',  // Do Not Track / Global Privacy Control
		'visit_timeout'   => '30', // minutes d'inactivité avant une nouvelle visite
		'excluded_params' => '',   // paramètres d'URL retirés, séparés par des virgules
		'raw_retention'   => '180', // jours de données brutes (journal des visites), 0 : jamais purgées
		'report_retention' => '0', // mois de rapports par jour, 0 : gardés
		'archived_until'  => '',   // dernier jour archivé (inc/archive.php)
		'archive_last'    => '0',  // dernier entretien (horodatage)
		'realtime_minutes' => '30', // temps réel : actualisation automatique pendant N minutes (0 : jamais)
		'geoip_license'   => '',   // date d'acceptation de la licence DB-IP (CC BY 4.0)
		'geoip_auto'      => '0',  // mise à jour mensuelle automatique de la base des pays
		'geoip_version'   => '',   // mois de la base installée (AAAA-MM)
		'geoip_sha1'      => '',
		'geoip_checked'   => '0',  // 1 : empreinte comparée à celle publiée par DB-IP
		'geoip_updated'   => '0',  // horodatage de l'installation
		'geoip_try'       => '0',  // dernière tentative automatique
	);
}

/**
 * Fuseau horaire du site (SBTIMEZONE de sbconfig.php) : les dates sont
 * enregistrées à l'heure du site ; l'administration, qui ne charge pas
 * sbconfig.php, serait sinon à l'heure de php.ini (jours et heures décalés)
 */
function stats_use_site_timezone() {
	if (!defined('SBTIMEZONE')) {
		$cfg = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'sbconfig.php';
		if (is_file($cfg)) include_once($cfg);
	}
	if (defined('SBTIMEZONE') && in_array(SBTIMEZONE, timezone_identifiers_list(), true)) date_default_timezone_set(SBTIMEZONE);
}

/** Base et accès : sbdb ou null */
function stats_db() {
	$db = function_exists('sbDb') ? sbDb() : null;
	return ($db instanceof sbdb) ? $db : null;
}

/** Tous les réglages (défauts + base) */
function stats_settings($reset = false) {
	static $cache = null;
	if ($cache !== null && !$reset) return $cache;
	$cache = stats_setting_defaults();
	$db = stats_db();
	$rows = $db ? $db->all('SELECT `name`, `value` FROM {sb_stats_setting}') : false;
	foreach ($rows ?: array() as $r) {
		if (array_key_exists($r['name'], $cache)) $cache[$r['name']] = (string) $r['value'];
	}
	return $cache;
}

function stats_setting($name) {
	$s = stats_settings();
	return isset($s[$name]) ? trim((string) $s[$name]) : '';
}

/** Enregistre des réglages connus ; true si tout est écrit */
function stats_settings_save(array $values) {
	$db = stats_db();
	if (!$db) return false;
	$ok = true;
	foreach ($values as $name => $value) {
		if (!array_key_exists($name, stats_setting_defaults())) continue;
		if (!$db->upsert('sb_stats_setting', array('name' => $name, 'value' => trim((string) $value)), array('name'))) $ok = false;
	}
	stats_settings(true);
	return $ok;
}

/**
 * Sel de l'empreinte pour un jour (64 caractères hexadécimaux), '' sinon.
 * Tiré au hasard le jour même (ligne salt:AAAA-MM-JJ de sb_stats_setting,
 * le premier écrivain l'emporte : deux requêtes simultanées gardent le même)
 * et effacé le surlendemain : les empreintes des jours passés ne peuvent plus
 * être recalculées, même avec une copie de la base (comme Matomo).
 * @param bool $create tirer le sel du jour s'il n'existe pas (aujourd'hui seulement)
 */
function stats_day_salt($day, $create = false, $now = null) {
	static $cache = array();
	if ($day === null) { $cache = array(); return ''; } // stats_day_salt(null) : oublie les sels en mémoire
	$now = $now ?? time();
	if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $day)) return '';
	if (isset($cache[$day])) return $cache[$day];
	$db = stats_db();
	if (!$db) return '';
	$name = 'salt:' . $day;
	$salt = (string) $db->value('SELECT `value` FROM {sb_stats_setting} WHERE `name` = ?', array($name));
	if ($salt === '' && $create && $day === date('Y-m-d', $now)) {
		$db->insertIgnore('sb_stats_setting', array('name' => $name, 'value' => bin2hex(random_bytes(32))));
		$salt = (string) $db->value('SELECT `value` FROM {sb_stats_setting} WHERE `name` = ?', array($name));
		// Ménage : sels d'avant-hier et plus anciens, ancienne clé permanente (avant 1.0.0)
		$db->run("DELETE FROM {sb_stats_setting} WHERE (`name` LIKE 'salt:%' AND `name` < ?) OR `name` = 'secret'", array('salt:' . date('Y-m-d', strtotime('-1 day', $now))));
	}
	if (!preg_match('/^[a-f0-9]{64}$/', $salt)) return '';
	return $cache[$day] = $salt;
}

// -----------------------------------------------------------------------
// Visiteur
// -----------------------------------------------------------------------

/** IP tronquée : /24 en IPv4, /48 en IPv6 ('' si invalide) */
function stats_mask_ip($ip) {
	$bin = filter_var($ip, FILTER_VALIDATE_IP) ? @inet_pton($ip) : false;
	if ($bin === false) return '';
	$keep = strlen($bin) === 4 ? 3 : 6;
	return inet_ntop(substr($bin, 0, $keep) . str_repeat("\0", strlen($bin) - $keep));
}

/** Empreinte du jour (16 caractères hexadécimaux) ; $salt : sel du jour (stats_day_salt()) */
function stats_visitor_hash($ip, $agent, $lang, $salt, $day) {
	return substr(hash_hmac('sha256', stats_mask_ip($ip) . '|' . $agent . '|' . $lang, $salt . '|' . $day), 0, 16);
}

/** Robot, outil ou navigateur sans interface ? */
function stats_is_bot($agent) {
	$agent = trim((string) $agent);
	if ($agent === '' || strlen($agent) < 20) return true;
	return (bool) preg_match('~bot\b|bot/|crawl|spider|slurp|scrap|fetch|archiv|monitor|uptime|pingdom|lighthouse|pagespeed|gtmetrix|headless|phantom|selenium|puppeteer|playwright|'
		. 'curl|wget|python|java/|okhttp|go-http|libwww|perl|ruby|php/|guzzle|axios|node-fetch|httpclient|http_request|postman|insomnia|'
		. 'facebookexternalhit|embedly|preview|validator|feed|rss|semrush|ahrefs|mj12|dotbot|petalbot|bytespider|gptbot|claude|ccbot|anthropic|perplexity|'
		. 'yandex|baidu|sogou|exabot|seznam|qwant|bingpreview|mediapartners|adsbot|google-|apis-google|chrome-lighthouse~i', $agent);
}

/**
 * Requête d'affichage d'une page par un navigateur ? (GET, ni AJAX, ni
 * préchargement, ni ressource : une image absente servie par index.php ne
 * compte pas comme une page)
 */
function stats_is_page_request(array $server) {
	if (strtoupper((string) ($server['REQUEST_METHOD'] ?? '')) !== 'GET') return false;
	if (strtolower((string) ($server['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest') return false;
	$purpose = strtolower((string) ($server['HTTP_SEC_PURPOSE'] ?? ($server['HTTP_PURPOSE'] ?? ($server['HTTP_X_MOZ'] ?? ''))));
	if (strpos($purpose, 'prefetch') !== false || strpos($purpose, 'prerender') !== false) return false;
	$dest = strtolower((string) ($server['HTTP_SEC_FETCH_DEST'] ?? ''));
	if ($dest !== '' && $dest !== 'document') return false;
	return stripos((string) ($server['HTTP_ACCEPT'] ?? ''), 'text/html') !== false;
}

/** Langue principale du navigateur : fr-fr, en, ... ('' sinon) */
function stats_language($accept) {
	if (!preg_match('/^\s*([a-z]{2,3})(?:-([a-z]{2}))?/i', (string) $accept, $m)) return '';
	return strtolower($m[1]) . (isset($m[2]) && $m[2] !== '' ? '-' . strtolower($m[2]) : '');
}

/**
 * Navigateur, système et appareil d'après l'en-tête User-Agent (et les
 * indications Sec-CH-UA quand le navigateur les envoie)
 * @return array browser, browser_version, os, os_version, device
 */
function stats_ua_parse($agent, array $server = array()) {
	$agent = (string) $agent;
	$out = array('browser' => 'Autre', 'browser_version' => '', 'os' => 'Autre', 'os_version' => '', 'device' => 'desktop');

	// Navigateur : les plus spécifiques d'abord (Edge et Opera se disent Chrome)
	$browsers = array(
		'Edge'             => '~Edg(?:e|A|iOS)?/(\d+)~',
		'Opera'            => '~(?:OPR|Opera|OPT)/(\d+)~',
		'Samsung Internet' => '~SamsungBrowser/(\d+)~',
		'Yandex Browser'   => '~YaBrowser/(\d+)~',
		'Vivaldi'          => '~Vivaldi/(\d+)~',
		'UC Browser'       => '~UCBrowser/(\d+)~',
		'Firefox'          => '~(?:Firefox|FxiOS)/(\d+)~',
		'Chrome'           => '~(?:Chrome|CriOS)/(\d+)~',
		'Safari'           => '~Version/(\d+)[\d.]* (?:Mobile/\S+ )?Safari/~',
		'Internet Explorer' => '~(?:MSIE |Trident/.*rv:)(\d+)~',
	);
	foreach ($browsers as $name => $re) {
		if (preg_match($re, $agent, $m)) { $out['browser'] = $name; $out['browser_version'] = $m[1]; break; }
	}
	if ($out['browser'] === 'Autre' && preg_match('~AppleWebKit.*Mobile/~', $agent)) $out['browser'] = 'Safari';

	// Système
	$platform = trim((string) ($server['HTTP_SEC_CH_UA_PLATFORM'] ?? ''), '" ');
	$pver     = trim((string) ($server['HTTP_SEC_CH_UA_PLATFORM_VERSION'] ?? ''), '" ');
	if (preg_match('~Windows NT (\d+\.\d+)~', $agent, $m)) {
		$out['os'] = 'Windows';
		$map = array('10.0' => '10', '6.3' => '8.1', '6.2' => '8', '6.1' => '7', '6.0' => 'Vista', '5.1' => 'XP');
		$out['os_version'] = $map[$m[1]] ?? $m[1];
		// Windows 11 ne se distingue que par Sec-CH-UA-Platform-Version (13 et plus)
		if ($out['os_version'] === '10' && $platform === 'Windows' && (int) $pver >= 13) $out['os_version'] = '11';
	} elseif (preg_match('~(?:iPhone|iPod).*OS (\d+)[_\d]*~', $agent, $m)) {
		$out['os'] = 'iOS'; $out['os_version'] = $m[1];
	} elseif (preg_match('~iPad.*OS (\d+)[_\d]*~', $agent, $m)) {
		$out['os'] = 'iPadOS'; $out['os_version'] = $m[1];
	} elseif (preg_match('~Android (\d+)~', $agent, $m)) {
		$out['os'] = 'Android'; $out['os_version'] = $m[1];
	} elseif (strpos($agent, 'CrOS') !== false) {
		$out['os'] = 'ChromeOS';
	} elseif (preg_match('~Mac OS X (\d+)[_.](\d+)~', $agent, $m)) {
		$out['os'] = 'macOS'; $out['os_version'] = $m[1] === '10' ? '10.' . $m[2] : $m[1];
	} elseif (preg_match('~Linux|X11|Ubuntu|Fedora~', $agent)) {
		$out['os'] = 'GNU/Linux';
	}

	// Appareil
	$mobileHint = trim((string) ($server['HTTP_SEC_CH_UA_MOBILE'] ?? ''));
	if (preg_match('~SmartTV|SMART-TV|HbbTV|GoogleTV|AppleTV|CrKey|Tizen.*TV|Web0S|\bTV\b~i', $agent)) {
		$out['device'] = 'tv';
	} elseif (preg_match('~iPad|Tablet|Kindle|Silk/|PlayBook~i', $agent) || (strpos($agent, 'Android') !== false && strpos($agent, 'Mobile') === false)) {
		$out['device'] = 'tablet';
	} elseif ($mobileHint === '?1' || preg_match('~Mobi|iPhone|iPod|Windows Phone|BlackBerry|Opera Mini~i', $agent)) {
		$out['device'] = 'smartphone';
	}
	return $out;
}

// -----------------------------------------------------------------------
// Adresse de la page et provenance
// -----------------------------------------------------------------------

/** Paramètres jamais gardés dans l'adresse (code d'accès de maintenance, jetons, campagnes, e-mail, téléphone) */
function stats_strip_param($name, array $extra = array()) {
	$name = strtolower((string) $name);
	if (in_array($name, array('d', 'maintenance', 'csrf', 'sbtoken', 'k', 'id_token', 'code', 'state', 'fbclid', 'gclid', 'msclkid', 'dclid', 'yclid', '_ga', '_gl'), true)) return true;
	// Données personnelles qu'un formulaire en GET laisserait dans l'adresse
	if (preg_match('/^(e-?mail|mail|courriel|tel|telephone|phone|mobile|portable|gsm)$|email|phone/', $name)) return true;
	if (preg_match('/^(utm_|mtm_|pk_|matomo_)|token|pass|pwd|secret|session|key$/', $name)) return true;
	return in_array($name, $extra, true);
}

/** Paramètres retirés en plus (réglage excluded_params) */
function stats_extra_params() {
	$out = array();
	foreach (preg_split('/[\s,;]+/', stats_setting('excluded_params')) as $p) {
		if ($p !== '' && preg_match('/^[A-Za-z0-9_\-\[\]]{1,64}$/', $p)) $out[] = strtolower($p);
	}
	return $out;
}

/** Adresse relative nettoyée (chemin + paramètres gardés), 255 caractères au plus */
function stats_clean_url($uri, array $extra = array()) {
	$uri  = preg_replace('/[\x00-\x1F\x7F]/', '', (string) $uri);
	$path = (string) parse_url($uri, PHP_URL_PATH);
	if ($path === '' || $path[0] !== '/') $path = '/' . ltrim($path, '/');
	$query = (string) parse_url($uri, PHP_URL_QUERY);
	$keep = array();
	if ($query !== '') {
		foreach (explode('&', $query) as $pair) {
			if ($pair === '') continue;
			$name = urldecode(explode('=', $pair, 2)[0]);
			if (!stats_strip_param(preg_replace('/\[.*$/', '', $name), $extra)) $keep[] = $pair;
		}
	}
	$url = $path . ($keep ? '?' . implode('&', $keep) : '');
	return mb_substr($url, 0, 255, 'UTF-8');
}

/** Moteurs de recherche : motif d'hôte => nom, paramètre du mot-clé */
function stats_search_engines() {
	return array(
		'~(^|\.)google\.~'          => array('Google', 'q'),
		'~(^|\.)bing\.com$~'        => array('Bing', 'q'),
		'~(^|\.)duckduckgo\.com$~'  => array('DuckDuckGo', 'q'),
		'~(^|\.)qwant\.com$~'       => array('Qwant', 'q'),
		'~(^|\.)ecosia\.org$~'      => array('Ecosia', 'q'),
		'~(^|\.)search\.yahoo\.~'   => array('Yahoo!', 'p'),
		'~(^|\.)yandex\.~'          => array('Yandex', 'text'),
		'~(^|\.)baidu\.com$~'       => array('Baidu', 'wd'),
		'~(^|\.)startpage\.com$~'   => array('Startpage', 'query'),
		'~^search\.brave\.com$~'    => array('Brave Search', 'q'),
		'~(^|\.)lilo\.org$~'        => array('Lilo', 'q'),
		'~(^|\.)ask\.com$~'         => array('Ask', 'q'),
		'~(^|\.)search\.aol\.~'     => array('AOL', 'q'),
		'~(^|\.)perplexity\.ai$~'   => array('Perplexity', 'q'),
		'~(^|\.)chatgpt\.com$~'     => array('ChatGPT', 'q'),
	);
}

/** Réseaux sociaux : motif d'hôte => nom */
function stats_social_networks() {
	return array(
		'~(^|\.)(facebook\.com|fb\.me|fb\.com)$~'     => 'Facebook',
		'~(^|\.)instagram\.com$~'                     => 'Instagram',
		'~(^|\.)(twitter\.com|x\.com|t\.co)$~'        => 'X (Twitter)',
		'~(^|\.)(linkedin\.com|lnkd\.in)$~'           => 'LinkedIn',
		'~(^|\.)(pinterest\.[a-z.]+|pin\.it)$~'       => 'Pinterest',
		'~(^|\.)reddit\.com$~'                        => 'Reddit',
		'~(^|\.)(youtube\.com|youtu\.be)$~'           => 'YouTube',
		'~(^|\.)tiktok\.com$~'                        => 'TikTok',
		'~(^|\.)bsky\.app$~'                          => 'Bluesky',
		'~(^|\.)threads\.(net|com)$~'                 => 'Threads',
		'~(^|\.)(whatsapp\.com|wa\.me)$~'             => 'WhatsApp',
		'~(^|\.)(telegram\.org|t\.me)$~'              => 'Telegram',
		'~(^|\.)(mastodon\.[a-z.]+|mstdn\.[a-z.]+)$~' => 'Mastodon',
		'~(^|\.)snapchat\.com$~'                      => 'Snapchat',
		'~(^|\.)viadeo\.com$~'                        => 'Viadeo',
	);
}

/** Texte nettoyé et coupé à $max caractères */
function stats_cut($text, $max) {
	$text = preg_replace('/[\x00-\x1F\x7F]/', '', html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
	return mb_substr(trim($text), 0, $max, 'UTF-8');
}

/**
 * Provenance de la visite
 * @param string $referer  en-tête Referer
 * @param string $ownHost  hôte du site (provenance interne = aucune)
 * @param array  $get      paramètres de l'adresse (campagnes utm_, mtm_, pk_)
 * @return array type, name, url, keyword, source, medium ; type '' = interne
 */
function stats_referrer($referer, $ownHost, array $get) {
	$out = array('type' => 'direct', 'name' => '', 'url' => '', 'keyword' => '', 'source' => '', 'medium' => '');

	// Campagne : prioritaire sur l'en-tête
	$pick = function (array $names) use ($get) {
		foreach ($names as $n) if (isset($get[$n]) && is_string($get[$n]) && trim($get[$n]) !== '') return stats_cut($get[$n], 100);
		return '';
	};
	$campaign = $pick(array('utm_campaign', 'mtm_campaign', 'pk_campaign', 'matomo_campaign'));
	if ($campaign !== '') {
		$out['type']    = 'campaign';
		$out['name']    = mb_strtolower($campaign, 'UTF-8');
		$out['keyword'] = $pick(array('utm_term', 'mtm_keyword', 'pk_keyword', 'pk_kwd', 'matomo_keyword'));
		$out['source']  = mb_strtolower($pick(array('utm_source', 'mtm_source', 'pk_source')), 'UTF-8');
		$out['medium']  = mb_strtolower($pick(array('utm_medium', 'mtm_medium', 'pk_medium')), 'UTF-8');
		$out['url']     = stats_cut($referer, 255);
		return $out;
	}

	$referer = trim((string) $referer);
	$host = strtolower((string) parse_url($referer, PHP_URL_HOST));
	if ($host === '' || !preg_match('~^https?://~i', $referer)) return $out;
	$own = strtolower(preg_replace('/:\d+$/', '', (string) $ownHost));
	$bare = function ($h) { return preg_replace('/^www\./', '', $h); };
	if ($own !== '' && $bare($host) === $bare($own)) { $out['type'] = ''; return $out; }

	$out['url'] = stats_cut($referer, 255);
	parse_str((string) parse_url($referer, PHP_URL_QUERY), $q);
	foreach (stats_search_engines() as $re => $engine) {
		if (preg_match($re, $host)) {
			$out['type'] = 'search';
			$out['name'] = $engine[0];
			$out['keyword'] = (isset($q[$engine[1]]) && is_string($q[$engine[1]])) ? mb_strtolower(stats_cut($q[$engine[1]], 100), 'UTF-8') : '';
			return $out;
		}
	}
	foreach (stats_social_networks() as $re => $name) {
		if (preg_match($re, $host)) { $out['type'] = 'social'; $out['name'] = $name; return $out; }
	}
	$out['type'] = 'website';
	$out['name'] = mb_substr($bare($host), 0, 100, 'UTF-8');
	return $out;
}

// -----------------------------------------------------------------------
// Enregistrement
// -----------------------------------------------------------------------

/**
 * Description d'un passage d'après la requête, ou null s'il ne compte pas
 * (robot, ressource, préchargement...)
 */
function stats_hit_from_request(array $server, array $get, $ip) {
	$agent = (string) ($server['HTTP_USER_AGENT'] ?? '');
	if (!stats_is_page_request($server) || stats_is_bot($agent)) return null;
	$ua = stats_ua_parse($agent, $server);
	return array(
		'ip'       => (string) $ip,
		'agent'    => $agent,
		'language' => stats_language($server['HTTP_ACCEPT_LANGUAGE'] ?? ''),
		'url'      => stats_clean_url((string) ($server['REQUEST_URI'] ?? '/'), stats_extra_params()),
		'referrer' => stats_referrer((string) ($server['HTTP_REFERER'] ?? ''), (string) ($server['HTTP_HOST'] ?? ''), $get),
		// Pays donné par Cloudflare : seulement si le site est réglé derrière Cloudflare (sinon falsifiable)
		'cf_country' => (function_exists('sbClientIpMode') && sbClientIpMode() === 'cloudflare') ? (string) ($server['HTTP_CF_IPCOUNTRY'] ?? '') : '',
	) + $ua;
}

/**
 * Note un passage : visite reprise (même empreinte, moins de visit_timeout
 * minutes) ou nouvelle visite, puis l'action.
 * @param string $type  page, 404, download, outlink, event, search
 * @return int identifiant de l'action, 0 en cas d'échec
 */
function stats_record(sbdb $db, array $hit, $type = 'page', $title = '', $now = null) {
	$now = $now ?? time();
	$today = date('Y-m-d', $now);
	$salt = stats_day_salt($today, true, $now);
	if ($salt === '') return 0;
	$date = date('Y-m-d H:i:s', $now);
	$visitor = stats_visitor_hash($hit['ip'], $hit['agent'], $hit['language'], $salt, $today);
	$timeout = max(1, min(1440, (int) stats_setting('visit_timeout') ?: 30));
	$isPage = in_array($type, array('page', '404'), true);
	$url = mb_substr((string) $hit['url'], 0, 255, 'UTF-8');

	// Juste après minuit, la visite commencée la veille porte l'empreinte de la veille
	$since = $now - $timeout * 60;
	$hashes = array($visitor);
	if (date('Y-m-d', $since) !== $today && ($prevSalt = stats_day_salt(date('Y-m-d', $since), false, $now)) !== '') {
		$hashes[] = stats_visitor_hash($hit['ip'], $hit['agent'], $hit['language'], $prevSalt, date('Y-m-d', $since));
	}
	$visit = $db->row('SELECT `id`, `first_action`, `last_action_id`, `last_action` FROM {sb_stats_visit} WHERE `visitor` IN (' . implode(', ', array_fill(0, count($hashes), '?')) . ') AND `last_action` >= ? ORDER BY `last_action` DESC LIMIT 1',
		array_merge($hashes, array(date('Y-m-d H:i:s', $since))));
	if ($visit === false) return 0;

	// Lien interne : continue la visite ; provenance externe ou campagne : nouvelle visite (comme Matomo)
	$ref = $hit['referrer'];
	if ($visit && $ref['type'] !== '' && $ref['type'] !== 'direct') $visit = null;

	if (!$visit) {
		if (!isset($hit['country'])) {
			require_once(__DIR__ . DIRECTORY_SEPARATOR . 'geoip.php');
			$hit['country'] = stats_country_of($hit['ip'], (string) ($hit['cf_country'] ?? ''));
		}
		$visitId = $db->insert('sb_stats_visit', array(
			'visitor' => $visitor, 'first_action' => $date, 'last_action' => $date,
			'entry_url' => $isPage ? $url : '', 'exit_url' => $isPage ? $url : '',
			'referer_type' => $ref['type'] === '' ? 'direct' : $ref['type'], 'referer_name' => $ref['name'], 'referer_url' => $ref['url'],
			'referer_keyword' => $ref['keyword'], 'campaign_source' => $ref['source'], 'campaign_medium' => $ref['medium'],
			'language' => $hit['language'], 'browser' => $hit['browser'], 'browser_version' => $hit['browser_version'],
			'os' => $hit['os'], 'os_version' => $hit['os_version'], 'device' => $hit['device'],
			'country' => (string) ($hit['country'] ?? ''),
		));
		if (!$visitId) return 0;
		$visit = array('id' => $visitId, 'first_action' => $date, 'last_action_id' => 0, 'last_action' => $date);
	}

	$pv = (string) ($hit['pv'] ?? '');
	$actionId = $db->insert('sb_stats_action', array(
		'visit_id' => (int) $visit['id'], 'pv' => preg_match('/^[a-f0-9]{16}$/', $pv) ? $pv : '', 'created_at' => $date,
		'type' => $type, 'url' => $url, 'title' => stats_cut($title, 255),
	));
	if (!$actionId) return 0;

	// Temps passé sur l'action précédente
	if ((int) $visit['last_action_id'] > 0) {
		$spent = max(0, $now - strtotime($visit['last_action']));
		$db->update('sb_stats_action', array('time_spent' => min($spent, $timeout * 60)), '`id` = ?', array((int) $visit['last_action_id']));
	}

	$set = array('last_action' => $date, 'last_action_id' => $actionId, 'duration' => max(0, $now - strtotime($visit['first_action'])));
	if ($isPage) $set['exit_url'] = $url;
	$db->run('UPDATE {sb_stats_visit} SET `actions` = `actions` + 1' . ($isPage ? ', `pageviews` = `pageviews` + 1' : '')
		. ', `last_action` = ?, `last_action_id` = ?, `duration` = ?' . ($isPage ? ', `exit_url` = ?' : '') . ' WHERE `id` = ?',
		array_merge(array_values($set), array((int) $visit['id'])));
	return $actionId;
}

/** Passage exclu par les réglages ? (admin connecté, IP exclue, Do Not Track) */
function stats_excluded(array $server, $ip) {
	if (stats_setting('enabled') !== '1') return true;
	if (stats_setting('exclude_admin') === '1' && session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['sbuiadmin_user_name'])) return true;
	if (stats_setting('respect_dnt') === '1' && (($server['HTTP_DNT'] ?? '') === '1' || ($server['HTTP_SEC_GPC'] ?? '') === '1')) return true;
	$list = stats_setting('excluded_ips');
	if ($list !== '' && function_exists('sbClientIpParseTrusted')) {
		list($ranges) = sbClientIpParseTrusted($list);
		if (sbClientIpIsTrusted($ip, $ranges)) return true;
	}
	return false;
}

/** Titre de la page affichée (variables Smarty du thème) */
function stats_page_title() {
	$s = $GLOBALS['sbsmarty'] ?? null;
	if (!is_object($s) || !method_exists($s, 'getTemplateVars')) return '';
	foreach (array('sb_pages_title', 'sb_title') as $v) {
		$t = $s->getTemplateVars($v);
		if (is_string($t) && trim($t) !== '') return $t;
	}
	return '';
}

/**
 * Nature de la réponse d'après ses en-têtes : 'page' (HTML) ou 'download'
 * (fichier servi, ex. module download) avec le nom du fichier
 * @param array $headers headers_list()
 * @return array type, nom de fichier
 */
function stats_response_kind(array $headers) {
	$type = ''; $disp = '';
	foreach ($headers as $h) {
		$h = (string) $h;
		if (stripos($h, 'Content-Type:') === 0) $type = trim(substr($h, 13));
		elseif (stripos($h, 'Content-Disposition:') === 0) $disp = trim(substr($h, 20));
	}
	$attach = stripos($disp, 'attachment') === 0;
	if (!$attach && ($type === '' || stripos($type, 'text/html') !== false)) return array('page', '');
	$name = '';
	if (preg_match("/filename\*=UTF-8''([^;]+)/i", $disp, $m)) $name = rawurldecode($m[1]);
	elseif (preg_match('/filename="?([^";]+)"?/i', $disp, $m)) $name = $m[1];
	return array('download', basename(str_replace('\\', '/', $name)));
}

/** Jeton de la page vue en cours (16 caractères hexadécimaux), tiré au démarrage */
function stats_pv() {
	return (string) ($GLOBALS['stats_pv'] ?? '');
}

/** Fin de requête : note la page servie (200), introuvable (404) ou le fichier téléchargé */
function stats_track_shutdown(array $server, array $get) {
	try {
		$code = (int) http_response_code();
		if ($code !== 200 && $code !== 404) return;
		$db = stats_db();
		if (!$db) return;
		$ip = function_exists('sbClientIp') ? sbClientIp() : (string) ($server['REMOTE_ADDR'] ?? '');
		if (stats_excluded($server, $ip)) return;
		$hit = stats_hit_from_request($server, $get, $ip);
		if (!$hit) return;
		list($kind, $file) = stats_response_kind(headers_list());
		if ($kind === 'download') {
			stats_record($db, $hit, 'download', $file);
			return;
		}
		$hit['pv'] = stats_pv();
		stats_record($db, $hit, $code === 404 ? '404' : 'page', stats_page_title());
		stats_maintenance_maybe($db);
	} catch (Throwable $e) {
	}
}

/**
 * Archivage et purge au plus une fois par heure (le réglage en mémoire évite
 * toute requête le reste du temps).
 * $front : fin d'une page du site. Seulement si la réponse part avant
 * (PHP-FPM, fastcgi_finish_request) : un visiteur n'attend jamais ; 7 jours
 * au plus par passage. Sinon l'administration s'en charge (page Statistiques).
 */
function stats_maintenance_maybe(sbdb $db, $now = null, $front = true) {
	$now = $now ?? time();
	if ((int) stats_setting('archive_last') > $now - 3600) return false;
	if ($front && !function_exists('fastcgi_finish_request')) return false;
	require_once(__DIR__ . DIRECTORY_SEPARATOR . 'archive.php');
	if ($front) @fastcgi_finish_request();
	return stats_maintenance($db, $now, false, $front ? 7 : 31);
}

/** Point d'entrée : seulement pour les pages publiques (index.php à la racine du site) */
function stats_boot() {
	static $done = false;
	if ($done || PHP_SAPI === 'cli' || !defined('SB_PATH')) return;
	$done = true;
	$script = realpath((string) ($_SERVER['SCRIPT_FILENAME'] ?? ''));
	if ($script === false || $script !== realpath(SB_PATH . 'index.php')) return;
	if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? '')) !== 'GET') return;
	$GLOBALS['stats_pv'] = bin2hex(random_bytes(8));
	register_shutdown_function('stats_track_shutdown', $_SERVER, $_GET);
}

// -----------------------------------------------------------------------
// Traceur JS (inc/stats.js -> beacon.php)
// -----------------------------------------------------------------------

/** Dossier web du site d'après SCRIPT_NAME (/sous-dossier, '' à la racine) */
function stats_web_base(array $server) {
	$dir = str_replace('\\', '/', dirname('/' . ltrim((string) ($server['SCRIPT_NAME'] ?? '/index.php'), '/')));
	return rtrim($dir, '/');
}

/**
 * Balise du traceur JS, ajoutée à l'en-tête des pages par le cœur
 * (sbModulesFrontHead()) : seulement pour une page qui sera notée
 */
function stats_front_head() {
	$pv = stats_pv();
	if (!preg_match('/^[a-f0-9]{16}$/', $pv)) return '';
	$ip = function_exists('sbClientIp') ? sbClientIp() : (string) ($_SERVER['REMOTE_ADDR'] ?? '');
	if (stats_excluded($_SERVER, $ip) || !stats_hit_from_request($_SERVER, $_GET, $ip)) return '';
	$base = stats_web_base($_SERVER) . '/datas/modules/stats/';
	$ver  = (int) @filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'stats.js');
	return "\n" . '<script src="' . htmlspecialchars($base . 'inc/stats.js?v=' . $ver, ENT_QUOTES) . '" data-pv="' . $pv
		. '" data-endpoint="' . htmlspecialchars($base . 'beacon.php', ENT_QUOTES) . '" defer></script>' . "\n";
}

/** Adresse absolue http(s) nettoyée (outlink, download), '' si invalide */
function stats_clean_absolute_url($url, array $extra = array()) {
	$url = trim((string) $url);
	if (!preg_match('~^https?://~i', $url) || strlen($url) > 2048) return '';
	$host = strtolower((string) parse_url($url, PHP_URL_HOST));
	if ($host === '' || !preg_match('/^[a-z0-9.\-\[\]:]+$/', $host)) return '';
	$scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
	$port = parse_url($url, PHP_URL_PORT);
	$path = stats_clean_url((string) parse_url($url, PHP_URL_PATH) . (($q = parse_url($url, PHP_URL_QUERY)) !== null ? '?' . $q : ''), $extra);
	return mb_substr($scheme . '://' . $host . ($port ? ':' . $port : '') . $path, 0, 255, 'UTF-8');
}

/**
 * Message du traceur JS. Le jeton pv désigne une page notée il y a moins de
 * 6 heures, par le même visiteur (empreinte du jour ou de la veille) :
 * un jeton recopié ailleurs ne sert à rien.
 *   e=view     : res (résolution) -> visite vue par le JS
 *   e=ping     : t (secondes de lecture) -> temps passé sur la dernière page
 *   e=outlink  : url (lien vers un autre site)
 *   e=download : url (fichier du site)
 * @return int code HTTP (204 : noté, 400 : invalide, 404 : jeton inconnu)
 */
function stats_beacon(sbdb $db, array $post, array $server, $ip, $now = null) {
	$now = $now ?? time();
	$pv = is_string($post['pv'] ?? null) ? $post['pv'] : '';
	$e  = is_string($post['e'] ?? null) ? $post['e'] : '';
	if (!preg_match('/^[a-f0-9]{16}$/', $pv) || !in_array($e, array('view', 'ping', 'outlink', 'download'), true)) return 400;
	$agent = (string) ($server['HTTP_USER_AGENT'] ?? '');
	if (stats_is_bot($agent)) return 400;

	$action = $db->row('SELECT a.`id`, a.`visit_id`, a.`time_spent`, v.`visitor`, v.`first_action`, v.`last_action`, v.`last_action_id`, v.`duration`, v.`actions`
		FROM {sb_stats_action} a JOIN {sb_stats_visit} v ON v.`id` = a.`visit_id`
		WHERE a.`pv` = ? AND a.`created_at` >= ? LIMIT 1', array($pv, date('Y-m-d H:i:s', $now - 6 * 3600)));
	if (!$action) return 404;
	$lang = stats_language($server['HTTP_ACCEPT_LANGUAGE'] ?? '');
	$known = false;
	foreach (array($now, $now - 86400) as $ts) {
		$d = date('Y-m-d', $ts);
		$salt = stats_day_salt($d, false, $now);
		if ($salt !== '' && hash_equals((string) $action['visitor'], stats_visitor_hash($ip, $agent, $lang, $salt, $d))) $known = true;
	}
	if (!$known) return 404;
	$visitId = (int) $action['visit_id'];

	if ($e === 'view') {
		$res = is_string($post['res'] ?? null) ? $post['res'] : '';
		$set = array('js' => 1);
		if (preg_match('/^(\d{2,5})x(\d{2,5})$/', $res, $m) && (int) $m[1] <= 20000 && (int) $m[2] <= 20000) $set['resolution'] = $res;
		$db->update('sb_stats_visit', $set, '`id` = ?', array($visitId));
		return 204;
	}

	if ($e === 'ping') {
		$t = (is_string($post['t'] ?? null) && ctype_digit($post['t'])) ? min(3600, (int) $post['t']) : -1;
		if ($t < 0) return 400;
		// Seulement la dernière action : les précédentes ont l'écart entre deux pages
		if ((int) $action['last_action_id'] !== (int) $action['id']) return 204;
		if ($t > (int) $action['time_spent']) $db->update('sb_stats_action', array('time_spent' => $t), '`id` = ?', array((int) $action['id']));
		$dur = (int) strtotime($action['last_action']) - (int) strtotime($action['first_action']) + $t;
		if ($dur > (int) $action['duration']) $db->update('sb_stats_visit', array('duration' => $dur), '`id` = ?', array($visitId));
		return 204;
	}

	// outlink / download : action ajoutée à la visite (500 au plus par visite)
	if ((int) $action['actions'] >= 500) return 204;
	$url = stats_clean_absolute_url(is_string($post['url'] ?? null) ? $post['url'] : '', stats_extra_params());
	if ($url === '') return 400;
	$own = strtolower(preg_replace('/:\d+$/', '', (string) ($server['HTTP_HOST'] ?? '')));
	$host = strtolower((string) parse_url($url, PHP_URL_HOST));
	if ($e === 'outlink' && $own !== '' && $host === $own) return 400;
	if ($e === 'download' && $own !== '' && $host !== $own) return 400;
	$date = date('Y-m-d H:i:s', $now);
	$file = $e === 'download' ? basename((string) parse_url($url, PHP_URL_PATH)) : '';
	$id = $db->insert('sb_stats_action', array('visit_id' => $visitId, 'created_at' => $date, 'type' => $e, 'url' => $url, 'title' => stats_cut($file, 255)));
	if (!$id) return 204;
	if ((int) $action['last_action_id'] > 0) {
		$spent = max(0, $now - (int) strtotime($action['last_action']));
		$db->run('UPDATE {sb_stats_action} SET `time_spent` = ? WHERE `id` = ? AND `time_spent` < ?', array(min($spent, 3600), (int) $action['last_action_id'], min($spent, 3600)));
	}
	$db->run('UPDATE {sb_stats_visit} SET `actions` = `actions` + 1, `last_action` = ?, `last_action_id` = ?, `duration` = ? WHERE `id` = ?',
		array($date, $id, max((int) $action['duration'], $now - (int) strtotime($action['first_action'])), $visitId));
	return 204;
}
