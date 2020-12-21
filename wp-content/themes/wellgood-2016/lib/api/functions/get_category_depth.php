<?php

/**
 * Check what level a category is on.
 * @param object $category - category object
 * @param integer $depth - depth level as integer
 */
function get_category_depth( $category ) {
  // Start at base 0
  $depth = 0;

  // Check if parent exists
  $parent = $category->parent ?? 0; 
  if ( $parent > 0 ) :
    // Parent exists, depth will be 1
    $depth++; 

    // Check if grandparent exists
    $grandparent = get_term( $parent, 'category' )->parent ?? 0; 

    // If either grandparent exists, depth will be 2
    if ( $grandparent > 0 ) :
      $depth++;
    endif;
  endif;
  
  return $depth;
}
