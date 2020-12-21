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
class WG_Varnish_Admin_Scripts extends  WG_Varnish_Admin {


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name   The name of this plugin.
	 * @param      string    $version    	The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->dev_server = array(
			'protocol' => 'https',
			'host' => 'localhost',
			'port' => 8080
		);

		parent::__construct( $plugin_name, $version );
	}

	/**
	 * Get webpack dev server
	 *
	 * @since    1.0.0
	 */
	public function get_dev_server($part = false){

		if($part) return $this->dev_server[$part];

		return $this->dev_server['protocol'].'://'.$this->dev_server['host'].':'.$this->dev_server['port'].'/';

	}

	/**
	 * Register the scripts and stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if(!(isset($_GET['page']) && $_GET['page'] === $this->plugin_name)) return;

		if ( $this->is_develop_serve() ) {
			$scripts_uri =  $this->get_dev_server();
		 } else {
			$scripts_uri =   plugin_dir_url( dirname(__DIR__) ) . 'dist/';
		}

		// javascript
		wp_enqueue_script( $this->plugin_name . '_chunks', $scripts_uri  . 'js/chunk-vendors.js', [], $this->version, false );
		wp_enqueue_script( $this->plugin_name, $scripts_uri . 'js/app.js', [], $this->version, false );

		// styles
		wp_enqueue_style( $this->plugin_name, $scripts_uri . 'css/app.css', [], $this->version, 'all' );

	}

	/**
	 * Check webpack server is running.
	 *
	 * @since    1.0.1
	 */
	private function is_develop_serve(){
		$connection = @fsockopen($this->get_dev_server('host'), $this->get_dev_server('port'));

		if ( $connection ) {
			return true;
		}

		return false;
	}

}
