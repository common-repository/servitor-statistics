<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://servitor.io
 * @since             0.0.1
 * @package           Servitor_Statistics
 *
 * @wordpress-plugin
 * Plugin Name:       Servitor statistics
 * Plugin URI:        https://servitor.io
 * Description:       Servitor is the easiest way to monitor your servers and website for resources, status, uptime, response times, ssl certificates, mixed contents en broken links.
 * Version:           0.0.6
 * Author:            Servitor.io
 * Author URI:        https://zandervdm.nl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       servitor-statistics
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'Servitor_Statistics_VERSION', '0.0.6' );

define( 'Servitor_Statistics_Settings_Group', 'servitor_credentials');

define( 'Servitor_Statistics_Cronjob', 'servitor_statistics_cronjob');

define( 'Servitor_Statistics_Host', 'https://servitor.io');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-servitor-statistics-activator.php
 */
function activate_Servitor_Statistics() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-servitor-statistics-activator.php';
	Servitor_Statistics_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-servitor-statistics-deactivator.php
 */
function deactivate_Servitor_Statistics() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-servitor-statistics-deactivator.php';
	Servitor_Statistics_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_Servitor_Statistics' );
register_deactivation_hook( __FILE__, 'deactivate_Servitor_Statistics' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-servitor-statistics.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Servitor_Statistics() {

	$plugin = new Servitor_Statistics();
	$plugin->run();

}

// Debug function, should only be used when developing.
//if(!function_exists('dd')) {
//    function dd($args) {
//        echo '<pre>';
//        print_r($args);
//        exit;
//    }
//}

run_Servitor_Statistics();
