<?php

/**
 * Custom shortcodes on the post
 */

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class WG extends Custom_Shortcode {

  protected $wg_shortcodes = null; 

  function fetch_shortcodes(){
    if($this->wg_shortcodes !== null) return;
    $this->wg_shortcodes = get_field('wg-shortcodes');
  }

  function shortcode( $atts ) {
    $this->fetch_shortcodes();
    $id = $atts['id'] - 1;
    if(!isset($this->wg_shortcodes[$id])) return false;
    $data = $this->wg_shortcodes[$id];
    return '<div class="theme-main-2020">'.brrl_get_module('wg-'.$data['acf_fc_layout'],$data).'</div>';
  }
  
}
