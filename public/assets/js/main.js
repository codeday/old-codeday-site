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
	var spanCurrentCity = $('span.current-city');
	var inputCurrentCity = $('input.current-city');
	var cityPicker = $('#city-picker');
    var previous_picker_xhr;
	var cityValue;

	// Retrieve city picker values
	function capitaliseFirstLetter(string) {
	    return string.charAt(0).toUpperCase() + string.slice(1);
	}

	function displayCities(response_object) {
		$.each(response_object, function(key, value) {
			var cityOption = '<li class="city-option">' + value.name + '</li>';
			if (key !== 0) {
				$('#city-picker ul').append(cityOption);
			} else {
				$('#city-picker ul').html(cityOption);
			}
			return key < 7;
		});
		$('.city-option').on('click', function(){
			var pickedCity = $(this).html();
			inputCurrentCity.val(pickedCity);
		});
	}

	$('.current-city').click(function(e){
		e.preventDefault();
		// Change current-city span into input box 
		spanCurrentCity.hide();
		inputCurrentCity.css('width', '6.2em').show().focus();
		cityPicker.fadeIn('fast'); // Display city picker when current city is clicked
		return false;
	})

	$(document).on('click', function() { 
		cityPicker.fadeOut('fast'); 
	});

	if (inputCurrentCity.is(':focus')) {
		cityPicker.show();
	}

	if($.trim(inputCurrentCity.val()).length == 0) { // If current city input box is empty
		$.ajax({
			url: "http://clear.codeday.org/api/regions/nearby?lat=" + window.lat + "&lng=" + window.lng + "&limit=5&with_current_event=1",
			method: "GET",
			dataType: "JSON",
			success: function(response_object) {
				displayCities(response_object);
			}
		});
	}

	inputCurrentCity.on('input propertychange paste', function(){
		cityValue = $(this).val().toString().toLowerCase();
		cityValue = capitaliseFirstLetter(cityValue);
		$('#city-picker ul').html('');
        if (typeof(previous_picker_xhr) !== 'undefined') {
            previous_picker_xhr.abort();
        }
		previous_picker_xhr = $.ajax({
			url: "https://clear.codeday.org/api/regions/search?term=" + cityValue,
			method: "GET",
			dataType: "JSON",
			success: function(response_object) {
				displayCities(response_object);
			}
		});
	});



})();

// Registration ticket price calculations and add coders
(function(){
	var ticketPrice = 10; // TODO

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




