<?php

  /**
   * Retrieve image attachment ID with it's url
   * @param string $image_url The url of the image being requested
   * @param int $parent_id The id of the parent post we're loading media on. This helps the SQL query performance
   * @return int $attachment[0] The attachment ID of the image
   */
  
  function get_image_id($image_url, $parent_id) {
    global $wpdb;
  
    $url = explode('uploads/',$image_url);
  
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type='attachment' and post_parent=$parent_id and post_status='inherit' and guid like '%s';", "%$url[1]%" ));
  
    return (!empty($attachment) ? $attachment[0] : false);
  }