<?php
$id = get_the_id();
$class = isset($post->recipe_header_image_field) ? 'recipe-header-image--sponsor' : 'recipe-header-image--standard';
$is_branded = isset($post->recipe_header_image_field) ? true : false;
$video = get_field('header_video');

?>

<figure class="post__featured-image">

	<div class="post__image-wrapper recipe-header-image <?= $class ?>">
    <?php if( $video ) : ?>
      <video class="recipe-header-image__elem recipe-header-image__elem--video" autoplay loop muted data-module-init="recipe-header-image">
        <source src="<?= get_template_directory_uri() ?>/assets/img/spacer.gif" data-src="<?= $video['url'] ?>" type="<?= $video['mime_type'] ?>">
      </video>
      <?php endif; ?>
			<?php the_post_thumbnail('large', array('class' => 'recipe-header-image__elem recipe-header-image__elem--image')); ?>
	</div>

	<?php if ( featured_image_has_caption() && $is_branded == false ): ?>
    <figcaption><?php the_featured_image_caption(); ?></figcaption>
  <?php endif; ?>

  <?php if( !$is_branded ) : ?>
    <?php the_module( 'recipe-tip' ); ?>
  <?php endif; ?>

</figure>
