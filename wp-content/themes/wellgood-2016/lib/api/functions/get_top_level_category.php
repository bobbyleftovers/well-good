<?php
/**
 * Get Top Level Category
 * 
 * This file controls the injection of Advertisements
 * and other media within `the_content` of a post
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 10.0.1
 */

 
/**
 * @param integer $category
 * @return object $top_level_category
 */
function get_top_level_category( $category ) {
  $category_object  = get_term( $category, 'category' );
  $depth            = get_category_depth( $category_object );

  switch ($depth) :
    case 0 :
      $top_level_category = $category_object->term_id;
      break;

    case 1 :
      $parent_object = get_term( $category_object->parent, 'category' );
      $top_level_category = $parent_object->term_id;
      break;

    case 2 :
      $parent_object = get_term( $category_object->parent, 'category' );
      $grandparent_object = get_term( $parent_object->parent, 'category' );
      $top_level_category = $grandparent_object->term_id;
      break;
    
    default :
      $top_level_category = $category;
      
  endswitch;

  return $top_level_category;
}
