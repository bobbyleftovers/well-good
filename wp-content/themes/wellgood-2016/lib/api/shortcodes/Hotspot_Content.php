<?php

/**
 * Add shortcode for [hotspot-content]. Shortcode is pre-populated as an ACF field with
 * funcion pre_populate_shortcode()
 * @param array $atts - Attributes being passed on the shortcode
 */

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Hotspot_Content extends Custom_Shortcode {

  protected $shortcode = 'hotspot-content';

  function __construct(){
    add_filter('acf/prepare_field/name=hotspot_content', array($this,'pre_populate_shortcode'));
    parent::__construct();
  }

  function shortcode( $atts ) {
    $atts = shortcode_atts(
      array(
        'id' => 'Please supply a row number for the content of this hotspot',
        'post' => 'Please supply the post ID for the proper ImageLinks post object.'
      ),
      $atts,
      'hotspot-content'
    );
  
    $repeater = get_field('hotspot_content', $atts['post']);
  
    if (isset($atts['post']) && isset($atts['id'])){
      return get_module('imagelinks', $repeater[$atts['id']]);
    }
  }

  /**
   * Filter a specific field name to pre-populate it with a shortcode.
   * @param array $field An array representing the field and all of its data
   * @return array $field The updated $field
   */
  function pre_populate_shortcode( $field ) {
    global $post;

    if( is_array($field['value']) ) {
      foreach ($field['value'] as $key=>$value){
        $field['value'][$key]['field_5a73be8c885e8'] = "[hotspot-content id=\"$key\" post=\"$post->ID\"]";
      }
    }

    return $field;
  }
  
}