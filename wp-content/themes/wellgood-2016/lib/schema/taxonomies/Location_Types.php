<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Location_Types extends Custom_Taxonomy { 

	protected $labels = array(
		'name' => 'Location Types',
		'singular_name' => 'Location Type',
		'search_items' =>   'Search Location Types',
		'all_items' => 'All Location Types',
		'parent_item' => 'Parent Location Type',
		'parent_item_colon' => 'Parent Location Type:',
		'edit_item' => 'Edit Location Type',
		'update_item' => 'Update Location Type',
		'add_new_item' => 'Add New Location Type',
		'new_item_name' => 'New Location Type Name',
		'menu_name' => 'Location Types',
		'back_to_items' => '← Back to Location Types'
	);

  protected $args = array(
		'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_quick_edit' => false,
    'show_in_rest' => true,
    'meta_box_cb' => false,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'city'),
  );

}
