<?php

namespace WG\API\REST;

abstract class REST_Controller {

  protected $namespace = null;
  protected $version = null;

  function __construct(){
    $this->set_namespace_and_version();
    $this->fix_routes_methods();
    add_action( 'rest_api_init', array($this, 'register_routes'));
  }

  function set_namespace_and_version(){
    if($this->namespace === null) $this->namespace = REST_NAMESPACE;
    if($this->version === null) $this->version = REST_VERSION;
  }
  
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

  function register_rest_route($route, $args) {
    if ( !function_exists( 'register_rest_route' ) ) return;

    $namespace = $this->namespace;
    $version = $this->version;

    $args = array_merge(array(
      'methods' => 'POST'
    ),$args);
       
    register_rest_route("${namespace}/v${version}", $route, $args);
  }

  /*
  * Validate callback function
  */
  function is_numeric( $param, $request, $key ) {
    return is_numeric( $param );
  }

}