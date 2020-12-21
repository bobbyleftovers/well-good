<?php

namespace WG\Schema\Cpt;

use WG\Schema\Cpt\Custom_Post_Type;


class Hub_Locations extends Custom_Post_Type { 

	protected $taxonomies = array('hub-locations', 'location-types');

  protected $args = array(
		'label' => 'Hub Locations',
		'singular_name' => 'Hub Location',
		'public' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'menu_icon' => 'dashicons-location-alt',
		'show_ui' => true,
		'query_var' => true,
		'menu_position' => 8,
		'rewrite'   => array( 'slug' => 'hub-location', 'with_front' => false ),
		'has_archive' => false,
		'capability_type' => 'post',
		'hierarchical' => false,
		'show_in_rest' => true,
		'supports' => array(
				'title',
				'thumbnail',
		)
	);
}
