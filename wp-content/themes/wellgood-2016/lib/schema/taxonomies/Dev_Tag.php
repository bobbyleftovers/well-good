<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Dev_Tag extends Custom_Taxonomy { 

	protected $taxonomy = 'dev_tag';

	protected $post_types = array('post', 'recipe');

	protected $labels = array(
		'name' => 'Dev Tags',
		'singular_name' => 'Dev Tag',
		'search_items' =>   'Search Dev Tags',
		'all_items' => 'All Dev Tags',
		'parent_item' => 'Parent Dev Tag',
		'parent_item_colon' => 'Parent Dev Tag:',
		'edit_item' => 'Edit Dev Tags',
		'update_item' => 'Update Dev Tags',
		'add_new_item' => 'Add New Dev Tags',
		'new_item_name' => 'New Dev Tags',
		'menu_name' => 'Dev Tags',
		'back_to_items' => '← Back to Dev Tags'
	);

  protected $args = array(
		'hierarchical' => false,
    'show_ui' => true,
    'show_admin_column' => false,
    'show_in_quick_edit' => true,
    'show_in_rest' => false,
		'query_var' => true,
		// @WORK
    // 'capabilities' => array(
    //   'manage_terms' => 'manage_dev_tags',
    //   'edit_terms' => 'edit_dev_tags',
    //   'delete_terms' => 'delete_dev_tags',
    //   'assign_terms' => 'assign_dev_tags'
    // )
	);
}
