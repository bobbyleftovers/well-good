<?php

$gutenberg = isset($gutenberg) ? $gutenberg : false;

if(!$gutenberg && get_field('show_related_content', 'option') !== false) return;

if(!$gutenberg){
  $url = get_parsely_key_url();
  $secret = 'w5ztterVB03LGZJLfXS0hf3EvQBuFFIWew9hmVQxthU';
  $apikey = 'wellandgood.com';
  $limit = 2;
  $title = 'Related Stories';
  $post_date = new DateTime(get_the_date());
  $post_date->modify('-6 months');
  $featured_image = null;
  try {
    $featured_image = basename(get_the_post_thumbnail_url(get_the_ID(), 'article'));
  } catch (\Throwable $th) {}
}
 
if (!is_amp_endpoint()): 
  include 'related-content.desktop.php';
else: 
  include 'related-content.amp.php';
endif;

?>
