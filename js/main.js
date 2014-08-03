(function(){
	// fade in body
	$('body').animate({'opacity': 1}, 500);
})();

(function(){
	// arrow

	// var faqHeight = $('#faq').height();

 //    $('#arrow').on('click', function() {
 //        $('body').animate({'scrollTop': window.scrollY+300}, 1000);
	// });

	var root = $('html, body');


	$('.anchorLink').click(function(){
	    root.animate({
	        scrollTop: $( $(this).attr('href') ).offset().top
	    }, 1000);
	    return false;
	});
})();

(function(){
	// display city picker when current city is clicked
	var cityPicker = $('#city-picker');
	$('#current-city').click(function(e){
		e.preventDefault();
		cityPicker.fadeToggle('fast');
		return false;
	})

	$(document).on('click', function() { 
		cityPicker.fadeOut('fast'); 
	});
})();

(function(){

	// $('#faq').waypoint(function(up) {
	// 	$('#faq .fadein').each(function(index) {
	// 	    $(this).delay(400 * index).animate({'opacity': 1}, 400);
	// 	});
	// }, { offset: 500 });

	// $('#schedule').waypoint(function(up) {
	// 	$('#schedule .fadein').each(function(index) {
	// 	    $(this).delay(300 * index).animate({'opacity': 1}, 400);
	// 	});
	// }, { offset: 650 });

	// $('#pitch').waypoint(function(up) {
	// 	$('#pitch .fadein').each(function(index) {
	// 	    $(this).delay(200 * index).animate({'opacity': 1}, 500);
	// 	});
	// }, { offset: 450 });

	// $('#sponsors').waypoint(function(up) {
	// 	$('#sponsors .fadein').each(function(index) {
	// 	    $(this).delay(50 * index).animate({'opacity': 1}, 500);
	// 	});
	// }, { offset: 350 });

	// $('footer').waypoint(function(up) {
	// 	$('footer .fadein').each(function(index) {
	// 	    $(this).delay(100 * index).animate({'opacity': 1}, 500);
	// 	});
	// }, { offset: 450 });


})();

// Masonry effect

(function(){

	var msnry = new Masonry( '#masonry-container', {
	  // options
	  itemSelector: '.showcase-column'
	});
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

