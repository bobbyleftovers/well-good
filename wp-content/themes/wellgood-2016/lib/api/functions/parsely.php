<?php

use \WG\Services\Parsely;

function get_parsely_key_url($post = false){
  if(!$post) global $post;
  return Parsely::get_parsely_key_url($post);
}
