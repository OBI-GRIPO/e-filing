   $(document).ready(function(){
	   $(window).bind('scroll', function() {
	   var navHeight = $( window ).height() - 220;
			 if ($(window).scrollTop() > navHeight) {
				 $('#filters').addClass('fixed-top');
			 }
			 else {
				 $('#filters').removeClass('fixed-top');
			 }
		});
	});
