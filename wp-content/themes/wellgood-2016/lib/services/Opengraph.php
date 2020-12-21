<?php

namespace WG\Services;

class Opengraph {

  function __construct() {
    add_filter( 'wpseo_add_opengraph_images', array( $this, 'wag_add_default_opengraph' ), 10,  2);
    add_filter( 'wpseo_og_article_section', array( $this, 'action_wpseo_admin_opengraph_section' ) );
  }

  /**
   * Setup a featured image to all single post for og:image tag
   * @param $object
   */
  function wag_add_default_opengraph( $object ) {
    if ( is_single() ) :
      global $post;

      $hero_data = get_post_meta( $post->ID, '_post_hero_data', TRUE ) ?: array();
      $template_name = array_key_exists( 'template_name', $hero_data ) && $hero_data['template_name'] && $hero_data['template_name'] !== 'standard' ? $hero_data['template_name'] : 'legacy';

      if ( $template_name === 'video' ) :
        $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $video_data = array_key_exists( 'video', $hero_data ) ? $hero_data['video'] : array();
        $video_thumb = array_key_exists( 'thumbnail', $video_data ) ? $video_data['thumbnail'] : '';
        $object->add_image( $video_thumb ); // URL
      elseif ( has_post_thumbnail() ) :
        $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $object->add_image( $featured_image[0] ); // URL
      endif;
    endif;
  }

  /**
   * Fix `article:section` meta key to hero tag instead of primary category
   * 
   * FUTURE NOTE: In the future we want to set up the hero tag to be the 
   * primary category, once this is in place, we can remove this hook
   */
  function action_wpseo_admin_opengraph_section( $content ) {
    if ( is_single() ) :
      global $post;
      
      $hero_tag = get_field( 'hero_tag', $post->ID );
      if(!$hero_tag) return;
      $hero_tag_object = get_term( $hero_tag, 'category' );

      return $hero_tag_object->name;
    endif;
  }
}