<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Ad_Cat extends Custom_Taxonomy { 

	protected $taxonomy = 'ad_cat';

	protected $post_types = array('post');

	protected $labels = array(
		'name' => 'Ad Cats',
		'singular_name' => 'Ad Cat',
		'search_items' =>   'Search Ad Cats',
		'all_items' => 'All Ad Cats',
		'parent_item' => 'Parent Ad Cat',
		'parent_item_colon' => 'Parent Ad Cat:',
		'edit_item' => 'Edit Ad Cat',
		'update_item' => 'Update Ad Cat',
		'add_new_item' => 'Add New Ad Cat',
		'new_item_name' => 'New Ad Cat',
		'menu_name' => 'Ad Cats',
		'back_to_items' => '← Back to Ad Cats'
	);

  protected $args = array(
		'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => false,
    'show_in_rest' => true,
    'query_var' => true,
    // @WORK
    // 'capabilities' => array(
    //   'manage_terms' => 'manage_ad_cats',
    //   'edit_terms' => 'edit_ad_cats',
    //   'delete_terms' => 'delete_ad_cats',
    //   'assign_terms' => 'assign_ad_cats'
    // )
	);
}
