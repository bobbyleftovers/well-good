<?php

/**
 * Retrun an array category IDs
 * 
 * @param array/string/object $categories - One or more categories
 * @return array $array - An array of category IDs
 */
function get_category_ids($categories) {
  switch (gettype($categories)) :
    case 'array':
      $category_ids = array_map(function($category) {
        return get_cat_ID($category);
      }, $categories);
      break;

    case 'object':
      $category_ids = array(
        $categories->ID
      );
      break;

    case 'string':
      $categories = array_filter(preg_split('/[,]{1}[\\s]?/', $categories));
      $category_ids = array_map(function($category) {
        return get_cat_ID($category);
      }, $categories);
      break;

  endswitch;

  return array_filter($category_ids);
}