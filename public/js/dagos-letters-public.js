(function( $ ) {
	'use strict';

	$(window).load(function(){
		dagosLettersAfterSubmit();
	})

	function dagosLettersAfterSubmit() {
		var curURL 		= window.location.href,
			parsedURL 	= curURL.split( '?' ),
			success 	= 'Your letter has been received and is pending review by our editors.',
			error   	= 'There was an error when submitting your letter. Please try again.',
			error_email = 'Please enter a valid email address before submitting.',
			recaptcha	= 'Please verify you are not a robot.';

		//exit if no url
		if ( parsedURL[1] === undefined ){
			return;
		}

		//activate letters area
		$('.comment-mod').addClass('comment-mod--is-active');


		//scroll back to dagos letters	
		$('html, body').animate({
			scrollTop: $('.dagos-letters-message').offset().top - 25
		}, 250);

		//determine the message to communicate to user after submitting the form
		if ( parsedURL[1] === 'success' ) {
			$('.dagos-letters-message').css( 'color', 'green' ).text(success);
		} 
		else if ( parsedURL[1] === 'error' ) {
			$('.dagos-letters-message').css( 'color', 'red' ).text(error);
		}
		else if ( parsedURL[1] === 'error_email' ) {
			$('.dagos-letters-message').css( 'color', 'red' ).text(error_email);
		}
		else if ( parsedURL[1] === 'verification' ) {
			$('.dagos-letters-message').css( 'color', 'red' ).text(recaptcha);
		}
	}
})( jQuery );
