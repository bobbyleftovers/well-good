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
class WG_Varnish_Admin_Logs extends WG_Varnish_Admin {

	function __construct($name, $ver){

		add_filter('set-screen-option', array($this,'set_option'), 10, 3);

		parent::__construct($name, $ver);
	}

	/**
	 * WP_List_Table object
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private $table;	
	
	/**
	 * Add admin page menu
	 *
	 * @since    1.3.0
	 */
	function admin_page_submenu(){

		if(!apply_filters( 'wg_varnish_db_logs', true)) return;

		$page_hook = add_submenu_page( $this->plugin_name, 'Logs', 'Logs', 'manage_options', $this->plugin_name.'_logs' , array($this, 'load_page') );

		/*
		* The $page_hook_suffix can be combined with the load-($page_hook) action hook
		* https://codex.wordpress.org/Plugin_API/Action_Reference/load-(page) 
		* 
		* The callback below will be called when the respective page is loaded	 	 
		*/				
		add_action( 'load-'.$page_hook, array( $this, 'load_page_options' ) );

	}

	/**
	 * Set length option
	 *
	 * @since    1.3.0
	 */
	function set_option($status, $option, $value) {
 
		if ( 'rows_per_page' == $option ) return $value;
	 
		return $status;
	 
	}

	/**
	 *  Load admin page options
	 *
	 * @since    1.3.0
	 */
	function load_page_options(){
		$arguments	=	array(
			'label'		=>	'Logs per page',
			'default'	=>	100,
			'option'	=>	'rows_per_page'
		);

		add_screen_option( 'per_page', $arguments );

		$this->loader->load('Admin/WG_Varnish_Admin_Table');

		$this->table = $this->loader->load('Admin/WG_Varnish_Admin_Logs_Table');

	}

	/**
	 * Add admin page DOM
	 *
	 * @since    1.3.0
	 */
	function load_page(){

		$this->table->prepare_items();

		include_once( 'views/admin-logs-table.php' );
	}


}