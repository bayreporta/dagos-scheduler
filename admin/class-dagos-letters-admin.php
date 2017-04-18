<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

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

	    add_menu_page( 'Dago\'s Letters Admin', 'Dago\'s Letters', 'administrator', $this->plugin_name, array($this, 'display_dagos_letters_menu_page'));

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
		$url = get_the_permalink( $letter_data['letter_post_ID'] );

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

		wp_mail( $to, $subject, $msg, $header );

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

		$letter_post_ID = 0;
		$letter_type = $letter_author = $letter_author_email = $letter_author_url = $letter_content = null;

		if ( isset( $letter_data['letter_post_ID'] ) ) {
			$letter_post_ID = (int) $letter_data['letter_post_ID'];
		}
		if ( isset( $letter_data['type'] ) ) {
			$letter_type = trim( strip_tags( $letter_data['type'] ) );
		}
		if ( isset( $letter_data['author'] ) && is_string( $letter_data['author'] ) ) {
			$letter_author = trim( strip_tags( $letter_data['author'] ) );
		}
		if ( isset( $letter_data['email'] ) && is_string( $letter_data['email'] ) ) {
			$letter_author_email = trim( $letter_data['email'] );
		}
		/*if ( isset( $letter_data['url'] ) && is_string( $letter_data['url'] ) ) {
			$comment_author_url = trim( $letter_data['url'] );
		}*/
		if ( isset( $letter_data['letter'] ) && is_string( $letter_data['letter'] ) ) {
			$letter_content = trim( $letter_data['letter'] );
		}

		$post = get_post( $letter_post_ID );

		if ( empty( $post->comment_status ) ) {

			/**
			 * Fires when a letter is attempted on a post that does not exist.
			 **/

			do_action( 'comment_id_not_found', $letter_post_ID );

			return new WP_Error( 'comment_id_not_found' );
		}

		// get_post_status() will get the parent status for attachments.
		$status = get_post_status( $post );

		if ( ( 'private' == $status ) && ! current_user_can( 'read_post', $letter_post_ID ) ) {
			return new WP_Error( 'comment_id_not_found' );
		}

		$status_obj = get_post_status_object( $status );


		if ( ! comments_open( $letter_post_ID ) ) {

			/**
			 * Fires when a letter is attempted on a post that has letters closed.
			 */

			do_action( 'comment_closed', $letter_post_ID );

			return new WP_Error( 'comment_closed', __( 'Sorry, comments are closed for this item.' ), 403 );

		} elseif ( 'trash' == $status ) {

			/**
			 * Fires when a letter is attempted on a trashed post.
			 */

			do_action( 'comment_on_trash', $letter_post_ID );

			return new WP_Error( 'comment_on_trash' );

		} elseif ( ! $status_obj->public && ! $status_obj->private ) {

			/**
			 * Fires when a letter is attempted on a post in draft mode.
			 */
			do_action( 'comment_on_draft', $letter_post_ID );

			return new WP_Error( 'comment_on_draft' );

		} elseif ( post_password_required( $letter_post_ID ) ) {

			/**
			 * Fires when a letter is attempted on a password-protected post.
			 */
			do_action( 'comment_on_password_protected', $letter_post_ID );

			return new WP_Error( 'comment_on_password_protected' );

		} else {

			/**
			 * Fires before a letter is posted.
			 */
			do_action( 'pre_comment_on_post', $letter_post_ID );

		}

		// If the user is logged in
		$user = wp_get_current_user();
		if ( $user->exists() ) {
			if ( empty( $user->display_name ) ) {
				$user->display_name=$user->user_login;
			}
			$letter_author       = $user->display_name;
			$letter_author_email = $user->user_email;
			$letter_author_url   = $user->user_url;
			$letter_user_ID      = $user->ID;
			if ( current_user_can( 'unfiltered_html' ) ) {
				if ( ! isset( $letter_data['_wp_unfiltered_html_comment'] )
					|| ! wp_verify_nonce( $letter_data['_wp_unfiltered_html_comment'], 'unfiltered-html-comment_' . $comment_post_ID )
				) {
					kses_remove_filters(); // start with a clean slate
					kses_init_filters(); // set up the filters
				}
			}
		} else {
			if ( get_option( 'comment_registration' ) ) {
				return new WP_Error( 'not_logged_in', __( 'Sorry, you must be logged in to comment.' ), 403 );
			}
		}

		$letterdata = compact(
			'letter_post_ID',
			'letter_author',
			'letter_author_email',
			'letter_content',
			'letter_type',
			'letter_user_ID'
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

		if ( isset( $letterdata['letter_user_ID'] ) ) {
			$letterdata['letter_user_ID'] = $letterdata['letter_user_ID'] = (int) $letterdata['letter_user_ID'];
		}

		$prefiltered_user_id = ( isset( $letterdata['letter_user_ID'] ) ) ? (int) $letterdata['letter_user_ID'] : 0;

		/**
		 * Filters a comment's data before it is sanitized and inserted into the database. Adds meta data
		 */

		$letterdata = apply_filters( 'preprocess_comment', $letterdata );

		$letterdata['letter_post_ID'] = (int) $letterdata['letter_post_ID'];

		if ( isset( $letterdata['letter_user_ID'] ) && $prefiltered_user_id !== (int) $letterdata['letter_user_ID'] ) {
			$letterdata['letter_user_ID'] = $letterdata['letter_user_ID'] = (int) $letterdata['letter_user_ID'];
		} elseif ( isset( $letterdata['letter_user_ID'] ) ) {
			$letterdata['letter_user_ID'] = (int) $letterdata['letter_user_ID'];
		}

		if ( ! isset( $letterdata['letter_author_IP'] ) ) {
			$letterdata['letter_author_IP'] = $_SERVER['REMOTE_ADDR'];
		}

		$letterdata['letter_author_IP'] = preg_replace( '/[^0-9a-fA-F:., ]/', '', $letterdata['letter_author_IP'] );

		if ( ! isset( $letterdata['letter_author_agent'] ) ) {
			$letterdata['letter_author_agent'] = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT']: '';
		}
		$letterdata['letter_author_agent'] = substr( $letterdata['letter_author_agent'], 0, 254 );

		if ( empty( $letterdata['letter_date'] ) ) {
			$letterdata['letter_date'] = current_time('mysql');
		}

		if ( empty( $letterdata['letter_date_gmt'] ) ) {
			$letterdata['letter_date_gmt'] = current_time( 'mysql', 1 );
		}		

		$letterdata = Dagos_Letters_Admin::dagos_letters_filter_letter($letterdata);

		$letterdata['letter_approved'] = 0;

		$letter_ID = Dagos_Letters_Admin::dagos_letter_insert_letter($letterdata);

		return $letter_ID;
	}

	/**
	 * Inserts a letter into the database.
	 */
	public function dagos_letter_insert_letter( $letterdata ) {

		global $wpdb;
		$data = wp_unslash( $letterdata );

		$letter_author       	= ! isset( $data['letter_author'] )       ? '' : $data['letter_author'];
		$letter_author_email	= ! isset( $data['letter_author_email'] ) ? '' : $data['letter_author_email'];
		$letter_author_IP    	= ! isset( $data['letter_author_IP'] )    ? '' : $data['letter_author_IP'];
		$letter_author_agent    = ! isset( $data['letter_author_agent'] )    ? '' : $data['letter_author_agent'];

		$letter_date     = ! isset( $data['letter_date'] )     ? current_time( 'mysql' )            : $data['letter_date'];
		$letter_date_gmt = ! isset( $data['letter_date_gmt'] ) ? get_gmt_from_date( $letter_date ) : $data['letter_date_gmt'];

		$letter_post_ID  = ! isset( $data['letter_post_ID'] )  ? 0  : $data['letter_post_ID'];
		$letter_content  = ! isset( $data['letter_content'] )  ? '' : $data['letter_content'];
		$letter_approved = ! isset( $data['letter_approved'] ) ? 0  : $data['letter_approved'];
		$letter_featured = ! isset( $data['letter_featured'] ) ? 0  : $data['featured'];
		$letter_type     = ! isset( $data['letter_type'] )     ? '' : $data['letter_type'];

		$letter_user_ID  = ! isset( $data['letter_user_ID'] ) ? 0 : $data['letter_user_ID'];
		
		$wpdb->insert( $wpdb->prefix . 'dagos_letters', array(
			'letter_post_ID' 		=> $letter_post_ID,
			'letter_featured' 		=> $letter_featured,
			'letter_author' 		=> $letter_author,
			'letter_author_email' 	=> $letter_author_email,
			'letter_author_agent'	=> $letter_author_agent,
			'letter_author_IP' 		=> $letter_author_IP,
			'letter_date' 			=> $letter_date,
			'letter_date_gmt' 		=> $letter_date_gmt,
			'letter_content' 		=> $letter_content,
			'letter_approved' 		=> $letter_approved,
			'letter_type' 			=> $letter_type,
			'letter_user_ID' 		=> $letter_user_ID
		) );
		$id = (int) $wpdb->insert_id;

		return $id;
	}


	/**
	 * Filters and sanitizes letter data.
	 */
	public function dagos_letters_filter_letter($letterdata) {		

		if ( isset( $letterdata['letter_user_ID'] ) ) {
			/**
			 * Filters the comment author's user id before it is set.
			 */
			$letterdata['letter_user_ID'] = apply_filters( 'pre_user_id', $letterdata['letter_user_ID'] );
		} elseif ( isset( $letterdata['letter_user_ID'] ) ) {
			/** This filter is documented in wp-includes/comment.php */
			$letterdata['letter_user_ID'] = apply_filters( 'pre_user_id', $letterdata['letter_user_ID'] );
		}

		/**
		 * Filters the comment author's browser user agent before it is set.
		 */
		$letterdata['letter_author_agent'] = apply_filters( 'pre_comment_user_agent', ( isset( $letterdata['letter_author_agent'] ) ? $letterdata['letter_author_agent'] : '' ) );
		/** This filter is documented in wp-includes/comment.php */
		$letterdata['letter_author'] = apply_filters( 'pre_comment_author_name', $letterdata['letter_author'] );
		/**
		 * Filters the comment content before it is set.
		 */
		$letterdata['letter_content'] = apply_filters( 'pre_comment_content', $letterdata['letter_content'] );
		/**
		 * Filters the comment author's IP before it is set.
		 */
		$letterdata['letter_author_IP'] = apply_filters( 'pre_comment_user_ip', $letterdata['letter_author_IP'] );

		/** This filter is documented in wp-includes/comment.php */
		$letterdata['letter_author_email'] = apply_filters( 'pre_comment_author_email', $letterdata['letter_author_email'] );
		$letterdata['filtered'] = true;

		return $letterdata;
	}

}
