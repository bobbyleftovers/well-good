<?php

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Experts extends Custom_Taxonomy {



	protected $taxonomy = 'experts';

	protected $post_types = array( 'post', 'recipe' );

	protected $labels = array(
		'name'              => 'Experts',
		'taxonomy general name',
		'singular_name'     => 'Expert',
		'taxonomy singular name',
		'search_items'      => 'Search Experts',
		'all_items'         => 'All Experts',
		'parent_item'       => 'Parent Expert',
		'parent_item_colon' => 'Parent Expert:',
		'edit_item'         => 'Edit Expert',
		'update_item'       => 'Update Expert',
		'add_new_item'      => 'Add New Expert',
		'new_item_name'     => 'New Expert Name',
		'menu_name'         => 'Experts',
		'back_to_items'     => '← Back to Experts',
	);

	protected $args = array(
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
	// @WORK
	// 'capabilities' => array(
		// 'manage_terms' => 'manage_experts',
		// 'edit_terms' => 'edit_experts',
		// 'delete_terms' => 'delete_experts',
		// 'assign_terms' => 'assign_experts'
		// )
	);

	static $expert_dev_tag = 'experts-set';

  function __construct() {
		add_action( 'save_post', array( $this, 'check_if_experts_set' ), 14 );
		
		parent::__construct();
	}
	
	function check_if_experts_set( $post_id ) {
		$experts_set = wp_get_object_terms( $post_id, 'experts', array() );

		if ( ! empty( $experts_set ) ) :
			wp_set_object_terms( $post_id, self::$expert_dev_tag, 'dev_tag', TRUE );
		else :
			wp_remove_object_terms( $post_id, self::$expert_dev_tag, 'dev_tag' );
		endif;
	}
}
