<?php

// Add Shortcode for [tip] - used on recipes

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Tip extends Custom_Shortcode {

  function shortcode( $atts ) {
    global $post;
    $atts = shortcode_atts( array(
        'text' => 'Please add text for the tip',
    ), $atts );
    $content = esc_attr($atts['text']);
    return get_module('recipe-tip', $content);
  }
  
}