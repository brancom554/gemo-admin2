(function ($) {
	"use strict";

	var nav = $('nav');
	var navHeight = nav.outerHeight();

	$('.navbar-toggler').on('click', function () {
		if (!$('#mainNav').hasClass('navbar-reduce')) {
			$('#mainNav').addClass('navbar-reduce');
		}
	});

	$('#example').whatsappChatSupport({
        defaultMsg : 'Bienvenue sur le chat de GEMO',
    });
	
	// START PRELOADED
    $(window).on('load', function () {
        $('#loader').fadeOut('slow', function () {
            $(this).remove();
        });
		$('#black').hide()
    });

	

	$('#mymodal').on('shown.bs.modal',function(){
		console.log('hello')
		$('#exampleModal').trigger('focus')
	})
	
	// Navbar Menu Reduce 
	$(window).trigger('scroll');
	$(window).on('scroll', function () {
		var pixels = 50;
		var top = 1200;
		
		if ($(window).scrollTop() > pixels) {
			$('.navbar-expand-md').addClass('navbar-reduce');
			$('.navbar-expand-md').removeClass('navbar-trans');
			$('#ligth').hide()
			$('#black').show()
		} else {
			$('.navbar-expand-md').addClass('navbar-trans');
			$('.navbar-expand-md').removeClass('navbar-reduce');
			$('#ligth').show()
			$('#black').hide()
			
		}
		if ($(window).scrollTop() > top) {
			$('.scrolltop-mf').fadeIn(1000, "easeInOutExpo");
		} else {
			$('.scrolltop-mf').fadeOut(1000, "easeInOutExpo");
		}
	});

	// Back to top button 
	$(window).on("scroll", function () {
		if ($(this).scrollTop() > 100) {
			$('.back-to-top').fadeIn('slow');
		} else {
			$('.back-to-top').fadeOut('slow');
		}
	});
	$('.back-to-top').on("click", function () {
		$('html, body').animate({
			scrollTop: 0
		}, 1500, 'easeInOutExpo');
		return false;
	});

	//  Star ScrollTop
	$('.scrolltop-mf').on("click", function () {
		$('html, body').animate({
			scrollTop: 0
		}, 1000);
	});

	//  Star Scrolling nav
	$('a.js-scroll[href*="#"]:not([href="#"])').on("click", function () {
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				$('html, body').animate({
					scrollTop: (target.offset().top - navHeight + 30)
				}, 1000, "easeInOutExpo");
				return false;
			}
		}
	});

	// Closes responsive menu when a scroll trigger link is clicked
	$('.js-scroll').on("click", function () {
		$('.navbar-collapse').collapse('hide');
	});

	// Activate scrollspy to add active class to navbar items on scroll
	$('body').scrollspy({
		target: '#mainNav',
		offset: navHeight
	});
	
    //  Star Counter
	$('.counter-number').counterUp({
		delay: 15,
		time: 2000
	});

    // Screenshort-slide owlCarousel
    $('.screenshort-slide.owl-carousel').owlCarousel({
        loop:true,
        margin: 15,
		center: true,
        mouseDrag:true,
        autoplay:true,
        dots: true,
		smartSpeed:800,
        responsiveClass:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:4
            }
        }
    });
    
    // Testimonials owlCarousel
    $('.testimonial-slide .owl-carousel').owlCarousel({
        loop: true
        , margin: 5
        , mouseDrag: true
        , autoplay: true
        , dots: true
        , responsiveClass: true
        , responsive: {
            0: {
                items: 1
            , }
            , 600: {
                items: 2
            }
            , 1000: {
                items: 3
            }
        }
    });
	
    //  POPUP VIDEO
    $('.popup-video').magnificPopup({
		type: 'iframe',
	});
	
	    // FAQ Accordion
        $(function() {
            $('.accordion').find('.accordion-title').on('click', function(){
                // Adds Active Class
                $(this).toggleClass('active');
                // Expand or Collapse This Panel
                $(this).next().slideToggle('slow');
                // Hide The Other Panels
                $('.accordion-content').not($(this).next()).slideUp('slow');
                // Removes Active Class From Other Titles
                $('.accordion-title').not($(this)).removeClass('active');		
            });
        });
	
})(jQuery);