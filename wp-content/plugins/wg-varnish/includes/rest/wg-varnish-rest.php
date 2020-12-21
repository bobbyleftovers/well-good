<?php

/**
 * Abstract API REST Class
 *
 *
 * @since      1.0.2
 * @package    WG_Varnish
 * @subpackage WG_Varnish/rest
 * @author     Barrel
 */

abstract class WG_Varnish_REST {

  /**
	 * API namespace
	 *
	 * @since    1.0.2
	 * @access   protected
	 * @var      string
	 */

  protected $namespace = null;

   /**
	 * API version
	 *
	 * @since    1.0.2
	 * @access   protected
	 * @var      string
	 */
  protected $version = null;

   /**
	 * Constructor for all endpoints
	 *
	 * @since    1.0.2
	 * @author   Barrel
	 */
  function __construct($name, $verion){

    $this->set_namespace_anv_version($name, $verion);
    $this->fix_routes_methods();

  }

  /**
	 * Set API url root
	 *
	 * @since    1.0.2
	 * @author   Barrel
	 */
  function set_namespace_anv_version($name, $version){
    $ver = explode('.',trim($version));
    if($this->namespace === null) $this->namespace = $name;
    if($this->version === null) $this->version = $ver[0];
  }
  
  /**
	 * Automate route constructor
	 *
	 * @since    1.0.2
	 * @author   Barrel
	 */
  function fix_routes_methods(){

    foreach ($this->routes as &$route){

      if(isset($route['callback'])) $route['callback'] = array($this,$route['callback']);

      if(isset($route['args']) && is_array($route['args'])){
        foreach($route['args'] as &$arg){
          if(isset($arg['validate_callback'])) $arg['validate_callback'] = array($this,$arg['validate_callback']);
        }
      }
    }
  }

  /**
	 * Register routes (will be hooked on main file)
	 *
	 * @since    1.0.2
	 * @author   Barrel
	 */
  function register_routes() { 
    foreach ($this->routes as $route){
      $args = array(
        'callback' => $route['callback']
      );
      if(isset($route['args'])) $args['args'] = $route['args'];
      if(isset($route['methods'])) $args['methods'] = $route['methods'];
      $this->register_rest_route($route['route'],$args);
    }
  }

  /**
	 * Register route
	 *
	 * @since    1.0.2
	 * @author   Barrel
	 */
  function register_rest_route($route, $args) {
    if ( !function_exists( 'register_rest_route' ) ) return;

    $args = array_merge(array(
      'methods' => 'POST'
    ),$args);

    register_rest_route($this->get_api_root(), $route, $args);
  }

  /**
	 * Get root route
	 *
	 * @since    1.3.0
	 * @author   Barrel
	 */
  function get_api_root(){
    $namespace = $this->namespace;
    $version = $this->version;

    return "${namespace}/v${version}";
  }

  /**
	 * Validation callback is_numeric
	 *
	 * @since    1.0.2
	 * @author   Barrel
	 */
  function is_numeric( $param, $request, $key ) {
    return is_numeric( $param );
  }

  /**
	 * Expose endpoints
	 *
	 * @since    1.0.2
	 * @author   Barrel
	 */
  function expose_endpoints_to_js($js_object){

    $namespace = $this->namespace;
    $version = $this->version;

    $js_object[get_called_class()] = array();

    foreach ($this->routes as $route){
      $js_object[get_called_class()][$route['callback'][1]] = "'".home_url()."/wp-json/${namespace}/v${version}".$route['route']."'";
    }

    return $js_object;

  }

}