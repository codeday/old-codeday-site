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
	    }, 800);
	});
})();

// Notification subscription
(function(){
        var eventId = $('.current-city').attr('id'),
        subscriptionSubmit = $('.subscription-submit');
        var onSubmitFunction = function(){
                var enteredEmail = $('.subscription-email').val();
                $.ajax({
                        method: 'post',
                        dataType: 'json',
                        url: 'https://clear.codeday.org/api/notify/subscribe',
                        data: {
                                email: enteredEmail,
                                event: eventId
                        },
                        success: function(){
                                $('.subscription-email').val('');
                                subscriptionSubmit.css('background', '#A0D388').val('Thanks!');
                                setTimeout(function(){
                                        subscriptionSubmit.css('background', '#DD5D5C').val('Subscribe');
                                }, 1500);
                        }
                });
        };
        subscriptionSubmit.on('click', onSubmitFunction);
        $('.subscription-email').on('keypress', function(e) { if(e.which == 13) { onSubmitFunction(); } });
})();

// City picker stuff
(function(){
	var spanCurrentCity = $('span.current-city'),
		inputCurrentCity = $('input.current-city'),
		cityPicker = $('#city-picker'),
   		previous_picker_xhr,
		cityValue;

	// Retrieve city picker values
	function capitaliseFirstLetter(string) {
	    return string.charAt(0).toUpperCase() + string.slice(1);
	}

	function displayCities(response_object) {
		$.each(response_object, function(key, value) {
			var cityOption = '<li id="' + value.id + ' " class="city-option">' + value.name + '</li>';
			if (key !== 0) {
				$('#city-picker ul').append(cityOption);
			} else {
				$('#city-picker ul').html(cityOption);
			}
			return key < 7;
		});
		$('.city-option').on('click', function(){
			if (!$(this).hasClass('no-click')) {
				var pickedCity = $(this).html();
				var pickedId = $(this).attr('id');
				spanCurrentCity.html(pickedCity);
                                if (typeof(window.location.replace) !== 'undefined') {
                               	    window.location.replace('/'+pickedId);
                                } else {
                                    window.location.href = '/'+pickedId;
                                }
           
			}
		});
	}

	$('.current-city').click(function(e){
		// Change current-city span into input box 
		spanCurrentCity.hide();
		inputCurrentCity.css('width', '6.2em').show().focus();
		cityPicker.fadeIn('fast'); // Display city picker when current city is clicked
		return false;
	})

	$(document).on('click', function() { 
		cityPicker.fadeOut('fast'); 
		inputCurrentCity.hide();
		spanCurrentCity.show();
	});

	if (inputCurrentCity.is(':focus')) {
		cityPicker.show();
	}

	if ($.trim(inputCurrentCity.val()).length == 0) { // If current city input box is empty
		$.ajax({
			url: "https://clear.codeday.org/api/regions/nearby?lat=" + window.lat + "&lng=" + window.lng + "&limit=5&with_current_event=1",
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
		$('#city-picker ul').html('<li class="city-option no-click">. . .</li>');
        if ($.trim(inputCurrentCity.val()).length == 0) { // If current city input box is empty, search for nearest cities
			$.ajax({
				url: "https://clear.codeday.org/api/regions/nearby?lat=" + window.lat + "&lng=" + window.lng + "&limit=5&with_current_event=1",
				method: "GET",
				dataType: "JSON",
				success: function(response_object) {
					displayCities(response_object);
				}
			});
		} else {
			$.ajax({
				url: "https://clear.codeday.org/api/regions/search?term=" + cityValue,
				method: "GET",
				dataType: "JSON",
				success: function(response_object) {
					displayCities(response_object);
				}
			});
		}
	});



})();

// Registration ticket price calculations and add coders
(function(){
	$('#ticket-amount').change(function() { 
		var quantity = $('#ticket-amount').val(),
			total = quantity * window.unit_cost;
		$('#total-cost').text('$' + total);
        window.quoted_price = total;

		$('#coder-profile-1').siblings('.coder-profile').remove(); // Remove extra coder profiles (reset)

		for (var i = 0; i < quantity - 1; i++) {
			var $coderProfile = $('#coder-profile-1').clone(); // Clone coder profile 1
			var $newCoderProfile = ($coderProfile).attr('id','coder-profile-' + (i + 2)); // Set new coder profile with id of, say, 3
            $newCoderProfile.find('input').val('');
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




