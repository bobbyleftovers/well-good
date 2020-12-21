<?php

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Anchor extends Custom_Shortcode {

  function shortcode( $atts ) {
    return '<a name="'.$atts['id'].'"></a>';
  }
  
}