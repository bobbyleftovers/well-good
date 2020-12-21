<?php
global $post;
$field_name = isset($post->relationship_stories_field) ? $post->relationship_stories_field : 'featured_stories';
$context = isset($post->relationship_stories_sub_field) ? $post->relationship_stories_sub_field : '';
$stories_data = $field_name == 'most_popular' ? get_field_object($field_name, 'options') : get_field_object($field_name);
$add_ignore = isset($post->relationship_stories_sub_sub_field) ? $post->relationship_stories_sub_sub_field : false;
$stories = $stories_data['value'];
$count = 1;
$ignore = array();
$header_graphic = '';

// highlight the last word
if ( $field_name == 'most_popular' ) {
  $words = explode( ' ', $stories_data['label'] );
  $last_word = '<strong>' . array_pop( $words ) . '</strong>';
  $words[] = $last_word;
  $stories_data['label'] = implode( ' ', $words );
  $header_graphic = get_field('most_popular_image', 'options');
}
$header_graphic_src = $header_graphic ? $header_graphic['sizes']['medium'] : '';
?>
<section class="relationship-stories relationship-stories--<?= underscores_to_dashes($field_name) ?> module-alternate">
  <div class="container relationship-stories__container">
    <?php if ( $field_name != 'featured_stories' ): ?>
      <h2 class="module-heading relationship-stories__headline"><span class="relationship-stories__headline-label"><?= @$stories_data['label']; ?></span><?php if ( $context == 'sidebar' ): ?><div class="relationship-stories__headline-background"><?php the_module('image', $header_graphic_src, $header_graphic_src); ?></div><?php endif; ?></h2>
    <?php endif; ?>
    <div class="relationship-stories__list carousel--mobile"<?php if ( $field_name == 'featured_stories' ): ?> data-module-init="flickity"<?php endif; ?>>
      <?php foreach ($stories as $story) : ?>
        <?php
          if ( $field_name == 'featured_stories' ) {
            if ( empty( $story['story'][0] ) ) {
              continue;
            }
            $post = $story['story'][0];
          } else {
            $post = $story;
          }

          setup_postdata($post);
          if($add_ignore) $ignore[] = $post->ID;

          $override = get_field( 'override_automatic_title_casing', $post->ID );
          $story_title = verify_title_case( ! empty( $story['content_title_override'] ) ? $story['content_title_override'] : wp_strip_all_tags( get_the_title() ), $post->post_date, $override );
          $story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
          $story_image_url = $story_image ? @$story_image[0] : '';
          $story_image     = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'article-retina' );
          $story_image_retina_url = $story_image ? @$story_image[0] : '';

          $story_categories = get_the_category();
          $story_category_name = $story_categories ? esc_html($story_categories[0]->cat_name) : false;
          $story_category_link = $story_categories ? esc_url(get_category_link( $story_categories[0]->term_id )) : false;

          if (get_post_type($post->ID) === 'page') {
            $label_link = get_field('story_label_link', $post->ID);
            $label_label = get_field('story_label', $post->ID);
            $label_newTab = get_field('story_link_new_tab', $post->ID);
            $link_target = $label_newTab ? "_blank" : "_self";
            $story_label = '';
            if (!empty($label_link)) {
              $story_label = '<a target="' . $link_target . '" href="' . $label_link . '">' . $label_label . '</a>';
            }
          } else {
            $story_label =  get_article_label($post->ID);
          }

	        $is_video_story = has_tag('video', $post->ID) || has_term('video', 'backend_tag', $post->ID) ? 'has_video_tag' : '';
          $franchise = get_franchise( $post->ID );

          if ( $field_name == 'featured_stories' ) {
            if ( ! empty( $story['featured_image'] ) ) {
              $story_image_url = $story['featured_image']['sizes']['medium'];
              $story_image_retina_url = $story['featured_image']['sizes']['large'];
            }

            if ( ! empty( $story['category_title'] ) ) {
              $story_category_link = false;
              $story_category_name = $story['category_title'];
            }

            if ( ! empty( $story['category_url'] ) ) {
              $story_category_link = $story['category_url'];
            }
          }
        ?>
        <article class="relationship-stories__card">
          <a class="relationship-stories__image-wrapper" href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', $post->ID) ?>">
            <div class="relationship-stories__image" title="<?php echo esc_attr( $story_title ); ?>"><?php the_module('image', array(
              'image_src' => $story_image_url,
              'image_src_retina' => $story_image_retina_url,
              'image_alt' => $story_title,
              'is_video' => $is_video_story,
              'franchise' => $franchise
            ) ); ?></div>
          </a>
            <div class="relationship-stories__category">
              <?php if (!empty($story_label)) : ?>
                <?= $story_label; ?>
              <?php endif; ?>
            </div>
          <a href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', $post->ID) ?>">
            <?php if ( $field_name != 'featured_stories' ): ?>
              <p class="relationship-stories__ranking"><?= $count; ?></p>
            <?php endif; ?>
            <h3 class="relationship-stories__title"><?= $story_title; ?></h3>
          </a>
        </article>
      <?php $count++; ?>
      <?php endforeach; ?>
      <?php wp_reset_postdata(); ?>
      <?php if($add_ignore) $post->relationship_stories_sub_sub_field = $ignore; ?>
    </div>
  </div>
</section>
