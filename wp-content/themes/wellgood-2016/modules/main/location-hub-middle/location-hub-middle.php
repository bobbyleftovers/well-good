<?php
		$image = get_field( 'middle_image' );
		$image_link = get_field( 'middle_image_link' );
		$image_url = wp_get_attachment_image_url( $image['ID'], 'post-thumbnail' );

		// Secondary Middle Content
		$second_middle_type = get_field('second_middle_content');
		$second_middle_image = get_field('second_middle_photo');
		$second_middle_image_link = get_field('second_middle_photo_link');
		$second_middle_video_id = get_field('second_middle_video');
		$second_middle_video_shortcode = '[bc_video video_id="' . $second_middle_video_id . '" account_id="4872551774001" player_id="default"]';
		
	
	?>

<?php if( $image || $second_middle_type == 'photo' && $second_middle_image || $second_middle_type == 'video' && $second_middle_video_id ) : ?>
	<div class="suja-middle chub-middle">

		<?php if ( $image && $image_link ): ?>
			<a href="<?php echo esc_url( $image_link ); ?>">
		<?php endif; ?>

		<?php if( $image ): ?>
		<div class="suja-middle__image">
			<img src="<?php echo wp_get_attachment_image_url( $image['ID'], 'post-thumbnail' ); ?>" alt="<?php echo $image['alt']; ?>">
		</div>
		<?php endif; ?>


		<?php if ( $image && $image_link ): ?>
			</a>
		<?php endif; ?>

		<?php if( $second_middle_type == 'photo' && $second_middle_image || $second_middle_type == 'video' && $second_middle_video_id ) : ?>
		<div class="chub-second-middle__content">
			<?php if( $second_middle_type == 'photo' && $second_middle_image) : ?>

				<?php if( $second_middle_image_link ) : ?>
				<a href="<?= $second_middle_image_link; ?>">
				<?php endif; ?>

				<div class="suja-middle__image">
					<img src="<?= wp_get_attachment_image_url( $second_middle_image['ID'], 'post-thumbnail' );  ?>" alt="<?= $second_middle_image['alt']; ?>">
				</div>

				<?php if( $second_middle_image_link ) : ?>
				</a>
				<?php endif; ?>

			<?php elseif($second_middle_type == 'video' && $second_middle_video_id) : ?>
				<?= do_shortcode( $second_middle_video_shortcode ); ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>

	</div>
<?php endif; ?>