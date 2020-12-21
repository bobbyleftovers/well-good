<?php

namespace WG\Schema\Cpt;

use WG\Schema\Cpt\Custom_Post_Type;

class Mailing_List extends Custom_Post_Type { 

	protected $type = 'mailing_list';
	
  protected $args = array(
      'label' => 'Mailing List',
      'singular_name' => 'Mailing List',
			'public' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
      'menu_icon' => 'dashicons-email',
			'menu_position' => 8,
			'rewrite'	=> array( 'slug' => 'mailing-list', 'with_front' => false ),
			'has_archive' => false,
			'supports' => array(
				'title',
				'custom-fields'
			)
  );


}
