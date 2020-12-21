<?php
/* Template Name: Council Hub */
get_header();
global $post;

$id = $post->ID;
if ($post->post_parent)	:
	$ancestors = get_post_ancestors($id);
	$root = count($ancestors) - 1;
	$parent_id = $ancestors[$root];
else :
	$parent_id = $id;
endif;
$page_color = get_field('page_background_color');
$show_council = get_field('show_council_members');
$council_hub_blocks = get_module('location-hub-blocks', 'featured_posts');
$middle_image = get_field( 'middle_image' );

$wellness_collective_custom = get_field('wellness_collective_hub_customizations', $parent_id);
$wellness_collective_display_images = get_field('wellness_collective_display_images', $id);
?>

<article class="post suja chub-post"<?= ( $page_color ? " style='background-color: $page_color';" : ""); ?>>
	<div class="post__inner">

		<?php the_module( "council-hub-header" ); ?>
		<?php if ($wellness_collective_custom && $wellness_collective_display_images) :
			$wellness_collective_images = get_field('wellness_collective_images', $parent_id); ?>
			<div class="container chub-wellness-collective-images">
				<?php foreach ($wellness_collective_images as $i => $image) : ?>
					<div class="chub-wellness-collective-image-container">
						<div class="chub-wellness-collective-image" style="background-image:url(<?= $image['image']['url'] ?>);"></div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ($council_hub_blocks || $middle_image || $show_council) : ?>
			<div class="container container-legacy container--with-aside post__content-wrapper">
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
						<?= $council_hub_blocks; ?>
						<?php the_module( "location-hub-middle" ); ?>
						<?php if( $show_council ) {
							the_module( "council-hub-members" );
						} ?>
					</main>
				</div>
				<aside class="sidebar post__sidebar">
					<?php the_module('advertisement', array(
						'slots' => 'rightrail',
						'page' => 0,
						'iteration' => 0,
						'sticky' => true
					)); ?>
				</aside>
			</div>
		<?php endif; ?>
	</div>

	<div class="container">
		<?php the_module('advertisement', array(
			'slots' => array(
				'horizontal'
			),
			'page' => 0,
			'iteration' => 1
		)); ?>
	</div>

</article>

<?php
get_footer();
?>
