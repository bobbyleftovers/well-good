<?php
/**
 * Editorial Tag Page - Video Module
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


$args = isset($post->editorialtag_module_videos_field) ? $post->editorialtag_module_videos_field : '';

$articles = $args['posts'];
$popular_videos_title = $args['title'];
$category_id = $args['category_id'];

$module_id = 'video carousel';
?>

<?php 
if ($articles) : ?>
	<div class="editorialtag-module editorialtag-module-videos" data-module-init="editorialtags-module-videos"  data-module="<?= $module_id; ?>">
		<div class="container">
			<div class="editorialtag-module-videos__slider">
				<div class="editorialtag-module-videos__header">
					<div class="editorialtag-module-videos__header--title"><?= $popular_videos_title; ?></div>
				</div>
				<div class="editorialtag-module-videos__slides">
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
						<div class="editorialtag-module-videos__slide">
							<a href="<?= $permalink; ?>" data-vars-event="<?= "{$module_id} article"; ?>">
								<div class="editorialtag-module-videos__slide--image" style="background-image:url(<?= $image_url; ?>);">
									<span class="editorialtag-module-videos__slide--play"></span>
								</div>
							</a>
							<div class="editorialtag-module-videos__slide--content">
								<a href="<?= $permalink; ?>" data-vars-event="<?= "{$module_id} article"; ?>">
									<div class="editorialtag-module-videos__slide--title"><?= $title; ?></div>
									<div class="editorialtag-module-videos__slide--excerpt"><?= $excerpt; ?></div>
								</a>
							</div>
						</div>
					<?php 
					endforeach; ?>
				</div>
			</div>
		</div>
	</div>
<?php 
endif; ?>