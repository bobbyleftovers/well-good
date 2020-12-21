<?php

function is_video($post = false){
  if(!$post) global $post;
  if(is_object($post)) $id = $post->ID;
  else $id = $post;
  return has_term('video', 'backend_tag', $id) || has_tag('video', $id);
}
