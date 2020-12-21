<?php

function get_module_slug($name){
  if (strpos($name, '/') !== false) {
    $name = explode('/',$name);
    $name = $name[1];
  } 

  return $name;

}