<?php

function wg_sitemap_page_links(){
  global $post;

  if ( $post->post_parent ) {

    \WG\Settings\Sitemap::the_page_links();

  } else {

    \WG\Settings\Sitemap::the_subpages_links();

    if(function_exists('wg_redirections_sitemap_index')) wg_redirections_sitemap_index();

   }
}
