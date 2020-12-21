<?php 

namespace WG\Plugins;

if( ! defined( 'ABSPATH' ) ) exit;

class Acf_Location_BlockPageTemplate extends \ACF_Location {

    public function initialize() {
      $this->name = 'block_page_template';
      $this->label = __( "Page Template For Block", 'acf' );
      $this->category = 'forms';
      $this->object_type = 'block';
    }

    public function get_values( $rule ) {
        $choices = array();
        $templates = get_page_templates();
        if($templates ) {
            foreach( $templates as $template_name => $template_filename ) {
                $choices[ $template_filename ] = $template_name;
            }
        }
        return $choices;
    }

    public function match( $rule, $screen, $field_group ) {

        if( !$_POST['post_id']) return false;

        // Compare 
        $result = ( get_page_template_slug($_POST['post_id']) == $rule['value'] );

        // Return result taking into account the operator type.
        if( $rule['operator'] == '!=' ) {
            return !$result;
        }
        return $result;
    }
}