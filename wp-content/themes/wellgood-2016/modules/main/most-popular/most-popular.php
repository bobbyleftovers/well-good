<?php
$field_name = isset($post->most_popular_field) ? $post->most_popular_field : 'featured_stories';
$context = isset($post->most_popular_sub_field) ? $post->most_popular_sub_field : '';

$label = 'Most <strong>Popular</strong>';
$header_graphic = get_field('most_popular_image', 'options');
$header_graphic_src = $header_graphic ? $header_graphic['sizes']['medium'] : '';
$count = 1;
$most_popular = get_most_popular(5);
if(!empty($most_popular) && $most_popular):
?>
<section class="relationship-stories relationship-stories--most-popular module-alternate">
  <div class="container relationship-stories__container">
    <div class="module-heading relationship-stories__headline">
      <span class="relationship-stories__headline-label"><?= $label; ?></span>
      <?php if ( $context == 'sidebar' ): ?>

      <div class="relationship-stories__headline-background">
        <?php the_module('image', $header_graphic_src, $header_graphic_src); ?>
      </div>
      <?php endif; ?>

    </div>
    <div class="relationship-stories__list">
      <?php foreach ( $most_popular as $post ): setup_postdata( $post ); ?>
        <?php
          $post_ID = get_the_ID();
          $override = get_field( 'override_automatic_title_casing', $post->ID );
          $story_title = verify_title_case( wp_strip_all_tags( get_the_title() ), $post->post_date, $override );
          $story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), 'medium' );
          $story_image_url = $story_image ? @$story_image[0] : '';
          $story_image     = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ID ), 'article-retina' );
          $story_image_retina_url = $story_image ? @$story_image[0] : '';
          $story_categories = get_the_category();
          $story_category_name = $story_categories ? esc_html($story_categories[0]->cat_name) : false;
          $story_category_link = $story_categories ? esc_url(get_category_link( $story_categories[0]->term_id )) : false;
          $franchise = get_franchise( $post_ID );
        ?>
        <article class="relationship-stories__card">
          <a class="relationship-stories__image-wrapper" href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', $post_ID) ?>">
            <div class="relationship-stories__image" title="<?php echo esc_attr( $story_title ); ?>"><?php the_module('image', array(
              'image_src' => $story_image_url,
              'image_src_retina' => $story_image_retina_url,
              'image_alt' => $story_title,
              'franchise' => $franchise
            ) ); ?></div>
          </a>
          <a href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', $post_ID) ?>">
            <p class="relationship-stories__ranking"><?= $count; ?></p>
            <p class="relationship-stories__title"><?= $story_title; ?></p>
          </a>
        </article>
      <?php $count++; ?>
      <?php endforeach; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?>
