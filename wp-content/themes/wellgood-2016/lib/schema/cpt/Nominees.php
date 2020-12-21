<?php

namespace WG\Schema\Cpt;

use WG\Schema\Cpt\Custom_Post_Type;

class Nominees extends Custom_Post_Type { 

  protected $args = array(
		'label' => 'Nominees',
    'singular_name' => 'Nominee',
    'public' => false,
    'publicly_queryable' => false,
    'exclude_from_search' => true,
    'menu_icon' => 'dashicons-awards',
    'show_ui' => true,
    'query_var' => true,
    'menu_position' => 10,
    'rewrite'	=> array( 'slug' => 'nominees', 'with_front' => false ),
    'has_archive' => false,
    'capability_type' => 'post',
    'hierarchical' => false,
    'supports' => array(
    	'title',
      'thumbnail',
    )
  );

}
