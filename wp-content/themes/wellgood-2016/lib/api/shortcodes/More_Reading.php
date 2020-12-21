<?php

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class More_Reading extends Custom_Shortcode {

  protected $shortcode = 'more-reading';

  function shortcode( $atts ) {
    $post_id = get_the_ID();
    $module_index = $atts['id'];
    $post_info = array();
    if( have_rows('more_reading') ): $index=1; while ( have_rows('more_reading') ) : the_row();
      if($index == $module_index):
            if( get_row_layout() == 'external_link' ):
              $post_image = get_sub_field('image', $post_id);
                $post_image_url = $post_image ? @$post_image['sizes']['medium'] : '';
              $post_info['url'] = get_sub_field('url', $post_id);
              $post_info['title'] = get_sub_field('title', $post_id);
              $post_info['image'] = $post_image_url;
              $post_info['target'] = '_blank';
            elseif( get_row_layout() == 'post' ):
              $post_info_post = get_sub_field('post', $post_id);
              $post_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_info_post->ID ), 'medium' );
                $post_image_url = $post_image ? @$post_image[0] : '';
              $post_info['url'] = get_permalink($post_info_post);
              $post_info['title'] = $post_info_post->post_title;
              $post_info['image'] = $post_image_url;
              $post_info['target'] = get_field('_pprredirect_newwindow', $post_info_post->ID);
            endif;
          endif;
    $index++; endwhile; endif;
    return !empty($post_info) ? get_module('more-reading', $post_info) : '';
  }
  
}