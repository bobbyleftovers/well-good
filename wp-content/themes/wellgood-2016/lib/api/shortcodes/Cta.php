<?php

// Add Shortcode for [cta] - call to action button

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Cta extends Custom_Shortcode {

  function shortcode( $atts ) {
    global $post;
    $atts = shortcode_atts( array(
      'href' => 'Please add link',
      'background-color' => 'Please add background color hex',
      'text-color' => 'Please add text color hex',
      'text' => 'Please add button text',
      'new-tab' => 'false',
      'align' => 'Align left or center'
    ), $atts );
    
    return get_module('cta-button', array(
      'href' => trim($atts['href']),
      'color' => trim($atts['text-color']),
      'background' => trim($atts['background-color']),
      'text' => trim($atts['text']),
      'newtab' => trim($atts['new-tab']),
      'align' => trim($atts['align'])
    ));
  }
  
}