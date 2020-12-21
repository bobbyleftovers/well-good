<?php
/* Template Name: Trends */
get_header();
global $post;
?>

<?php
	$trends_logo = get_field( 'logo' );
	$trends_logo_url = $trends_logo ? wp_get_attachment_image_url( $trends_logo, 'medium' ) : false;
	$trends_background = wp_get_attachment_image_src( get_field( 'background' ), 'large' );
	$trends_background_url = $trends_background ? @$trends_background[0] : '';
	$trends_background_mobile = wp_get_attachment_image_src( get_field( 'background_mobile' ), 'large' );
  $trends_background_mobile_url = $trends_background_mobile ? @$trends_background_mobile[0] : '';
  $trend_type = get_field('trends_type');
?>
<style>
.trends-header__hero {
	background-image: url('<?= $trends_background_mobile_url; ?>');
}
@media ( min-width:641px ) {
	.trends-header__hero {
		background-image: url('<?= $trends_background_url; ?>');
	}
}
</style>

<article class="post trend">
	<header class="trends-header">
		<div class="trends-header__hero">
			<div class="trends-header__box">
				<div class="trends-header__logo"><img src="<?= $trends_logo_url; ?>" class="trends-header__logo__image" /></div>
				<h1 class="big center-underline-lg-pink post__title trends-header__title"><?= wp_strip_all_tags(get_the_title()) ?></h1>
				<?= the_content(); ?>
			</div>
		</div>
	</header>
	<div class="post__inner">

		<div class="container container post__content-wrapper">
			<div class="post__content post__content--trends">
				<aside class="post__share social-share--circle" data-module-init="post-share">
					<div class="post__share--inner">
						<a class="social-share__button social-share__button--facebook" target="_blank" href="//www.facebook.com/sharer/sharer.php?u=<?= urlencode(get_the_permalink()) ?>" data-vars-event="Facebook" data-vars-info="<?= esc_url($_SERVER['REQUEST_URI']) ?>">
							<span class="icon-facebook"></span>
						</a>
						<a class="social-share__button social-share__button--twitter" target="_blank" href="//twitter.com/share?text=<?= wg_esc_url(get_the_title() . ' via ' . get_twitter_handle()) ?>&amp;url=<?= urlencode(get_the_permalink()) ?>" data-vars-event="Twitter" data-vars-info="<?= esc_url($_SERVER['REQUEST_URI']) ?>">
							<span class="icon-twitter"></span>
						</a>
						<a class="social-share__button social-share__button--pinterest" target="_blank" href="//pinterest.com/pin/create/link/?url=<?= urlencode(get_the_permalink()) ?>&amp;description=<?= wg_esc_url(get_the_title()) ?>&amp;media=<?= urlencode( get_the_post_thumbnail_url(get_the_ID(), 'medium') ); ?>" data-vars-event="Pinterest" data-vars-info="<?= esc_url($_SERVER['REQUEST_URI']) ?>">
							<span class="icon-pinterest-p"></span>
						</a>
					    <a class="social-share__button social-share__button--flipboard" target="_blank" href="https://share.flipboard.com/bookmarklet/popout?v=2&amp;title=<?= wg_esc_url(get_the_title()) ?>&amp;url=<?= urlencode(get_the_permalink()) ?>" data-flip-widget="shareflip" data-vars-event="Flipboard" data-vars-info="<?= esc_url($_SERVER['REQUEST_URI']) ?>">
					    	<span></span>
					    </a>
						<a class="social-share__button social-share__button--email" href="mailto:?subject=<?php echo wg_esc_url( get_the_title() ); ?>&body=<?php echo wg_esc_url( get_the_excerpt() . "\n\n" . get_the_permalink() ); ?>" data-vars-event="Email" data-vars-info="<?= esc_url($_SERVER['REQUEST_URI']) ?>">
							<span class="icon-paper-plane"></span>
						</a>
						<?php // TODO: Backend code for short urls
						sprintf('<a class="%1$s %1$s--link" target="_blank" href="%2$s">%3$s</a>',
                            "social-share__button",
                            "#",
                            '<span class="icon-link"></span>'
                        ); ?>
					</div>
				</aside>

				<main class="page-main js-modal-trends__container">

				<?php
					$page_url = get_the_permalink();
					$num = 0;
				?>
        <?php $index = 1; ?>
				<?php while ( have_rows( 'trends' ) ): the_row();

						$title = get_sub_field( 'title' );
						$image = get_sub_field( 'featured_image' );
						$image_credit = get_sub_field( 'featured_image_credit' );
						$excerpt = get_sub_field( 'excerpt' );
						$body = get_sub_field( 'body' );
            $anchor = sanitize_title( $title );
            $cta_value = !empty(get_sub_field('call_to_action_label')) ? get_sub_field('call_to_action_label') : 'Read More';
						$trend_url = !empty(get_sub_field('article_link')) && $trend_type == 'article' ? get_sub_field('article_link') : $page_url . '#' . $anchor;
						$num++;

						$sponsored = get_sub_field( 'sponsored' );
						$sponsored_by_text = get_sub_field( 'sponsored_by_text' );
						$sponsor_logo = get_sub_field( 'sponsor_logo' );
						$sponsor_logo_url = $sponsor_logo ? wp_get_attachment_image_url( $sponsor_logo, 'medium' ) : false;
						$sponsor_link_url = get_sub_field( 'sponsor_link_url' );

						$trend_image = wp_get_attachment_image_src( $image, 'medium' );
						$trend_image_url = $trend_image ? @$trend_image[0] : '';
						$trend_image_retina = wp_get_attachment_image_src( $image, 'article-retina' );
						$trend_image_retina_url = $trend_image_retina ? @$trend_image_retina[0] : '';

						$trend_image_modal = get_sub_field( 'featured_image_modal' );
						$trend_image_modal_url = $trend_image_modal ? wp_get_attachment_image_url( $trend_image_modal, 'large' ) : false;

						$trend_title_prefix = get_sub_field('title_prefix');

						if( $trend_title_prefix ){
							$prefix = "<span class='js-modal-trends__number'>$trend_title_prefix</span>";
						}else {
							$prefix = "<span class='js-modal-trends__number'>No. $num</span>";
						}
					?>

					<a name="<?= $anchor ?>" class="trend__anchor"></a>
					<article class="article-card article-card--trend js-modal-trends__article <?php echo $sponsored ? 'trend--sponsored ' : ''; echo ( $num % 2 == 0 ) ? 'odd' : ''; ?>">

			        	<a href="<?= $trend_url; ?>" class="trend__image__wrapper js-open-modal-trends">
			            	<span class="article-card__image trend__image js-modal-trends__image" data-src="<?= $trend_image_url ?>"><?php the_module('image', $trend_image_url, $trend_image_retina_url, $title); ?></span>
							<span class="js-modal-trends__image--full" data-src="<?= $trend_image_modal_url ?>"></span>
							<?php if( !empty( $image_credit ) ) : ?>
								<p class="h6 article-card__image__credit js-modal-trends__image__credit"><?= $image_credit; ?></p>
							<?php endif; ?>
				        </a>
				        <div class="article-card__meta trend__meta">
							<?php if( $sponsored ) : ?>
								<?php if( !empty( $sponsor_link_url ) ) : ?>
									<a href="<?= $sponsor_link_url; ?>" target="_blank" class="article-card__sponsored js-modal-trends__sponsored-link">
								<?php elseif( !empty( $sponsored_by_text ) || !empty( $sponsor_logo_url ) ): ?>
									<span class="article-card__sponsored js-modal-trends__sponsored">
								<?php endif; ?>
									<?php if( !empty( $sponsored_by_text ) ) : ?>
										<span class="h6 article-card__sponsor-link__by js-modal-trends__sponsor-link__by"><?= $sponsored_by_text; ?></span>
									<?php endif; ?>
									<?php if( !empty( $sponsor_logo_url ) ) : ?>
										<img src="<?= $sponsor_logo_url; ?>" class="article-card__sponsor-link__image js-modal-trends__sponsor-link__image" />
									<?php endif; ?>
								<?php if( !empty( $sponsor_link_url ) ) : ?>
									</a>
								<?php elseif( !empty( $sponsored_by_text ) || !empty( $sponsor_logo_url ) ): ?>
									</span>
								<?php endif; ?>
							<?php endif; ?>
              <?php if( !empty($prefix) ) : ?>
                <a href="<?= $trend_url; ?>" class="trend__link js-open-modal-trends">
                  <span class="h1 center-underline-sm-pink article-card--trend__number"><?= $prefix; ?></span>
                </a>
              <?php endif; ?>
              <?php if( !empty( $title ) ) : ?>
                <a href="<?= $trend_url; ?>" class="trend__link js-open-modal-trends">
                  <h2 class="article-card--trend__title js-modal-trends__title"><?= $title; ?></h2>
                </a>
              <?php endif; ?>
								<?php if( !empty( $excerpt ) ) : ?>
									<div class="article-card--trend__excerpt"><small><?= $excerpt; ?></small></div>
                <?php endif; ?>
                <a href="<?= $trend_url; ?>" class="trend__link js-open-modal-trends">
                  <p class="article-card__read-more small"><?= $cta_value; ?></p>
                </a>
							<div class="article-card--trend__body js-modal-trends__content"><?= $body; ?></div>
              <?php if ($trend_type != 'article') : ?>
                <div class="article-card__share">
                  <span class="h5 article-card--trend__social-share__label">Share</span>
                  <a class="social-share__button article-card--trend__social-share__button social-share__button--facebook js-modal-trends__share__button--facebook" target="_blank" href="//www.facebook.com/dialog/feed?app_id=504459123062068&display=popup&link=<?= urlencode($trend_url); ?>&name=<?= urlencode( esc_attr( $title ) ); ?>&description=<?= urlencode( mb_substr( wp_filter_nohtml_kses( apply_filters('the_content', $excerpt) ), 0, 350 ) ); ?>...&picture=<?= urlencode( $trend_image_url ); ?>">
                    <span class="icon-facebook"></span>
                  </a>
                  <a class="social-share__button article-card--trend__social-share__button social-share__button--twitter js-modal-trends__share__button--twitter" target="_blank" href="//twitter.com/share?text=<?= wg_esc_url($title . ' via ' . get_twitter_handle()) ?>&amp;url=<?= urlencode($trend_url) ?>">
                    <span class="icon-twitter"></span>
                  </a>
                  <a class="social-share__button article-card--trend__social-share__button social-share__button--pinterest js-modal-trends__share__button--pinterest" target="_blank" href="//pinterest.com/pin/create/link/?url=<?= urlencode($trend_url) ?>&amp;description=<?= wg_esc_url($title) ?>&amp;media=<?= urlencode( $trend_image_url ); ?>">
                    <span class="icon-pinterest-p"></span>
                  </a>
                  <a class="social-share__button article-card--trend__social-share__button social-share__button--flipboard js-modal-trends__share__button--flipboard" target="_blank" href="https://share.flipboard.com/bookmarklet/popout?v=2&amp;title=<?= wg_esc_url(get_the_title()) ?>&amp;url=<?= urlencode(get_the_permalink()) ?>" data-flip-widget="shareflip">
                    <span></span>
                  </a>
                  <a class="social-share__button article-card--trend__social-share__button social-share__button--email js-modal-trends__share__button--email" href="mailto:?subject=<?php echo wg_esc_url( $title ); ?>&body=<?php echo wg_esc_url( get_the_excerpt() . "\n\n" . $trend_url ); ?>">
                    <span class="icon-paper-plane"></span>
                  </a>
                </div>
              <?php endif; ?>
			      </div>
			    </article>
          <?php $index++; ?>
				<?php endwhile; ?>

				<?php the_module('advertisement', array(
					'slots' => array(
						'horizontal'
					),
					'page' => 0,
					'iteration' => 1
				)); ?>

        <div class="trends-footer post__wysiwyg">
          <?= the_field( 'footer' ); ?>
        </div>
				</main>
			</div>
		</div>
	</div>

</article>

<?php
if ($trend_type != 'article') {
  the_module( "modal-trends" );
}
?>

<?php
get_footer();
?>
