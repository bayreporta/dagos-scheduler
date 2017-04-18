<?php

/**
 *
 * @wordpress-plugin
 * Plugin Name:       Dago's Scheduler
 * Description:       Tool to document meetings with elected officials.
 * Version:           1.0.0
 * Author:            John Osborn D'Agostino
 * Author URI:        http://johnosborndagostino.com/
 * Text Domain:       dagos-letters-to-editor
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_dagos_letters() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dagos-letters-activator.php';
	Dagos_Letters_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_dagos_letters() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dagos-letters-deactivator.php';
	Dagos_Letters_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dagos_letters' );
register_deactivation_hook( __FILE__, 'deactivate_dagos_letters' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dagos-letters.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dagos_letters() {

	$plugin = new Dagos_Letters();
	$plugin->run();

}
run_dagos_letters();

