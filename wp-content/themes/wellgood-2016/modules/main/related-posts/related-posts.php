<?php
global $post;
$module_name = isset( $post->related_posts_field ) ? $post->related_posts_field : 'related-posts';

if( $module_name == 'recipe--branded' ) :

  $all_partners = get_terms('partners', array('fields' => 'ids'));
  $current_partner = get_the_terms($post, 'partners')[0];

  $branded_args = array (
    'post_type' => 'recipe',
    'posts_per_page' => 1,
    'post__not_in' => array( $post->ID ),
    'tax_query' => array(
        array(
            'taxonomy'      => 'partners',
            'terms'         => $current_partner,
            'field'         => 'slug',
            'operator'      => 'IN'
        )
    )
  );

  $branded_post = get_related_posts( $post->ID, 12, 6, $branded_args );
  $unbranded_count = empty($branded_post) ? 3 : 2;

  $unbranded_args = array (
    'post_type' => 'recipe',
    'post__not_in' => array( $post->ID ),
    'posts_per_page' => $unbranded_count,
    'orderby'   => 'rand',
    'tax_query' => array(
      array(
        'taxonomy'  => 'partners',
        'field'     => 'term_id',
        'operator'  => 'NOT IN',
        'terms'     => $all_partners
      )
    )
  );
  $unbranded_posts = get_related_posts( $post->ID, 12, 6, $unbranded_args );
  $related_posts = array_merge($branded_post, $unbranded_posts);
else:
  $related_posts = get_related_posts( $post->ID, 12, 6);
endif;

if (!empty($related_posts)) :
?>

<section class="post-grid related-posts <?= $module_name ?>"
	<?php if ( $module_name == 'related-posts--header' ): ?>
	    data-module-init="related-posts"
	    data-display-at=".post__title"
	    data-container="."
	<?php endif; ?>
>
	<div class="post-grid__headline-container">
		<?php if( $module_name == 'recipe--branded' ): ?>
			<p class="module-heading post-grid__headline">Pairs Well With</p>
		<?php else: ?>
			<p class="module-heading post-grid__headline">You May Also Like</p>
			<?php if ( $module_name == 'related-posts--header' ): ?>
				<a href="#" class="related-posts__to-top js-click-to-top"><span class="icon-arrow-up-thin"></span></a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<div class="post-grid__container">
			<?php foreach ( $related_posts as $post ): setup_postdata( $post ); ?>
			<?php
			$story_image     = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'article' );
			$story_image_url = $story_image ? @$story_image[0] : '';
			$story_image     = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'article-retina' );
      $story_image_retina_url = $story_image ? @$story_image[0] : '';
      $story_image_id = get_post_thumbnail_id( $post->ID );
      $story_title = get_post_meta($story_image_id, '_wp_attachment_image_alt', true) ? get_post_meta($story_image_id, '_wp_attachment_image_alt', true) : 'Well+Good - ' . $post->post_title;
			?>
			<a class="post-grid__card<?= $module_name == 'related-posts--header' ? ' post-grid__card--header' : ''; ?> " href="<?= esc_url( get_the_permalink() ) ?>" target="<?= get_field('_pprredirect_newwindow', $post->ID) ?>">
				<article>
					<div class="post-grid__image" title="<?= esc_attr(wp_strip_all_tags(get_the_title())); ?>"><?php the_module('image', $story_image_url, $story_image_retina_url, $story_title); ?></div>
					<?php if ( $module_name == 'related-posts--header' || $module_name == 'recipe--branded' ): ?>
						<p class="post-grid__title"><?= wp_strip_all_tags(get_the_title()) ?></p>
					<?php else: ?>
						<h3 class="post-grid__title"><?= wp_strip_all_tags(get_the_title()) ?></h3>
					<?php endif; ?>
				</article>
			</a>
		<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</section>
<?php endif; ?>
