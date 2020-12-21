<?php

namespace WG\Settings;

class Menus {

	private $menus = array(
		'header' => 'Header',
		'footer' => 'Footer',
		'cities' => 'Cities',
		'drawer' => 'Drawer',
		'all' => 'All Topics'
	);

  function __construct() {
		add_action( 'after_setup_theme', array($this, 'register'));
		add_filter( 'nav_menu_link_attributes', array($this,'add_atts_to_menu'), 10, 3 );
		add_filter('wp_nav_menu_objects', array($this,'nav_menu_icons'), 10, 2);
	}
	
	function nav_menu_icons( $items, $args ) {

		// loop
		foreach( $items as &$item ) {
	
			// vars
			$icon = get_field('icon', $item);
	
	
			// append icon
			if( $icon ) {
				$icon_src = $icon['url'];
				$item->title = "<img src='$icon_src' class='menu-item__icon'>" .  $item->title;
	
			}
	
		}
	
	
		// return
		return $items;
	
	}

	function register(){

		foreach($this->menus as $slug => &$label) {
			$label = __($label);
		}

		register_nav_menus(
			$this->menus
		);
	}


/**
 * Add attributes to nav menu items
 * @link https://developer.wordpress.org/reference/hooks/nav_menu_link_attributes/
 */
function add_atts_to_menu( $atts, $item, $args ) {
	$theme_location = $args->theme_location;
	// add attributes by theme location
	if($theme_location == 'header') {
  	$atts['data-vars-event'] = 'header nav';
  }
  if($theme_location == 'drawer') {
  	$atts['data-vars-event'] = 'hamburger nav';
  }
	// add atttributes by class name if theme location is not set
  if( empty($theme_location) ) {
	  $menu_class = $args->menu_class;
	  if ( strpos($menu_class, 'drawer-submenu') ) {
		  $atts['data-vars-event'] = 'sub nav';
	  }
  }
  return $atts;
}

}