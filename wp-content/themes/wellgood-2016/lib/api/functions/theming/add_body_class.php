<?php

function add_body_class($class){
  add_filter( 'body_class',function( $classes ) use ($class) {
    $classes[] = $class;
    return $classes;
  } );
}
