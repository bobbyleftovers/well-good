<?php
global $post;
$series_collection = get_sub_field('series');
$series_count = count($series_collection);

?>
<section class="series-spotlight post-grid module-alternate">
  <div class="container series-spotlight-container">
    <?php the_module('spotlight-header', 'Series Spotlight'); ?>


    <div class="post-grid__container series-spotlight__container carousel--mobile series-count-<?= $series_count; ?>" data-module-init="flickity">
      <?php

        foreach( $series_collection as $series ):

          $series_id = $series['series_tag']->term_id;
          $series_name = !empty(trim($series['series_title'])) ? $series['series_title'] : $series['series_tag']->name;

          $stories = new WP_Query( array(
            'post_type'  =>  'post',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => 1,
            'tax_query' => array(
              array(
                'taxonomy' => 'post_tag',
                'field' => 'id',
                'terms' => $series_id
              )
            )
          ) );
          $post = $stories->posts[0];
          if($post):
            setup_postdata($post);
            $story_title = wp_strip_all_tags(get_the_title());
            $story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
            $story_image_url = $story_image ? @$story_image[0] : '';
            $story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
            $story_image_retina_url = $story_image ? @$story_image[0] : '';
        ?>
          <article class="post-grid__card series-spotlight__card">
            <a class="no-hover-touch" href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', $post->ID) ?>"><div class="post-grid__image post-grid__image--no-touch" title="<?= esc_attr( $story_title ); ?>"><?php the_module('image', $story_image_url, $story_image_retina_url, $story_title); ?></div></a>
            <h3 class="series-spotlight__series"><a href="<?= get_term_link( $series['series_tag'] ) ?>"><?= $series_name; ?></a></h3>
            <h4 class="post-grid__title series-spotlight__title"><a href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', $post->ID) ?>"><?= $story_title; ?></a></h4>
          </article>
        <?php endif; endforeach; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
</section>
