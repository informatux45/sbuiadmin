/*
 * Sélecteur d'icônes Font Awesome (classe form addIconFA(), voir
 * inc/class/sbuiadmin-form.php) : grille filtrable de toutes les icônes
 * réellement présentes dans le bundle FA embarqué (assets/bower_components/
 * font-awesome/css/font-awesome.css), pas de dépendance externe. Un seul
 * modal partagé, injecté une fois et réutilisé pour n'importe quel champ
 * data-icon-picker de la page - réutilise le composant .modal déjà en
 * place (voir modal.js) pour l'ouverture/fermeture.
 */
(function () {
	var ICONS = ["adjust","adn","align-center","align-justify","align-left","align-right","ambulance","anchor","android","angellist","angle-double-down","angle-double-left","angle-double-right","angle-double-up","angle-down","angle-left","angle-right","angle-up","apple","archive","area-chart","arrow-circle-down","arrow-circle-left","arrow-circle-o-down","arrow-circle-o-left","arrow-circle-o-right","arrow-circle-o-up","arrow-circle-right","arrow-circle-up","arrow-down","arrow-left","arrow-right","arrows","arrows-alt","arrows-h","arrows-v","arrow-up","asterisk","at","automobile","backward","ban","bank","bar-chart","bar-chart-o","barcode","bars","beer","behance","behance-square","bell","bell-o","bell-slash","bell-slash-o","bicycle","binoculars","birthday-cake","bitbucket","bitbucket-square","bitcoin","bold","bolt","bomb","book","bookmark","bookmark-o","briefcase","btc","bug","building","building-o","bullhorn","bullseye","bus","cab","calculator","calendar","calendar-o","camera","camera-retro","car","caret-down","caret-left","caret-right","caret-square-o-down","caret-square-o-left","caret-square-o-right","caret-square-o-up","caret-up","cc","cc-amex","cc-discover","cc-mastercard","cc-paypal","cc-stripe","cc-visa","certificate","chain","chain-broken","check","check-circle","check-circle-o","check-square","check-square-o","chevron-circle-down","chevron-circle-left","chevron-circle-right","chevron-circle-up","chevron-down","chevron-left","chevron-right","chevron-up","child","circle","circle-o","circle-o-notch","circle-thin","clipboard","clock-o","close","cloud","cloud-download","cloud-upload","cny","code","code-fork","codepen","coffee","cog","cogs","columns","comment","comment-o","comments","comments-o","compass","compress","copy","copyright","credit-card","crop","crosshairs","css3","cube","cubes","cut","cutlery","dashboard","database","dedent","delicious","desktop","deviantart","digg","dollar","dot-circle-o","download","dribbble","dropbox","drupal","edit","eject","ellipsis-h","ellipsis-v","empire","envelope","envelope-o","envelope-square","eraser","eur","euro","exchange","exclamation","exclamation-circle","exclamation-triangle","expand","external-link","external-link-square","eye","eyedropper","eye-slash","facebook","facebook-square","fast-backward","fast-forward","fax","female","fighter-jet","file","file-archive-o","file-audio-o","file-code-o","file-excel-o","file-image-o","file-movie-o","file-o","file-pdf-o","file-photo-o","file-picture-o","file-powerpoint-o","files-o","file-sound-o","file-text","file-text-o","file-video-o","file-word-o","file-zip-o","film","filter","fire","fire-extinguisher","flag","flag-checkered","flag-o","flash","flask","flickr","floppy-o","folder","folder-o","folder-open","folder-open-o","font","forward","foursquare","frown-o","futbol-o","gamepad","gavel","gbp","ge","gear","gears","gift","git","github","github-alt","github-square","git-square","gittip","glass","globe","google","google-plus","google-plus-square","google-wallet","graduation-cap","group","hacker-news","hand-o-down","hand-o-left","hand-o-right","hand-o-up","hdd-o","header","headphones","heart","heart-o","history","home","hospital-o","h-square","html5","ils","image","inbox","indent","info","info-circle","inr","instagram","institution","ioxhost","italic","joomla","jpy","jsfiddle","key","keyboard-o","krw","language","laptop","lastfm","lastfm-square","leaf","legal","lemon-o","level-down","level-up","life-bouy","life-buoy","life-ring","life-saver","lightbulb-o","line-chart","link","linkedin","linkedin-square","linux","list","list-alt","list-ol","list-ul","location-arrow","lock","long-arrow-down","long-arrow-left","long-arrow-right","long-arrow-up","magic","magnet","mail-forward","mail-reply","mail-reply-all","male","map-marker","maxcdn","meanpath","medkit","meh-o","microphone","microphone-slash","minus","minus-circle","minus-square","minus-square-o","mobile","mobile-phone","money","moon-o","mortar-board","music","navicon","newspaper-o","openid","outdent","pagelines","paint-brush","paperclip","paper-plane","paper-plane-o","paragraph","paste","pause","paw","paypal","pencil","pencil-square","pencil-square-o","phone","phone-square","photo","picture-o","pie-chart","pied-piper","pied-piper-alt","pinterest","pinterest-square","plane","play","play-circle","play-circle-o","plug","plus","plus-circle","plus-square","plus-square-o","power-off","print","puzzle-piece","qq","qrcode","question","question-circle","quote-left","quote-right","ra","random","rebel","recycle","reddit","reddit-square","refresh","remove","renren","reorder","repeat","reply","reply-all","retweet","rmb","road","rocket","rotate-left","rotate-right","rouble","rss","rss-square","rub","ruble","rupee","save","scissors","search","search-minus","search-plus","send","send-o","share","share-alt","share-alt-square","share-square","share-square-o","shekel","sheqel","shield","shopping-cart","signal","sign-in","sign-out","sitemap","skype","slack","sliders","slideshare","smile-o","soccer-ball-o","sort","sort-alpha-asc","sort-alpha-desc","sort-amount-asc","sort-amount-desc","sort-asc","sort-desc","sort-down","sort-numeric-asc","sort-numeric-desc","sort-up","soundcloud","space-shuttle","spinner","spoon","spotify","square","square-o","stack-exchange","stack-overflow","star","star-half","star-half-empty","star-half-full","star-half-o","star-o","steam","steam-square","step-backward","step-forward","stethoscope","stop","strikethrough","stumbleupon","stumbleupon-circle","subscript","suitcase","sun-o","superscript","support","table","tablet","tachometer","tag","tags","tasks","taxi","tencent-weibo","terminal","text-height","text-width","th","th-large","th-list","thumbs-down","thumbs-o-down","thumbs-o-up","thumbs-up","thumb-tack","ticket","times","times-circle","times-circle-o","tint","toggle-down","toggle-left","toggle-off","toggle-on","toggle-right","toggle-up","trash","trash-o","tree","trello","trophy","truck","try","tty","tumblr","tumblr-square","turkish-lira","twitch","twitter","twitter-square","umbrella","underline","undo","university","unlink","unlock","unlock-alt","unsorted","upload","usd","user","user-md","users","video-camera","vimeo-square","vine","vk","volume-down","volume-off","volume-up","warning","wechat","weibo","weixin","wheelchair","wifi","windows","won","wordpress","wrench","xing","xing-square","yahoo","yelp","yen","youtube","youtube-play","youtube-square"];

	var modal, grid, search, currentTargetId;

	function buildModal() {
		if (modal) return;

		modal = document.createElement('div');
		modal.className = 'modal';
		modal.id = 'sbIconPickerModal';
		modal.innerHTML =
			'<div class="modal-dialog" style="max-width:640px">' +
				'<div class="modal-content">' +
					'<div class="modal-header">' +
						'<h4 class="modal-title">Choisir une icône</h4>' +
						'<button type="button" class="close" data-dismiss="modal">&times;</button>' +
					'</div>' +
					'<div class="modal-body">' +
						'<input type="search" class="input" placeholder="Rechercher..." id="sbIconPickerSearch" style="margin-bottom:14px;width:100%">' +
						'<div id="sbIconPickerGrid" class="sb-icon-picker-grid"></div>' +
					'</div>' +
				'</div>' +
			'</div>';
		document.body.appendChild(modal);

		grid   = modal.querySelector('#sbIconPickerGrid');
		search = modal.querySelector('#sbIconPickerSearch');

		search.addEventListener('input', function () {
			renderGrid(search.value.trim().toLowerCase());
		});
	}

	function renderGrid(filter) {
		grid.innerHTML = '';
		ICONS.forEach(function (name) {
			if (filter && name.indexOf(filter) === -1) return;
			var btn = document.createElement('button');
			btn.type = 'button';
			btn.title = name;
			btn.className = 'sb-icon-picker-item';
			btn.innerHTML = '<i class="fa fa-' + name + '"></i><span>' + name + '</span>';
			btn.addEventListener('click', function () { selectIcon(name); });
			grid.appendChild(btn);
		});
	}

	function selectIcon(name) {
		var input = document.getElementById(currentTargetId);
		if (input) {
			input.value = name;
			input.dispatchEvent(new Event('change', { bubbles: true }));
		}
		var preview = document.getElementById(currentTargetId + 'Preview');
		if (preview) preview.innerHTML = '<i class="fa fa-fw fa-' + name + '"></i>';

		modal.classList.remove('is-open');
	}

	document.addEventListener('click', function (e) {
		var trigger = e.target.closest('[data-icon-picker-trigger]');
		if (!trigger) return;
		e.preventDefault();

		buildModal();
		currentTargetId = trigger.getAttribute('data-icon-picker-trigger');
		search.value = '';
		renderGrid('');
		modal.classList.add('is-open');
		modal.style.display = '';
		setTimeout(function () { search.focus(); }, 50);
	});
})();
