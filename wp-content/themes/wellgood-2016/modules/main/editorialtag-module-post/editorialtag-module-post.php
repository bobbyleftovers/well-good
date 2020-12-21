<?php
/**
 * Editorial Tag Page - Post Module
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


$args = isset( $post->editorialtag_module_post_field ) ? $post->editorialtag_module_post_field : '';

$article = $args['posts'][0];
$is_sponsored = $args['sponsored'];
$override_title = $args['title'];
$category_id = $args['category_id'];

$module_id = 'featured post';

$article_id = $article->ID;
$permalink = get_permalink( $article_id );
$title = $override_title ? $override_title : $article->post_title;
$author_id = $article->post_author;
$author_name = get_the_author_meta( 'display_name', $author_id );
$author_url = get_author_posts_url( $author_id );
$excerpt = $article->post_excerpt;
$content = $article->post_content;
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $article_id ), 'article-retina' );
$image_url = $image ? @$image[0] : ''; 
?>

<?php 
if ($article) : ?>
	<div class="container">
		<div class="editorialtag-module editorialtag-module-post" data-module="<?= $module_id; ?>">
			<div class="editorialtag-module-post__header">
				<a href="<?= $permalink; ?>" data-vars-event="<?= "{$module_id} article"; ?>">
					<div class="editorialtag-module-post__header--title"><?= $title; ?></div>
					<div class="editorialtag-module-post__header--excerpt"><?= $excerpt; ?></div>
				</a>
				<div class="editorialtag-module-post__header--meta">
					<div class="editorialtag-module-post__header--author">By <a href="<?= $author_url ?>" data-vars-event="<?= "{$module_id} author"; ?>"><?= $author_name; ?></a></div>
					<?php 
					if ( $is_sponsored ) : ?>
						<span class="editorialtag__ad-label editorialtag__ad-label--dark"></span>
					<?php 
					endif; ?>
				</div>
			</div>
			<div class="editorialtag-module-post__background" style="background-image:url(<?= $image_url; ?>);"></div>
		</div>
	</div>
<?php 
endif; ?>