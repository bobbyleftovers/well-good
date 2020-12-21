<?php
	$suja_logo = get_field( 'suja_logo' );
	$suja_presented_by = get_field( 'suja_presented_by' );
	$image_override = get_field( 'mobile_image_override' );
?>
<header class="suja-header container container-legacy">
	<div class="suja-header__hero" style="background-image: url('<?php the_post_thumbnail_url(); ?>');">
		<?php if ( $image_override ) : ?><style>
			@media (max-width: 640px) {
				.suja-header__hero {
					background-image: url('<?= $image_override['url']; ?>') !important;
					padding-top: 66%;
					height: 0;
					margin-bottom: 60px;
				}
			}
		</style><?php else : ?>

		<div class="suja-header__box">
			<h1 class="post__title suja-header__title"><?= wp_strip_all_tags(get_the_title()) ?></h1>
			<div class="suja-header__presented"><?php if( !empty( $suja_presented_by ) ): ?><?= $suja_presented_by; ?> <?php endif; ?><?php if( !empty( $suja_logo ) ): ?><img src="<?php echo wp_get_attachment_image_url( $suja_logo['ID'], array( 30, 30 ) ); ?>" alt="<?php echo $suja_logo['alt']; ?>"><?php endif; ?></div>
		</div><?php endif; ?>

	</div>
	<div class="suja-header__intro  post__main"><?php the_field( 'intro' ); ?></div>
</header>
