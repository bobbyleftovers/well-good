<?php

namespace WG\Plugins;

use WG\Schema\Cpt\Custom_Post_Type;

class WP_Ultimate_Recipe extends Custom_Post_Type {

  function __construct() {
    add_filter( 'wpurp_register_post_type', array($this,'filter_recipe_cpt_args'), 10, 1 );
    add_filter( 'wpurp_output_recipe', array($this,'amp_custom_template'), 10, 2 );
    add_filter( 'pre_get_posts', array($this,'add_recipe_to_main_query') );
    add_filter( 'post_type_link', array($this,'send_category_to_recipe_link'), 10, 4 );
  }

   /**
   * Add recipe to main query on tag archives
   * @link https://premium.wpmudev.org/blog/how-to-add-custom-post-types-to-your-home-page-and-feeds/
   */

  function add_recipe_to_main_query( $query ) {
    if ( is_tag() && $query->is_main_query() )
    $query->set( 'post_type', array( 'post', 'recipe') );
    return $query;
  }


  function send_category_to_recipe_link( $post_link, $post ){
    if ( $post->post_type == 'recipe' && get_option( 'permalink_structure' ) ) {
      return str_replace( '/wellgood_recipe_slug' , '' , $post_link );
    }
    return $post_link;
  }

  function amp_custom_template( $content, $recipe ){
    if (is_amp_endpoint()){
      global $post;
      return get_module('recipe-card', $post->ID, wag_get_fallback_image());
    }
  }


  /**
   * Filter the rewrite rule in recipe post type registration
   * Filter post_type_link to remove pre
   * @link https://stackoverflow.com/questions/47786110/permalink-not-working-on-custom-post-and-category
   * @link https://wordpress.stackexchange.com/questions/65075/use-register-post-type-to-modify-an-existing-post-type
   */

  function filter_recipe_cpt_args( $args ) {
    if ( in_array( 'category', $args['taxonomies'] ) ) {
      $args['rewrite']['slug'] = 'wellgood_recipe_slug';
    }
    return $args;
  }

  
}