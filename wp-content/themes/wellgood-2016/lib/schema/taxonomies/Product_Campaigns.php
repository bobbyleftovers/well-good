<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Product_Campaigns extends Custom_Taxonomy { 

	protected $taxonomy = 'product_campaigns';

	protected $labels = array(
		'name' => 'Campaigns',
		'singular_name' => 'Campaign',
		'search_items' =>   'Search Campaigns',
		'all_items' => 'All Campaigns',
		'parent_item' => 'Parent Campaign',
		'parent_item_colon' => 'Parent Campaign:',
		'edit_item' => 'Edit Campaign',
		'update_item' => 'Update Campaign',
		'add_new_item' => 'Add New Campaign',
		'new_item_name' => 'New Campaign',
		'menu_name' => 'Campaigns',
		'back_to_items' => '← Back to Campaigns'
	);

  protected $args = array(
		'hierarchical' => true,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'query_var' => true
  );

}
