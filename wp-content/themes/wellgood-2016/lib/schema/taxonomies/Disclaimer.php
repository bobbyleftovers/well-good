<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Disclaimer extends Custom_Taxonomy {

  protected $taxonomy = 'disclaimer';

	protected $post_types = array('post', 'recipe');

	protected $labels = array(
    'name' => 'Disclaimers', 'taxonomy general name' ,
    'singular_name' => 'Disclaimer', 'taxonomy singular name',
    'search_items' =>  'Search Disclaimers',
    'all_items' => 'All Disclaimers',
    'parent_item' => 'Parent Disclaimer',
    'parent_item_colon' => 'Parent Disclaimer:',
    'edit_item' => 'Edit Disclaimer',
    'update_item' => 'Update Disclaimer',
    'add_new_item' => 'Add New Disclaimer',
    'new_item_name' => 'New Disclaimer Name',
    'menu_name' => 'Disclaimers',
		'back_to_items' => '← Back to Disclaimers'
	);

  protected $args = array(
		'hierarchical' => false,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_quick_edit' => true,
    'show_in_rest' => false,
    'query_var' => true,
    'publicly_queryable' => false,
    // @WORK
    // 'capabilities' => array(
		// 	'manage_terms' => 'manage_disclaimers',
		// 	'edit_terms' => 'edit_disclaimers',
		// 	'delete_terms' => 'delete_disclaimers',
		// 	'assign_terms' => 'assign_disclaimers'
		// )
	);

	function __construct() {
		add_action( 'save_post', array( $this, 'check_for_links_in_post_content' ), 15, 2 );
		parent::__construct();
  }
  
	function check_for_links_in_post_content( $post_id, $post ) {
		$is_post = $post->post_type === 'post';
		$is_branded = has_tag( 'branded', $post_id ) || has_term( 'branded', 'backend_tag', $post_id );
		if ( $is_branded || ! $is_post ) :
			return;
		endif;

		$post_content_disclaimer_search = 'commerce';

		$search_for = array();
		if ( have_rows( 'commerce_urls', 'options' ) ) :
			while ( have_rows( 'commerce_urls', 'options' ) ) :
				the_row();
				$search_for[] = get_sub_field( 'url', 'options' );
			endwhile;
		else :
			$search_for[] = 'amazon.com';
			$search_for[] = 'z-na.amazon-adsystem.com';
		endif;

		$commerce_disclaimer = false;
		if ( ! empty( $post->post_content ) ) :
			foreach ( $search_for as $string ) :
				if ( strpos( $post->post_content, $string ) !== false ) :
					$commerce_disclaimer = true;
				endif;
			endforeach;
		endif;

		if ( $commerce_disclaimer === true ) :
			wp_set_object_terms( $post_id, $post_content_disclaimer_search, 'disclaimer', true );
		endif;
	}
}
