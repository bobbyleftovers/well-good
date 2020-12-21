<?php
  get_header();
?>
<article class="post-404">
	<div class="post__inner">
		<div class="container container">
			<div class="post__content">
				<main class="post-404__main">

					<?= the_field('404', 'options') ?>

				</main>
			</div>
		</div>
	</div>
</article>
<?php brrl_the_module( 'trending-articles' ); ?>
<?php the_module( 'collection', 'collection' ); ?>
<?php
  get_footer();
?>
