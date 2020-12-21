<?php

function flatten_array(array $array) {
  $flattened_array = array();
  array_walk_recursive($array, function($a) use (&$flattened_array) { $flattened_array[] = $a; });
  return $flattened_array;
}
