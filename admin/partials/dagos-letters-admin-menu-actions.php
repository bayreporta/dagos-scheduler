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
	if ( $_REQUEST['target'] === 'Approve' ){
		$wpdb->update( 
			$table,
			array( //column
				'letter_approved' => '1' 
			),
			array( //where
				'id' => $_REQUEST['id']
			),
			array( //format
				'%s'
			),
			array( //where format
				'%d'
			)
		);
		Dagos_Letters_Admin::dagos_letters_email_submitter( $_REQUEST['email'], $_REQUEST['article'] );
		unset($q);
		
	} elseif ( $_REQUEST['target'] === 'Unapprove' ){
		$wpdb->update( 
			$table,
			array( //column
				'letter_approved' => '0' 
			),
			array( //where
				'id' => $_REQUEST['id']
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
				'id' => $_REQUEST['id']
			),
			array( //where format
				'%d'
			)
		);
	}
}

/** #2: UPDATE LETTERS FROM ADMIN SCREEN
----------------------------------------------------*/
if ( $_REQUEST['postid'] ) {
	$wpdb->update( 
		$table,
		array( //column
			'letter_author' => $_REQUEST['name'],
			'letter_author_email' => $_REQUEST['email'],
			'letter_content' => $_REQUEST['content']
		),
		array( //where
			'id' => $_REQUEST['id']
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