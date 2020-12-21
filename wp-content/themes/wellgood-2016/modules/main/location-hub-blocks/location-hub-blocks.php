<?php 
global $post;

// TODO - combine $i and $index
$i = 0;
$ad_iteration = 0;
$ad_slots_freq = 3;
$ad_slots_max = 5;

if ( have_rows( $post->location_hub_blocks_field ) ):?>

	<section class="lochub-blocks suja-blocks suja-blocks--<?php echo underscores_to_dashes( $post->location_hub_blocks_field ); echo ( is_page_template('templates/page-council-hub.php') ? ' chub-blocks' : '' ); ?> wg__inline-ad-wrapper">
		<h2 class="module-heading lochub-blocks-heading"><?php the_field( $post->location_hub_blocks_field . '_title' ); ?></h2>
		<?php $hide_numbers = 'hide'; ?>
		<ol class="suja-blocks__list <?php if($hide_numbers == 'hide'): ?> hide-num<?php endif; ?>">
			<?php 
			$index = 1;
			$article_count = count(get_field($post->location_hub_blocks_field));
			$is_features = $post->location_hub_blocks_field == 'features' ? true : false;

			while ( have_rows( $post->location_hub_blocks_field ) ): the_row();
      	$override = get_field( 'override_automatic_title_casing', get_sub_field('post')->ID );
				$title = verify_title_case( get_sub_field( 'title' ), get_sub_field('post')->post_date, $override );
				$image = get_sub_field( 'featured_image' );

				if( $is_features ){
					$post_type = get_sub_field('post_or_city');
					$the_post = get_sub_field('post');

					if( !$the_post ){
						$city = get_sub_field('city');
						$city_id = $city->term_id;
						$the_post = get_field('landing_page', "term_$city_id");
					}

					$link = get_the_permalink( $the_post );
				}else {

					$link = get_sub_field('link');

					if( !$link ){
						$the_post = get_sub_field('post');
						$link = get_the_permalink( $the_post );
					}
				}


				$story_image = wp_get_attachment_image_src( $image, 'large' ); // this is calling the header image -- coming back to debug this to defer to this call as best practice
				$story_image_url = $image ? $image['sizes']['large'] : '';
				$story_image_retina_url = $story_image_url;
			?>

			<?php if ( ! empty( $title ) ): ?>
				<li class="suja-blocks__block">
					<?php if ( $post->location_hub_blocks_field === 'graphic_blocks' ): ?>
					<h2 class="suja-blocks__title">
						<?php if ( ! empty( $link ) ): ?>
						<a href="<?php echo esc_url( $link ); ?>" data-vars-event="article link" data-vars-info="<?= $index; ?>">
						<?php endif; ?>
							<?php echo $title; ?>
						<?php if ( ! empty( $link ) ): ?>
						</a>
						<?php endif; ?>
					</h2>
					<?php endif; ?>

					<div class="suja-blocks__image-wrapper lochub-blocks__image-wrapper">
						<?php if ( ! empty( $link ) ): ?>
							<a href="<?php echo esc_url( $link ); ?>" data-vars-event="article link" data-vars-info="<?= $index; ?>">
						<?php endif; ?>
						<?php if( $post_type == 'city' ) :?>
						<img src="<?= get_template_directory_uri() . '/assets/img/map_logo.png'; ?>" class="lochub-blocks__map-logo" />
						<?php endif; ?>
							<div class="suja-blocks__image" title="<?php the_sub_field( 'title' ); ?>"><?php the_module('image', $story_image_url, $story_image_retina_url, get_sub_field( 'title' ) ); ?></div>
						<?php if ( ! empty( $link ) ): ?>
							</a>
						<?php endif; ?>
						<?php if ( $post->location_hub_blocks_field == 'graphic_blocks' ): ?>
							<a href="//pinterest.com/pin/create/link/?url=<?= urlencode( get_the_permalink() ) ?>&amp;description=<?= wg_esc_url( get_sub_field( 'title' ) ) ?>&amp;media=<?= urlencode( $story_image_retina_url ); ?>" class="post__pin-image social-share__button social-share__button--circle social-share__button--pinterest" data-vars-event="article link" data-vars-info="<?= $index; ?>"><span class="icon-pinterest-p"></span></a>
						<?php endif; ?>
					</div>

					<?php if ( $post->location_hub_blocks_field !== 'graphic_blocks' ): ?>
					<h2 class="suja-blocks__title">
						<?php if ( ! empty( $link ) ): ?>
						<a href="<?php echo esc_url( $link ); ?>" data-vars-event="article link" data-vars-info="<?= $index; ?>">
						<?php endif; ?>
							<?php echo $title; ?>
						<?php if ( ! empty( $link ) ): ?>
						</a>
						<?php endif; ?>
					</h2>
					<?php endif; ?>

					<?php if ( get_sub_field( 'description' ) ): ?>
						<div class="suja-blocks__description">
							<?php the_sub_field( 'description' ); ?>
						</div>
					<?php endif; ?>
				</li>
				<?php endif;

				if ($i % $ad_slots_freq == 0 && $ad_iteration < $ad_slots_max) : ?>
					<div class="suja-blocks__slot">
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
				
				$index++;
				$i++;
			endwhile; ?>
		</ol>
	</section>
<?php endif; ?>