// Fade in body
(function(){
	$('body').animate({'opacity': 1}, 500);
})();

// Playing background video 
(function(){
	var splashReturn = $('#splash-return'),
		splashVideo =  $('#splash-video'),
		splash = $('#splash');

	$('#video-link').on('click', function(){
		splash.fadeOut('fast', function(){ 
			splashVideo.css('z-index', 0).get(0).play();
			splashReturn.fadeIn();
		});
	});

	function returnToSplash() {
		splashReturn.hide();
		splashVideo.css('z-index', -100).get(0).pause();
		splash.fadeIn('fast');
	}

	splashVideo.bind('ended', function(){
		returnToSplash();
	});

	splashReturn.on('click', function(){
		returnToSplash();
	});

})();

// Stop anchor links that lead to #
(function(){
	$('a').on('click', function(e){
		if ($(this).attr('href') === '#' || $(this).attr('class') === 'anchorLink') {
			e.preventDefault();
		}
	});
})();

// Display schedule popup
(function(){
	$('.schedule-task').hover(function() {
		$(this).children('.schedule-popup').stop().show();
	}, function() {
		$(this).children('.schedule-popup').stop().hide();
	});
})();

// Site navigation
(function(){
	$('.header-regular #nav-button, .nav-large #nav-button').on('click', function(){
		$('.site-nav ul').animate({width: 'toggle'});
	});
	$('.header-mobile #nav-button, .nav-mobile #nav-button').on('click', function(){
		$('.site-nav ul').animate({height: 'toggle'});
	});
})();

// Splash arrow
(function(){
	var root = $('html, body');
	$('.anchorLink').click(function(){
	    root.animate({
	        scrollTop: $( $(this).attr('href') ).offset().top
	    }, 1000);
	});
})();


// City picker stuff
(function(){

	// Display city picker when current city is clicked
	var cityPicker = $('#city-picker');
	$('#current-city').click(function(e){
		e.preventDefault();
		cityPicker.fadeIn('fast');
		return false;
	})
	$(document).on('click', function() { 
		cityPicker.fadeOut('fast'); 
	});
	if ($('#current-city').is(':focus')) {
		cityPicker.show();
	}

	// Retrieve city picker values
	var cityValue;
	function capitaliseFirstLetter(string) {
	    return string.charAt(0).toUpperCase() + string.slice(1);
	}
	$('#current-city').on('input propertychange paste', function(){
		cityValue = $(this).val().toString();
		cityValue = capitaliseFirstLetter(cityValue);
		$('#city-picker ul').html('');
		$.ajax({
			url: "https://clear.codeday.org/api/regions/search?term=" + cityValue,
			method: "GET",
			dataType: "JSON",
			success: function(response_object) {
				$.each(response_object, function(key, value) {
					var cityOption = '<li class="city-option">' + value.name + '</li>';
					$('#city-picker ul').append(cityOption);
				});
				$('.city-option').on('click', function(){
					var pickedCity = $(this).html();
					$('#current-city').val(pickedCity);
					// Display nearest cities to pickedCity inside panel
				});
			}
		});
	});


})();

// Registration ticket price calculations and add coders
(function(){
	var ticketPrice = 10; // Set ticket price here

	$('#ticket-amount').change(function() { 
		var quantity = $('#ticket-amount').val(),
			total = quantity * ticketPrice;
		$('#total-cost').text('$' + total); 

		$('#coder-profile-1').siblings('.coder-profile').remove(); // Remove extra coder profiles (reset)

		for (var i = 0; i < quantity - 1; i++) {
			var $coderProfile = $('#coder-profile-1').clone(); // Clone coder profile 1
			var $newCoderProfile = ($coderProfile).attr('id','coder-profile-' + (i + 2)); // Set new coder profile with id of, say, 3
			($newCoderProfile).insertAfter($('#coder-profile-' + (i + 1))); // Put new coder profile 3 after coder profile 2
		}
	});
})();

// Masonry effect
(function(){
	var msnry = new Masonry( '#masonry-container', {
	  // options
	  itemSelector: '.showcase-column'
	});
})();

// Load view more content on Press
(function(){
	$('#view-more').on('click', function(e){
		$('#press-more-past-coverage').load('past-coverage.html', function(){
	      $(this).fadeIn();
	    });
	    $(this).hide();
	});
})();




