<?php

/**
 * Return flat array from a multi-dimensional array
 * @param array $array - multi-dimensional array
 * @return object $result - flat array
 */
function array_flatten($array) { 
  if (!is_array($array)) { 
    return false; 
  } 
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } else { 
      $result[$key] = $value; 
    } 
  } 
  return $result; 
}
