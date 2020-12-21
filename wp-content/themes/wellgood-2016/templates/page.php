<?php
/**
 * Post Template
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 1.0.0
 */

$page_title 					= wp_strip_all_tags( get_the_title() );
$show_instagram_feed 	= get_field( 'show_instagram_feed' ) == 'yes';
?>


<article class="page">
	<div class="post__inner">
		<div class="container page__content-wrapper">
			<div class="page__content">
				<header class="page__header">
					<h1 class="big page__title"><?= $page_title; ?></h1>
				</header>
				<main class="page__main">
					<?php the_content(); ?>
				</main>
			</div>
		</div>
	</div>

	<?php if ( $show_instagram_feed ) : ?>
		<div class="container">
			<?php the_module('instagram-feed'); ?>
		</div>
	<?php endif; ?>
</article>
