<?php
$ad_slots_freq = 3;
$ad_slots_max = 5;
?>

<main>
<?php
	$current_page = max( 1, get_query_var('paged') );
	$post_feature = $current_page == 1 ? 1 : 0;
	$page_type = 'tag';
	// $posts = new WP_Query( array('posts_per_page' => $max_posts, 'tag' => $tag->slug, 'offset' => $offset, 'post_type' => 'post', 'post_status' => 'publish', 'ignore_sticky_posts' => true ));
?>
	<div class="container">

		<section class="tag-featured-article-list">
			<h1 class="module-heading"><?= single_tag_title( '', false ); ?></h1>
			<?php $post_count=1; if (have_posts()) : while (have_posts()) : the_post(); if ($post_count <= $post_feature): ?>
				<?= include_partial('post-list-card', array(
					'name' => 'tag-featured',
					'page_type' => 'tag'
				)); ?>
			<?php endif; $post_count++; endwhile; endif; ?>
		</section>
	</div>

	<div class="container container--with-aside tag__module-group wg__inline-ad-wrapper"
	         data-module-init="ajax-load-more"
	         data-ajax-selector=".tag__module-group"
	         data-ajax-child-selector=".tag__article-list article"
	         data-ajax-button=".load-more .btn">
		<section>
			<div class="article-list tag__article-list">
			<?php 
			$post_count = 1; 
			$ad_iteration = 0;
			
			if (have_posts()) : 
				while (have_posts()) : the_post(); 
					if ($post_count > $post_feature): ?>
						<?= include_partial('post-list-card', array( 'name' => 'first' ) ); ?>
					<?php endif; 
					if ($post_count % $ad_slots_freq == 0 && $ad_iteration < $ad_slots_max) : ?>
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
					$post_count++; 
				endwhile; 
			endif; ?>
			</div>
			<?php if($wp_query->max_num_pages > $current_page): ?>
				<div class="load-more">
					<a class="btn" href="<?= get_pagenum_link($current_page+1) ?>">Load More</a>
				</div>
			<?php 
		endif; ?>
		</section>

		<aside class="sidebar">
			<?php the_module( 'most-popular', 'most_popular', 'sidebar' ); ?>
			<?php the_module('advertisement', array(
				'slots' => array(
					'rightrail',
				),
				'page' => 0,
				'iteration' => 0,
				'sticky' => true
			)); ?>
		</aside>
	</div>
</main>
<?php the_module('cluster-share'); ?>