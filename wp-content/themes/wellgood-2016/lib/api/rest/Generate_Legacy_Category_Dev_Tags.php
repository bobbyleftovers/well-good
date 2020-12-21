<?php

namespace WG\API\REST;

use WG\API\REST\REST_Controller;
use WG\Schema\Taxonomies\Category;

class Generate_Legacy_Category_Dev_Tags extends REST_Controller {

  protected $routes = array(
    array(
      'route' => '/check-missing-legacy-category-dev-tag-count',
      'callback' => 'check_missing_legacy_category_dev_tag_count',
      'methods' => 'GET'
    ),
    array(
      'route' => '/generate-legacy-category-dev-tag',
      'callback' => 'get_legacy_category_to_create_dev_tag',
      'methods' => 'GET'
    ),
    array(
      'route' => '/generate-legacy-category-dev-tag',
      'callback' => 'generate_legacy_category_dev_tag',
      'methods' => 'POST'
    )
  );

  static $unprotected_tag_transfers = array(
    'video',
    'news',
    'branded'
  );

  static $target_legacy_category = 0;

  /**
   * Generate legacy category dev_tag
   *
   * @param [object] $request
   * @return object $dev_tags
   */
  function generate_legacy_category_dev_tag( $request ) {
    $post_id = isset( $request['post'] ) ? intval( $request['post'] ) : NULL;
    $dev_tag = isset( $request['dev_tag'] ) ? get_term_by( 'slug', $request['dev_tag'], 'dev_tag' ) : NULL;

    $transfer_is_allowed = in_array( str_replace('legacy-category-', '', $dev_tag->slug), Category::$legacy_categories );
    if ( ! $transfer_is_allowed || ! $post_id || ! $dev_tag ) :
      return FALSE;
    endif;

    $dev_tags = wp_set_post_terms( $post_id, array( $dev_tag->term_id ), 'dev_tag', true );

    return $dev_tags;
  }

  /**
   * Check count of posts that have a set legacy 
   * category but not the corresponding dev tag
   *
   * @param [array] $request
   * @return int count of posts
   */
  function check_missing_legacy_category_dev_tag_count( $request ) {
    $legacy_category = isset( $request['legacyCategory'] ) ? $request['legacyCategory'] : array();

    self::set_class_legacy_category( $legacy_category );

    $posts = self::get_posts_for_legacy_category_dev_tag_generation();
    return count( $posts );

  }

  static function set_class_legacy_category( $legacy_category ) {
    $legacy_category_object = get_term_by( 'slug', $legacy_category, 'category' ) ? get_term_by( 'slug', $legacy_category, 'category' ) : get_term_by( 'name', $legacy_category, 'category' );

    self::$target_legacy_category = $legacy_category_object ? $legacy_category_object->term_id : 0;
  }

  /**
   * Get all posts with a set legacy category but
   * no legacy category dev_tag
   * 
   * @param [array] $request
   * @return array $formatted_results
   */
  function get_legacy_category_to_create_dev_tag( $request ) {
    $legacy_category = isset( $request['legacyCategory'] ) ? $request['legacyCategory'] : array();

    self::set_class_legacy_category( $legacy_category );

    $posts = self::get_posts_for_legacy_category_dev_tag_generation();
    $formatted_results = array_map( array( $this, 'map_post_data' ), (array) $posts );

    return $formatted_results;
  }

  /**
   * Build post query for posts with a legacy
   * category but no matching legacy cagegory
   * dev_tag
   *
   * @return array post array
   */
  static function get_posts_for_legacy_category_dev_tag_generation() {
    if ( self::$target_legacy_category === 0 ) :
      return FALSE;
    endif;

    $args = array(
      'post_status' => 'any',
      'posts_per_page' => -1,
      'tax_query' => array(
        array(
          'taxonomy' => 'category',
          'terms' => self::$target_legacy_category,
          'field' => 'id',
          'operator' => 'IN',
          'include_children' => false,
        ),
        array(
          'taxonomy' => 'dev_tag',
          'terms' => array_map( function( $legacy_category ) {
            return 'legacy-category-' . $legacy_category;
          }, Category::$legacy_categories ),
          'field' => 'slug',
          'operator' => 'NOT IN',
          'include_children' => false,
        )
      )
    );

    return get_posts( $args );
  }

  /**
   * Map data returned as JSON
   */
  static function map_post_data( $post ) {
    $post_data = array();

    $post_id = $post->ID;
    $legacy_category = Category::get_legacy_category( $post_id );
    
    $post_data['id'] = $post_id;
    $post_data['legacy_category'] = $legacy_category->slug;

    return $post_data;
  }
}