<?php

function get_featured_image_caption() {
  $thumbnail_id = get_post_thumbnail_id();
  return get_post_field( 'post_excerpt', $thumbnail_id );
}