<?php
/**
 * Font Awesome 7 Free (hébergé dans assets/fontawesome/).
 * sbFaClass() renvoie les classes FA7 d'une icône : accepte la forme FA7
 * ("fa-solid fa-house", "fab fa-github") ou un ancien nom FA4 ("home",
 * "envelope-o", "facebook"), converti d'après la table ci-dessous (générée
 * depuis le fichier v4-shims de Font Awesome). Valeur invalide : chaîne vide.
 */
if (!function_exists('sbFaClass')) {
	function sbFaClass($icon) {
		static $map = null;
		$icon = strtolower(trim(preg_replace('/\s+/', ' ', (string) $icon)));
		if (preg_match('/^(fa-solid|fa-regular|fa-brands|fas|far|fab) (fa-[a-z0-9-]+)$/', $icon, $m)) {
			$style = array('fas' => 'fa-solid', 'far' => 'fa-regular', 'fab' => 'fa-brands');
			return ($style[$m[1]] ?? $m[1]) . ' ' . $m[2];
		}
		$icon = preg_replace('/^(fa )?fa-/', '', $icon);
		if (!preg_match('/^[a-z0-9][a-z0-9-]*$/', $icon)) return '';
		if ($map === null) $map = sbFaMap();
		$style = array('s' => 'fa-solid', 'r' => 'fa-regular', 'b' => 'fa-brands');
		if (!isset($map[$icon])) return 'fa-solid fa-' . $icon;
		$v = explode(':', $map[$icon], 2);
		return $style[$v[0]] . ' fa-' . ($v[1] ?? $icon);
	}
}

if (!function_exists('sbFaMap')) {
	// Ancien nom FA4 => style (s, r, b) et nouveau nom s'il a changé
	function sbFaMap() {
		return array(
		'500px'=>'b', 'address-book-o'=>'r:address-book', 'address-card-o'=>'r:address-card', 'adjust'=>'s:circle-half-stroke', 'adn'=>'b', 'amazon'=>'b',
		'ambulance'=>'s:truck-medical', 'american-sign-language-interpreting'=>'s:hands-asl-interpreting', 'android'=>'b', 'angellist'=>'b', 'angle-double-down'=>'s:angles-down', 'angle-double-left'=>'s:angles-left',
		'angle-double-right'=>'s:angles-right', 'angle-double-up'=>'s:angles-up', 'apple'=>'b', 'archive'=>'s:box-archive', 'area-chart'=>'s:chart-area', 'arrow-circle-down'=>'s:circle-arrow-down',
		'arrow-circle-left'=>'s:circle-arrow-left', 'arrow-circle-o-down'=>'r:circle-down', 'arrow-circle-o-left'=>'r:circle-left', 'arrow-circle-o-right'=>'r:circle-right', 'arrow-circle-o-up'=>'r:circle-up', 'arrow-circle-right'=>'s:circle-arrow-right',
		'arrow-circle-up'=>'s:circle-arrow-up', 'arrows'=>'s:up-down-left-right', 'arrows-alt'=>'s:maximize', 'arrows-h'=>'s:left-right', 'arrows-v'=>'s:up-down', 'asl-interpreting'=>'s:hands-asl-interpreting',
		'assistive-listening-systems'=>'s:ear-listen', 'automobile'=>'s:car', 'balance-scale'=>'s:scale-balanced', 'bandcamp'=>'b', 'bank'=>'s:building-columns', 'bar-chart'=>'s:chart-column',
		'bar-chart-o'=>'s:chart-column', 'bathtub'=>'s:bath', 'battery'=>'s:battery-full', 'battery-0'=>'s:battery-empty', 'battery-1'=>'s:battery-quarter', 'battery-2'=>'s:battery-half',
		'battery-3'=>'s:battery-three-quarters', 'battery-4'=>'s:battery-full', 'beer'=>'s:beer-mug-empty', 'behance'=>'b', 'behance-square'=>'b:square-behance', 'bell-o'=>'r:bell',
		'bell-slash-o'=>'r:bell-slash', 'birthday-cake'=>'s:cake-candles', 'bitbucket'=>'b', 'bitbucket-square'=>'b:bitbucket', 'bitcoin'=>'b:btc', 'black-tie'=>'b',
		'blind'=>'s:person-walking-with-cane', 'bluetooth'=>'b', 'bluetooth-b'=>'b', 'bookmark-o'=>'r:bookmark', 'btc'=>'b', 'building-o'=>'r:building',
		'buysellads'=>'b', 'cab'=>'s:taxi', 'calendar'=>'s:calendar-days', 'calendar-check-o'=>'r:calendar-check', 'calendar-minus-o'=>'r:calendar-minus', 'calendar-o'=>'r:calendar',
		'calendar-plus-o'=>'r:calendar-plus', 'calendar-times-o'=>'r:calendar-xmark', 'caret-square-o-down'=>'r:square-caret-down', 'caret-square-o-left'=>'r:square-caret-left', 'caret-square-o-right'=>'r:square-caret-right', 'caret-square-o-up'=>'r:square-caret-up',
		'cc'=>'r:closed-captioning', 'cc-amex'=>'b', 'cc-diners-club'=>'b', 'cc-discover'=>'b', 'cc-jcb'=>'b', 'cc-mastercard'=>'b',
		'cc-paypal'=>'b', 'cc-stripe'=>'b', 'cc-visa'=>'b', 'chain'=>'s:link', 'chain-broken'=>'s:link-slash', 'check-circle'=>'s:circle-check',
		'check-circle-o'=>'r:circle-check', 'check-square'=>'s:square-check', 'check-square-o'=>'r:square-check', 'chevron-circle-down'=>'s:circle-chevron-down', 'chevron-circle-left'=>'s:circle-chevron-left', 'chevron-circle-right'=>'s:circle-chevron-right',
		'chevron-circle-up'=>'s:circle-chevron-up', 'chrome'=>'b', 'circle-o'=>'r:circle', 'circle-o-notch'=>'s:circle-notch', 'circle-thin'=>'r:circle', 'clipboard'=>'s:paste',
		'clock-o'=>'r:clock', 'clone'=>'r', 'close'=>'s:xmark', 'cloud-download'=>'s:cloud-arrow-down', 'cloud-upload'=>'s:cloud-arrow-up', 'cny'=>'s:yen-sign',
		'code-fork'=>'s:code-branch', 'codepen'=>'b', 'codiepie'=>'b', 'coffee'=>'s:mug-saucer', 'cog'=>'s:gear', 'cogs'=>'s:gears',
		'columns'=>'s:table-columns', 'comment-o'=>'r:comment', 'commenting'=>'s:comment-dots', 'commenting-o'=>'r:comment-dots', 'comments-o'=>'r:comments', 'compass'=>'r',
		'compress'=>'s:down-left-and-up-right-to-center', 'connectdevelop'=>'b', 'contao'=>'b', 'copyright'=>'r', 'creative-commons'=>'b', 'credit-card'=>'r',
		'credit-card-alt'=>'s:credit-card', 'css3'=>'b', 'cut'=>'s:scissors', 'cutlery'=>'s:utensils', 'dashboard'=>'s:gauge-high', 'dashcube'=>'b',
		'deaf'=>'s:ear-deaf', 'deafness'=>'s:ear-deaf', 'dedent'=>'s:outdent', 'delicious'=>'b', 'deviantart'=>'b', 'diamond'=>'r:gem',
		'digg'=>'b', 'dollar'=>'s:dollar-sign', 'dot-circle-o'=>'r:circle-dot', 'dribbble'=>'b', 'drivers-license'=>'s:id-card', 'drivers-license-o'=>'r:id-card',
		'dropbox'=>'b', 'drupal'=>'b', 'edge'=>'b', 'edit'=>'r:pen-to-square', 'eercast'=>'b:sellcast', 'ellipsis-h'=>'s:ellipsis',
		'ellipsis-v'=>'s:ellipsis-vertical', 'empire'=>'b', 'envelope-o'=>'r:envelope', 'envelope-open-o'=>'r:envelope-open', 'envelope-square'=>'s:square-envelope', 'envira'=>'b',
		'etsy'=>'b', 'eur'=>'s:euro-sign', 'euro'=>'s:euro-sign', 'exchange'=>'s:right-left', 'exclamation-circle'=>'s:circle-exclamation', 'exclamation-triangle'=>'s:triangle-exclamation',
		'expand'=>'s:up-right-and-down-left-from-center', 'expeditedssl'=>'b', 'external-link'=>'s:up-right-from-square', 'external-link-square'=>'s:square-up-right', 'eye'=>'r', 'eye-slash'=>'r',
		'eyedropper'=>'s:eye-dropper', 'fa'=>'b:font-awesome', 'facebook'=>'b:facebook-f', 'facebook-f'=>'b', 'facebook-official'=>'b:facebook', 'facebook-square'=>'b:square-facebook',
		'fast-backward'=>'s:backward-fast', 'fast-forward'=>'s:forward-fast', 'feed'=>'s:rss', 'female'=>'s:person-dress', 'fighter-jet'=>'s:jet-fighter', 'file-archive-o'=>'r:file-zipper',
		'file-audio-o'=>'r:file-audio', 'file-code-o'=>'r:file-code', 'file-excel-o'=>'r:file-excel', 'file-image-o'=>'r:file-image', 'file-movie-o'=>'r:file-video', 'file-o'=>'r:file',
		'file-pdf-o'=>'r:file-pdf', 'file-photo-o'=>'r:file-image', 'file-picture-o'=>'r:file-image', 'file-powerpoint-o'=>'r:file-powerpoint', 'file-sound-o'=>'r:file-audio', 'file-text'=>'s:file-lines',
		'file-text-o'=>'r:file-lines', 'file-video-o'=>'r:file-video', 'file-word-o'=>'r:file-word', 'file-zip-o'=>'r:file-zipper', 'files-o'=>'r:copy', 'firefox'=>'b',
		'first-order'=>'b', 'flag-o'=>'r:flag', 'flash'=>'s:bolt', 'flickr'=>'b', 'floppy-o'=>'r:floppy-disk', 'folder-o'=>'r:folder',
		'folder-open-o'=>'r:folder-open', 'font-awesome'=>'b', 'fonticons'=>'b', 'fort-awesome'=>'b', 'forumbee'=>'b', 'foursquare'=>'b',
		'free-code-camp'=>'b', 'frown-o'=>'r:face-frown', 'futbol-o'=>'r:futbol', 'gbp'=>'s:sterling-sign', 'ge'=>'b:empire', 'get-pocket'=>'b',
		'gg'=>'b', 'gg-circle'=>'b', 'git'=>'b', 'git-square'=>'b:square-git', 'github'=>'b', 'github-alt'=>'b',
		'github-square'=>'b:square-github', 'gitlab'=>'b', 'gittip'=>'b:gratipay', 'glass'=>'s:martini-glass-empty', 'glide'=>'b', 'glide-g'=>'b',
		'globe'=>'s:earth-americas', 'google'=>'b', 'google-plus'=>'b:google-plus-g', 'google-plus-circle'=>'b:google-plus', 'google-plus-official'=>'b:google-plus', 'google-plus-square'=>'b:square-google-plus',
		'google-wallet'=>'b', 'gratipay'=>'b', 'grav'=>'b', 'group'=>'s:users', 'h-square'=>'s:square-h', 'hacker-news'=>'b',
		'hand-grab-o'=>'r:hand-back-fist', 'hand-lizard-o'=>'r:hand-lizard', 'hand-o-down'=>'r:hand-point-down', 'hand-o-left'=>'r:hand-point-left', 'hand-o-right'=>'r:hand-point-right', 'hand-o-up'=>'r:hand-point-up',
		'hand-paper-o'=>'r:hand', 'hand-peace-o'=>'r:hand-peace', 'hand-pointer-o'=>'r:hand-pointer', 'hand-rock-o'=>'r:hand-back-fist', 'hand-scissors-o'=>'r:hand-scissors', 'hand-spock-o'=>'r:hand-spock',
		'hand-stop-o'=>'r:hand', 'handshake-o'=>'r:handshake', 'hard-of-hearing'=>'s:ear-deaf', 'hdd-o'=>'r:hard-drive', 'header'=>'s:heading', 'heart-o'=>'r:heart',
		'heartbeat'=>'s:heart-pulse', 'history'=>'s:clock-rotate-left', 'home'=>'s:house', 'hospital-o'=>'r:hospital', 'hotel'=>'s:bed', 'hourglass-1'=>'s:hourglass-start',
		'hourglass-2'=>'s:hourglass-half', 'hourglass-3'=>'s:hourglass-end', 'hourglass-o'=>'s:hourglass', 'houzz'=>'b', 'html5'=>'b', 'id-badge'=>'r',
		'id-card-o'=>'r:id-card', 'ils'=>'s:shekel-sign', 'image'=>'r', 'imdb'=>'b', 'info-circle'=>'s:circle-info', 'inr'=>'s:indian-rupee-sign',
		'instagram'=>'b', 'institution'=>'s:building-columns', 'internet-explorer'=>'b', 'intersex'=>'s:mars-and-venus', 'ioxhost'=>'b', 'joomla'=>'b',
		'jpy'=>'s:yen-sign', 'jsfiddle'=>'b', 'keyboard-o'=>'r:keyboard', 'krw'=>'s:won-sign', 'lastfm'=>'b', 'lastfm-square'=>'b:square-lastfm',
		'leanpub'=>'b', 'legal'=>'s:gavel', 'lemon-o'=>'r:lemon', 'level-down'=>'s:turn-down', 'level-up'=>'s:turn-up', 'life-bouy'=>'s:life-ring',
		'life-buoy'=>'s:life-ring', 'life-saver'=>'s:life-ring', 'lightbulb-o'=>'r:lightbulb', 'line-chart'=>'s:chart-line', 'linkedin'=>'b:linkedin-in', 'linkedin-square'=>'b:linkedin',
		'linode'=>'b', 'linux'=>'b', 'list-alt'=>'r:rectangle-list', 'long-arrow-down'=>'s:down-long', 'long-arrow-left'=>'s:left-long', 'long-arrow-right'=>'s:right-long',
		'long-arrow-up'=>'s:up-long', 'low-vision'=>'s:eye-low-vision', 'magic'=>'s:wand-magic-sparkles', 'mail-forward'=>'s:share', 'mail-reply'=>'s:reply', 'mail-reply-all'=>'s:reply-all',
		'male'=>'s:person', 'map-marker'=>'s:location-dot', 'map-o'=>'r:map', 'map-signs'=>'s:signs-post', 'mars-stroke-h'=>'s:mars-stroke-right', 'mars-stroke-v'=>'s:mars-stroke-up',
		'maxcdn'=>'b', 'medium'=>'b', 'medkit'=>'s:suitcase-medical', 'meetup'=>'b', 'meh-o'=>'r:face-meh', 'minus-circle'=>'s:circle-minus',
		'minus-square'=>'s:square-minus', 'minus-square-o'=>'r:square-minus', 'mixcloud'=>'b', 'mobile'=>'s:mobile-screen-button', 'mobile-phone'=>'s:mobile-screen-button', 'modx'=>'b',
		'money'=>'s:money-bill-1', 'moon-o'=>'r:moon', 'mortar-board'=>'s:graduation-cap', 'mouse-pointer'=>'s:arrow-pointer', 'navicon'=>'s:bars', 'newspaper-o'=>'r:newspaper',
		'object-group'=>'r', 'object-ungroup'=>'r', 'odnoklassniki'=>'b', 'odnoklassniki-square'=>'b:square-odnoklassniki', 'opencart'=>'b', 'openid'=>'b',
		'opera'=>'b', 'optin-monster'=>'b', 'pagelines'=>'b', 'paint-brush'=>'s:paintbrush', 'paper-plane-o'=>'r:paper-plane', 'pause-circle'=>'s:circle-pause',
		'pause-circle-o'=>'r:circle-pause', 'paypal'=>'b', 'pencil-square'=>'s:square-pen', 'pencil-square-o'=>'r:pen-to-square', 'phone-square'=>'s:square-phone', 'photo'=>'r:image',
		'picture-o'=>'r:image', 'pie-chart'=>'s:chart-pie', 'pied-piper'=>'b', 'pied-piper-alt'=>'b', 'pied-piper-pp'=>'b', 'pinterest'=>'b',
		'pinterest-p'=>'b', 'pinterest-square'=>'b:square-pinterest', 'play-circle'=>'s:circle-play', 'play-circle-o'=>'r:circle-play', 'plus-circle'=>'s:circle-plus', 'plus-square'=>'s:square-plus',
		'plus-square-o'=>'r:square-plus', 'product-hunt'=>'b', 'qq'=>'b', 'question-circle'=>'s:circle-question', 'question-circle-o'=>'r:circle-question', 'quora'=>'b',
		'ra'=>'b:rebel', 'random'=>'s:shuffle', 'ravelry'=>'b', 'rebel'=>'b', 'reddit'=>'b', 'reddit-alien'=>'b',
		'reddit-square'=>'b:square-reddit', 'refresh'=>'s:arrows-rotate', 'registered'=>'r', 'remove'=>'s:xmark', 'renren'=>'b', 'reorder'=>'s:bars',
		'repeat'=>'s:arrow-rotate-right', 'resistance'=>'b:rebel', 'rmb'=>'s:yen-sign', 'rotate-left'=>'s:arrow-rotate-left', 'rotate-right'=>'s:arrow-rotate-right', 'rouble'=>'s:ruble-sign',
		'rss-square'=>'s:square-rss', 'rub'=>'s:ruble-sign', 'ruble'=>'s:ruble-sign', 'rupee'=>'s:indian-rupee-sign', 's15'=>'s:bath', 'safari'=>'b',
		'save'=>'r:floppy-disk', 'scribd'=>'b', 'search'=>'s:magnifying-glass', 'search-minus'=>'s:magnifying-glass-minus', 'search-plus'=>'s:magnifying-glass-plus', 'sellsy'=>'b',
		'send'=>'s:paper-plane', 'send-o'=>'r:paper-plane', 'share-alt'=>'s:share-nodes', 'share-alt-square'=>'s:square-share-nodes', 'share-square'=>'s:share-from-square', 'share-square-o'=>'s:share-from-square',
		'shekel'=>'s:shekel-sign', 'sheqel'=>'s:shekel-sign', 'shirtsinbulk'=>'b', 'shopping-bag'=>'s:bag-shopping', 'shopping-basket'=>'s:basket-shopping', 'shopping-cart'=>'s:cart-shopping',
		'sign-in'=>'s:right-to-bracket', 'sign-language'=>'s:hands', 'sign-out'=>'s:right-from-bracket', 'signing'=>'s:hands', 'simplybuilt'=>'b', 'skyatlas'=>'b',
		'skype'=>'b', 'slack'=>'b', 'slideshare'=>'b', 'smile-o'=>'r:face-smile', 'snapchat'=>'b', 'snapchat-ghost'=>'b:snapchat',
		'snapchat-square'=>'b:square-snapchat', 'snowflake-o'=>'r:snowflake', 'soccer-ball-o'=>'r:futbol', 'sort-alpha-asc'=>'s:arrow-down-a-z', 'sort-alpha-desc'=>'s:arrow-down-z-a', 'sort-amount-asc'=>'s:arrow-down-short-wide',
		'sort-amount-desc'=>'s:arrow-down-wide-short', 'sort-asc'=>'s:sort-up', 'sort-desc'=>'s:sort-down', 'sort-numeric-asc'=>'s:arrow-down-1-9', 'sort-numeric-desc'=>'s:arrow-down-9-1', 'soundcloud'=>'b',
		'space-shuttle'=>'s:shuttle-space', 'spotify'=>'b', 'square-o'=>'r:square', 'stack-exchange'=>'b', 'stack-overflow'=>'b', 'star-half-empty'=>'r:star-half-stroke',
		'star-half-full'=>'r:star-half-stroke', 'star-half-o'=>'r:star-half-stroke', 'star-o'=>'r:star', 'steam'=>'b', 'steam-square'=>'b:square-steam', 'step-backward'=>'s:backward-step',
		'step-forward'=>'s:forward-step', 'sticky-note'=>'s:note-sticky', 'sticky-note-o'=>'r:note-sticky', 'stop-circle'=>'s:circle-stop', 'stop-circle-o'=>'r:circle-stop', 'stumbleupon'=>'b',
		'stumbleupon-circle'=>'b', 'subway'=>'s:train-subway', 'sun-o'=>'r:sun', 'superpowers'=>'b', 'support'=>'s:life-ring', 'tablet'=>'s:tablet-screen-button',
		'tachometer'=>'s:gauge-high', 'tasks'=>'s:bars-progress', 'telegram'=>'b', 'television'=>'s:tv', 'tencent-weibo'=>'b', 'th'=>'s:table-cells',
		'th-large'=>'s:table-cells-large', 'th-list'=>'s:table-list', 'themeisle'=>'b', 'thermometer'=>'s:temperature-full', 'thermometer-0'=>'s:temperature-empty', 'thermometer-1'=>'s:temperature-quarter',
		'thermometer-2'=>'s:temperature-half', 'thermometer-3'=>'s:temperature-three-quarters', 'thermometer-4'=>'s:temperature-full', 'thermometer-empty'=>'s:temperature-empty', 'thermometer-full'=>'s:temperature-full', 'thermometer-half'=>'s:temperature-half',
		'thermometer-quarter'=>'s:temperature-quarter', 'thermometer-three-quarters'=>'s:temperature-three-quarters', 'thumb-tack'=>'s:thumbtack', 'thumbs-o-down'=>'r:thumbs-down', 'thumbs-o-up'=>'r:thumbs-up', 'times'=>'s:xmark',
		'times-circle'=>'s:circle-xmark', 'times-circle-o'=>'r:circle-xmark', 'times-rectangle'=>'s:rectangle-xmark', 'times-rectangle-o'=>'r:rectangle-xmark', 'tint'=>'s:droplet', 'toggle-down'=>'r:square-caret-down',
		'toggle-left'=>'r:square-caret-left', 'toggle-right'=>'r:square-caret-right', 'toggle-up'=>'r:square-caret-up', 'transgender'=>'s:mars-and-venus', 'transgender-alt'=>'s:transgender', 'trash'=>'s:trash-can',
		'trash-o'=>'r:trash-can', 'trello'=>'b', 'try'=>'s:turkish-lira-sign', 'tumblr'=>'b', 'tumblr-square'=>'b:square-tumblr', 'turkish-lira'=>'s:turkish-lira-sign',
		'twitch'=>'b', 'twitter'=>'b', 'twitter-square'=>'b:square-twitter', 'undo'=>'s:arrow-rotate-left', 'university'=>'s:building-columns', 'unlink'=>'s:link-slash',
		'unlock-alt'=>'s:unlock', 'unsorted'=>'s:sort', 'usb'=>'b', 'usd'=>'s:dollar-sign', 'user-circle'=>'s:circle-user', 'user-circle-o'=>'r:circle-user',
		'user-md'=>'s:user-doctor', 'user-o'=>'r:user', 'user-times'=>'s:user-xmark', 'vcard'=>'s:address-card', 'vcard-o'=>'r:address-card', 'viacoin'=>'b',
		'viadeo'=>'b', 'viadeo-square'=>'b:square-viadeo', 'video-camera'=>'s:video', 'vimeo'=>'b:vimeo-v', 'vimeo-square'=>'b:square-vimeo', 'vine'=>'b',
		'vk'=>'b', 'volume-control-phone'=>'s:phone-volume', 'volume-down'=>'s:volume-low', 'volume-up'=>'s:volume-high', 'warning'=>'s:triangle-exclamation', 'wechat'=>'b:weixin',
		'weibo'=>'b', 'weixin'=>'b', 'whatsapp'=>'b', 'wheelchair-alt'=>'b:accessible-icon', 'wikipedia-w'=>'b', 'window-close'=>'s:rectangle-xmark',
		'window-close-o'=>'r:rectangle-xmark', 'window-maximize'=>'r', 'window-restore'=>'r', 'windows'=>'b', 'won'=>'s:won-sign', 'wordpress'=>'b',
		'wpbeginner'=>'b', 'wpexplorer'=>'b', 'wpforms'=>'b', 'xing'=>'b', 'xing-square'=>'b:square-xing', 'y-combinator'=>'b',
		'y-combinator-square'=>'b:hacker-news', 'yahoo'=>'b', 'yc'=>'b:y-combinator', 'yc-square'=>'b:hacker-news', 'yelp'=>'b', 'yen'=>'s:yen-sign',
		'yoast'=>'b', 'youtube'=>'b', 'youtube-play'=>'b:youtube', 'youtube-square'=>'b:square-youtube',
		);
	}
}
