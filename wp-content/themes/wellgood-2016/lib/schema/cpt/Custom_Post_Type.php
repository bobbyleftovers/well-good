<?php

namespace WG\Schema\Cpt;

/**
 * CPT Abstract Class
 */
abstract class Custom_Post_Type { 

  protected $type = null;
  protected $taxonomies = array();
  protected $defaultArgs = array(
    'public' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => false,
    'menu_icon' => 'dashicons-settings',
    'show_ui' => true,
    'query_var' => true,
    'has_archive' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'show_in_rest' => true,
    'supports' => array(
      'title',
      'custom-fields'
    )
  );

  function __construct(){
    add_action('init', array($this, 'register_post_type'));
    $this->register_taxonomies();
  }

  function register_post_type(){
    register_post_type( $this->get_type(),$this->get_args());
  }

  function register_taxonomies(){
    foreach($this->taxonomies as $tax){
      $this->register_taxonomy($tax);
    }
  }

  function get_args(){
    return array_merge($this->defaultArgs, $this->args);
  }

  function add_exerpt(){
    add_action( 'init', function () {
      add_post_type_support( 'page', 'excerpt' );
      }, 99 );
  }

  function get_type(){
    if($this->type !== null) return $this->type;
    $class = explode('\\',get_class($this));
    $this->type = str_replace('_', '-',  strtolower(end($class)));
    return $this->type;
  }

  function register_taxonomy($tax){
    add_action('init', function() use($tax) {
      register_taxonomy_for_object_type($tax, $this->get_type());
    });
  }

}