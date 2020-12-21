<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WG_Redirections
 * @subpackage WG_Redirections/includes/core
 * @author     Barrel
 */

class WG_Redirections_i18n {

	use WG_Redirections_Core;

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->plugin_name,
			false,
			$this->plugin_dir . '/languages/'
		);

	}

}
