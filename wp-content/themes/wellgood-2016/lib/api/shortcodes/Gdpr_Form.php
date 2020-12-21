<?php

/**
 * Shortcode to print markup for GDPR form.
 * @return array Markup from gdpr-form module.
 */

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Gdpr_Form extends Custom_Shortcode {

  function shortcode( $atts ) {
    return get_module('gdpr-form');
  }
  
}
