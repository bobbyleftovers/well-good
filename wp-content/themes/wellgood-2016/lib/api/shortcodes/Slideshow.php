<?php

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Slideshow extends Custom_Shortcode {

  function __construct(){
    add_action('add_meta_boxes', array($this,'slideshow_add_meta_box'));
    parent::__construct();
  }

  function shortcode( $atts ) {
    return get_module('slideshow', $atts['id']);
  }

  // Add metabox to slideshow post admin, showing the shortcode to copy
  function slideshow_meta_box_markup() {
    global $post;
    echo "<p>Paste the following into a post editor to display this slideshow:</p>";
    echo "<input style=\"width: 100%;\" readonly type=\"text\" value='[slideshow id=\"$post->ID\"]'/>";
  }

  function slideshow_add_meta_box() {
    add_meta_box('demo-meta-box', 'Slideshow Shortcode', array($this,'slideshow_meta_box_markup'), 'slideshow', 'side', 'high', null);
  }
  
}