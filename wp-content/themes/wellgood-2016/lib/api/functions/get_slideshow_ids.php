<?php

/**
   * Get slideshow IDs from shortcode in the_content.
   * @param string $content - string of $post->post_content
   * @return array $matches[1] - access group matches
   */

function get_slideshow_ids($content){
    global $post;
    $pattern = '/\[slideshow id="(.*?)"\]/';
    $shortcodes_found = preg_match_all($pattern, $content, $matches);
    return $matches[1];
  }
