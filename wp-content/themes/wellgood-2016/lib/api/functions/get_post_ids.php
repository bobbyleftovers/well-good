<?php

/*
 * Get post IDs from array of posts.
 * Checks whether param is object or array
 * and parses
 * @param object $posts - posts
 * @return array $post_ids - array of post ids
 */
function get_post_ids($posts) {
  $post_ids = array();
  if (is_object($posts)) :
    array_push($post_ids, $posts->ID);
  else :
    foreach ($posts as $post) :
      if (is_object($post)) :
        array_push($post_ids, $post->ID);
      else :
        array_push($post_ids, $post);
      endif;
    endforeach;
  endif;
}
