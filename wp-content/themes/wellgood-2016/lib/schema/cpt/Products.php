<?php

namespace WG\Schema\Cpt;

use WG\Schema\Cpt\Custom_Post_Type;

class Products extends Custom_Post_Type { 

	protected $taxonomies = array('product_categories', 'product_taxonomies');

  protected $args = array(
		'label' => 'Products',
		'singular_name' => 'Product',
		'public' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'menu_icon' => 'dashicons-products',
		'show_ui' => true,
		'query_var' => true,
		'menu_position' => 11,
		'rewrite'   => array( 'slug' => 'product', 'with_front' => false ),
		'has_archive' => false,
		'capability_type' => 'post',
		'hierarchical' => false,
		'show_in_rest' => true,
		'supports' => array(
				'title',
				'thumbnail',
				'editor'
		)
	);

}
