/* SBUIADMIN - compatibilité des anciens plugins jQuery des thèmes front
 * (jQuery 4 + jQuery Migrate 4 rétablissent la plupart des fonctions retirées ;
 * celles retirées dès jQuery 1.9 ne le sont pas, d'où ce fichier).
 *  - $.browser : détection de navigateur (prettyPhoto, quicksand...) ; aucun
 *    navigateur actuel n'est un vieil Internet Explorer : tout à false.
 *  - .live() / .die() : liaison directe (.on / .off) sur les éléments déjà
 *    présents. Pour des éléments ajoutés plus tard, utiliser
 *    $(document).on(événement, sélecteur, fonction).
 *  - .load(fonction) / .unload / .error, .size(), .andSelf(),
 *    $.support.opacity : voir plus bas.
 */
(function ($) {
	if (!$) return;
	if (!$.browser) $.browser = { msie: false, mozilla: false, webkit: false, opera: false, safari: false, version: '0' };
	if (!$.fn.live) $.fn.live = function (types, data, fn) { return this.on(types, data, fn); };
	if (!$.fn.die) $.fn.die = function (types, fn) { return this.off(types, fn); };
	// .load(fonction), .unload(fonction), .error(fonction) : raccourcis
	// d'événements retirés en jQuery 3 (bxSlider, anythingSlider...) ;
	// .load(url) reste le chargement Ajax de jQuery
	var ajaxLoad = $.fn.load;
	$.fn.load = function (url) {
		if (!arguments.length) return this.trigger('load'); // .load() : déclenche (bxSlider)
		return (typeof url === 'function') ? this.on('load', url) : ajaxLoad.apply(this, arguments);
	};
	$.each(['unload', 'error'], function (i, name) {
		if (!$.fn[name]) $.fn[name] = function (fn) { return this.on(name, fn); };
	});
	// .size() et .andSelf() : retirés en jQuery 3 (prettyPhoto...)
	if (!$.fn.size) $.fn.size = function () { return this.length; };
	if (!$.fn.andSelf) $.fn.andSelf = $.fn.addBack;
	// $.support.opacity (retiré) : absent, Colorbox croit être sous un vieil IE
	if ($.support && typeof $.support.opacity === 'undefined') $.support.opacity = true;
})(window.jQuery);
