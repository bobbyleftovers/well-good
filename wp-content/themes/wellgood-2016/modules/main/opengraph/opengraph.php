<?php
  $post_id = is_page_template('templates/page-summer.php') ? get_the_parent_id() : get_the_ID();
  $og_tags = array(
    'title' => get_the_title(),
    'url' => get_the_permalink(),
    'site_name' => get_bloginfo('name'),
    'description' => get_the_excerpt(),
    'image' => get_the_post_thumbnail_url($post_id) ? get_the_post_thumbnail_url($post_id) : wag_get_fallback_image('url')
  );

  foreach ($og_tags as $key=>$tag) {
    echo "<meta property=\"og:$key\" content=\"$tag\">";
  }
