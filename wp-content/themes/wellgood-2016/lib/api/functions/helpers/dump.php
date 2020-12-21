<?php

/**
*
*  Nice var_dump
*
*/
if ( ! function_exists('dump')) {
  function dump($var, $die = true){
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    if($die)wp_die();
  }
}
