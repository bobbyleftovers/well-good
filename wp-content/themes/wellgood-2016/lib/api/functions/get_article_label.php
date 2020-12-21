<?php

function get_article_label( $post_id ) {
  $label = '';
  $is_branded = article_is_branded( $post_id );
  // @FUTURE
  // $is_sponsored = article_is_sponsored( $post_id );

  if ( $is_branded ) :
    $label = 'Paid Content';
  else :
    $hero_tag = get_field( 'hero_tag', $post_id );
    $categories = get_the_category( $post_id );

    if ( $hero_tag || $categories ) :
      $link = $hero_tag ? get_category_link( $hero_tag ) : get_category_link( $categories[0]->term_id );
      $name = $hero_tag ? get_category( $hero_tag )->name : $categories[0]->name;
      
      $label = "<a href='$link'>$name</a>";
    endif;
  endif;

  return $label;
}