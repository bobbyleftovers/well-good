<?php

/**
 * Shortcode to load snappapp script
 * @return html str: Markup from gdpr-form module.
 */

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Snapapp extends Custom_Shortcode {

  function shortcode( $atts ) {
    $script = "<script>(function(){var s = document.createElement('script');s.type = 'text/javascript';s.async = true;var host = (document.location.protocol == 'http:') ? 'cdn.snapapp.com' : 'scdn.snapapp.com'; s.src = '//' + host + '/widget/widget.js'; s.id = 'eeload'; document.getElementsByTagName('head')[0].appendChild(s); })()</script>";
    return $script;
  }
  
}