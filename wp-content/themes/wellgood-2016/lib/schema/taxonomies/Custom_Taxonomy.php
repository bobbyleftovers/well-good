<?php

namespace WG\Schema\Taxonomies;

/**
 * Taxonomy Abstract Class
 */

abstract class Custom_Taxonomy { 

  protected $taxonomy = null;
  protected $labels = array();
  protected $post_types = array();
  protected $defaultArgs = array(
    'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_quick_edit' => false,
    'show_in_rest' => true,
    'query_var' => true
  );

  function __construct(){
    add_action('init', array($this, 'register_taxonomy'));
  }

  function register_taxonomy(){
    register_taxonomy($this->get_taxonomy_name(),  $this->post_types, $this->get_args());
  }

  function get_args(){
    $this->defaultArgs['rewrite'] = array( 'slug' => $this->get_taxonomy_name() );
    foreach($this->labels as $key => &$label){
      switch($key){
        case 'name';
          $label = _x( $label, 'taxonomy general name' );
          break;
        case 'singular_name':
          $label = _x( $label, 'taxonomy singular name' );
          break;
        default: 
         $label = __($label);
      };
    }
    $args = array_merge($this->defaultArgs, $this->args);
    $args['labels'] = $this->labels;
    return $args;
  }

  function get_taxonomy_name(){
    if($this->taxonomy !== null) return $this->taxonomy;
    $class = explode('\\',get_class($this));
    $this->taxonomy = str_replace('_', '-',  strtolower(end($class)));
    return $this->taxonomy;
  }


}