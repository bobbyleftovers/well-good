<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WG_Redirections
 * @subpackage WG_Redirections/includes/core
 * @author     Barrel
 */

class WG_Redirections_Activator {

	use WG_Redirections_Core;

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {

		$this->create_plugin_tables();

	}

	/**
	 * Create plugin tables
	 *
	 * @since    1.0.0
	 * @author   Barrel
	 */
	public function create_plugin_tables() {

		$this->create_table('',"
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			source_uri varchar(999) NOT NULL,
			target_uri varchar(999) NOT NULL DEFAULT '/',
			http_response mediumint(3) NOT NULL DEFAULT 301,
			type tinytext,
			counter mediumint(9) NOT NULL DEFAULT 0,
			last_visit datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			edit_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			options LONGTEXT DEFAULT '',
			user_id mediumint(9),
			is_active tinyint NOT NULL DEFAULT 1,
			add_to_sitemap tinyint DEFAULT 0,
			PRIMARY KEY  (id)
		");

		flush_rewrite_rules();

	}

}
