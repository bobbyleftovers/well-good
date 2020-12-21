<?php
global $post;
$campaign_collection = get_sub_field('campaign');

?>
<section class="series-spotlight post-grid module-alternate">
  <div class="container series-spotlight-container">
    <?php the_module('spotlight-header', 'Editor\'s Picks'); ?>

    <div class="post-grid__container series-spotlight__container carousel--mobile" data-module-init="flickity">
      <?php
        foreach( $campaign_collection as $campaign ):


            $campaign_name = !empty(trim($campaign['series_title'])) ? $campaign['series_title'] : $post->post_title;

            $post = $campaign['series_post'];

            if($post):
              setup_postdata( $post );

              $story_title = !empty(trim($campaign['series_title'])) ? $campaign['series_title'] : get_the_title();
              $story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
              $story_image_url = $story_image ? @$story_image[0] : '';
              $story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'article-retina' );
              $story_image_retina_url = $story_image ? @$story_image[0] : '';
              $story_excerpt = !empty(trim($campaign['description_override'])) ? $campaign['description_override'] : get_the_excerpt();
        ?>
          <article class="post-grid__card series-spotlight__card">
            <a class="no-hover-touch" href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', $post->ID) ?>"><div class="post-grid__image post-grid__image--no-touch" title="<?= esc_attr( $story_title ); ?>"><?php the_module('image', $story_image_url, $story_image_retina_url, $story_title); ?></div></a>
            <h3 class="series-spotlight__series"><a href="<?= get_term_link( $campaign['series_post'] ) ?>"><?= $story_title; ?></a></h3>
            <h4 class="post-grid__title series-spotlight__title"><a href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', $post->ID) ?>"><?= $story_excerpt; ?></a></h4>
          </article>
          <?php endif; endforeach; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
</section>
