<?php
/**
 * Post Content
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 1.0.0
 */


global $post;

$args = $post->post_content_field;

$post_id = array_key_exists( 'post_id', $args ) && $args['post_id'] ? $args['post_id'] : '';
$instance = array_key_exists( 'instance', $args ) && $args['instance'] ? $args['instance'] : 0;

$post = get_post( $post_id );
setup_postdata( $post );

$is_legacy = is_post_legacy( $post_id );
$is_ajax = wag_post_is_ajax();
$is_branded = has_term( 'branded', 'backend_tag', $post_id ) || has_tag( 'branded', $post_id ) ? true : false;

$partners = get_the_terms( $post_id, 'partners' );
$enable_slideshow = get_field( 'enable_slideshow' ) == true ? true : false;
$show_featured_image = get_field( 'show_featured_image' ) == 'no' ? false : true;


$post_container_classes = array(
	'post',
	'theme-main-2020'
);
if ( $is_branded && ! empty( $partners ) ) :
	$post_container_classes[] = 'post--sponsored';
endif;
if ( $is_ajax ) :
	$post_container_classes[] = 'infinite';
	$post_container_classes[] = 'border-t-1';
	$post_container_classes[] = 'border-gray-10';
endif;
if ( $is_legacy ) :
	$post_container_classes[] = 'post--legacy';
endif;

$post_content_modules = array(
	'pinterest',
	'audio-tracking',
	'inline-image'
);

$experts = get_the_terms( $post_id, 'experts' );

$datalayer_data = json_encode(
	get_datalayer_data(
		$post_id,
		'standard',
		$is_ajax,
		$instance
	), JSON_HEX_APOS
);
$permutive_data = json_encode(
	get_permutive_data(
		$post_id,
		'standard',
		$is_ajax,
		$instance
	), JSON_HEX_APOS|JSON_UNESCAPED_SLASHES
);

$disclaimers_earmark = array(
	'commerce'
);
$disclaimer_tax = get_the_terms( $post_id, 'disclaimer' );
$disclaimers =  $disclaimer_tax ? array_map( function( $disclaimer ) use ( $disclaimers_earmark ) {
	$term_tax = $disclaimer->taxonomy;
	$term_id = $disclaimer->term_id;

	if ( in_array( $disclaimer->slug, $disclaimers_earmark ) ) :
		$disclaimer_style = 'earmark';
	else :
		$disclaimer_style = 'standard';
	endif;

	return array(
		'style' => $disclaimer_style,
		'position' => get_field( 'disclaimer_position', "{$term_tax}_{$term_id}" ),
		'text' => get_field( 'disclaimer_text', "{$term_tax}_{$term_id}" )
	);
}, $disclaimer_tax) : array();


$the_content = '';
if ( $instance > 0 ) :
	$the_content .= "<!-- data-instance: $instance -->";
endif;

$the_content .= trim( get_module( 'disclaimer', array(
	'position' => 'before_content',
	'disclaimers' => array_map( function( $disclaimer ) {
		return array(
			'text' => $disclaimer['text'],
			'style' => 'standard',
		);
	}, array_filter( $disclaimers, function( $disclaimer ) {
		return $disclaimer['position'] == 'before_content';
	} ) )
) ) );

$the_content .= trim( $post->post_content );


$simplereach_enabled = get_field( 'enable_simplereach_tracking' );
if ( $simplereach_enabled ) :
	$post_content_modules[] = 'simple-reach';

	$sr_data = json_encode( get_simplereach_data( $post_id ) ); ?>
	<script type="text/javascript">__reach_config = <?= $sr_data; ?></script>
<?php
endif;

if ( $is_ajax ) :
	echo get_advertising_sra($post_id, $instance);

	the_module('advertisement', array(
		'class' => array(
			'container__ad--infinite',
			'container--grey'
		),
		'slots' => array(
			'horizontal',
			'adhesion'
		),
		'page' => $instance,
		'iteration' => 0
	));
endif;

$post_vars = compile_attrs( array(
	'id' => "post-$post_id",
	'class' => implode( ' ', $post_container_classes ),
	'data-url' => get_the_permalink(),
	'data-datalayer' => strval($datalayer_data),
	'data-permutive' => strval($permutive_data),
	'data-title' => get_the_title(),
	'data-instance' => $instance
) );
?>

<article <?= $post_vars; ?>>
	<?php the_module( 'post-hero', array(
		'post_id' => $post_id,
		'is_branded' => $is_branded,
		'partners' => $partners
	) ); ?>

	<?php
	// New post body
	if ( ! $is_legacy ) : ?>
		<section class="main">
			<div class="container relative ml:flex ml:items-start xl:block">

				<?php the_module( 'post-share' ); ?>

				<?php the_module('advertisement', array(
					'slots' => array(
						'rightrail'
					),
					'class' => array(
						'ml:order-3',
						'xl:order-none'
					),
					'page' => $instance,
					'iteration' => 0,
					'sticky' => true
				)); ?>

				<div class="post__content wg__inline-ad-wrapper" data-module-init="<?= implode( ' ', $post_content_modules ); ?>">
					<main class="post__main">
						<?= apply_filters( 'the_content', $the_content ); ?>

						<?php
						if ( ! empty( $experts ) ) :
							the_module( 'experts', array() );
						endif;

						the_module( 'post-tags' );

						the_module( 'disclaimer', array(
							'position' => 'after_content',
							'disclaimers' => array_map( function( $disclaimer ) {
								return array(
									'text' => $disclaimer['text'],
									'style' => $disclaimer['style']
								);
							},
							array_filter( $disclaimers, function( $disclaimer ) {
								return $disclaimer['position'] == 'after_content';
							} ) )
						)); ?>

						<?php if ( get_field( 'post_tracking_code' ) ) : ?>
							<div class="no-pin">
								<?= prepare_timestamp_tracking_code( get_field( 'post_tracking_code' ) ); ?>
							</div>
						<?php endif; ?>
						
					</main>
				</div>
			</div>
		</section>

	<?php
	// Legacy post body
	else : ?>
		<div class="container post__inner">

			<?php the_module( 'post-share', array(
				'new_version' => true,
				'circle' => true
			) ); ?>

			<section class="main">
				<section class="post-section">
					<div class="post__content wg__inline-ad-wrapper" data-module-init="<?= implode( ' ', $post_content_modules ); ?>">
						<?php if ( $show_featured_image ): ?>
							<figure class="post__featured-image">
								<div class="post__featured-image-wrapper">
									<?php
									$image = get_the_post_thumbnail_url( $post_id, 'large');
									$retina = get_the_post_thumbnail_url( $post_id, 'large');
									$alt = 'Thumbnail for ' . get_the_title(  $post_id );
									$pinterest_link = '//pinterest.com/pin/create/link/?url=' . urlencode(get_the_permalink( $post_id )) . '&amp;description=' . wg_esc_url( wp_strip_all_tags( get_the_title( $post_id ) ) ) . '&amp;media=' . urlencode( $image );

									the_module('image', array(
										'image_src' => $image,
										'image_src_retina' => $retina,
										'image_alt' => $alt
									)); ?>
									<a target="_blank" href="<?= $pinterest_link; ?>" class="post__pin-link">
										<span class="post__pin-wrapper">
											<span class="post__pin-image social-share__button social-share__button--circle social-share__button--pinterest">
												<span class="icon-pinterest-p"></span>
											</span>
										<span class="post__pin-label">Pin It</span>
										</span>
									</a>
								</div>
								<?php if ( featured_image_has_caption() ): ?>
									<figcaption class="figcaption--legacy text-caption"><?php the_featured_image_caption(); ?></figcaption>
								<?php endif; ?>
								<?php the_module( 'post-share', array(
									'mobile' => true
								) ); ?>
							</figure>
						<?php endif; ?>
						<?php if ( $enable_slideshow ) : ?>
							<figure class="post__featured-slideshow">
								<?php the_module( 'slideshow', get_field( 'slideshow' ) ); ?>
							</figure>
						<?php endif; ?>

						<main class="post__main">

							<?= apply_filters( 'the_content', $the_content ); ?>

							<?php
							if ( ! empty( $experts ) ) :
								the_module( 'experts', array() );
							endif;

							the_module( 'post-tags' );

							the_module( 'disclaimer', array(
								'position' => 'after_content',
								'disclaimers' => array_map( function( $disclaimer ) {
									return array(
										'text' => $disclaimer['text'],
										'style' => $disclaimer['style']
									);
								},
								array_filter( $disclaimers, function( $disclaimer ) {
									return $disclaimer['position'] == 'after_content';
								} ) )
							)); ?>

							<?php if ( get_field( 'post_tracking_code' ) ) : ?>
								<div class="no-pin">
									<?= prepare_timestamp_tracking_code( get_field( 'post_tracking_code' ) ); ?>
								</div>
							<?php endif; ?>

						</main>
					</div>
				</section>
				<section class="sidebar">
					<?php
					the_module('disclaimer', array(
						'position' => 'rightrail',
						'texts' => array_filter($disclaimers, function($disclaimer) {
							return $disclaimer['position'] == 'rightrail';
						})
					));
					the_module('advertisement', array(
						'slots' => 'rightrail',
						'page' => $instance,
						'iteration' => 0,
						'sticky' => true
					)); ?>
				</section>
			</section>
		</div>
	<?php endif; ?>
</article>

<?php wp_reset_postdata(); ?>
