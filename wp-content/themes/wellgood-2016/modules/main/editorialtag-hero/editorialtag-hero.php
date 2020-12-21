<?php
/**
 * Editorial Tag Page - Hero
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


$args = isset( $post->editorialtag_hero_field ) ? $post->editorialtag_hero_field : '';

$category = $args['category'];
$subhead = $args['subhead'];

$header_classes = array( 'editorialtag-hero' );
$header_style = get_field( 'editorialtag_theme', $category ) ?? 'white';
$hero_image = get_field( 'editorialtag_image', $category );
$sponsor = get_field( 'editorialtag_sponsor', $category, false );

$header_classes[] = $header_style;
if ( $hero_image ) :
	$header_classes[] = 'hero-image';
endif;
if ( $subhead ) :
	$header_classes[] = 'editorialtag-hero--with-subhead';
endif;

$subhead_classes = array( 'editorialtag-hero__menu' );
$subhead_classes[] = $header_style;

$subhead_items = array();
foreach( $subhead as $editorialtag ) :
	$data = get_term( $editorialtag );
	$name = $data->name;
	$link = get_term_link( $editorialtag );
	$subhead_items[] = '<li class="editorialtag-hero__menu--item"><a class="editorialtag-hero__menu--link" href="'.$link.'">'.$name.'</a></li>';
endforeach;

$name = span_per_word( $category->name );
$slug = $category->slug;
$description = $category->description;

$breadcrumbs = '';
$depth = get_category_depth( $category );
if ( $depth > 0 ) :
	$parent_category_id = get_term( $category )->parent;
	$parent_category_obj = get_term( $parent_category_id );

	$breadcrumbs .= '<div class="container"><span class="editorialtag__breadcrumbs meta">Back to <a class="editorialtag__breadcrumbs--item" href="' . get_category_link( $parent_category_obj->term_id ) . '"><span class="editorialtag__breadcrumb">' . $parent_category_obj->name . '</span></a></span></div>';
endif;
?>

<div class="<?php echo implode( ' ', $header_classes ); ?>">
	<?php
	if ( $breadcrumbs ) :
		echo $breadcrumbs;
	endif;

	if ( $hero_image ) : ?>
		<div class="editorialtag-hero__top editorialtag-hero__image" style="background-image:url(<?php echo $hero_image['url']; ?>);">
			<h1 class="editorialtag-hero__title">
				<?php echo $name; ?>
			</h1>
		</div>
	<?php
	else: ?>
		<div class="editorialtag-hero__top container">
			<h1 class="editorialtag-hero__title relative z-10">
				<span><?php echo $name; ?></span>
			</h1>
		</div>
	<?php
	endif; ?>
</div>

<?php
if ( $subhead ) : ?>
	<div class="<?php echo implode( ' ', $subhead_classes ); ?>">
		<div class="container">
			<ul class="editorialtag-hero__menu--items">
				<?php echo implode( '', $subhead_items ); ?>
			</ul>
		</div>
	</div>
<?php
endif;

if ( $sponsor ) : ?>
	<div class="editorialtag-hero__sponsor <?php echo $header_style; ?>">
		<?php echo $sponsor; ?>
	</div>
<?php
endif; ?>
