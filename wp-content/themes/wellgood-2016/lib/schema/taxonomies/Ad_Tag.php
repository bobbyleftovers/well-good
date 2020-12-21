<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Ad_Tag extends Custom_Taxonomy { 

	protected $taxonomy = 'ad_tag';

	protected $post_types = array('post');

	protected $labels = array(
		'name' => 'Ad Tags',
		'singular_name' => 'Ad Tag',
		'search_items' =>   'Search Ad Tags',
		'all_items' => 'All Ad Tags',
		'parent_item' => 'Parent Ad Tag',
		'parent_item_colon' => 'Parent Ad Tag:',
		'edit_item' => 'Edit Ad Tag',
		'update_item' => 'Update Ad Tag',
		'add_new_item' => 'Add New Ad Tag',
		'new_item_name' => 'New Ad Tag',
		'menu_name' => 'Ad Tags',
		'back_to_items' => '← Back to Ad Tags'
);

  protected $args = array(
		'hierarchical' => false,
    'show_ui' => true,
    'show_admin_column' => false,
    'show_in_rest' => true,
		'query_var' => true,
		// @WORK
    // 'capabilities' => array(
		// 	'manage_terms' => 'manage_ad_tags',
		// 	'edit_terms' => 'edit_ad_tags',
		// 	'delete_terms' => 'delete_ad_tags',
		// 	'assign_terms' => 'assign_ad_tags'
		// )
  );

}
