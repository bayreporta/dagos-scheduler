<?php


/**
 * Handles Actions via WP Admin Panel
 *
 *
 * @link       http://johnosborndagostino.com
 * @since      1.0.0
 *
 * @package    dagos-letters
 * @subpackage dagos-letters/admin
 */

/** Sets up the WordPress Environment 
----------------------------------------------------*/
require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
nocache_headers();

global $wpdb;
$table = $wpdb->prefix . 'dagos_letters';

/** #1: APPROVE, UNAPPROVE, DELETE LETTERS
----------------------------------------------------*/

if ( $_REQUEST['target'] ) {
	$id = stripslashes( $_REQUEST['id'] );

	if ( $_REQUEST['target'] === 'Approve' ){
		$wpdb->update( 
			$table,
			array( //column
				'letter_complete' => '1' 
			),
			array( //where
				'id' => $id
			),
			array( //format
				'%s'
			),
			array( //where format
				'%d'
			)
		);
		unset($q);
		
	} elseif ( $_REQUEST['target'] === 'Unapprove' ){
		$wpdb->update( 
			$table,
			array( //column
				'letter_complete' => '0' 
			),
			array( //where
				'id' => $id
			),
			array( //format
				'%s'
			),
			array( //where format
				'%d'
			)
		);
	} elseif ( $_REQUEST['target'] === 'trash' ){
		$wpdb->delete( 
			$table,			
			array( //where
				'id' => $id
			),
			array( //where format
				'%d'
			)
		);
	}
}

/** #2: UPDATE LETTERS FROM ADMIN SCREEN
----------------------------------------------------*/
if ( $_REQUEST['content'] ) {
	$id = stripslashes( $_REQUEST['id'] );
	$name = stripslashes( $_REQUEST["name"] );
	$email = stripslashes( $_REQUEST["email"] );
	$content = stripslashes( $_REQUEST["content"] );

	$wpdb->update( 
		$table,
		array( //column
			"letter_name" => $name,
			'letter_email' => $email,
			'letter_content' => $content
		),
		array( //where
			'id' => $id
		),
		array( //format
			'%s',
			'%s',
			'%s'
		),
		array( //where format
			'%d'
		)
	);
}