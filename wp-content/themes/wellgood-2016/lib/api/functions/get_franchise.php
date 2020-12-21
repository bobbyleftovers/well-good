<?php

function get_franchise( $post_id ) {
  $franchise = array();
  $taxonomy = 'category';
  $categories = get_the_category($post_id);

  $franchises = array_map( function( $cat ) {
    return $cat->term_id;
  }, array_values(
    array_filter( (array) $categories, function( $cat ) {
      $is_franchise = get_field( 'editorialtag_franchise', $cat ) == true;
      return $is_franchise;
    }))
  );

  $franchise_id = count( $franchises ) ? $franchises[0] : NULL;

  if ( $franchise_id ) :
    $franchise['id'] = $franchise_id;
    $franchise['crest'] = get_field( 'editorialtag_franchise_logo', "{$taxonomy}_{$franchise_id}" );
  endif;

  return $franchise;
}