<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Product_Categories extends Custom_Taxonomy { 
	
	protected $taxonomy = 'product_categories';

	protected $labels = array(
		'name' => 'Product Categories',
		'singular_name' => 'Product Category',
		'search_items' =>   'Search Product Categories',
		'all_items' => 'All Product Categories',
		'parent_item' => 'Parent Product Category',
		'parent_item_colon' => 'Parent Product Category:',
		'edit_item' => 'Edit Product Category',
		'update_item' => 'Update Product Category',
		'add_new_item' => 'Add New Product Category',
		'new_item_name' => 'New Product Category',
		'menu_name' => 'Product Categories',
		'back_to_items' => '← Back to Product Categories'
	);

  protected $args = array(
		'hierarchical' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_rest' => true,
		'query_var' => true
  );

}
