<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *  Load traits
 */
require_once dirname( __FILE__ ) . '/trait-wg-redirections-sql.php';
require_once dirname( __FILE__ ) . '/trait-wg-redirections-scripts-server.php';

/**
 * The core functionality of the plugin extended in many classes.
 *
 * Defines the plugin name, version,..
 *
 * @package    WG_Redirections
 * @subpackage WG_Redirections/includes/core
 * @author     Barrel
 */
trait WG_Redirections_Core {

	use WG_Redirections_SQL, WG_Redirections_Scripts_Server;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	public $plugin_name = 'wg-redirections';

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	public $version = '1.0.0';

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WG_Redirections_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	public $loader;

	/**
	 * Plugin directory
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_dir
	 */
	public $plugin_dir;

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function get_loader() {

		if ($this->loader) return $this->loader;
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once $this->get_plugin_dir() . 'includes/core/class-wg-redirections-loader.php';

		/**
		 * Loader instance
		 */
		$this->loader = new WG_Redirections_Loader($this->get_plugin_name(), $this->get_version());

		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	/**
	 * Retrieve plugin dir
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_plugin_dir(){

		if($this->plugin_dir) return $this->plugin_dir;

		$this->plugin_dir = plugin_dir_path( dirname(dirname( __FILE__ )) );

		return $this->plugin_dir;
	}


	/**
	 * Get formatted path
	 *
	 * @since    1.0.0
	 */

	static function get_formatted_path($url){

		if(!empty($url)){
			if(is_string($url)) {
				$orig_url = parse_url($url);
				if(isset($orig_url['scheme'])){
					$url = $orig_url;
				} else {
					if(substr( $url, 0, 4 ) === 'www.'){
						$url = '//'.$url;
					} else {
						$url = str_replace('//','/', $url);
					}
					$url = parse_url($url);
				}
			}
			
			$path = isset($url["path"]) ? $url["path"] : "";

			$path = trim($path, '/');

			$path = preg_replace('/\/amp$/', '', $path);

		} else {
			$path = '';
		}

		return '/'.$path;

	}

	/**
     * Get default data
     */

    public function get_default_row(){
        return array(
            'id'         => null,
            'source_uri' => '',
            'target_uri' => '',
            'http_response' => 301,
            'is_active' => true,
			'type' => null,
			'add_to_sitemap' => 0,
            'options' => array(
				'skip_amp_on_redirect' => 0,
				'remove_query_on_redirect' => 0
            )
          );
        
	}
	
	/**
	 * Build target url
	 *
	 * @since    1.0.0
	 */

	function build_target($redirection, $source = null) {

		if($redirection['type'] === 'external') {
			if(substr( $redirection['target_uri'], 0, 4 ) === 'www.'){
				$redirection['target_uri'] = '//'.$redirection['target_uri'];
			}
			$url = parse_url($redirection['target_uri']);
		} else {
			if(!$source) $source = $this->source;
			$url = $source;
			$url['path'] = str_replace($source['formatted_path'], $redirection['target_uri'], $source['path']);
		}

		$scheme = isset($url["scheme"]) ? $url["scheme"] . ":" : "";
		$user = isset($url["user"]) ? rawurlencode($url["user"]) : "";
		$pass = isset($url["pass"]) ? ":" . rawurlencode($url["pass"]) : "";
		$at = strlen($user.$pass) ? "@" : "";
		$host = isset($url["host"]) ? rawurlencode($url["host"]) : "";
		$double_slash = strlen($at.$host) ? "//" : "";
		$port = isset($url["port"]) ? ":" . $url["port"] : "";
		$path = isset($url["path"]) ? $url["path"] : "";
		$query = isset($url["query"]) ? "?" . $url["query"] : "";
		$fragment = isset($url["fragment"]) ? "#" . $url["fragment"] : "";

		return $scheme.$double_slash.$user.$pass.$at.$host.$port.$path.$query.$fragment;

	  }
}
