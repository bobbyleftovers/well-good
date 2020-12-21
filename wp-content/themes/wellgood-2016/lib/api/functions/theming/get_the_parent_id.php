<?php

/**
 * Function to return the ID of a parent page based on if we're on that page or one of
 * it's children.
 * @param int ID - Optionally passed post object ID to use for evaulation
 */
function get_the_parent_id($id = false) {
  $child_pages = array();
  if (!$id) {
    $id = get_the_ID();
  }
  $children = get_children($id);

  foreach ($children as $child) {
    if ($child->post_type == 'page') {
      array_push($child_pages, $child);
    }
  }
  return !empty($child_pages) ? $id : wp_get_post_parent_id($id);
}