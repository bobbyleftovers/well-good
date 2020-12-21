<?php

// Add Shortcode for [shoplinks]

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Shoplinks extends Custom_Shortcode {

  function shortcode( $atts ) {
    global $post;
    $atts = shortcode_atts(
      array(
        'id' => 1,
      ),
      $atts,
      'shoplinks'
    );
  
    // Return only if has ID attribute
    if (isset($atts['id'])) {
  
      $shop_features = array();
      $order = $atts['id'] - 1;
      $prefix = 'shop_features_'.$order.'_';

      if(!$post || !is_object($post)) {
        return '';
      }

      $headline = get_post_meta($post->ID, $prefix.'headline', true);
      $display_format = get_post_meta($post->ID, $prefix.'display_format', true);
  
      if ( !$headline ) {
        return '';
      }
  
      $shop_features['headline'] = $headline;
      $shop_features['display_format'] = $display_format;
      $shop_features['shop_links'] = array();
  
      $post_type = get_post_type( $post );
  
      $index = 0;
      if ($headline) {
        while ($index < get_post_meta($post->ID, $prefix.'shop_links', true)) {
          $image_id = get_post_meta($post->ID, $prefix.'shop_links_'.$index.'_image', true);
          $image = wp_get_attachment_image_src( $image_id, 'photolay' );
          $image_url = $image ? @$image[0] : '';
          $image_width = $image ? @$image[1] : '';
          $image_height = $image ? @$image[2] : '';
          $shop_features['shop_links'][] = array(
            'url' => get_post_meta($post->ID, $prefix.'shop_links_'.$index.'_url', true),
            'button_text' => get_post_meta($post->ID, $prefix.'shop_links_'.$index.'_button_text', true),
            'image' => $image_url,
            'image_width' => $image_width,
            'image_height' => $image_height,
            'title' => get_post_meta($post->ID, $prefix.'shop_links_'.$index.'_title', true),
            'hover_text' => get_post_meta($post->ID, $prefix.'shop_links_'.$index.'_hover_text', true),
            'hover_text_bg_color' => get_post_meta($post->ID, $prefix.'shop_links_'.$index.'_hover_text_background_color', true),
            'price' => get_post_meta($post->ID, $prefix.'shop_links_'.$index.'_price', true),
            'button_color' => get_post_meta($post->ID, $prefix.'shop_links_'.$index.'_button_color', true),
            'button_text_color' => get_post_meta($post->ID, $prefix.'shop_links_'.$index.'_button_text_color', true)
          );
          $index++;
        }
      }
  
      return get_module( 'shop-features', $shop_features, $post_type );
    }
  }
  
}