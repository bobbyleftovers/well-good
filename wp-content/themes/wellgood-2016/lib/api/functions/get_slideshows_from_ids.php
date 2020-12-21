<?php
/**
   * Get array of slideshow post objects from array of IDs.
   * @param array $ids - array slideshow IDs
   * @return array array of slideshow post objects
   */

  function get_slideshows_from_ids($ids) {
    $args = array(
      'post_type' => 'slideshow',
      'post__in' => $ids
    );

    return get_posts($args);
  }