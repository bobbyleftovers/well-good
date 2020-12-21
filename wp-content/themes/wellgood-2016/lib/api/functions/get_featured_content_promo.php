<?php
/**
 * Get Featured Content Promo
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 14.12.2
 */

function get_featured_content_promo( $vertical, $post_id ) {
  $is_branded = article_is_branded( $post_id );
  $verticals = get_field( 'featured_content_promo_content', 'options' );
  if ( ! $vertical || ! $verticals || $is_branded ) :
    return;
  endif;
  
  $featured_content = NULL;
  $featured_content_array = array_values(
    array_filter(
      (array) $verticals, 
      function( $featured_content ) use( $vertical ) {
        $vertical_match = array_key_exists( 'vertical', $featured_content ) ? $featured_content['vertical'] == $vertical : FALSE;
        $has_preset_post = array_key_exists( 'content', $featured_content ) ? ! empty( $featured_content['content'] ) : FALSE;

        return $vertical_match && $has_preset_post;
      }
    )
  );
  if ( count( $featured_content_array ) > 0 ) :
    $featured_content = $featured_content_array[0]['content'];
  endif;

  return $featured_content;
}