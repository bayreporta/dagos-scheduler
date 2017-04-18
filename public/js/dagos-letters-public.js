(function( $ ) {
	'use strict';

	$(window).load(function(){
		dagosLettersAfterSubmit();
	})

	function dagosLettersAfterSubmit() {
		var curURL 		= window.location.href,
			parsedURL 	= curURL.split( '?' ),
			success 	= 'Your submission was successful!',
			error   	= 'There was an error when submitting. Please try again.',
			error_email = 'Please enter a valid email address before submitting.',
			recaptcha	= 'Please verify you are not a robot.';

		//exit if no url
		if ( parsedURL[1] === undefined ){
			return;
		}

		//determine the message to communicate to user after submitting the form
		if ( parsedURL[1] === 'success' ) {
			$('#dagos-scheduler-message h2').css( 'color', 'green' ).text(success);
		} 
		else if ( parsedURL[1] === 'error' ) {
			$('#dagos-scheduler-message h2').css( 'color', 'red' ).text(error);
		}
		else if ( parsedURL[1] === 'error_email' ) {
			$('#dagos-scheduler-message h2').css( 'color', 'red' ).text(error_email);
		}
		else if ( parsedURL[1] === 'verification' ) {
			$('#dagos-scheduler-message h2').css( 'color', 'red' ).text(recaptcha);
		}
	}
})( jQuery );
