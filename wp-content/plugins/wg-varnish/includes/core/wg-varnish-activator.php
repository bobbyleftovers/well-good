<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.1
 * @package    WG_Varnish
 * @subpackage WG_Varnish/includes
 * @author     Barrel
 */

class WG_Varnish_Activator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.1
	 */
	public static function activate() {

		require_once plugin_dir_path( dirname(__FILE__ ) ) . 'sql/wg-varnish-sql.php';

		$sql = new WG_Varnish_SQL();

		$sql->create_plugin_tables();

		if(apply_filters( 'wg_varnish_db_logs', true)){
			wp_schedule_event( time(), 'daily', 'wg_varnish_db_daily_cleanup' );
		}

		add_option( 'wg_varnish_version', WG_VARNISH_VERSION );

	}

}
