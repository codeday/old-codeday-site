(function(){
	
	$('body').animate({'opacity': 1}, 500);

})();

(function(){
	
	// header pop out on scroll

	var subpageHeader = $('.subpage header');
	var subpage = $('.subpage');
	var step = $('.step');

	$(window).scroll(function(){
	    var winHeight = $(window).scrollTop();

		if ( winHeight != 0 ) {
			subpageHeader.addClass('popout');
			// step.css('margin-top', '1em')
		} else {
			subpageHeader.removeClass('popout');
			// step.css('margin-top', '0')
		}
	});

})();