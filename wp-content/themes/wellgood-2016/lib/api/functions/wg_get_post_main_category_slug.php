<?php

function wg_get_post_main_category_slug($post_id){
  return \WG\Schema\Taxonomies\Category::get_post_main_category_slug($post_id);
}
