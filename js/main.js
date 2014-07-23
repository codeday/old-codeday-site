(function(){
	$('body').animate({'opacity': 1}, 500);
})();

(function(){
	// display city picker when current city is clicked
	$('#current-city').click(function(e){
		e.preventDefault();
		$('#city-picker').fadeToggle('fast');
	})
})();

(function(){

	$('#faq').waypoint(function(up) {
		$('#faq .fadein').each(function(index) {
		    $(this).delay(300 * index).animate({'opacity': 1}, 500);
		});
	}, { offset: 350 });

	$('#schedule').waypoint(function(up) {
		$('#schedule .fadein').each(function(index) {
		    $(this).delay(300 * index).animate({'opacity': 1}, 500);
		});
	}, { offset: 350 });

	$('#pitch').waypoint(function(up) {
		$('#pitch .fadein').each(function(index) {
		    $(this).delay(100 * index).animate({'opacity': 1}, 500);
		});
	}, { offset: 450 });

	$('#sponsors').waypoint(function(up) {
		$('#sponsors .fadein').each(function(index) {
		    $(this).delay(100 * index).animate({'opacity': 1}, 500);
		});
	}, { offset: 350 });

	$('footer').waypoint(function(up) {
		$('footer .fadein').each(function(index) {
		    $(this).delay(100 * index).animate({'opacity': 1}, 500);
		});
	}, { offset: 450 });


})();

// (function(){
	
// 	// splash blur on scroll

// 	$(window).scroll(function(){
// 	    var winHeight = $(window).scrollTop(),
// 	    	opacityVal = (100.0 / (winHeight * 3)),
// 	    	regSplash = $('.reg-splash');

// 		regSplash.css('opacity', opacityVal); 

// 		if (winHeight == 0) {
// 			regSplash.css('opacity', 1); 
// 		}
// 	});

// })();

