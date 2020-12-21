<?php

namespace WG\Schema\Cpt;

use WG\Schema\Cpt\Custom_Post_Type;

class Collections extends Custom_Post_Type { 

  protected $args = array(
		'label' => 'Collections',
		'singular_name' => 'Collection',
		'public' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
				'menu_icon' => 'dashicons-exerpt-view',
		'show_ui' => true,
		'query_var' => true,
		'menu_position' => 8,
		'rewrite'	=> array( 'slug' => 'collection', 'with_front' => false ),
		'has_archive' => false,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array(
			'title'
			)
  );

}
