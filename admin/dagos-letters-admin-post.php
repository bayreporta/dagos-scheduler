<?php

/**
 * Handles Posting Letters to database and sending notification to editors
 *
 *
 * @link       http://johnosborndagostino.com
 * @since      1.0.0
 *
 * @package    dagos-letters
 * @subpackage dagos-letters/admin
 */

/** Check headers
----------------------------------------------------*/

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	$protocol = $_SERVER['SERVER_PROTOCOL'];
	if ( ! in_array( $protocol, array( 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0' ) ) ) {
		$protocol = 'HTTP/1.0';
	}

	header('Allow: POST');
	header("$protocol 405 Method Not Allowed");
	header('Content-Type: text/plain');
	exit;
}

/** Sets up the WordPress Environment 
----------------------------------------------------*/
require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

nocache_headers();

$location = get_the_permalink($_POST['letter_post_ID']);


/** ReCAPTCHA checkin
----------------------------------------------------*/

/** Sets up reCAPTCHA */
require_once plugin_dir_path( __FILE__ ) . '\recaptchalib.php';

$secret = "6Lfo1BgUAAAAAPqG6XTN4-hMBf5DEYgErYcTaHWc";
 
$response = null;
 
$reCaptcha = new ReCaptcha($secret);

if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
} else {
	$location .= '?verification';
	wp_safe_redirect( $location );
	exit;
} 

/** Validate email and add letter if human
----------------------------------------------------*/

if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL ) && $response->success && $response !== null ){
	$location .= '?error_email';
} elseif ( $response->success && $response !== null ) {
	$status = Dagos_Letters_Admin::dagos_letters_submission( wp_unslash( $_POST ) );

	Dagos_Letters_Admin::dagos_letters_email_editors( $_POST );

	$location .= '?' . $status;
}

wp_safe_redirect( $location );
exit;
