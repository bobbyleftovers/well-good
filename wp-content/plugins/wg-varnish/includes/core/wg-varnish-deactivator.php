<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.1
 * @package    WG_Varnish
 * @subpackage WG_Varnish/includes
 * @author     Barrel
 */

class WG_Varnish_Deactivator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.1
	 */
	public static function deactivate() {

		wp_clear_scheduled_hook( 'wg_varnish_db_daily_cleanup' );

	}

}
