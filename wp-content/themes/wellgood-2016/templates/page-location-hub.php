<?php
/* Template Name: Location Hub */
get_header();
global $post;
?>

<article class="post suja">
	<div class="post__inner">
		<?php
		the_module( "location-hub-header" ); ?>
		<div class="container container-legacy container--with-aside post__content-wrapper location-hub-content">
			<div class="post__content">
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
				<main class="page-main">
					<?php the_module( "location-hub-blocks", "features" ); ?>
					<?php the_module( "location-hub-middle" ); ?>
					<?php the_module( "location-hub-blocks", "more_features" ); ?>
				</main>
			</div>
			<aside class="sidebar post__sidebar">
				<div class="post__sticky-advertisement">
					<?php the_module('advertisement', array(
						'slots' => array(
							'rightrail',
						),
						'page' => 0,
						'iteration' => 0,
						'sticky' => true
					)); ?>
				</div>
			</aside>
		</div>
	</div>

</article>

<?php
get_footer();
?>
