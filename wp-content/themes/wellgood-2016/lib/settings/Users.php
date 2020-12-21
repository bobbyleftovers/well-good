<?php

namespace WG\Settings;

class Users {

	// array with the roles config

	private $roles = array(
		// @WORK on hold until we can more clearly plan out the delegation of capabilities
		// 'wpseo_editor' => false,
		// 'wpseo_manager' => false,
		// 'contributor' => array(	
		// 	'cannot' => array(
		// 		'manage_categories',
		// 		'edit_categories',
		// 		'delete_categories'
		// 	)
		// ),
		// 'editor' => array(	
		// 	'cannot' => array(
		// 		'manage_categories',
		// 		'edit_categories',
		// 		'delete_categories'
		// 	)
		// ),
		// 'author' => array(	
		// 	'cannot' => array(
		// 		'manage_categories',
		// 		'edit_categories',
		// 		'delete_categories'
		// 	)
		// ),
		// 'developer_administrator' => array(
		// 	'name' => 'Developer Administrator',
		// 	'extends' => 'administrator',
		// 	'can' => array(
		// 		'field_admin',
		// 		'manage_categories',
		// 		'edit_categories',
		// 		'delete_categories',
		// 		'assign_categories',
		// 		'manage_ad_cats',
		// 		'edit_ad_cats',
		// 		'delete_ad_cats',
		// 		'assign_ad_cats',
		// 		'manage_ad_tags',
		// 		'edit_ad_tags',
		// 		'delete_ad_tags',
		// 		'assign_ad_tags',
		// 		'manage_backend_tags',
		// 		'edit_backend_tags',
		// 		'delete_backend_tags',
		// 		'assign_backend_tags',
		// 		'manage_disclaimers',
		// 		'edit_disclaimers',
		// 		'delete_disclaimers',
		// 		'assign_disclaimers',
		// 		'manage_post_tags',
    //     'edit_post_tags',
    //     'delete_post_tags',
		// 		'assign_post_tags',
		// 		'manage_dev_tags',
		// 		'edit_dev_tags',
		// 		'delete_dev_tags',
		// 		'assign_dev_tags'
		// 	)
		// ),
		// 'product_administrator' => array(
		// 	'name' => 'Product Administrator',
		// 	'extends' => 'administrator',
		// 	'can' => array(
		// 		'manage_categories',
		// 		'edit_categories',
		// 		'delete_categories',
		// 		'assign_categories',
		// 		'manage_ad_cats',
		// 		'edit_ad_cats',
		// 		'delete_ad_cats',
		// 		'assign_ad_cats',
		// 		'manage_ad_tags',
		// 		'edit_ad_tags',
		// 		'delete_ad_tags',
		// 		'assign_ad_tags',
		// 		'manage_backend_tags',
		// 		'edit_backend_tags',
		// 		'delete_backend_tags',
		// 		'assign_backend_tags',
		// 		'manage_disclaimers',
		// 		'edit_disclaimers',
		// 		'delete_disclaimers',
		// 		'assign_disclaimers',
		// 		'manage_post_tags',
    //     'edit_post_tags',
    //     'delete_post_tags',
		// 		'assign_post_tags',
		// 		'manage_dev_tags',
		// 		'edit_dev_tags',
		// 		'delete_dev_tags',
		// 		'assign_dev_tags'
		// 	)
		// ),
		// 'editorial_administrator' => array(
		// 	'name' => 'Editorial Administrator',
		// 	'extends' => 'administrator',
		// 	'can' => array(
		// 		'manage_categories',
		// 		'edit_categories',
		// 		'assign_categories',
		// 		'manage_backend_tags',
		// 		'edit_backend_tags',
		// 		'delete_backend_tags',
		// 		'assign_backend_tags',
		// 		'manage_disclaimers',
		// 		'edit_disclaimers',
		// 		'delete_disclaimers',
		// 		'assign_disclaimers',
		// 	)
		// ),
		// 'advertisement_administrator' => array(
		// 	'name' => 'Advertisement Administrator',
		// 	'extends' => 'administrator',
		// 	'can' => array(
		// 		'manage_categories',
		// 		'edit_categories',
		// 		'delete_categories',
		// 		'assign_categories',
		// 		'manage_ad_cats',
		// 		'edit_ad_cats',
		// 		'delete_ad_cats',
		// 		'assign_ad_cats',
		// 		'manage_ad_tags',
		// 		'edit_ad_tags',
		// 		'delete_ad_tags',
		// 		'assign_ad_tags',
		// 		'manage_backend_tags',
		// 		'edit_backend_tags',
		// 		'delete_backend_tags',
		// 		'assign_backend_tags',
		// 		'manage_disclaimers',
		// 		'edit_disclaimers',
		// 		'delete_disclaimers',
		// 		'assign_disclaimers',
		// 		'manage_post_tags',
    //     'edit_post_tags',
    //     'delete_post_tags',
		// 		'assign_post_tags',
		// 		'manage_dev_tags',
		// 		'edit_dev_tags',
		// 		'delete_dev_tags',
		// 		'assign_dev_tags'
		// 	)
		// )
	);

	//constructor with hooks

  function __construct() {
		add_action('profile_update', array($this, 'build_roles'));
    add_filter('user_contactmethods',array($this, 'remove_unused_fields'),10,1);
  }

	// build roles
	function build_roles(){

		if(!isset($this->roles)) return;

		/** get existing roles */

		$current_roles = wp_roles()->roles;


		/** loop all custom roles */
		
		foreach($this->roles as $name => $role){

			/** remove unwanted roles */
			
			if(!$role) {

				if(isset($current_roles[$name])) remove_role($name);

				continue;

			}
			

			/** add unexisting roles */

			if (!isset($current_roles[$name])){
				if(isset($role['name'])){
					$display_name = $role['name'];
				} else {
					$display_name = $name;
				}
				add_role($name, $display_name);
			}


			/** map/extends roles capabilities to other roles */

			if(isset($role['extends']) && isset($current_roles[$role['extends']])){
				$this->add_capabilities($name, $current_roles[$role['extends']]['capabilities'], true);
			}

			if(isset($role['can'])){
				$this->add_capabilities($name, $role['can']);
			}

			if(isset($role['cannot'])){
				$this->remove_capabilities($name, $role['cannot']);
			}
		}

	}

	// add capabilities to a role

	function add_capabilities($name, $caps, $use_key = false){
		$role = get_role($name);
		foreach ( $caps as $key => $cap ) {
			if($use_key){
				if($cap) $role->add_cap( $key );
				else $role->remove_cap( $key );
			} else {
				$role->add_cap( $cap );
			}	
		}
	}

	// remove capabilities from a rol

	function remove_capabilities($name, $caps){
		$role = get_role($name);
		foreach ( $caps as $cap ) {
			$role->remove_cap( $cap );
		}
	}


	// Remove unused user profile fields
	
	function remove_unused_fields( $contactmethods ) {
			unset($contactmethods['aim']);
			unset($contactmethods['jabber']);
			unset($contactmethods['yim']);
			unset($contactmethods['googleplus']);
			unset($contactmethods['url']);
			unset($contactmethods['pre_user_url']);
			return $contactmethods;
		}

}