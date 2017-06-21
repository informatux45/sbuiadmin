jQuery(document).ready(function(){
    jQuery('.featured-projects-widget').flexslider({
        animation: 'fade', // fade, scrollLeft
        slideshowSpeed: 8000,
        animationSpeed: 300,
        selectors: 'ul > li',
        directionNav: true,
        slideshow: true,

		pauseOnAction: false,
		controlNav: false,
		touch: true
		
    });
});
