<?php
/**
 * Plugin Name: SBUIADMIN SEARCH
 * Description: Recherche
 * Version: 0.2.0
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 * File: functions.php
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

/* -------------------------------
 * Available functions :
 * -------------------------------
 * sbSearchTable
 * sbSearchTerms
 * sbSearchAccentMap
 * sbSearchFold
 * sbSearchCharForms
 * sbSearchRegexEscape
 * sbSearchRegexTerm
 * sbSearchEngine
 * sbSearchLikeTerm
 * sbSearchWhere
 * sbSearchDecoded
 * sbSearchPlainText
 * sbSearchMatches
 * sbSearchTableCells
 * sbSearchEscape
 * sbSearchRanges
 * sbSearchHighlight
 * sbSearchExcerpt
 * sbSearchHostIndex
 * sbSearchHostFor
 * sbSearchTitle
 * sbSearchTarget
 * sbSearchUrl
 * shortcode_sbsearch
 * ------------------------------- */

// --- Longueur minimale d'un terme retenu
defined('SB_SEARCH_MIN_LENGTH') OR define('SB_SEARCH_MIN_LENGTH', 3);
// --- Longueur de l'extrait affiche sous chaque resultat
defined('SB_SEARCH_EXCERPT')    OR define('SB_SEARCH_EXCERPT', 220);


/**
* Nom complet d'une table du CMS.
*
* Ce fichier est inclus globalement par header.php pour TOUS les modules :
* $module ne decrit donc pas forcement le module Search au moment ou une de
* ces fonctions s'execute. On ne lit jamais $module['tables'] ici.
* @param	string	$name	nom court (ex: sb_pages)
* @return	string
*/
if (!function_exists("sbSearchTable")) {
	function sbSearchTable($name) {
		return _AM_DB_PREFIX . $name;
	}
}

/**
* Decoupe la saisie du visiteur en termes exploitables.
* Les termes de moins de SB_SEARCH_MIN_LENGTH caracteres sont ecartes (bruit),
* sauf si la saisie entiere est plus courte - dans ce cas c'est l'appelant qui
* affiche le message "trop court", pas cette fonction qui devine.
* @param	string	$query	saisie brute
* @return	array	termes en minuscules, sans doublon
*/
if (!function_exists("sbSearchTerms")) {
	function sbSearchTerms($query) {
		$query = trim(preg_replace('/\s+/u', ' ', (string)$query));
		if ($query === '') return array();

		$terms = array();
		foreach (explode(' ', $query) as $term) {
			$term = trim($term, " \t\n\r\0\x0B\"'");
			if ($term === '') continue;
			if (mb_strlen($term, 'UTF-8') < SB_SEARCH_MIN_LENGTH) continue;
			// --- Replie des l'entree : tout le reste de la chaine compare des
			// --- textes replies, jamais des textes bruts.
			$terms[sbSearchFold($term)] = true;
		}

		return array_keys($terms);
	}
}

/**
* Table de repliage des accents, STRICTEMENT 1 caractere pour 1 caractere.
*
* Le 1:1 n'est pas cosmetique : sbSearchExcerpt() et sbSearchHighlight()
* reperent les termes sur le texte replie puis decoupent le texte D'ORIGINE
* aux memes positions. Une correspondance qui changerait la longueur
* (oe -> oe en deux lettres, ae -> ae) decalerait tout. Ces ligatures sont
* donc volontairement absentes.
* @return	array
*/
if (!function_exists("sbSearchAccentMap")) {
	function sbSearchAccentMap() {
		static $map = null;
		if ($map !== null) return $map;

		$map = array(
			'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a',
			'ç'=>'c',
			'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
			'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i',
			'ñ'=>'n',
			'ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o',
			'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u',
			'ý'=>'y','ÿ'=>'y',
			'ß'=>'s',
		);
		// --- Majuscules : meme repliage, vers la minuscule non accentuee
		foreach (array_keys($map) as $lower) {
			$upper = mb_strtoupper($lower, 'UTF-8');
			if (mb_strlen($upper, 'UTF-8') === 1 && !isset($map[$upper])) $map[$upper] = $map[$lower];
		}

		return $map;
	}
}

/**
* Replie un texte pour la comparaison : accents retires, minuscules.
*
* Conserve le nombre de CARACTERES (voir sbSearchAccentMap) afin que les
* positions trouvees sur le texte replie restent valables sur l'original.
* @param	string	$text
* @return	string
*/
if (!function_exists("sbSearchFold")) {
	function sbSearchFold($text) {
		return mb_strtolower(strtr((string)$text, sbSearchAccentMap()), 'UTF-8');
	}
}

/**
* Toutes les formes sous lesquelles un caractere peut se trouver EN BASE.
*
* Trois formes coexistent selon le champ et le nombre de passages d'encodage :
* le caractere litteral (e), l'entite (&eacute;), et l'entite doublement
* encodee (&amp;eacute;) pour les contenus repasses par l'editeur puis par
* displayText(). Voir project-sbuiadmin-html-entities-storage.
* @param	string	$char	un caractere replie (non accentue, minuscule)
* @return	array	formes a chercher
*/
if (!function_exists("sbSearchCharForms")) {
	function sbSearchCharForms($char) {
		static $families = null;

		if ($families === null) {
			$families = array();
			foreach (sbSearchAccentMap() as $accented => $base) {
				$families[$base][] = $accented;
			}
		}

		$forms = array($char);
		if (isset($families[$char])) {
			foreach ($families[$char] as $accented) {
				$forms[] = $accented;
				$entity  = htmlentities($accented, ENT_QUOTES, 'UTF-8');
				if ($entity !== $accented) {
					$forms[] = $entity;
					$forms[] = '&amp;' . substr($entity, 1);
				}
			}
		}

		return array_values(array_unique($forms));
	}
}

/**
* Echappe une chaine pour une expression reguliere SQL.
*
* Volontairement limite aux metacaracteres POSIX ERE : MySQL 5.7 utilise
* encore l'ancien moteur POSIX, qui ne connait ni (?:...) ni \d. On n'emploie
* donc que des groupes simples et des alternations.
* @param	string	$text
* @return	string
*/
if (!function_exists("sbSearchRegexEscape")) {
	function sbSearchRegexEscape($text) {
		return preg_replace('/([.\[\]()*+?{}|^$\\\\])/', '\\\\$1', (string)$text);
	}
}

/**
* Traduit un terme en expression reguliere SQL insensible aux accents ET a
* la forme de stockage : chaque caractere devient l'alternation de toutes
* ses ecritures possibles en base.
*
* "geoles" produit ainsi g(e|è|é|ê|ë|&egrave;|...)(o|ô|...)les — ce qui
* retrouve aussi bien "geoles" que "geôles" que "ge&ocirc;les".
* @param	string	$term	terme deja replie par sbSearchFold()
* @return	string
*/
if (!function_exists("sbSearchRegexTerm")) {
	function sbSearchRegexTerm($term) {
		$chars  = preg_split('//u', (string)$term, -1, PREG_SPLIT_NO_EMPTY);
		$regex  = '';

		foreach ((array)$chars as $char) {
			$forms = sbSearchCharForms($char);
			if (count($forms) === 1) {
				$regex .= sbSearchRegexEscape($forms[0]);
			} else {
				$escaped = array();
				foreach ($forms as $form) $escaped[] = sbSearchRegexEscape($form);
				$regex .= '(' . implode('|', $escaped) . ')';
			}
		}

		return $regex;
	}
}

/**
* Quel moteur de filtrage SQL utiliser : "regexp" ou "like".
*
* REGEXP est le seul des deux qui puisse ignorer les accents, mais son
* comportement sur du multi-octets depend du moteur : MySQL 8 (ICU) et
* MariaDB (PCRE) le gerent, l'ancien moteur POSIX de MySQL 5.7 non. Plutot
* que de parier sur la version, on POSE LA QUESTION a la base une fois par
* requete avec un motif temoin. Si elle ne repond pas 1, on retombe sur le
* LIKE : la recherche redevient sensible aux accents, mais elle fonctionne.
* @return	string	'regexp' ou 'like'
*/
if (!function_exists("sbSearchEngine")) {
	function sbSearchEngine() {
		global $sbsql;
		static $engine = null;

		if ($engine !== null) return $engine;

		$engine  = 'like';
		$request = $sbsql->query("SELECT ('g" . "\xc3\xa9" . "oles' REGEXP 'g(e|" . "\xc3\xa9" . ")oles') AS sbok");
		if ($request) {
			$row = $sbsql->assoc($request);
			if ($row && intval($row['sbok']) === 1) $engine = 'regexp';
		}

		return $engine;
	}
}

/**
* Prepare un terme pour un LIKE : echappement SQL puis neutralisation des
* jokers LIKE (% et _). Sans cette seconde etape, une recherche sur "%"
* remonterait la totalite du site.
* @param	string	$term
* @return	string
*/
if (!function_exists("sbSearchLikeTerm")) {
	function sbSearchLikeTerm($term) {
		global $sbsql;

		$safe = $sbsql->escape_string($term);

		return str_replace(array('%', '_'), array('\%', '\_'), $safe);
	}
}

/**
* Construit la clause WHERE d'une source : TOUS les termes doivent etre
* presents (ET), chacun pouvant apparaitre dans N'IMPORTE laquelle des
* colonnes cherchees (CONCAT_WS, qui ignore les NULL contrairement a CONCAT).
*
* Ce filtre ne fait que DEGROSSIR. Il travaille sur la forme STOCKEE, ou les
* accents sont des entites HTML (voir project-sbuiadmin-html-entities-storage)
* et ou rien ne distingue le mot cherche du nom d'une entite : c'est
* sbSearchMatches() qui tranche ensuite, en PHP, sur le texte visible.
*
* @param	array	$columns	colonnes SQL (deja prefixees t1./t2. si besoin)
* @param	array	$terms		termes issus de sbSearchTerms()
* @return	string	fragment SQL commencant par " AND (" , ou '' si rien
*/
if (!function_exists("sbSearchWhere")) {
	function sbSearchWhere($columns, $terms) {
		global $sbsql;

		if (empty($columns) || empty($terms)) return '';

		$haystack = "CONCAT_WS(' ', " . implode(', ', $columns) . ")";
		$engine   = sbSearchEngine();
		$clauses  = array();

		foreach ($terms as $term) {
			$regex = ($engine === 'regexp') ? sbSearchRegexTerm($term) : '';

			// --- Garde-fou de taille : chaque lettre accentuable devient une
			// --- alternation d'une cinquantaine de caracteres. Un terme tres
			// --- long produirait un motif que l'ancien moteur POSIX de MySQL
			// --- 5.7 refuse - et un refus se traduirait par une source vide
			// --- en silence (query() renvoie false). Au-dela, on retombe sur
			// --- le LIKE, moins juste mais toujours fonctionnel.
			if ($regex !== '' && strlen($regex) <= 4000) {
				$clauses[] = "$haystack REGEXP '" . $sbsql->escape_string($regex) . "'";
			} else {
				// --- Repli : pas d'insensibilite aux accents, on cherche donc
				// --- le terme tel quel ET sa forme encodee en entites.
				$variants = array(sbSearchLikeTerm($term));
				$encoded  = htmlentities($term, ENT_QUOTES, 'UTF-8');
				if ($encoded !== $term) $variants[] = sbSearchLikeTerm($encoded);

				$ors = array();
				foreach ($variants as $variant) $ors[] = "$haystack LIKE '%$variant%'";
				$clauses[] = implode(' OR ', $ors);
			}
		}

		return ' AND (' . implode(') AND (', $clauses) . ')';
	}
}

/**
* Ramene un champ de la base a sa forme decodee, SANS retirer les balises.
*
* DEUX PIEGES, tous deux verifies en conditions reelles :
*
* 1. displayLang() ne lit que le PREMIER bloc [fr]...[/fr] d'une chaine
*    (preg_match, pas preg_match_all). Concatener deux champs multilingues
*    avant de le lui passer - "[fr]titre[/fr] [fr]contenu[/fr]" - jette donc
*    silencieusement le second. On extrait ici TOUS les blocs de la langue
*    courante. Signale par la session informatux, ou ce defaut faisait perdre
*    le corps de tous les articles a leur recherche.
*
* 2. Le contenu est DOUBLEMENT encode en base ("&amp;lt;p&amp;gt;") : il faut
*    deux passages de decodage pour retrouver le HTML reel. Voir
*    project-sbuiadmin-html-entities-storage.
*
* @param	string	$raw	valeur telle que stockee
* @return	string	texte decode, balises comprises
*/
if (!function_exists("sbSearchDecoded")) {
	function sbSearchDecoded($raw) {
		$lang = (isset($_SESSION['lang']) && $_SESSION['lang'] != '') ? $_SESSION['lang'] : 'fr';
		$text = (string)$raw;

		// --- TOUS les blocs de la langue courante, pas seulement le premier
		if (preg_match_all('#\[' . $lang . '\](.*?)\[/' . $lang . ']#s', $text, $matches)) {
			$text = implode(' ', $matches[1]);
		}

		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

		return $text;
	}
}

/**
* Ramene un champ de la base au TEXTE VISIBLE par un lecteur : decodage
* (sbSearchDecoded), puis suppression de ce qui ne se lit pas.
*
* strip_tags() ne suffit pas : il retire les balises <script> et <style> mais
* CONSERVE leur contenu. Sans le nettoyage prealable ci-dessous, une regle CSS
* ou un bloc <script type="application/json"> ressortirait en clair dans
* l'extrait presente au visiteur (mesure faite sur la base informatux : une
* recherche sur un code couleur "9b56ff" remontait un article, avec une
* declaration de variables CSS en guise d'extrait).
*
* DISTINCTION VOLONTAIRE SUR <script>, et elle n'est pas cosmetique :
*  - <style> et <noscript> : supprimes AVEC leur contenu, ils ne sont jamais
*    editoriaux ;
*  - <script> portant un type de DONNEES (application/json, ld+json,
*    template...) : supprime avec son contenu, un bloc de donnees n'est pas
*    de la prose ;
*  - <script> ordinaire ou text/javascript : son contenu est CONSERVE. Sur un
*    site qui publie du code, l'exemple JavaScript EST l'article. Tout
*    supprimer rendait introuvable un billet dont le corps est un exemple
*    (constate chez informatux : "jquery" passait de 18 a 17 resultats, et
*    "DOMNodeInserted", qui n'existe que dans cet exemple, devenait
*    introuvable).
*
* @param	string	$raw	valeur telle que stockee
* @return	string	texte brut
*/
if (!function_exists("sbSearchPlainText")) {
	function sbSearchPlainText($raw) {
		$text = sbSearchDecoded($raw);

		// --- Jamais editorial : on retire aussi ce que ces balises portent
		$text = preg_replace('#<(style|noscript)\b[^>]*>.*?</\1\s*>#is', ' ', $text);
		// --- <script> de DONNEES uniquement (un type= qui n'est pas du
		// --- JavaScript) : json, ld+json, x-template, text/template...
		$text = preg_replace('#<script\b[^>]*\btype\s*=\s*["\']?[^"\'>]*(json|template)[^"\'>]*["\']?[^>]*>.*?</script\s*>#is', ' ', $text);

		// --- Shortcodes non resolus : on ne les affiche pas au visiteur
		$text = preg_replace('/\[CS[^\]]*\]/', ' ', $text);
		$text = preg_replace('/\[\/?[a-z]{2}\]/i', ' ', $text);

		// --- Balises de BLOC : remplacees par une espace, sinon deux cellules
		// --- ou deux paragraphes voisins se collent ("AUTEURDave Chapman").
		$text = preg_replace('#<\s*/?\s*(br|p|div|li|ul|ol|dl|dt|dd|td|th|tr|table|thead|tbody|tfoot|h[1-6]|section|article|aside|header|footer|nav|figure|figcaption|blockquote|cite|pre|hr)\b[^>]*>#i', ' ', $text);

		$text = strip_tags($text);
		$text = preg_replace('/\s+/u', ' ', $text);

		return trim($text);
	}
}

/**
* Confirme en PHP qu'un resultat merite d'etre presente : TOUS les termes
* doivent apparaitre dans le TEXTE VISIBLE de la fiche.
*
* Le LIKE SQL ne fait que degrossir, et il travaille sur la forme STOCKEE :
* il ne fait donc pas la difference entre le mot cherche et le nom d'une
* entite HTML. Chercher "amp" remontait ainsi tout le site (chaque "&amp;"
* stocke), et "eacute" tous les contenus accentues. Cette seconde passe
* elimine ces faux positifs. Parade mise au point avec la session informatux,
* qui observait le meme comportement.
*
* @param	array|string	$fields	valeurs brutes des colonnes cherchees
* @param	array			$terms
* @return	bool
*/
if (!function_exists("sbSearchMatches")) {
	function sbSearchMatches($fields, $terms) {
		if (empty($terms)) return true;

		$text = '';
		foreach ((array)$fields as $field) $text .= ' ' . sbSearchPlainText($field);
		$text = sbSearchFold($text);

		// --- Les termes sont deja replies par sbSearchTerms()
		foreach ($terms as $term) {
			if (mb_strpos($text, $term, 0, 'UTF-8') === false) return false;
		}

		return true;
	}
}

/**
* Valeurs d'une ligne de tableau (module Table).
*
* sb_table_datas.content ne contient pas du texte mais du JSON produit par
* l'administration : {"0":{"i":"<champ>","v":"<valeur>"}, ...}. Sans ce
* decodage, l'extrait affiche au visiteur serait le JSON brut, accolades et
* noms de champs compris.
* @param	string	$json
* @return	string	valeurs concatenees, ou la chaine d'origine si ce n'est
*					pas du JSON exploitable
*/
if (!function_exists("sbSearchTableCells")) {
	function sbSearchTableCells($json) {
		$decoded = json_decode((string)$json, true);
		if (!is_array($decoded)) return (string)$json;

		$values = array();
		foreach ($decoded as $cell) {
			if (is_array($cell) && isset($cell['v']) && trim((string)$cell['v']) !== '') $values[] = $cell['v'];
		}

		return (empty($values)) ? '' : implode(' - ', $values);
	}
}

/**
* Echappement HTML COMPLET, pour l'affichage d'un resultat de recherche.
*
* On n'utilise volontairement PAS $sbsanitize->htmlSpecialChars() : cette
* methode defait l'echappement de l'esperluette juste apres l'avoir pose
* (sbuiadmin-sanitize.php, le preg_replace "/&amp;/i" => "&" en fin de
* methode), afin de laisser passer les &nbsp; du contenu redactionnel. Un
* extrait de recherche est du texte deja entierement decode : le "&" doit y
* redevenir "&amp;", sans quoi une suite comme "&lt;" ecrite en clair par un
* redacteur serait reinterpretee par le navigateur.
* @param	string	$text
* @return	string
*/
if (!function_exists("sbSearchEscape")) {
	function sbSearchEscape($text) {
		return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
	}
}

/**
* Positions de tous les termes dans un texte, calculees sur sa forme repliee.
*
* Les plages qui se chevauchent sont fusionnees : sans cela, deux termes qui
* se recouvrent produiraient des <mark> imbriques.
* @param	string	$text	texte brut (non echappe)
* @param	array	$terms	termes deja replies
* @return	array	plages [debut, fin] en CARACTERES, triees et fusionnees
*/
if (!function_exists("sbSearchRanges")) {
	function sbSearchRanges($text, $terms) {
		$folded = sbSearchFold($text);

		// --- Garde-fou : le repliage doit conserver le nombre de caracteres
		// --- (voir sbSearchAccentMap). Sinon les positions ne sont pas
		// --- reportables sur le texte d'origine et on prefere ne rien
		// --- surligner plutot que de decouper au mauvais endroit.
		if (mb_strlen($folded, 'UTF-8') !== mb_strlen($text, 'UTF-8')) return array();

		$ranges = array();
		foreach ($terms as $term) {
			$length = mb_strlen($term, 'UTF-8');
			if ($length === 0) continue;
			$offset = 0;
			while (($pos = mb_strpos($folded, $term, $offset, 'UTF-8')) !== false) {
				$ranges[] = array($pos, $pos + $length);
				$offset   = $pos + $length;
			}
		}
		if (empty($ranges)) return array();

		usort($ranges, function($a, $b) { return ($a[0] - $b[0]); });

		$merged = array();
		foreach ($ranges as $range) {
			$last = count($merged) - 1;
			if ($last >= 0 && $range[0] <= $merged[$last][1]) {
				if ($range[1] > $merged[$last][1]) $merged[$last][1] = $range[1];
			} else {
				$merged[] = $range;
			}
		}

		return $merged;
	}
}

/**
* Echappe un texte pour l'affichage en entourant les termes trouves d'un
* <mark>. Le texte entre BRUT (non echappe) : c'est cette fonction qui
* echappe, morceau par morceau, de facon a n'inserer que le balisage de son
* propre fait.
* @param	string	$text	texte brut
* @param	array	$terms	termes deja replies
* @return	string	HTML pret a afficher
*/
if (!function_exists("sbSearchHighlight")) {
	function sbSearchHighlight($text, $terms) {
		$ranges = (empty($terms)) ? array() : sbSearchRanges($text, $terms);
		if (empty($ranges)) return sbSearchEscape($text);

		$html   = '';
		$cursor = 0;
		foreach ($ranges as $range) {
			if ($range[0] > $cursor) {
				$html .= sbSearchEscape(mb_substr($text, $cursor, $range[0] - $cursor, 'UTF-8'));
			}
			$html  .= '<mark>' . sbSearchEscape(mb_substr($text, $range[0], $range[1] - $range[0], 'UTF-8')) . '</mark>';
			$cursor = $range[1];
		}
		$html .= sbSearchEscape(mb_substr($text, $cursor, null, 'UTF-8'));

		return $html;
	}
}

/**
* Extrait le passage du texte qui entoure le premier terme trouve, tronque a
* SB_SEARCH_EXCERPT caracteres, echappe puis surligne.
* @param	string	$raw	valeur brute de la base
* @param	array	$terms	termes deja replies
* @return	string	HTML pret a afficher
*/
if (!function_exists("sbSearchExcerpt")) {
	function sbSearchExcerpt($raw, $terms) {
		$text = sbSearchPlainText($raw);
		if ($text === '') return '';

		// --- Position du premier terme, cherchee sur la forme repliee : une
		// --- recherche sur "geoles" doit se caler sur "geoles" comme sur
		// --- "geoles" accentue.
		$ranges = (empty($terms)) ? array() : sbSearchRanges($text, $terms);
		$pos    = (empty($ranges)) ? 0 : $ranges[0][0];

		// --- On recule d'un quart de l'extrait pour donner du contexte avant
		$start = max(0, $pos - intval(SB_SEARCH_EXCERPT / 4));
		$piece = mb_substr($text, $start, SB_SEARCH_EXCERPT, 'UTF-8');

		$prefix = ($start > 0) ? '...' : '';
		$suffix = (mb_strlen($text, 'UTF-8') > $start + SB_SEARCH_EXCERPT) ? '...' : '';

		return $prefix . sbSearchHighlight($piece, $terms) . $suffix;
	}
}

/**
* Index des contenus HOTES qui portent un shortcode.
*
* Certains modules cherches (Telechargements, Galeries, Tableaux, Tabbs,
* Formulaires) n'ont AUCUNE page publique : ils ne s'affichent qu'a travers
* un shortcode [CS name=sbxxx id=N] pose dans un contenu. Un resultat de
* recherche portant sur ces modules n'a donc pas d'URL naturelle - on
* retrouve ici le contenu qui l'affiche reellement.
*
* Sources scannees : pages, actualites, onglets (Tabbs) et blocs de contenu.
* Un seul balayage par table (les shortcodes sont rares), puis analyse en PHP :
* c'est 4 requetes au total quel que soit le nombre de resultats, la ou une
* resolution resultat par resultat en couterait 4 par resultat.
*
* @return	array	['sbgallery' => [12 => ['url' => ..., 'label' => ...]], ...]
*/
if (!function_exists("sbSearchHostIndex")) {
	function sbSearchHostIndex() {
		global $sbsql, $sbsanitize;
		static $index = null;

		if ($index !== null) return $index;
		$index = array();

		$t_pages    = sbSearchTable('sb_pages');
		$t_news     = sbSearchTable('sb_news');
		$t_tabbstab = sbSearchTable('sb_tabbs_tab');
		$t_blocs    = sbSearchTable('sb_blocs');

		$lang = (isset($_SESSION['lang']) && $_SESSION['lang'] != '') ? $_SESSION['lang'] : 'fr';

		// --- Enregistre tous les shortcodes trouves dans un contenu
		$collect = function($content, $url, $label) use (&$index) {
			if ($url === false) return;
			if (!preg_match_all('/\[CS([^\]]*)\]/', (string)$content, $matches)) return;
			foreach ($matches[1] as $attrs) {
				if (!preg_match('/\bname=([a-z0-9_]+)/i', $attrs, $mn)) continue;
				if (!preg_match('/\bid=(\d+)/i', $attrs, $mi)) continue;
				$name = strtolower($mn[1]);
				$id   = intval($mi[1]);
				// --- Premier hote trouve gagne : un meme shortcode peut etre
				// --- pose a plusieurs endroits, un lien suffit au visiteur.
				if (!isset($index[$name][$id])) $index[$name][$id] = array('url' => $url, 'label' => $label);
			}
		};

		// --------------------------
		// --- Pages
		// --------------------------
		$query   = "SELECT id, menu, title, seo_url, url_custom, content
					FROM {$t_pages}
					WHERE active = '1' AND content LIKE '%[CS %'";
		$request = $sbsql->query($query);
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				$collect($row['content'], sbSearchPageUrl($row), sbSearchPageLabel($row));
			}
		}

		// --------------------------
		// --- Actualites
		// --------------------------
		$query   = "SELECT id, title, desc_short, desc_full
					FROM {$t_news}
					WHERE active = '1' AND (desc_full LIKE '%[CS %' OR desc_short LIKE '%[CS %')";
		$request = $sbsql->query($query);
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				$url   = sbSearchNewsUrl($row['id'], $row['title']);
				$label = $sbsanitize->displayText($sbsanitize->displayLang($row['title'], $lang), 'UTF-8');
				$collect($row['desc_full'] . ' ' . $row['desc_short'], $url, $label);
			}
		}

		// --------------------------
		// --- Onglets (Tabbs) : deux sauts. Le contenu d'un onglet peut porter
		// --- un shortcode, mais l'onglet lui-meme n'a pas d'URL - il s'affiche
		// --- via [CS name=sbtabbs id=<groupe>], dont l'hote est deja indexe
		// --- ci-dessus. On resout donc l'onglet vers l'hote de son groupe.
		// --------------------------
		$query   = "SELECT tid, content
					FROM {$t_tabbstab}
					WHERE active = '1' AND content LIKE '%[CS %'";
		$request = $sbsql->query($query);
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				$host = isset($index['sbtabbs'][intval($row['tid'])]) ? $index['sbtabbs'][intval($row['tid'])] : false;
				if ($host) $collect($row['content'], $host['url'], $host['label']);
			}
		}

		// --------------------------
		// --- Blocs de contenu : rattaches a une ou plusieurs pages
		// --- (pages_id separes par |). On lie vers la premiere page active.
		// --------------------------
		$query   = "SELECT pages_id, content
					FROM {$t_blocs}
					WHERE active = '1' AND content LIKE '%[CS %'";
		$request = $sbsql->query($query);
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				$page_ids = array_filter(array_map('intval', explode('|', (string)$row['pages_id'])));
				if (empty($page_ids)) continue;
				$in       = implode(',', $page_ids);
				$q_page   = "SELECT id, menu, title, seo_url, url_custom
							 FROM {$t_pages}
							 WHERE active = '1' AND id IN ($in) ORDER BY sort ASC LIMIT 1";
				$r_page   = $sbsql->query($q_page);
				$page     = $sbsql->assoc($r_page);
				if ($page) $collect($row['content'], sbSearchPageUrl($page), sbSearchPageLabel($page));
			}
		}

		return $index;
	}
}

/**
* URL publique d'une page du CMS (meme construction que le menu principal,
* voir sbGetMenu() dans inc/functions.php).
* @param	array	$row	ligne de sb_pages (seo_url, url_custom)
* @return	string|false
*/
if (!function_exists("sbSearchPageUrl")) {
	function sbSearchPageUrl($row) {
		if (isset($row['url_custom']) && trim((string)$row['url_custom']) != '') return $row['url_custom'];
		$seo = trim((string)$row['seo_url']);
		if ($seo == '') return SB_URL;
		return sbGetSeoUrl("index.php?p=pages&id=$seo", $seo, false);
	}
}

/**
* Libelle lisible d'une page (titre, a defaut entree de menu).
* @param	array	$row	ligne de sb_pages
* @return	string
*/
if (!function_exists("sbSearchPageLabel")) {
	function sbSearchPageLabel($row) {
		$label = sbSearchTitle($row['title']);
		if (trim($label) == '') $label = sbSearchTitle($row['menu']);

		return $label;
	}
}

/**
* URL publique d'un article (meme construction que shortcode_sbnews_item()).
* @param	int		$id
* @param	string	$title	titre brut de la base
* @return	string
*/
if (!function_exists("sbSearchNewsUrl")) {
	function sbSearchNewsUrl($id, $title) {
		global $sbsanitize;

		$lang      = (isset($_SESSION['lang']) && $_SESSION['lang'] != '') ? $_SESSION['lang'] : 'fr';
		$id        = intval($id);
		$clean     = $sbsanitize->displayText($sbsanitize->displayLang($title, $lang), 'UTF-8');
		$title_url = $sbsanitize->stripTags(strtolower(sbRewriteString($clean)));

		return sbGetSeoUrl("index.php?p=news&op=article&id=$id", "news/article/$id/$title_url", false);
	}
}

/**
* Hote d'un shortcode donne, ou false si ce contenu n'est pose nulle part.
* @param	string	$name	nom du shortcode (ex: sbgallery)
* @param	int		$id
* @return	array|false		['url' => ..., 'label' => ...]
*/
if (!function_exists("sbSearchHostFor")) {
	function sbSearchHostFor($name, $id) {
		$index = sbSearchHostIndex();
		$name  = strtolower($name);
		$id    = intval($id);

		return isset($index[$name][$id]) ? $index[$name][$id] : false;
	}
}

/**
* Titre d'un resultat, pret a etre affiche : extraction de la langue courante
* puis echappement HTML.
*
* L'echappement est indispensable ici : Smarty n'est pas configure en
* echappement automatique sur ce CMS (aucun escape_html), et displayText()
* DECODE les entites au lieu de les poser. Un titre contenant du balisage
* serait donc interprete par le navigateur.
* @param	string	$raw
* @return	string
*/
if (!function_exists("sbSearchTitle")) {
	function sbSearchTitle($raw) {
		global $sbsanitize;

		$lang = (isset($_SESSION['lang']) && $_SESSION['lang'] != '') ? $_SESSION['lang'] : 'fr';

		return sbSearchEscape($sbsanitize->displayText($sbsanitize->displayLang((string)$raw, $lang), 'UTF-8'));
	}
}

/**
* Ou pointe la recherche sur ce site.
*
* Deux integrations possibles, et il faut les distinguer :
*  1. une PAGE du CMS dont le champ "Module" vaut "search" (integration
*     recommandee : URL propre, titre et vue de theme choisis dans l'admin) ;
*  2. a defaut, l'appel direct index.php?p=search.
*
* sbRewriteUrl() exclut volontairement "search" (comme "pages" et "contact")
* de sa detection de modules : avec la reecriture d'URL active, /search n'est
* donc PAS route vers ce module mais vers une page portant ce seo_url. D'ou
* la recherche de la page hote ci-dessous plutot qu'une URL codee en dur.
*
* @return	array	['action' => url de formulaire, 'hidden' => champs caches,
*					 'base' => url complete prete a recevoir des parametres]
*/
if (!function_exists("sbSearchTarget")) {
	function sbSearchTarget() {
		global $sbsql;
		static $target = null;

		if ($target !== null) return $target;

		$table   = sbSearchTable('sb_pages');
		$request = $sbsql->query("SELECT seo_url FROM $table WHERE active = '1' AND module_view = 'search' ORDER BY sort ASC LIMIT 1");
		$page    = $sbsql->assoc($request);
		$seo     = ($page && trim((string)$page['seo_url']) != '') ? trim($page['seo_url']) : false;

		if ($seo !== false) {
			// --- Integration par une page du CMS
			if (SBREWRITEURL) {
				$target = array(
					'action' => SB_URL . $seo . DIRECTORY_SEPARATOR,
					'hidden' => array(),
					'base'   => SB_URL . $seo . DIRECTORY_SEPARATOR,
				);
			} else {
				$target = array(
					'action' => SB_URL . 'index.php',
					'hidden' => array('p' => 'pages', 'id' => $seo),
					'base'   => SB_URL . 'index.php?p=pages&id=' . rawurlencode($seo),
				);
			}
		} else {
			// --- Appel direct du module. Fonctionne aussi avec la reecriture
			// --- active : sbRewriteUrl() rend la main a $_GET['p'] des que le
			// --- chemin demande est index.php.
			$target = array(
				'action' => SB_URL . 'index.php',
				'hidden' => array('p' => 'search'),
				'base'   => SB_URL . 'index.php?p=search',
			);
		}

		return $target;
	}
}

/**
* URL complete d'une recherche (utilisee par la pagination).
*
* Un formulaire en method="get" ECRASE la chaine de requete de son action :
* c'est pourquoi le formulaire, lui, passe par des champs caches (voir
* sbSearchTarget) et n'utilise pas cette fonction.
*
* @param	string	$query	saisie du visiteur
* @param	int		$offset	rang du premier resultat affiche
* @return	string
*/
if (!function_exists("sbSearchUrl")) {
	function sbSearchUrl($query, $offset = 0) {
		$target = sbSearchTarget();
		$url    = $target['base'];
		$url   .= (strpos($url, '?') === false) ? '?' : '&';
		$url   .= 's=' . rawurlencode((string)$query);
		if ($offset > 0) $url .= '&l=' . intval($offset);

		return $url;
	}
}

/**
* Shortcode [CS name=sbsearch] : formulaire de recherche a poser dans
* n'importe quel contenu (le theme du site n'en fournit pas forcement un).
* Parametres optionnels : class=maclasse , placeholder=Mon+texte
* @param	array	$param
* @return	string	HTML
*/
if (!function_exists("shortcode_sbsearch")) {
	function shortcode_sbsearch($param = '') {
		global $sbsanitize;

		// --- header.php inclut ce fichier pour TOUS les modules mais jamais
		// --- le fichier de langue : sans ce chargement, les libelles du
		// --- formulaire seraient des constantes indefinies des que le
		// --- shortcode est pose ailleurs que sur la page de recherche.
		if (!defined('_CMS_SEARCH_SUBMIT')) {
			$sblang_search = (SBLANG && (!isset($_SESSION['lang']) || $_SESSION['lang'] != 'en')) ? SBLANG : 'en_US';
			$lang_path     = SB_MODULES_DIR . 'search' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_search . '.php';
			if (file_exists($lang_path)) include_once($lang_path);
		}

		$class       = (isset($param['class'])) ? sbSearchEscape($param['class']) : 'sbsearch-form';
		$placeholder = (isset($param['placeholder']))
					 ? sbSearchEscape(str_replace('+', ' ', $param['placeholder']))
					 : (defined('_CMS_SEARCH_PLACEHOLDER') ? _CMS_SEARCH_PLACEHOLDER : 'Rechercher...');
		$submit      = (defined('_CMS_SEARCH_SUBMIT')) ? _CMS_SEARCH_SUBMIT : 'Rechercher';
		$target      = sbSearchTarget();
		$current     = (isset($_GET['s'])) ? sbSearchEscape($sbsanitize->stopXSS($_GET['s'])) : '';

		$html  = '<form class="' . $class . '" action="' . $target['action'] . '" method="get" role="search">';
		// --- Champs caches obligatoires : un formulaire GET remplace la chaine
		// --- de requete de son action, "?p=search" ecrit dans l'action serait
		// --- donc perdu a la soumission.
		foreach ($target['hidden'] as $name => $value) {
			$html .= '<input type="hidden" name="' . sbSearchEscape($name) . '" value="' . sbSearchEscape($value) . '" />';
		}
		$html .= '<input class="sbsearch-input" type="text" name="s" value="' . $current . '" placeholder="' . $placeholder . '" aria-label="' . $placeholder . '" />';
		$html .= '<button class="sbsearch-submit" type="submit">' . $submit . '</button>';
		$html .= '</form>';

		return $html;
	}
}

?>
