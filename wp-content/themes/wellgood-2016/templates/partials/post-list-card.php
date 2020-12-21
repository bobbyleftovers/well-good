<?php
$include_name = $vars['name'];
$page_type = array_key_exists('page_type', $vars) ? $vars['page_type'] : '';
$trim = (array_key_exists('trim',$vars)) ? $vars['trim'] : false ;
$id = get_the_ID();
$permalink = get_permalink();
$story_category = get_the_category($id);
$story_title = $trim ? cut_words_by_character(wp_strip_all_tags(get_the_title()), 75) : wp_strip_all_tags(get_the_title());
$story_excerpt = get_the_excerpt();
$story_image_size = ( $include_name == 'category-featured' || $include_name == 'tag-featured' ) ? 'article-retina' : 'medium';
$story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $story_image_size );
$story_image_url = $story_image ? @$story_image[0] : '';
$story_image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'article-retina' );
$story_image_retina_url = $story_image ? @$story_image[0] : '';
$story_author_id = get_the_author_meta('ID');
$story_author_name = get_the_author();
$story_author_avatar = get_wp_user_avatar_src( $story_author_id, 'thumbnail' );
$story_author_link = $story_author_id ? esc_url(get_author_posts_url( $story_author_id )) : false;
$story_show_author_link = get_field('show_author', "user_{$story_author_id}");
$is_video_story = has_tag('video', $id) || has_term('video', 'backend_tag', $id) ? 'has_video_tag' : ''; 
$story_label = get_article_label($id);
$franchise = get_franchise( $id );
?>

<article class="article-card article-card--<?= $include_name ?>">
  <a href="<?= $permalink ?>" target="<?= get_field('_pprredirect_newwindow', $id) ?>" class="article-card__image">
    <?php the_module('image', array(
      'image_src' => $story_image_url,
      'image_src_retina' => $story_image_retina_url,
      'image_alt' => $story_title,
      'is_video' => $is_video_story,
      'franchise' => $franchise
    )); ?></a
  ><div class="article-card__meta <?= $include_name ?>__meta">
    <?php if ($story_label) : ?>
      <span class="article-card__category"><?= $story_label; ?></span>
    <?php endif; ?>
    <a href="<?= $permalink ?>" target="<?= get_field('_pprredirect_newwindow', $id) ?>" class="<?= $include_name ?>-story-link">
      <h3 class="article-card__title"><?= $story_title; ?></h3>
      <p class="article-card__excerpt"><?=truncate_by_word( $story_excerpt, 27, '...' ); ?></p>
    </a>
    <?php if($story_author_name): ?>
      <span class="meta by article-card__by"><?php if($story_show_author_link): ?><a href="<?= $story_author_link; ?>"><?php endif; ?><?php if($story_author_avatar): ?><span class="avatar-wrapper article-card__avatar"><?php the_module('image', $story_author_avatar, $story_author_avatar, esc_attr($story_author_name), false ); ?></span><?php endif; ?> by <?= $story_author_name; ?><?php if($story_show_author_link): ?></a><?php endif; ?></span>
    <?php endif; ?>
  </div>
</article>
