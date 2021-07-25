// Navbar fix on scroll

if ($(window).width() < 982) {
	$('#navbar').addClass('fixed');
	$('a.nav-link').click(function () {
		$('.navbar-collapse.collapse').removeClass('show');
		$('.navbar-toggler').addClass('collapsed');
	});
	$('.navbar-toggler').click(function () {
		$('#navbar').addClass('fixed');
	});
}
else {
	$(window).scroll(function () {
		var nav = $('#navbar');
		var top = 100;
		if ($(window).scrollTop() >= top) {

			nav.addClass('fixed');

		} else {
			nav.removeClass('fixed');
		}
	});
}
//balls! 

// AOS
AOS.init();

// Owlcarousel
$(document).ready(function () {
	$(".owl-carousel").owlCarousel({
		loop: true,
		margin: 10,
		nav: true,
		navText: [
			"<i class='fa fa-caret-left'></i>",
			"<i class='fa fa-caret-right'></i>"
		],
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 1
			},
			1000: {
				items: 1
			}
		}
	});
});


//linear-gradient(-45deg,  #000000, #000000, #1D2243, #3863EC);
//background-size: 400% 400%;
//animation: gradient 15s ease infinite;