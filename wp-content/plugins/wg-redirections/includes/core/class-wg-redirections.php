<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load the core trait
 */
require_once dirname( __FILE__ ) . '/trait-wg-redirections-core.php';

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WG_Redirections
 * @subpackage WG_Redirections/includes/core
 * @author     Barrel
 */

class WG_Redirections {

	use WG_Redirections_Core;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
		if ( defined( 'WG_REDIRECT_VERSION' ) ) {
			$this->version = WG_REDIRECT_VERSION;
		} 

		$this->load_functions();
		$this->get_loader();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_ajax_hooks();
		$this->define_frontend_hooks();
		
	}

	/**
	 * Autoload all procedural functions
	 */

	public function load_functions(){
		foreach (glob(dirname(dirname(__DIR__)) . "/includes/functions/*.php") as $filename){
			include_once $filename;
		}
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WG_Redirections_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$this->loader->add_action( 'plugins_loaded', 'Core/WG_Redirections_i18n', 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		if(!is_admin()) return;

		// Traits and abstract dependencies
		$this->loader->use('Admin/WG_Redirections_Admin_Data');
		$this->loader->extend('Admin/WG_Redirections_Table');

		// Menus
		$admin_pages = $this->loader->add_action( 'admin_menu', 'Admin/WG_Redirections_Admin_Pages', 'admin_menu' );
		
		// Return if outside of plugin pages
		if(!isset($_GET['page']) || (strpos($_GET['page'] , $this->plugin_name) === false)) return;

		// Edit actions 
		$this->loader->add_action( 'admin_init', 'Admin/WG_Redirections_Admin_Pages', 'handle_form' );

		// Notices
		$this->loader->add_action( 'admin_notices', 'Admin/WG_Redirections_Admin_Notices', 'show_admin_notice' );

		// Scripts
		$this->loader->add_action( 'admin_enqueue_scripts', 'Admin/WG_Redirections_Admin_Scripts', 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to ajax requests (used in admin)
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_ajax_hooks() {

		if(!wp_doing_ajax()) return;

		// Traits and abstract dependencies
		$this->loader->use('Admin/WG_Redirections_Admin_Data');
		
		// Ajax actions
		$this->loader->add_action( 'wp_ajax_wg_redirections_new_redirection', 'Admin/WG_Redirections_Admin_Ajax', 'save_redirection_ajax' );
		$this->loader->add_action( 'wp_ajax_wg_redirections_edit_redirection', 'Admin/WG_Redirections_Admin_Ajax', 'save_redirection_ajax');
		$this->loader->add_action( 'wp_ajax_wg_redirections_maintanance_flatten_target_urls', 'Admin/WG_Redirections_Admin_Ajax', 'maintanance_flatten_target_urls');

	}


	/**
	 * Register all of the hooks related to the frontend functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_frontend_hooks(){

		// Sitemap
		$this->loader->add_action( 'generate_rewrite_rules', 'Front/WG_Redirections_Sitemap', 'generate_rewrite_rules' );
		$this->loader->add_action( 'query_vars', 'Front/WG_Redirections_Sitemap', 'query_vars' );
		$this->loader->add_action( 'template_redirect', 'Front/WG_Redirections_Sitemap', 'template_redirect' );

	}


	/**
	 * Activate plugin
	 *
	 * @since    1.0.0
	 */

	public static function activate() {

		require_once plugin_dir_path( __FILE__ ) . '/class-wg-redirections-activator.php';

		$activator = new WG_Redirections_Activator();

		$activator -> activate();

	}

	/**
	 * Deactivate plugin
	 *
	 * @since    1.0.0
	 */

	public static function deactivate() {

		require_once plugin_dir_path( __FILE__ ) . '/class-wg-redirections-deactivator.php';

		$deactivator = new WG_Redirections_Deactivator();

		$deactivator -> deactivate();

	}

	/**
	 * Execute plugin
	 *
	 * @since    1.0.0
	 */

	public static function execute(){

		$plugin = new self;

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 */

		$plugin->loader->run();
		
	}
}
