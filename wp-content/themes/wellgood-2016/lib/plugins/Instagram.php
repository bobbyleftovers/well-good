<?php

namespace WG\Plugins;

class Instagram {


  function __construct() {
		add_filter( 'wpiw_list_class', array($this,'instagram_plugin_class') );
		add_filter( 'wpiw_template_part', array($this,'instagram_plugin_template'));
	}

	/**
	 * Rename the <ul> class
	 * @return string class for the ul
	*/
	function instagram_plugin_class( $classes ) {
		$class = "instagram-feed-list";
		return $class;
	}

	/**
	 * Overwrite the template for each <li>
	 * @return string template path
	*/
	function instagram_plugin_template() {
		return 'modules/main/instagram-feed/instagram-post.php';
	}
}

