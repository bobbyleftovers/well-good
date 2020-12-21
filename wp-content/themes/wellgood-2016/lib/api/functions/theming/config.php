<?php


function get_config($name, $dir = false, $lang = 'php'){
  if(!$dir) $dir = "/config/$name.$lang";
  return include( get_template_directory() . $dir );
}

function set_class_config_vars($class, $configNamespaces = array(), $dir = false, $lang = 'php'){
  foreach($configNamespaces as $name){
    $configs = get_config($name, $dir = false, $lang);
    if(!is_assoc($configs)){
      $class->{$name} = $configs;
    } else {
      foreach($configs as $varname => $config){
        $class->{$varname} = $config;
      }
    }
  }
}