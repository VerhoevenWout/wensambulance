/*jslint vars: true, plusplus: true, devel: true, nomen: true, indent: 4, maxerr: 50 */
(function(){
	'use strict';

$( document ).ready(function() {
	// --------------------------------------------------------------------
	// SLICK
	$('.banner-carousel').slick({
		autoplay: true,
		autoplaySpeed: 7000,
		draggable: false,
	    arrows: false,
	    dots: false,
	    fade: true,
	    speed: 900,
	    infinite: true,
	    cssEase: 'cubic-bezier(0.7, 0, 0.3, 1)',
	    touchThreshold: 100,
	});
	// $('.bannersub-carousel').slick({
	// 	autoplay: true,
	// 	autoplaySpeed: 3000,
	// 	draggable: false,
	//     arrows: false,
	//     dots: false,
	//     fade: true,
	//     speed: 900,
	//     infinite: true,
	//     cssEase: 'cubic-bezier(0.7, 0, 0.3, 1)',
	//     touchThreshold: 100,
	//     slidesToShow: 1,
	// });
	$('.testimonials-carousel').slick({
		autoplay: true,
		autoplaySpeed: 3000,
		draggable: true,
	    arrows: false,
	    dots: true,
	    fade: false,
	    speed: 900,
	    infinite: true,
	    cssEase: 'cubic-bezier(0.7, 0, 0.3, 1)',
	    touchThreshold: 100,
	    slidesToShow: 1,
	    slidesToScroll: 1,
	});
	$('.stories-carousel').slick({
		autoplay: true,
		autoplaySpeed: 3000,
		draggable: true,
	    arrows: true,
	    dots: true,
	    fade: false,
	    speed: 900,
	    infinite: true,
	    cssEase: 'cubic-bezier(0.7, 0, 0.3, 1)',
	    touchThreshold: 100,
	    slidesToShow: 4,
	    slidesToScroll: 1,
	});


	// --------------------------------------------------------------------
	// NAVIGATION
	$('.hamburger').click(function(){
		$(this).toggleClass('is-active');
		$('.menu').toggleClass('menu-is-active');
		$('html, body').toggleClass('stop-scrolling');
	});
	$('nav a').click(function(){
		$('.hamburger').removeClass('is-active');
		$('.menu').removeClass('menu-is-active');
	});
	// --------------------------------------------------------------------
	// SCROLL NAV UP DOWN
	var didScroll;
	var lastScrollTop = 0;
	var delta = 5;
	var navbarHeight = $('nav').outerHeight();
	$(window).scroll(function(event){
	    didScroll = true;
	});
	setInterval(function() {
	    if (didScroll) {
	      hasScrolled();
	      didScroll = false;
	    }
	}, 250);
	function hasScrolled() {
	    var st = $(this).scrollTop();
	    // Make sure they scroll more than delta
	    if(Math.abs(lastScrollTop - st) <= delta)
	        return;
	    if (st > lastScrollTop && st > navbarHeight){
			// Scroll Down
			$('.hamburger').removeClass('is-active');
			$('nav ul').removeClass('main-menu-expand');
			$('nav').removeClass('nav-down').addClass('nav-up');
	    } else {
			// Scroll Up
			if(st + $(window).height() < $(document).height()) {
				$('nav').removeClass('nav-up').addClass('nav-down');
			}
	    }
    	lastScrollTop = st;
	}
    
});

})(); // END OF USE STRICT FUNCTION