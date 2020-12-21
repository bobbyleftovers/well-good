<?php

function article_is_video( $post_id ) {
  $is_video = has_term( 'video', 'backend_tag', $post_id ) || has_tag( 'video', $post_id ) ? true : false;

  return $is_video;
}
