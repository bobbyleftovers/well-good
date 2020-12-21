<?php
/**
 * Editorial Tag Page - Tag Module
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


$args = isset( $post->editorialtag_module_tag_field ) ? $post->editorialtag_module_tag_field : '';

$tag = $args['tag'];
$articles = $args['posts'];
$override_title = $args['title'];
$category_id = $args['category_id'];

$module_id = 'featured tag';

$title = $override_title ? $override_title : $tag->name;
$background = ( ! empty( get_field( 'image', $tag ) ) ) ? get_field( 'image', $tag ) : get_field( 'featured_image_fallback', 'options' );
$more_link = get_tag_link( $tag->term_id );
?>

<?php 
if ( $articles ) : ?>
	<div class="container">
		<div class="editorialtag-module editorialtag-module-tag" data-module="<?= $module_id; ?>">
			<div class="editorialtag-module-tag__slider">
				<div class="editorialtag-module-tag__header">
					<div class="editorialtag-module-tag__header--title"><?= $title; ?></div>
					<?php
					if ( $more_link ) : ?>
						<a href="<?= $more_link; ?>" data-vars-event="<?= "$module_id view all"; ?>" class="editorialtag-module-tag__header--more">View All</a>
					<?php
					endif; ?>
				</div>
				<div class="editorialtag-module-tag__slides">
					<?php 
					foreach( $articles as $key => $article ) :
						$article_id = $article->ID;
						$permalink = get_permalink( $article_id );
						$title = $article->post_title;
						$author_id = $article->post_author;
						$author_name = get_the_author_meta( 'display_name', $author_id );
						$author_url = get_author_posts_url( $author_id );
						$excerpt = $article->post_excerpt;
						$content = $article->post_content;
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $article_id ), 'article-retina' );
						$image_url = $image ? @$image[0] : ''; ?>
						<div class="editorialtag-module-tag__slide">
							<div class="editorialtag-module-tag__slide--content">
								<a href="<?= $permalink; ?>" data-vars-event="<?= "$module_id article"; ?>">
									<div class="editorialtag-module-tag__slide--title"><?= $title; ?></div>
									<div class="editorialtag-module-tag__slide--excerpt"><?= $excerpt; ?></div>
								</a>
								<div class="editorialtag-module-tag__slide--author">By <a href="<?= $author_url ?>" data-vars-event="<?= "$module_id author"; ?>"><?= $author_name; ?></a></div>
							</div>
						</div>
					<?php 
					endforeach; ?>
				</div>
			</div>
			<div class="editorialtag-module-tag__background" style="background-image:url(<?= $background['url']; ?>);"></div>
		</div>
	</div>
<?php 
endif; ?>