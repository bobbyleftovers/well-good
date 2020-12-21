<?php

  /**
   * Helper function to retrieve pattern matches in filters
   * @param $pattern - regex pattern e.g. '/video_id="(.*?)"/'
   * @param $content - where to find search for the match
   * @return $matches[1] - the group result from preg_match_all
   *
  */

  function get_pattern_match($pattern, $content) {
    $shortcodes_found = preg_match_all($pattern, $content, $matches);
    return $matches[1];
  }