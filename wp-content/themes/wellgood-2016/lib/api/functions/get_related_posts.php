<?php

function get_related_posts( $post_id, $limit = 12, $trim = 6, $custom_args = null) {
  if ( empty( $post_id ) ) {
      global $post;
      $post_id = $post->ID;
  }
  $params = array( 'fields' => 'ids' );
  $cats = wp_get_post_categories( $post_id, $params );
  $tags = wp_get_post_tags( $post_id, $params );

  $args = array(
      'posts_per_page'           => $limit,
      'post_type'                => 'post',
      'post__not_in'             => array( $post_id ),
      'orderby'                  => 'date',
      'order'                    => 'DESC',
      'tax_query'                => array(
          'relation'             => 'OR',
          array(
              'taxonomy'         => 'category',
              'field'            => 'term_id',
              'terms'            => $cats,
              'include_children' => false
          ),
          array(
              'taxonomy'         => 'post_tag',
              'field'            => 'term_id',
              'terms'            => $tags,
          )
      )
  );

  if($custom_args !== null) {
    $posts = get_posts($custom_args);
  } else {
    $posts = get_posts($args);
  }

  shuffle( $posts );
  $posts = array_slice($posts, 0, $trim);

  return $posts;
}
