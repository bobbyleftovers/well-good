<?php

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Year extends Custom_Shortcode {

  function shortcode( $atts ) {

    return '<span class="year">'.date('Y').'</span>';
    
  }
  
}