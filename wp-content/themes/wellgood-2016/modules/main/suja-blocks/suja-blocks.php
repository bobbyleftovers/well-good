<?php 
global $post; 
$i = 0;
$ad_iteration = 0;
$ad_slots_freq = 3;
$ad_slots_max = 5;

if ( have_rows( $post->suja_blocks_field ) ): ?>
	<section class="suja-blocks suja-blocks--<?php echo underscores_to_dashes( $post->suja_blocks_field ); ?> wg__inline-ad-wrapper">
		<?php if ( $post->suja_blocks_field == 'graphic_blocks' ): ?>
			<h2 class="module-heading"><?php the_field( 'graphic_blocks_heading' ); ?></h2>
		<?php endif; ?>
		<?php $hide_numbers = false;
		if ( $post->suja_blocks_field != 'graphic_blocks' && get_field('hide_numbers_thumbnail') ):
			$hide_numbers = get_field('hide_numbers_thumbnail')[0];
		endif; ?>
		<ol class="suja-blocks__list<?php if($hide_numbers == 'hide'): ?> hide-num<?php endif; ?>">
      <?php 
      $index = 1;
      $article_count = count(get_field($post->suja_blocks_field));

      while ( have_rows( $post->suja_blocks_field ) ): the_row();
        $override = get_field( 'override_automatic_title_casing', get_sub_field( 'post' )->ID );
        $title = verify_title_case( get_sub_field( 'title' ), get_sub_field( 'post' )->post_date, $override );
        $image = get_sub_field( 'image' );
        $link = get_sub_field( 'link' );
        if ( ! $link ) {
          $link = get_sub_field( 'story' );
        }
        $story_image = wp_get_attachment_image_src( $image, 'large' );
        $story_image_url = $story_image ? @$story_image[0] : '';
        $story_image_retina_url = $story_image_url;

        if ( ! empty( $title ) ): ?>
          <li class="suja-blocks__block">
            <?php if ( $post->suja_blocks_field === 'graphic_blocks' ): ?>
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

            <div class="suja-blocks__image-wrapper">
              <?php if ( ! empty( $link ) ): ?>
                <a href="<?php echo esc_url( $link ); ?>" data-vars-event="article link" data-vars-info="<?= $index; ?>">
              <?php endif; ?>
                <div class="suja-blocks__image" title="<?php the_sub_field( 'title' ); ?>"><?php the_module('image', $story_image_url, $story_image_retina_url, get_sub_field( 'title' ) ); ?></div>
              <?php if ( ! empty( $link ) ): ?>
                </a>
              <?php endif; ?>
              <?php if ( $post->suja_blocks_field == 'graphic_blocks' ): ?>
                <a href="//pinterest.com/pin/create/link/?url=<?= urlencode( get_the_permalink() ) ?>&amp;description=<?= wg_esc_url( get_sub_field( 'title' ) ) ?>&amp;media=<?= urlencode( $story_image_retina_url ); ?>" class="post__pin-image social-share__button social-share__button--circle social-share__button--pinterest" data-vars-event="article link" data-vars-info="<?= $index; ?>"><span class="icon-pinterest-p"></span></a>
              <?php endif; ?>
            </div>

            <?php if ( $post->suja_blocks_field !== 'graphic_blocks' ): ?>
            <h2 class="suja-blocks__title">
              <?php if ( ! empty( $link ) ): ?>
                <a href="<?php echo esc_url( $link ); ?>" data-vars-event="article link" data-vars-info="<?= $index; ?>">
                  <?php echo $title; ?>
                </a>
              <?php else : ?>
                <?php echo $title; ?>
              <?php endif; ?>
            </h2>
            <?php endif; ?>

            <?php if ( get_sub_field( 'description' ) ): ?>
              <div class="suja-blocks__description">
                <?php the_sub_field( 'description' ); ?>
              </div>
            <?php endif; ?>
          </li>
        <?php 
        endif;

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