<?php 
// Add Shortcode for [form]

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Form extends Custom_Shortcode {
  function shortcode( $atts ) {
    global $post;
    $atts = shortcode_atts( array(
      'id' => 'Please add ID'
    ), $atts );
    
    return get_module('signup-form', array(
      'form_id' => trim($atts['id']),
      'inline' => true
    ));
  }  
}
