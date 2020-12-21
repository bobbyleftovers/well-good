<?php
/**
 * Get Infinite Preset
 * 
 * When infinite scroll loads a post to display below the
 * current Article, it first checks if there is a manually
 * set Article that will appear first on infinite scroll.
 * This gives W+G the ability to promote Articles by placing
 * it at the beginning of infinite scroll
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 10.0.0
 */

function get_infinite_preset( $vertical, $post_id ) {
  $is_branded = article_is_branded( $post_id );
  $verticals = get_field( 'infinite_scroll_preset_posts', 'options' );
  if ( ! $vertical || ! $verticals || $is_branded ) :
    return;
  endif;
  
  $preset_post = NULL;
  $preset_post_array = array_map(
    function( $preset_post ) {
      return $preset_post['infinite_scroll_post']->ID;
    }, array_values(
      array_filter(
        (array) $verticals, 
        function( $preset_post ) use( $vertical ) {
          $vertical_match = array_key_exists( 'infinite_scroll_vertical', $preset_post ) ? $preset_post['infinite_scroll_vertical'] == $vertical : FALSE;
          $has_preset_post = array_key_exists( 'infinite_scroll_post', $preset_post ) ? ! empty( $preset_post['infinite_scroll_post'] ) : FALSE;

          return $vertical_match && $has_preset_post;
        }
      )
    )
  );
  
  if ( count( $preset_post_array ) > 0 ) :
    $preset_post = get_post( $preset_post_array[0] );

    if ( $preset_post->ID === $post_id ) :
      $preset_post = NULL;
    endif;
  endif;

  return $preset_post;
}