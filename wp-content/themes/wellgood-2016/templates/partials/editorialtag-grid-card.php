<?php
/**
 * Tag card
 * 
 * $vars['article'] (array) -   post info
 * $vars['type']    (str) -     post type
 * 
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


$article = $vars['article'];
$class = $vars['class'];
$id = $article->ID;
$permalink = get_permalink( $id );
$override = get_field( 'override_automatic_title_casing', $article->ID );
$title = verify_title_case( $article->post_title, $article->post_date, $override );
$excerpt = ( $class == 'featured' ) ? get_the_excerpt( $id ) : '';
$author = get_the_author_meta( 'display_name', $article->post_author );
$author_url = get_author_posts_url( $article->post_author );
$content = $article->post_content;
$image_size = 'article-retina';
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $image_size );
$image_url = $image ? @$image[0] : '';
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'article-retina' );
$retina_url = $image ? @$image[0] : '';
$url_target = get_field('_pprredirect_newwindow', $id);
$is_video = has_tag('video', $id) || has_term( 'video', 'backend_tag', $id ) ? true : false;
$is_branded = article_is_branded( $id );

$classes = ['editorialtag-grid-card'];
if ( $class ) :
	array_push( $classes, 'editorialtag-grid-card--' . $class );
endif;
?>

<article class="<?php echo implode( ' ', $classes ); ?> show-social-share">
	<div class="editorialtag-grid-card__image">
		<?php brrl_the_module('main-2020/post-card-share',array(
				'title' => $title,
				'id' => $id,
                'permalink' => $permalink
              )); ?>
		<a href="<?php echo $permalink; ?>" target="<?php echo $url_target; ?>">
			<?php the_module('image', array(
				'image_src' => $image_url,
				'image_src_retina' => $retina_url,
				'image_alt' => $title,
				'is_video' => $is_video
			)); ?>
		</a>
	</div>
	<div class="editorialtag-grid-card__content">
		<a href="<?php echo $permalink; ?>" target="<?php echo $url_target; ?>">
			<div class="editorialtag-grid-card__content--title">
				<?php echo $title; ?>
			</div>
		</a>
		<?php if ( $excerpt ) : ?>
			<div class="editorialtag-grid-card__content--excerpt">
				<?= truncate_by_word( $excerpt, 20, '...' ); ?>
			</div>
		<?php endif; ?>
		<div class="editorialtag-grid-card__content--meta">
			<div class="editorialtag-grid-card__content--author">
				<?php if (!$is_branded) : ?>
					By <a href="<?php echo $author_url; ?>"><?php echo $author; ?></a>
				<?php else : ?>
					Paid Content
				<?php endif; ?>
			</div>
			<span class="editorialtag-grid-card__content--share">
				<?php echo get_svg('share-thin'); ?>
			</span>
		</div>
	</div>
</article>
