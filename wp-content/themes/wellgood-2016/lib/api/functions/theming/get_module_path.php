<?php

function get_module_path($name, $file = false){
  if (strpos($name, '/') !== false) {
    $name = explode('/',$name);
    $build = $name[0];
    $name = $name[1];
  } else {
    $build = 'main';
  }

  $mod = explode('.',$name)[0];

  if(!$file){
    $file = $name.'.php';
  }
  
  return "/modules/$build/$mod/".$file;
}