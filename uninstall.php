<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://servitor.io
 * @since      1.0.0
 *
 * @package    Servitor_Statistics
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// An user is uninstalling the plugin, we don't know why. But at the very least we should
// Reset the users credentials so they stay safe. :)

// List of all the options, found in class-servitor-statistics-admin.php
$options = [
    'servitor_user_key',
    'servitor_api_key',
    'servitor_monitor_id',
];
foreach($options as $option){
    delete_option($option);
}
