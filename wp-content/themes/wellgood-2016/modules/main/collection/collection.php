<?php
global $post;
$field_name = isset($post->collection_field) ? $post->collection_field : 'collection';
$field_id = isset($post->collection_sub_field) ? $post->collection_sub_field : null;
$collection = get_sub_field($field_name, $field_id) ? :get_field($field_name, $field_id);
$collection = $collection ? :get_field('collection', 'options');

if($collection):

  $post = get_post($collection, OBJECT);
  $sponsor_title = get_field( 'sponsor_title' );
  $sponsor_logo = get_field('sponsor_logo');
  $sponsor_logo_src = $sponsor_logo ? $sponsor_logo['sizes']['medium'] : false;

  $collection_posts = get_field('posts');
?>
<section class="collection <?php if ( $field_name != 'collection' ) echo ' collection--' . underscores_to_dashes($field_name); ?> collection--<?= $post->post_name; ?> <?php if ( count($collection_posts) == 3 ): ?>collection__three-up<?php endif; ?>">
  <div class="container collection__container">
    <div class="module-heading collection__headline">
      <span class="collection__headline-main"><?= wp_strip_all_tags(get_the_title()); ?></span>

      <?php if(get_field('sponsor_link')): ?><a class="collection__sponsor" href="<?= get_field('sponsor_link') ?>"><?php endif; ?>
        <?php if( $sponsor_title ): ?><span class="collection__sponsor-title"><?= $sponsor_title ?></span><?php endif; ?>
        <?php if($sponsor_logo_src): ?><img src="<?= $sponsor_logo_src ?>" alt="<?= esc_attr( $sponsor_title ) ?>" class="collection__sponsor-logo" /><?php endif; ?>
      <?php if(get_field('sponsor_link')): ?></a><?php endif; ?>

      <?php if(( $sponsor_title || $sponsor_logo_src) && get_field('archive_title')): ?><span class="collection-sponsor__archive-divider"></span><?php endif; ?>

      <?php if(get_field('archive_link')): ?><a class="collection__archive-link" href="<?= get_field('archive_link') ?>"><?php endif; ?>
        <?php if(get_field('archive_title')): ?><span class="collection__archive-title"><?= get_field('archive_title') ?></span><?php endif; ?>
      <?php if(get_field('archive_link')): ?></a><?php endif; ?>
    </div>
    <div class="collection__list carousel--mobile" <?php if ( count($collection_posts) == 3 ): ?>data-module-init="flickity"<?php endif; ?>>
      <?php
      $first = true;
      if($collection_posts):
        foreach( $collection_posts as $collection_post ): ?>
        <?php
          $post = $collection_post['post'];
          $story_title = $collection_post['title'];

          setup_postdata($post);

          $override = get_field( 'override_automatic_title_casing', $post->ID );
          $story_title = !empty(trim($story_title)) ? verify_title_case( $story_title, $post->post_date, $override ) : verify_title_case( wp_strip_all_tags( get_the_title(), $post->post_date, $override ) );
          $story_excerpt = get_the_excerpt();
          $story_category = get_the_category();
          $story_label = get_article_label($post->ID);
          $story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
          $story_image_url = $story_image ? @$story_image[0] : '';
          $story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
          $story_image_retina_url = $story_image ? @$story_image[0] : '';
          $is_video_story = has_tag('video', $post->ID) || has_term('video', 'backend_tag', $post->ID) ? 'has_video_tag' : '';
        ?>
          <article class="collection__card <?php if ( $first ) echo "collection__card--featured"; ?>">
            <a href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', $post->ID) ?>">
              <div class="collection__image" title="<?= esc_attr( $story_title ); ?>"><?php the_module('image', $story_image_url, $story_image_retina_url, $story_title, $is_video_story); ?></div>
              <span class="collection__card__a-text"><?=  $story_title ?></span>
            </a>
            <?php if ($story_label) : ?>
              <span class="collection__category">
                <?= $story_label; ?>
              </span>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', $post->ID) ?>">
                <p class="collection__title"><?= $story_title; ?></p>
                <?php if ( $first ): ?>
                  <p class="excerpt collection__excerpt"><?= $story_excerpt ?></p>
                <?php endif; ?>
            </a>
          </article>
      <?php $first = false; endforeach; ?>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>
