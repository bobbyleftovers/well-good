<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Dietary extends Custom_Taxonomy { 

	protected $post_types = array('recipe');

	protected $labels = array(
		'name' => 'Dietary',
		'singular_name' => 'Dietary Type',
		'search_items' => 'Search Dietary Types',
		'all_items' => 'All Dietary Types',
		'parent_item' => 'Parent Dietary Type',
		'parent_item_colon' => 'Parent Dietary Type:',
		'edit_item' => 'Edit Dietary Type',
		'update_item' => 'Update Dietary Type',
		'add_new_item' => 'Add New Dietary Type',
		'new_item_name' => 'New Dietary Type Name',
		'menu_name' => 'Dietary Types',
		'back_to_items' => '← Back to Dietary Types'
	);

  protected $args = array(
		'hierarchical' => true,
      'show_ui' => true,
      'show_admin_column' => false,
      'show_in_rest' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'dietary')
  );

}
