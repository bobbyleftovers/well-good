<?php
// this module was clone from the wellness-experts module, we'll use those same classes
global $post;
$features = get_sub_field('features');
$feature_count = count( $features );
?>
<section class="wellness-experts module-alternate">
  <div class="container wellness-experts-container">
    <?php the_module('spotlight-header', 'Curated Picks'); ?>
    <div class="wellness-experts__list" data-module-init="flickity">
      <?php

        $has_author_descriptions = false;
        foreach( $features as $feature ):
          $feature_id = $feature['user']['ID'];
          $feature_description = get_field( 'short_bio', "user_{$feature_id}" );
          if(!empty($feature_description)):
            $has_author_descriptions = true;
          endif;
        endforeach;
        foreach( $features as $feature ): ?>
        <?php

          // $feature_id = $feature['user']['ID'];
          $post = $feature['featured_post'];
          $post_id = $post->ID;

          $feature_name = $feature['featured_title'];
          $feature_avatar = $feature['feature_header_image']['sizes']['medium'];

          $feature_description = (has_excerpt( $post_id ) ) ? get_the_excerpt( $post_id ) : $feature['post_description'];
          $feature_link = esc_url(get_the_permalink($post_id));
          $show_author_link = true;

          $cta_text = $feature['cta_label'];
          $cta_link = $feature['cta_link'];

          $sponsor_logo = $feature['sponsor_logo']['url'];
          $sponsor_link = $feature['sponsor_link'];
          $sponsor_text = $feature['sponsored_by_text'];

          if($post):
            setup_postdata($post);
            $story_title = ($feature['post_title_override']) ? $feature['post_title_override'] : wp_strip_all_tags(get_the_title());
            $story_excerpt = ($feature['post_description']) ? $feature['post_description'] : get_the_excerpt();
            $story_excerpt = cut_words_by_character($story_excerpt, 155);
            $story_image_override = $feature['post_image_override'];
            $story_image_attachment = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
            $story_image = $story_image_attachment ? $story_image_attachment[0] : acf_check_fallback_images( array('header','background', 'hero_desktop'), 'medium' );
            $story_image_url = ( $story_image_override ) ? $story_image_override['sizes']['medium'] : ( $story_image ? $story_image : '' );
        ?>
        <div class="wellness-experts__card wellness-count-<?= $feature_count; ?>">
          <div class="wellness-experts__inner">
            <span class="wellness-experts__author">
            <?php if($show_author_link): ?><a href="<?= $feature_link; ?>" class="wellness-experts__author"><?php endif; ?>
              <?php if($feature_avatar): ?><span class="avatar-wrapper wellness-experts__avatar"><?php the_module('image', $feature_avatar, $feature_avatar, esc_attr($feature_name) ); ?></span><?php endif; ?>
              <h3 class="wellness-experts__author-name"><?= $feature_name; ?></h3>
              <?php if($has_author_descriptions): ?><h6 class="wellness-experts__author-description"><?= $feature_description; ?></h6><?php endif; ?>
            <?php if($show_author_link): ?></a><?php endif; ?>
            </span>
            <a class="wellness-experts__story" href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', get_the_ID()) ?>">
              <article>
                <div class="wellness-experts__story-image" title="<?= esc_attr( $story_title ); ?>"><?php the_module('image', $story_image_url, $story_image_url, $story_title); ?></div>
                <h4 class="wellness-experts__story-title"><?= $story_title ?></h4>
              </article>
            </a>

            <a class="wellness-experts__story alt-spotlight__excerpt" href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', get_the_ID()) ?>">
              <article>
                <h4 class="wellness-experts__story-title"><?= $story_excerpt ?></h4>
              </article>
            </a>

            <?php if( $sponsor_logo ) : ?>
            <div class="alt-sponsor">
              <span class="collection__sponsor-title"><?= ($sponsor_text) ? $sponsor_text : 'Presented By'; ?></span>
              <?php if( $sponsor_link ) : ?><a href="<?= $sponsor_link; ?>" class="alt-sponsor__sponsor-link"><?php endif;?>
                <img src="<?= $sponsor_logo; ?>" class="alt-sponsor__sponsor-logo">
              <?php if( $sponsor_link ) : ?></a><?php endif;?>
              </div>
            <?php endif; ?>



            <?php if($post): ?><a href="<?= ($cta_link) ? $cta_link : the_permalink(); ?>" class="wellness-experts__author-more"><?= ($cta_text) ? $cta_text : 'Read More'; ?></a><?php endif; ?>
          </div>
        </div>
       <?php endif; ?>
      <?php endforeach; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
</section>
