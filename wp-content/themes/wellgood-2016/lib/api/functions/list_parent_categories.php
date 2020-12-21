<?php

/**
 * Retrun an array of only top level (sub-0) categories
 * 
 * @param array $categories - An array of categories
 * @return array $field - An array of only top level categories
 */
function list_parent_categories($categories) {
  $parents = array_values(array_filter($categories, function($category) {
    return $category->parent === 0;
  }));

  return $parents;
}