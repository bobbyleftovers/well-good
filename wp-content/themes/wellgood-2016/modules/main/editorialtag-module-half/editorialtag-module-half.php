<?php
/**
 * Editorial Tag Page - Half Module
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


$args = isset($post->editorialtag_module_half_field) ? $post->editorialtag_module_half_field : '';

$articles = $args['posts'];
$most_popular_title = $args['title'];
$most_popular_placeholder = $args['background_image']['url'];
$category_id = $args['category_id'];

$module_id = 'half background carousel';
?>

<?php 
if ($articles) : ?>
	<div class="container">
		<div class="editorialtag-module editorialtag-module-half" data-module-init="editorialtag-module-half"  data-module="<?= $module_id; ?>">
			<div class="editorialtag-module-half__slider">
				<div class="editorialtag-module-half__header--arrow editorialtag-module-half__header--left"><?= get_svg('tag_page-prev') ?></div>
				<div class="editorialtag-module-half__header">
					<div class="editorialtag-module-half__header--title"><?= $most_popular_title; ?></div>
				</div>
				<div class="editorialtag-module-half__slides">
					<?php 
					foreach($articles as $key => $article) :  
						$article_id = $article->ID;
						$permalink = get_permalink( $article_id );
						$title = $article->post_title;
						$author = get_the_author_meta('display_name', $article->post_author);
						$author_url = get_author_posts_url($article->post_author);
						$excerpt = $article->post_excerpt;
						$content = $article->post_content;
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $article_id ), 'article-retina' );
						$image_url = $image ? @$image[0] : ''; ?>
						<div class="editorialtag-module-half__slide">
							<a href="<?= $permalink; ?>" data-vars-event="<?= "{$module_id} article"; ?>">
								<div class="editorialtag-module-half__slide--image" style="background-image:url(<?= $image_url; ?>);"></div>
							</a>
							<div class="editorialtag-module-half__slide--content">
								<a href="<?= $permalink; ?>" data-vars-event="<?= "{$module_id} article"; ?>">
									<div class="editorialtag-module-half__slide--title"><?= $title; ?></div>
									<div class="editorialtag-module-half__slide--excerpt"><?= $excerpt; ?></div>
								</a>
							</div>
						</div>
					<?php 
					endforeach; ?>
				</div>
				<div class="editorialtag-module-half__header--arrow editorialtag-module-half__header--right"><?= get_svg('tag_page-next') ?></div>
			</div>
			<div class="editorialtag-module-half__background" style="background-image:url(<?= $most_popular_placeholder; ?>);"></div>
		</div>
	</div>
<?php 
endif; ?>