<?php

/**
 * Fired during plugin activation
 *
 * @link       http://johnosborndagostino.com
 * @since      1.0.0
 *
 * @package    dagos-letters
 * @subpackage dagos-letters/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    dagos-letters
 * @subpackage dagos-letters/includes
 * @author     John Osborn D'Agostino <bayreporta@gmail.com>
 */
class Dagos_Letters_Activator {
	public static $dago_letters_db_version = 1.0;

	/**
	 * Activation protocols
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		Dagos_Letters_Activator::create_tables();
	}

	/**
	 * Build database tables
	 *
	 * @access 	private
	 * @since   1.0.0
	 */
	private static function create_tables() {
		global $wpdb;
	
		//only create table if it doesn't exist
		if ($dbChk = $wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . 'dagos_letters' . "'" )) {
			if ( $dbChk->num_rows === 1) {
				die;
			}
		}
	
		$table_name = $wpdb->prefix . 'dagos_letters';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				letter_post_ID bigint(20) NOT NULL,
				letter_featured varchar(20)  DEFAULT '0' NOT NULL,
				letter_author tinytext NOT NULL,
				letter_author_email varchar(100) DEFAULT '' NOT NULL,
				letter_author_agent varchar(255) DEFAULT '' NOT NULL,
				letter_author_IP varchar(100) DEFAULT '' NOT NULL,
				letter_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				letter_date_gmt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				letter_content text NOT NULL,
				letter_approved varchar(20)  DEFAULT '0' NOT NULL,
				letter_type varchar(100) NOT NULL,	
				letter_user_ID bigint(20) NOT NULL,		
				PRIMARY KEY (id)
		) $charset_collate";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		add_option( 'dago_letters_db_version', Dagos_Letters_Activator::$dago_letters_db_version );
	}

}
