<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WG_Varnish
 * @subpackage WG_Varnish/includes/admin
 * @author     Barrel
 */
class WG_Varnish_Admin_Custom_Purge extends WG_Varnish_Admin {

	/**
	 * Add admin page menu
	 *
	 * @since    1.0.1
	 */
	function admin_page_submenu(){

		add_submenu_page( $this->plugin_name, 'Purge', 'Purge', 'manage_options', $this->plugin_name, array($this, 'admin_page_dom') );
	}

	/**
	 * Add admin page DOM
	 *
	 * @since    1.0.1
	 */
	function admin_page_dom(){

		include_once( 'views/admin-js-vars.php' );
		include_once( 'views/admin-custom-purge.php' );
		
	}
}
