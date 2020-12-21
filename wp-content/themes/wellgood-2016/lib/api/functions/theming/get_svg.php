<?php

/**
 * Get SVG from svg directory in the Assets directory
 * @param string $name - The filename of the svg to retrieve
 * @return markup The contents of the svg file being requested
 */
function get_svg($name, $args = array()) {
  $dir  = TEMPLATEPATH.'/assets/img/';
  $path = $dir.$name.'.svg';

  if ( $name && file_exists($path) ){
    $svg = file_get_contents($path);

    if (!empty($args)) :
      $dom = new DOMDocument();
      @$dom->loadHTML($svg);
      $tag = $dom->getElementsByTagName('svg')[0];
      foreach($args as $key => $label) :
        $tag->setAttribute($key, $label);
      endforeach;

      $svg = $dom->saveHTML();
    endif;

    return $svg;
  }
  return '';
}