<?php
  get_header();
  $current_page = max( 1, get_query_var('paged') );
?>
<article class="search">

	<div class="post__inner">
		<div class="container container">
			<div class="page__content">

				<header class="search__header">
					<h1 class="post__title">Search Results for: <span><?= get_search_query() ?></span></h1>
				</header>

				<main class="search__main"
			         data-module-init="ajax-load-more"
			         data-ajax-selector=".search__main"
			         data-ajax-child-selector=".search__results li"
			         data-ajax-button=".load-more .btn">

					<?php if ( have_posts() )  : $count = 0; ?>

						<ol class="search__results">

						<?php while ( have_posts() ) : the_post(); $count++; ?>
						    <?php
					          $story_title = wp_strip_all_tags(get_the_title());
					          $story_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'article' );
					          $story_image_url = $story_image ? @$story_image[0] : '';
					        ?>

							<li>
							  <a href="<?php the_permalink(); ?>" target="<?= get_field('_pprredirect_newwindow', get_the_ID()) ?>" rel="bookmark">
								  <div class="post__result__inner">
									<div class="post__result <?= !empty($story_image) ? 'post__result--has-thumb' : '' ?>">
										<h2 class="post__title">
											<?= $story_title ?>
										</h2>
										<div class="post__summary">
											<p class="excerpt"><?= get_the_excerpt(); ?></p>
										</div>
									</div>
									<?php if(!empty($story_image)): ?>
									<div class="post__thumb">
										<img src="<?= $story_image_url ?>" alt="<?= esc_attr($story_title); ?>" />
									</div>
									<?php endif; ?>
								  </div>
								</a>
							</li>

						<?php endwhile; ?>

						</ol>

						<?php if($wp_query->max_num_pages > $current_page): ?>
							<div class="load-more">
								<a class="btn" href="<?= get_pagenum_link($current_page+1) ?>">Load More</a>
							</div>
						<?php endif; ?>

					<?php else : ?>

						<div class="search__empty">
							<p>Sorry, but nothing matched your search criteria. Please try again with different keywords.</p>
						</div>

					<?php endif; ?>

				</main>
			</div>
		</div>
	</div>
</article>
<?php
  get_footer();
?>
