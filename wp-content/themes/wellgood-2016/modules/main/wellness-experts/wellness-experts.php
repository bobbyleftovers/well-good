<?php
global $post;
$authors = get_sub_field('authors');
$author_count = count($authors);
?>
<section class="wellness-experts module-alternate">
  <div class="container wellness-experts-container">
      <?php the_module('spotlight-header', 'The Council'); ?>

    <div class="wellness-experts__list carousel--mobile" data-module-init="flickity">
      <?php

        $has_author_descriptions = false;
        foreach( $authors as $author ):
          $author_id = $author['user']['ID'];
          $author_description = get_field( 'short_bio', "user_{$author_id}" );
          if(!empty($author_description)):
            $has_author_descriptions = true;
          endif;
        endforeach;
        foreach( $authors as $author ): ?>
        <?php

          $author_id = $author['user']['ID'];
          $author_name = $author['user']['display_name'];
          $author_avatar = get_wp_user_avatar_src( $author_id, 'thumbnail' );//$author['user']['user_avatar'];
          $author_description = get_field( 'short_bio', "user_{$author_id}" );
          $author_link = esc_url(get_author_posts_url( $author_id ));
          $show_author_link = get_field('show_author', "user_{$author_id}");

          $stories = new WP_Query( array(
            'post_type'  =>  'post',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => 2,
            'author' => $author_id
          ) );

          $post = $stories->posts[0];
          if($post):
            setup_postdata($post);
            $story_title = wp_strip_all_tags(get_the_title());
            $story_excerpt = get_the_excerpt();
            $story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
            $story_image_retina = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
            $story_image_url = $story_image ? @$story_image[0] : '';
            $story_image_retina_url = $story_image_retina ? @$story_image_retina[0] : '';

        ?>
        <div class="wellness-experts__card wellness-count-<?= $author_count; ?>">
          <div class="wellness-experts__inner">
            <span class="wellness-experts__author">
            <?php if($show_author_link): ?><a href="<?= $author_link; ?>" class="wellness-experts__author"><?php endif; ?>
              <?php if($author_avatar): ?><span class="avatar-wrapper wellness-experts__avatar"><?php the_module('image', $author_avatar, $author_avatar, esc_attr($author_name) ); ?></span><?php endif; ?>
              <h3 class="wellness-experts__author-name"><?= $author_name; ?></h3>
              <?php if($has_author_descriptions): ?><h6 class="wellness-experts__author-description"><?= $author_description; ?></h6><?php endif; ?>
            <?php if($show_author_link): ?></a><?php endif; ?>
            </span>
            <a class="wellness-experts__story" aria-label="<?= $story_title ?>" href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', get_the_ID()) ?>">
              <article>
                <div class="wellness-experts__story-image" title="<?= esc_attr( $story_title ); ?>"><?php the_module('image', $story_image_url, $story_image_retina_url, $story_title); ?></div>
                <h4 class="wellness-experts__story-title"><?= $story_title ?></h4>
              </article>
            </a>
            <?php
                $post = @$stories->posts[1];
                if($post):
                  setup_postdata($post);
                  $story_title = wp_strip_all_tags(get_the_title());
            ?>
            <a class="wellness-experts__story" aria-label="<?= $story_title ?>" href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', get_the_ID()) ?>">
              <article>
                <h4 class="wellness-experts__story-title"><?= $story_title ?></h4>
              </article>
            </a>
            <?php endif; ?>
            <?php if($show_author_link): ?><a href="<?= $author_link; ?>" aria-label="More stories from <?= $author_name; ?>" class="wellness-experts__author-more">More Stories</a><?php endif; ?>
          </div>
        </div>
       <?php endif; ?>
      <?php endforeach; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
</section>
