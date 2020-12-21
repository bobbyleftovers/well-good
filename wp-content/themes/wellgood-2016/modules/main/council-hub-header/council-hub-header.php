<?php
	$page_title = wp_strip_all_tags( get_the_title() );
	$header_image = get_field('header');
	$header_image_url = $header_image ? $header_image['sizes']['large'] : false;
	$header_image_retina_url = $header_image ? $header_image['sizes']['large'] : false; // article-retina crops the image and displays incorrectly -- disabling for now...

	$header_mobile_image = get_field('header_mobile');
	$header_mobile_image_url = $header_mobile_image ? $header_mobile_image['sizes']['medium'] : false;

	$header_title = get_field('header_title');

	$content = get_field('content');
	$lower_content = get_field('lower');
?>
<header class="suja-header container container-legacy">
	<div class="suja-header__hero chub-header__hero" style="background-image: url('<?= $header_image_url ?>');">
		<?php if ( $header_mobile_image_url ) : ?><style>
			@media (max-width: 640px) {
				.suja-header__hero {
					background-image: url('<?= $header_mobile_image_url ?>') !important;
					padding-top: 66%;
					height: 0;
				}
			}
		</style><?php endif; ?>

	</div>

	<?php the_module( "council-hub-subnav" ); ?>

	<h1 class="chub-header__title"><?= $header_title; ?></h1>
	<div class="suja-header__intro  lochub-header__intro post__main chub-header__intro"><?php the_content(); ?></div>
</header>
