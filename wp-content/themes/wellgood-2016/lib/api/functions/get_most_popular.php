<?php

function get_most_popular( $limit = 5 ) {
  $popular_IDs = maybe_unserialize( get_option( 'popularity_index' ) );
  if(!empty($popular_IDs)):
    $query = new WP_Query( array(
        'posts_per_page'  => $limit,
        'post_type'       => 'post',
        'orderby'         => 'post__in',
        'post__in'        => $popular_IDs,
    ) );
    $posts = $query->get_posts();
    $posts = array_slice($posts, 0, $limit);
  else:
    $posts = Array();
  endif;
  return $posts;
}
