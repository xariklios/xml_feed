<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://softexpert.gr
 * @since             1.0.0
 * @package           Se_xml_feed
 *
 * @wordpress-plugin
 * Plugin Name:       SE xml feed
 * Plugin URI:        https://softexpert.gr
 * Description:       woo xml feed
 * Version:           1.0.0
 * Author:            Charis Valtzis
 * Author URI:        https://softexpert.gr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       se_xml_feed
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
define( 'SE_XML_FEED_VERSION', '1.0.0' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-se_xml_feed-activator.php
 */
function activate_se_xml_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-se_xml_feed-activator.php';
	Se_xml_feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-se_xml_feed-deactivator.php
 */
function deactivate_se_xml_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-se_xml_feed-deactivator.php';
	Se_xml_feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_se_xml_feed' );
register_deactivation_hook( __FILE__, 'deactivate_se_xml_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-se_xml_feed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_se_xml_feed() {

	$plugin = new Se_xml_feed();
	$plugin->run();

}
run_se_xml_feed();
