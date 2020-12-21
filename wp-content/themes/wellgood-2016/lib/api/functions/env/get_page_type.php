<?php

/**
 * Get page type
 *
 * @param object $query - wp_query in pre_get_posts
 */
function get_page_type( $id = null, $args = array() ) {
  global $post;

  $conventions = array(
    'standard' => array(
      'home' => 'home',
      'page' => 'page',
      'category' => 'category',
      'article' => 'article',
      'unknown' => 'unknown',
    ),
    'advertisement' => array(
      'home' => 'homepage',
      'page' => 'page',
      'category' => 'category',
      'article' => 'article',
      'unknown' => 'notfound',
    )
  );
  
  $convention = array_key_exists('convention', $args) && $args['convention'] && array_key_exists( $args['convention'], $conventions ) ? $args['convention'] : 'standard';
  $return_data = array_key_exists('return_data', $args) && $args['return_data'] === true ? true : false;

  if (!$id && $post) :
    $id = $post->ID;
  endif;

  
  if ( is_home( $id ) || is_front_page( $id ) ) :
    $obj = get_post( $id );
    $pagetype = $conventions[$convention]['home'];
    $content_name = $conventions[$convention]['home'];
  elseif ( is_page( $id ) || is_page_template( $id ) ) :
    $obj = get_post( $id );
    $pagetype = $conventions[$convention]['page'];
    $content_name = get_the_title( $id );
  elseif ( is_tag( $id ) || is_category( $id ) ) :
    $obj = get_queried_object( $id );
    $pagetype = $conventions[$convention]['category'];
    $content_name = $obj->name;
  elseif ( is_single( $id ) || wag_post_is_ajax( $id ) ) :
    $obj = get_post( $id );
    $pagetype = $conventions[$convention]['article'];
    $content_name = get_the_title( $id );
  else :
    $obj = NULL;
    $pagetype = $conventions[$convention]['unknown'];
    $content_name = $conventions[$convention]['unknown'];
  endif;

  if ( $return_data ) :
    return array(
      'object' => $obj,
      'pagetype' => $pagetype,
      'content_name' => $content_name
    );
  else :
    return $pagetype;
  endif;
}