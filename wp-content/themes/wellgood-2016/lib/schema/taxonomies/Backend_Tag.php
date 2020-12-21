<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Backend_Tag extends Custom_Taxonomy { 

	protected $taxonomy = 'backend_tag';

	protected $post_types = array('post', 'recipe');

	protected $labels = array(
		'name' => 'Backend Tags',
		'singular_name' => 'Backend Tag',
		'search_items' =>   'Search Backend Tags',
		'all_items' => 'All Backend Tags',
		'parent_item' => 'Parent Backend Tag',
		'parent_item_colon' => 'Parent Backend Tag:',
		'edit_item' => 'Edit Backend Tag',
		'update_item' => 'Update Backend Tag',
		'add_new_item' => 'Add New Backend Tag',
		'new_item_name' => 'New Backend Tag Name',
		'menu_name' => 'Backend Tags',
		'back_to_items' => '← Back to Backend Tags'
	);

  protected $args = array(
		'hierarchical' => false,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_quick_edit' => true,
    'show_in_rest' => false,
		'query_var' => true,
		// @WORK
    // 'capabilities' => array(
		// 	'manage_terms' => 'manage_backend_tags',
		// 	'edit_terms' => 'edit_backend_tags',
		// 	'delete_terms' => 'delete_backend_tags',
		// 	'assign_terms' => 'assign_backend_tags'
		// )
  );

}
