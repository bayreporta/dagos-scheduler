<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://johnosborndagostino.com
 * @since      1.0.0
 *
 * @package    dagos-letters
 * @subpackage dagos-letters/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    dagos-letters
 * @subpackage dagos-letters/public
 * @author     John Osborn D'Agostino <bayreporta@gmail.com>
 */
class Dagos_Letters_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $dagos_letters    The ID of this plugin.
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $dagos_letters, $version ) {

		$this->plugin_name = $dagos_letters;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dagos-letters-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dagos-letters-public.js', array( 'jquery' ), $this->version, false );

	}

	public function dagos_letters_query_letters() {
		global $wpdb;
		$id = get_the_ID();
		$table = $wpdb->prefix . 'dagos_letters';

		$query = array();
		$query[] = "SELECT *";
		$query[] = "FROM wp_dagos_letters";
		$query[] = "WHERE letter_post_ID = " . $id . "";
		$query[] = "AND letter_approved = 1";
		$results = $wpdb->get_results( implode( " ", $query ) );

		return Dagos_Letters_Public::dagos_letters_populate_letters($results);
	}

	public function dagos_letters_populate_letters($results) {
		$ret = '';
		$size = sizeof($results);

		if ( $size === 0 ) {
			$ret .= '<div class="dagos-letters-letter">';
		        $ret .= '<p class="dagos-letters-none">No letters have been published at this time.</p>';		        
		    $ret .= '</div>';
			return $ret;
		}

		for ( $i=0 ; $i < $size ; $i++ ) {
			//convert line breaks to p tags
			$content = explode( "\n", $results[$i]->letter_content );

			//data convertions
			$date = strtotime($results[$i]->letter_date);
			$date = date('M d, Y', $date);

			$ret .= '<div id="letter-' . $results[$i]->id . '" class="dagos-letters-letter">';
		        $ret .= '<div>';
		        	foreach ($content as $c) {
			        	$ret .= '<p>' . $c . '</p>';
			        }
		        $ret .= '</div>';
		        $ret .= '<div>';
		        	$ret .= '<p>';
			          $ret .= '<span>- from ' . $results[$i]->letter_author . '</span>, '; 
			          $ret .= '<span>' . $date . '</span>';
			        $ret .= '</p>';
		        $ret .= '</div>';		        
		    $ret .= '</div>';
		}
		unset($results);
		return $ret;
	}
}