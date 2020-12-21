<?php

namespace WG\API\REST;

use WG\API\REST\REST_Controller;

class Transfer_To_Backend_Tags extends REST_Controller {

  protected $routes = array(
    array(
      'route' => '/check-missing-backend-tag-count',
      'callback' => 'check_missing_backend_tag_count',
      'methods' => 'GET'
    ),
    array(
      'route' => '/transfer-to-backend-tags',
      'callback' => 'get_post_tags_to_transfer',
      'methods' => 'GET'
    ),
    array(
      'route' => '/transfer-to-backend-tags',
      'callback' => 'transfer_to_backend_tags',
      'methods' => 'POST'
    )
  );

  static $unprotected_tag_transfers = array(
    'video',
    'news',
    'branded'
  );

  static $target_legacy_tag = 0;
  static $target_backend_tag = 0;

  function transfer_to_backend_tags( $request ) {
    $post_id = isset( $request['post'] ) ? intval( $request['post'] ) : NULL;
    $transfer_tag = isset( $request['tag'] ) ? intval( $request['tag'] ) : NULL;

    $transfer_is_allowed = in_array( $transfer_tag, array_map( function( $tag ) {
      $backend_tag_object = get_term_by( 'slug', $tag, 'backend_tag' ) ?? get_term_by( 'name', $tag, 'backend_tag' );
      return $backend_tag_object->term_id;
    }, self::$unprotected_tag_transfers ) );
    if ( ! $transfer_is_allowed || ! $post_id || ! $transfer_tag ) :
      return FALSE;
    endif;

    $backend_tags = wp_set_post_terms( $post_id, array( $transfer_tag ), 'backend_tag', true );

    return $backend_tags;
  }

  /**
   * Check count of posts that have a given legacy tag
   * but not the corresponding backend tag
   *
   * @param [array] $request
   * @return int count of posts
   */
  function check_missing_backend_tag_count( $request ) {
    $tag = isset( $request['tag'] ) ? $request['tag'] : array();

    $posts = self::get_posts_for_transfer( $tag );
    return count( $posts );
  }

  static function set_class_tags( $tag ) {
    $legacy_tag_object = get_term_by( 'slug', $tag, 'post_tag' ) ?? get_term_by( 'name', $tag, 'post_tag' );
    $backend_tag_object = get_term_by( 'slug', $tag, 'backend_tag' ) ?? get_term_by( 'name', $tag, 'backend_tag' );

    if ($backend_tag_object) :
      $backend_tag_id = $backend_tag_object->term_id;
    else :
      $backend_tag_insert = wp_insert_term(
        $tag,
        'backend_tag',
        array('slug' => $tag)
      );

      $backend_tag_id = ( ! is_wp_error( $backend_tag_insert ) && isset( $backend_tag_insert['term_id'] ) ) ? $backend_tag_insert['term_id'] : 0;
    endif;

    self::$target_legacy_tag = $legacy_tag_object->term_id;
    self::$target_backend_tag = $backend_tag_id;
  }

  /**
   * Get all posts with the backend tag '301'
   */
  function get_post_tags_to_transfer( $request ) {
    $tag = isset( $request['tag'] ) ? $request['tag'] : array();

    self::set_class_tags( $tag );

    $posts = self::get_posts_for_transfer( $tag );
    $formatted_results = array_map( array( $this, 'map_post_data' ), (array) $posts );

    return $formatted_results;
  }

  /**
   * Build post query for posts with a tag in 
   * legacy_tag but not in backend_tag
   *
   * @param [string] $tag
   * @return array post array
   */
  static function get_posts_for_transfer( $tag ) {
    $args = array(
      'post_status' => 'any',
      'posts_per_page' => -1
    );

    if ( $tag ) :
      $args['tax_query'] = array(
        array(
          'taxonomy' => 'post_tag',
          'terms' => $tag,
          'field' => 'slug',
          'operator' => 'IN',
          'include_children' => false,
        ),
        array(
          'taxonomy' => 'backend_tag',
          'terms' => $tag,
          'field' => 'slug',
          'operator' => 'NOT IN',
          'include_children' => false,
        )
      );
    endif;

    return get_posts( $args );
  }

  /**
   * Map data returned as JSON
   */
  static function map_post_data( $post ) {
    $post_data = array();

    $post_id = $post->ID;
    
    $tags_array = get_the_tags( $post_id );
    $tags = $tags_array ? array_map( function( $tag ) {
      return $tag->term_id;
    }, $tags_array) : array();

    $backend_tags_array = wp_get_post_terms( $post_id, 'backend_tag' );
    $backend_tags = $backend_tags_array ? array_map( function( $tag ) {
      return $tag->term_id;
    }, $backend_tags_array ) : array();

    
    $post_data['id'] = $post_id;
    $post_data['backend_tags'] = $backend_tags;
    $post_data['tags'] = $tags;
    $post_data['target_backend_tag'] = self::$target_backend_tag;

    return $post_data;
  }
}