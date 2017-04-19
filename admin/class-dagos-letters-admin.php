<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://johnosborndagostino.com
 * @since      1.0.0
 *
 * @package    dagos-letters
 * @subpackage dagos-letters/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    dagos-letters
 * @subpackage dagos-letters/admin
 * @author     John Osborn D'Agostino <bayreporta@gmail.com>
 */
class Dagos_Letters_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $dagos_letters;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $dagos_letters, $version ) {

		$this->plugin_name = $dagos_letters;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dagos_Letters_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dagos_Letters_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dagos-letters-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dagos_Letters_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dagos_Letters_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dagos-letters-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	 
	public function add_dagos_letters_admin_menu() {

	    /*
	     * Add a settings page for this plugin to the Settings menu.
	     *
	     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
	     *
	     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
	     *
	     */
	    //add_options_page( 'Dago\'s Letters Setup', 'Dago\'s Letters', 'manage_options', $this->plugin_name, array($this, 'display_dagos_letters_setup_page'));

	    add_menu_page( 'Dago\'s Scheduler Admin', 'Dago\'s Scheduler', 'administrator', $this->plugin_name, array($this, 'display_dagos_letters_menu_page'));

	}

	 /**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	 
	public function add_action_links( $links ) {
	    /*
	    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	    */
	   $settings_link = array(
	    '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	 
	public function display_dagos_letters_setup_page() {
	    include_once( 'partials/dagos-letters-settings-display.php' );
	}

	/**
	 * Render the admin page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_dagos_letters_menu_page() {
	    include_once( 'partials/dagos-letters-admin-display.php' );		
	}

	public function dagos_letters_email_editors( $letter_data ) {

		$to = 'bayreporta@gmail.com';

		$subject = 'Letter to Editor Submission - ' . strtoupper($letter_data['type']);

		$msg = '';
		$msg .= "From: " . $letter_data['author'] . " (" . $letter_data['email'] . ")\r\n";
		$msg .= "\r\n";
		$msg .= $letter_data['letter'];
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "On the article: " . $url;
		//jump to edit page

		$header = "From: " . $letter_data['email'];

		wp_mail( $to, $subject, $msg, $header );
	}

	public function dagos_letters_email_submitter( $email, $url ) {
		$url = get_the_permalink( $url );
		$subject = get_field( 'dl_approved_subject', 'option' );
		$body = get_field( 'dl_approved_body', 'option' );

		$to = $email;

		$msg = $body;
		//$msg .= "Your letter to the editor has been approved and is now available for viewing online.\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "Link to article: " . $url;

		$header = "From: josborn25@gmail.com"; //need to change this

		//wp_mail( $to, $subject, $msg, $header );

	}

	/**
	 * Handles the submission of a comment, usually posted to wp-comments-post.php via a comment form.
	 *
	 * This function expects unslashed data, as opposed to functions such as `wp_new_comment()` which
	 * expect slashed data.
	 *
	 * @since 1.0.0
	 *
	 */
	public function dagos_letters_submission( $letter_data ) {

		$letter_represent = $letter_district = $letter_mtg_date = $letter_author = $letter_author_email = $letter_content = null;

		if ( isset( $letter_data['author'] ) && is_string( $letter_data['author'] ) ) {
			$letter_author = trim( strip_tags( $letter_data['author'] ) );
		}
		if ( isset( $letter_data['email'] ) && is_string( $letter_data['email'] ) ) {
			$letter_author_email = trim( $letter_data['email'] );
		}
		if ( isset( $letter_data['content'] ) && is_string( $letter_data['content'] ) ) {
			$letter_content = trim( $letter_data['content'] );
		}
		if ( isset( $letter_data['represent'] ) && is_string( $letter_data['represent'] ) ) {
			$letter_represent = trim( $letter_data['represent'] );
		}
		if ( isset( $letter_data['district'] ) && is_string( $letter_data['district'] ) ) {
			$letter_district = trim( $letter_data['district'] );
		}
		if ( isset( $letter_data['date'] ) && is_string( $letter_data['date'] ) ) {
			$letter_mtg_date = trim( $letter_data['date'] );
		}

		$letterdata = compact(
			'letter_author',
			'letter_author_email',
			'letter_content',
			'letter_represent',
			'letter_district',
			'letter_mtg_date'
		);

		$letter_id = Dagos_Letters_Admin::dagos_letter_create_letter( wp_slash( $letterdata ), true );

		if ( is_wp_error( $letter_id ) ) {
			return 'error';
		}

		if ( ! $letter_id ) {
			return 'error';
		}

		return 'success';
	}

	public function dagos_letter_create_letter( $letterdata, $avoid_die = false ) {
		global $wpdb;

		/**
		 * Filters data before it is sanitized and inserted into the database. Adds meta data
		 */

		$letterdata = apply_filters( 'preprocess_comment', $letterdata );

		if ( empty( $letterdata['letter_date'] ) ) {
			$letterdata['letter_date'] = current_time('mysql');
		}

		if ( empty( $letterdata['letter_date_gmt'] ) ) {
			$letterdata['letter_date_gmt'] = current_time( 'mysql', 1 );
		}		

		$letterdata = Dagos_Letters_Admin::dagos_letters_filter_letter($letterdata);

		$letterdata['letter_complete'] = 0;

		$letter_ID = Dagos_Letters_Admin::dagos_letter_insert_letter($letterdata);

		return $letter_ID;
	}

	/**
	 * Inserts a letter into the database.
	 */
	public function dagos_letter_insert_letter( $letterdata ) {

		global $wpdb;
		$data = wp_unslash( $letterdata );

		$letter_author       	= ! isset( $data['letter_author'] )       	? '' : $data['letter_author'];
		$letter_author_email	= ! isset( $data['letter_author_email'] ) 	? '' : $data['letter_author_email'];
		$letter_date     		= ! isset( $data['letter_date'] )     		? current_time( 'mysql' )            : $data['letter_date'];
		$letter_date_gmt 		= ! isset( $data['letter_date_gmt'] ) 		? get_gmt_from_date( $letter_date ) : $data['letter_date_gmt'];
		$letter_content  		= ! isset( $data['letter_content'] )  		? '' : $data['letter_content'];
		$letter_complete 		= ! isset( $data['letter_approved'] ) 		? 0  : $data['letter_complete'];
		$letter_represent  		= ! isset( $data['letter_represent'] ) 		? '' : $data['letter_represent'];
		$letter_district  		= ! isset( $data['letter_district'] ) 		? '' : $data['letter_district'];
		$letter_mtg_date  		= ! isset( $data['letter_mtg_date'] )  		? '' : $data['letter_mtg_date'];

		$wpdb->insert( $wpdb->prefix . 'dagos_letters', array(
			'letter_name'	 		=> $letter_author,
			'letter_email'		 	=> $letter_author_email,
			'letter_date' 			=> $letter_date,
			'letter_date_gmt' 		=> $letter_date_gmt,
			'letter_content' 		=> $letter_content,
			'letter_complete' 		=> $letter_complete,
			'letter_represent' 		=> $letter_represent,
			'letter_district' 		=> $letter_district,
			'letter_meeting_date' 	=> $letter_mtg_date,
		) );
		$id = (int) $wpdb->insert_id;

		return $id;
	}


	/**
	 * Filters and sanitizes letter data.
	 */
	public function dagos_letters_filter_letter($letterdata) {		

		$letterdata['letter_author'] = apply_filters( 'pre_comment_author_name', $letterdata['letter_author'] );
		$letterdata['letter_content'] = apply_filters( 'pre_comment_content', $letterdata['letter_content'] );
		$letterdata['letter_mtg_date'] = apply_filters( 'pre_comment_content', $letterdata['letter_mtg_date'] );
		$letterdata['letter_represent'] = apply_filters( 'pre_comment_content', $letterdata['letter_represent'] );
		$letterdata['letter_district'] = apply_filters( 'pre_comment_content', $letterdata['letter_district'] );
		$letterdata['letter_author_email'] = apply_filters( 'pre_comment_author_email', $letterdata['letter_author_email'] );

		$letterdata['filtered'] = true;

		return $letterdata;
	}

}
