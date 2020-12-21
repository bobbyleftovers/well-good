<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.1
 * @package    WG_Varnish
 * @subpackage WG_Varnish/includes
 * @author     Barrel
 */

class WG_Varnish_Init {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      WG_Varnish_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Plugin directory
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      string    $plugin_dir
	 */
	protected $plugin_dir;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.1
	 */
	public function __construct() {

		//date_default_timezone_set('America/New_York');

		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = WG_VARNISH_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wg-varnish';
		$this->plugin_dir = plugin_dir_path( dirname(dirname( __FILE__ )) );

		$this->start_loader();
		$this->set_locale();
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_purge_hooks();
		$this->add_rest_endpoints();
		$this->cron_jobs();
	}

	/**
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function start_loader() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once $this->plugin_dir . 'includes/core/wg-varnish-loader.php';

		/**
		 * Loader instance
		 */
		$this->loader = new WG_Varnish_Loader($this->get_plugin_name(), $this->get_version());

	}

	/**
	 * Autoload mandatory global dependencies
	 *
	 * @since    1.0.1
	 */

	public function load_dependencies() {
		
		// Procedural functions
		foreach (glob(__DIR__ . "/helpers/*.php") as $filename){
			include_once $filename;
		}

		// Classes
		$this->loader->load('SQL/WG_Varnish_SQL');
	}

	/**
	 * Register all of the hooks related to the automated purge endpoints
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function define_purge_hooks() {

		/**
		* Abstarct class
		*/
		$this->loader->load('Purge/WG_Varnish_Purge');

		/**
		* Purge posts
		*/
		$this->loader->add_action( 'post_updated', 'Purge/WG_Varnish_Purge_Post', 'purge_on_update', 10, 3 );
		$this->loader->add_action( 'wg_varnish_secondary_purge_post', 'Purge/WG_Varnish_Purge_Post', 'secondary_purge' );
		$this->loader->add_action( 'publish_future_post', 'Purge/WG_Varnish_Purge_Post', 'secondary_purge' );

		/**
		* Purge Terms
		*/

	}

	/**
	 * Add REST Endpoints
	 *
	 * @since    1.0.2
	 * @access   private
	 */
	private function add_rest_endpoints() {

		/**
		* Abstarct class
		*/
		$this->loader->load('REST/WG_Varnish_REST');

		/**
		* Headless purge endpoints
		*/
		$this->loader->add_action( 'rest_api_init', 'REST/WG_Varnish_REST_Purge', 'register_routes' );
		$this->loader->add_filter( 'wg_varnish_expose_to_js', 'REST/WG_Varnish_REST_Purge', 'expose_endpoints_to_js' );
		
	}

	/**
	 * Add async hooks
	 * Deprecated on 1.0.2: replaced by cronjobs
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function add_async_hooks() {

		/**
		* Abstarct class
		*/
		$this->loader->load('Async/WG_Varnish_Async');

		/**
		* Load async hooks classes
		*/
		$this->loader->load('Async/WG_Varnish_Async_Save_Post');

		/**
		* Init hooks
		*/
		new WG_Varnish_Async_Save_Post();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WG_Varnish_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function set_locale() {

		$this->loader->add_action( 'plugins_loaded', 'Core/WG_Varnish_i18n', 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		// Preload main class
		$this->loader->load('Admin/WG_Varnish_Admin');

		// Admin scripts
		$this->loader->add_action( 'admin_enqueue_scripts', 'Admin/WG_Varnish_Admin_Scripts', 'enqueue_scripts' );

		// Admin notices
		$this->loader->add_action( 'admin_notices', 'Admin/WG_Varnish_Admin_Notices', 'purge_notices' );

		//Pages
		$this->loader->add_action( 'admin_menu', 'Admin/WG_Varnish_Admin', 'admin_page_menu' );
		$this->loader->add_action( 'admin_menu', 'Admin/WG_Varnish_Admin_Custom_Purge', 'admin_page_submenu' );
		$this->loader->add_action( 'admin_menu', 'Admin/WG_Varnish_Admin_Logs', 'admin_page_submenu' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.1
	 * @return    WG_Varnish_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Cron jobs
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private function cron_jobs() {

		/**
		* Clean Logs DB
		*/
		$this->loader->add_action( 'wg_varnish_db_daily_cleanup', 'SQL/WG_Varnish_SQL_Logs', 'daily_cleanup' );
		
	}

}
