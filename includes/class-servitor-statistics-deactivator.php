<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://servitor.io
 * @since      1.0.0
 *
 * @package    Servitor_Statistics
 * @subpackage Servitor_Statistics/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Servitor_Statistics
 * @subpackage Servitor_Statistics/includes
 * @author     Your Name <email@example.com>
 */
class Servitor_Statistics_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        $timestamp = wp_next_scheduled( Servitor_Statistics_Cronjob );
        wp_unschedule_event( $timestamp, Servitor_Statistics_Cronjob );
	}
}
