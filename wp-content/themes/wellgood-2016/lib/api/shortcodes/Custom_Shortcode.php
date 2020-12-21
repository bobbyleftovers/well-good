<?php

namespace WG\API\Shortcodes;

abstract class Custom_Shortcode {

  protected $shortcode = null;

  protected $function = 'shortcode';

  function __construct(){
    $this->init();
  }

  function init(){
    $names = $this->get_shortcode_names();
    foreach ( $names as $name ) {
      add_shortcode( $name, array($this, $this->function) );
    }
  }

  function get_shortcode_names(){
    if($this->shortcode !== null) return array($this->shortcode);
    $class = explode('\\',get_class($this));
    $slugged = strtolower(end($class));
    $dashed = str_replace('_', '-',  $slugged);

    if ($slugged === $dashed) {
      $this->shortcode = $slugged;
      return array($slugged);
    }

    $this->shortcode = $dashed;
    return array(
      $slugged,
      $this->shortcode
    );
  }
}
