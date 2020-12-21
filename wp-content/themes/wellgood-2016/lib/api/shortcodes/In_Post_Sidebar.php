<?php

// Add Shortcode for [sidebar] - used in post_content

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class In_Post_Sidebar extends Custom_Shortcode {

  function shortcode( $atts, $content = null ) {
    global $post;
    $inpost_content = get_field('in_post_sidebar', $post);
    return get_module('in-post-sidebar', $inpost_content);
  }
  
}