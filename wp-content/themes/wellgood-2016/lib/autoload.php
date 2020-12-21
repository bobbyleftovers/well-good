<?php

/**
 *
 * Load Vendor
 *
 */

include_once( __DIR__ . '/vendor/autoload.php' );


/**
 *
 * Autoload all procedural functions
 *
 */
foreach (glob(__DIR__ . "/api/functions/**/*.php") as $filename){
  include_once $filename;
}

foreach (glob(__DIR__ . "/api/functions/*.php") as $filename){
  include_once $filename;
}

/**
 *
 * WG Spl autoload
 *
 */

spl_autoload_register(function($class) {
  $namespace = 'WG\\';
  $length = strlen($namespace);
  $base_directory = __DIR__.'/';
  if(strncmp($namespace, $class, $length) !== 0 )  return;
  $dir = str_replace('-','_',str_replace('\\', '/',  substr($class, $length)));
  $path = explode('/',$dir);
  if(sizeof($path)>1){
    $realpath = array();
    foreach ($path as $i => $chunk) {
      if($i == sizeof($path)-1){
        $realpath[] = $chunk;
      } else {
        $realpath[] = strtolower($chunk);
      }
    }
    $dir = implode('/',$realpath);
  }
  $file = $base_directory . $dir . '.php';
  if(file_exists($file)) {
    require_once $file;
  }
});
