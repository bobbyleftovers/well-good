<div class="suja-middle">
	<?php
		$image = get_field( 'custom_image' );
		$image_link = get_field( 'custom_image_link' );
		$image_url = wp_get_attachment_image_url( $image['ID'], 'post-thumbnail' );
	?>
	<?php if ( $image_link ): ?>
		<a href="<?php echo esc_url( $image_link ); ?>">
	<?php endif; ?>

	<div class="suja-middle__image">
		<img src="<?php echo wp_get_attachment_image_url( $image['ID'], 'post-thumbnail' ); ?>" alt="<?php echo $image['alt']; ?>">
	</div>

	<?php if ( $image_link ): ?>
		</a>
	<?php endif; ?>
</div>
