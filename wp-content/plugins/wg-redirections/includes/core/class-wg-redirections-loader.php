<?php

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    WG_Redirections
 * @subpackage WG_Redirections/includes/core
 * @author     Barrel
 */

class WG_Redirections_Loader {

	use WG_Redirections_Core;

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * The array of instances
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected $instances;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct($plugin_name, $version) {

		global $WG_Redirections_Loader;

		if(isset($WG_Redirections_Loader)) return;

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->actions = array();
		$this->filters = array();
		$this->instances = array();

		$WG_Redirections_Loader = $this;
	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {

		//autoload namespaced
		if(is_string($component)) $component = $this->load($component);

		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {

		//autoload namespaced
		if(is_string($component)) $component = $this->load($component);
		
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Get class instance from string
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress hook that is being registered.
	 */
	public function load($component, $type = 'class'){

		if(is_bool($type)){
			$contruct = $type;
			$type = 'class';
		} else if($type === 'class'){
			$construct = true;
		} else {
			$construct = false;
		}

		if (!isset($this->instances[$component])){
			
			$component_path = explode('/',$component);
			$classname = end($component_path);

			require_once plugin_dir_path( dirname(dirname( __FILE__ )) ) . 'includes/'. str_replace('_','-',strtolower( str_replace($classname, $type.'-'.$classname, $component) )) . '.php';

			if($construct === true){
				$reflectionClass = new ReflectionClass($classname);
				if(!$reflectionClass->isAbstract()){
					$this->instances[$component] = new $classname($this->get_plugin_name(), $this->get_version());
				} else {
					$this->instances[$component] = $reflectionClass;
				}
				$this->instances[$component]->loader = $this;
				$this->instances[$component]->plugin_name = $this->get_plugin_name();
				$this->instances[$component]->version = $this->get_version();
				$this->instances[$component]->plugin_dir = $this->get_plugin_dir();
			} else {
				$this->instances[$component] = 'no-construct';
			}
		}

		return $this->instances[$component];
	}


	/**
	 * preload traits
	 *
	 * @since    1.0.0
	 */
	public function use($component){

		$this->load($component, 'trait');

	}

	/**
	 * preload abstract classes
	 *
	 * @since    1.0.0
	 */
	public function extend($component){

		$this->load($component, 'abstract');

	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

	}

}
