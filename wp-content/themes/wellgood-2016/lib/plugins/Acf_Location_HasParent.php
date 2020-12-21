<?php 

namespace WG\Plugins;

if( ! defined( 'ABSPATH' ) ) exit;

class Acf_Location_HasParent extends \ACF_Location {

  public function initialize() {
    $this->name = 'has_parent';
    $this->label = __( "Is Child Post", 'acf' );
    $this->category = 'post';
    $this->object_type = 'post';
  }

  public static function get_operators( $rule ) {
    return array(
        '==' => __( "?" )
    );
  }

  public function get_values( $rule ) {
      return array(
        1 => 'Yes',
        0 => 'No',
      );
  }

  public function match( $rule, $screen, $field_group ) {

        global $post;
        if( !$post) return false;

        // Has parent?
        $has_parent = $post->post_parent;

        // Return result taking into account the operator type.
        if( $has_parent && $rule['value'] || !$has_parent && !$rule['value']) {
          return true;
        } 
        return false;
    }
}