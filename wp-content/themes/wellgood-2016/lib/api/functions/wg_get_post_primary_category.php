<?php

function wg_get_post_primary_category($post_id, $term='category', $return_all_categories=false){
  return \WG\Schema\Taxonomies\Category::get_post_primary_category($post_id, $term, $return_all_categories);
}
