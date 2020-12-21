<?php

/**
 * Check if a given post id is of a post type contained in infinite scroll
 * This is primarily used to check if we're on a single() post, because `is_single()` won't
 * work on an infinite scroll post. If we're in any of the given post types, it's safe to assume
 * we're on a single post.
 * @param int $post_id - The post id to check against
 * @return bool False if post id is not of a defined post type
 */
function wag_post_has_infinite($post_id) {
  $post_types = array(
    'post',
    'recipe'
  );
  $post_type = get_post_type($post_id);

  return in_array($post_type,$post_types);
}