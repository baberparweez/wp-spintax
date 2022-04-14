<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              -
 * @since             1.0.0
 * @package           Spintax
 *
 * @wordpress-plugin
 * Plugin Name:       Spintax
 * Plugin URI:        -
 * Description:       This is a small WordPress plugin used to 'spin' through alternative words on page reload.
 * Version:           1.0.0
 * Author:            Baber Parweez
 * Author URI:        -
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spintax
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SPINTAX_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-spintax-activator.php
 */
function activate_Spintax() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spintax-activator.php';
	Spintax_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-spintax-deactivator.php
 */
function deactivate_Spintax() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spintax-deactivator.php';
	Spintax_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_spintax' );
register_deactivation_hook( __FILE__, 'deactivate_spintax' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-spintax.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Spintax() {

	$plugin = new Spintax();
	$plugin->run();

}
run_Spintax();
