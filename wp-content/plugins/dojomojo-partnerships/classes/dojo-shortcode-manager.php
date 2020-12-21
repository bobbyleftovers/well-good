<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class DojoShortcodeManager {

  function __construct() {
    add_shortcode('dojomojo_giveaway', [$this, 'dojomojo_giveaway_shortcode']);
  } 

  function extract_url_param($desired, $parameters) {
    $temp = array();
    preg_match('/'. $desired . '=([^&#]*)/', $parameters, $temp);
    return (explode('=', $temp[0], 2)[1]);
  }
  
  function dojomojo_giveaway_shortcode( $atts ) {
    $a = shortcode_atts( array(
      'width' => '100%',
      'height' => '500px',
      'border' => '1px solid grey',
      'tracking' => null
    ), $atts ); 

    if ($a['tracking']) {
      $current_url_params = array();
      preg_match('/\?(.*)/', $a['tracking'], $current_url_params);

      $campaign_id = $this->extract_url_param('campaign_id', $current_url_params[0]);

      return '<iframe src="http://giveaways.dojomojo.ninja/landing/campaign/' . $campaign_id . $current_url_params[0] . '" width="' . $a["width"] . '" height="' . $a["height"] . '" style="border: ' . $a["border"] . '"></iframe>';
    } else {
      return '<p><span style="color: red;">ERROR:</span> Please specify a tracking link.</p>';
    }
  }

}

?>