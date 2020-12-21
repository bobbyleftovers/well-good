<?php
$category = get_category( get_query_var( 'cat' ) );
$current_page = max( 1, get_query_var('paged') );
$post_feature_count = $current_page == 1 ? 2 : 0;
$ad_slots_freq = 3;
$ad_slots_max = 5;
$category_selector = 'category_' . $category->cat_ID;
$category_image = get_field('image', $category_selector);
$fallback_image = wag_get_fallback_image();
$fallback_image = $fallback_image && array_key_exists('sizes', (array)$fallback_image) && array_key_exists('large', $fallback_image['sizes']) ? $fallback_image['sizes']['large'] : '';
$category_image_src = $category_image && array_key_exists('sizes', (array)$category_image) && array_key_exists('large', $category_image['sizes']) ? $category_image['sizes']['large'] : $fallback_image;
$category_tags = get_field('show_tags', $category_selector);

?>
<h1 class="image-headline image-headline--hero"
    style="background-image: url('<?= $category_image_src ?>');">
	<span class="image-headline__label"><?= $category->name ?></span>
</h1>

<main class="category">

	<?php if($post_feature_count): ?>
		<div class="container">

			<section class="category__featured-article-list">
				<?php
				$i = 0;
				if (have_posts()) :
					while (have_posts()) : the_post();
						if ($i < $post_feature_count):
							include_partial('post-list-card', array(
								'name' => 'category-featured',
								'trim' => true
							));
						endif;
						$i++;
					endwhile;
				endif; ?>
			</section>
		</div>
	<?php endif; ?>
	<div class="container container--with-aside wg__inline-ad-wrapper">
		<div class="category__module-group"
	         data-module-init="ajax-load-more"
	         data-ajax-selector=".category__module-group"
	         data-ajax-child-selector=".category__article-list article"
	         data-ajax-button=".load-more .btn">
			<?php if($category_tags && $post_feature_count && isset($category->cat_ID)): ?>
			<section class="popular-tags tag-list info">
				<h3 class="popular-tags__headline">Popular Tags</h3>
				<span class="icon-tag"></span>
				<?= get_top_category_tags($category->cat_ID, 5) ?>
			</section>
			<hr class="hr__under-info" />
			<?php endif; ?>

			<section class="article-list category__article-list <?php if($category_tags && $post_feature_count && isset($category->cat_ID)): ?>category__article-list-with-tags<?php endif; ?>">
				<?php
				$i = 0;
				$ad_iteration = 0;

				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						if ($i >= $post_feature_count) :
							include_partial( 'post-list-card', array( 'name' => 'article' ) );

							if ( $i % $ad_slots_freq == 0 && $ad_iteration < $ad_slots_max ) : ?>
								<div class="category__slot">
									<?php
									the_module('advertisement', array(
										'slots' => array(
											'slot',
										),
										'page' => 0,
										'iteration' => $ad_iteration
									));
									$ad_iteration++; ?>
								</div>
							<?php
							endif;
						endif;
						$i++;
					endwhile;
				endif; ?>
			</section>
			<?php if($wp_query->max_num_pages > $current_page): ?>
				<div class="load-more">
					<a class="btn" href="<?= get_pagenum_link($current_page+1) ?>">Load More</a>
				</div>
			<?php endif; ?>

		</div>
		<aside class="category__sidebar sidebar">
			<?php the_module('advertisement', array(
				'slots' => array(
					'rightrail',
					'slot'
				),
				'page' => 0,
				'iteration' => 0,
				'sticky' => true
			)); ?>
		</aside>
	</div>

	<?php the_module( 'collection', 'collection', $category_selector ); ?>
</main>
