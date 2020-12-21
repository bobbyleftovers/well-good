<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Cities extends Custom_Taxonomy { 

	protected $labels = array(
		'name' => 'Cities',
		'singular_name' => 'City',
		'search_items' =>  'Search Cities',
		'all_items' => 'All Cities',
		'parent_item' => 'Parent City',
		'parent_item_colon' => 'Parent City:',
		'edit_item' => 'Edit City',
		'update_item' => 'Update City',
		'add_new_item' => 'Add New City',
		'new_item_name' => 'New City Name',
		'menu_name' => 'Cities',
		'back_to_items' => '← Back to Cities'
	);

  protected $args = array(
		'hierarchical' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_quick_edit' => false,
		'show_in_rest' => true,
		'meta_box_cb' => false,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'city' )
  );

}
