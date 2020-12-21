<?php
/**
 * Editorial Tag Page - "Older" Grid
 *
 * At the bottom of an editorial tag page, there is a grid
 * where older posts are displayed
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


$args = isset($post->editorialtag_older_field) ? $post->editorialtag_older_field : '';
$articles = $args['articles'];

$title = 'Older';
?>
<?php 
if ($articles) : ?>

	<div class="container">
		<h2 class="editorialtag-section__header editorialtag-section__header--with-margin">
			<?= $title; ?>
		</h2>
	</div>

	<div class="container">
		<div class="editorialtag-older">
			<?php 
			foreach($articles as $key => $article) :  
				$id = $article->ID;
				$permalink = get_permalink($id);
				$override = get_field( 'override_automatic_title_casing', $article->ID );
				$title = verify_title_case( truncate_by_word( $article->post_title, 16, '...' ), $article->post_date, $override );
				$author = get_the_author_meta('display_name', $article->post_author);
				$author_url = get_author_posts_url($article->post_author);
				$excerpt = $article->post_excerpt;
				$content = $article->post_content;
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'medium' );
				$image_url = $image ? @$image[0] : '';
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'article-retina' );
				$retina_url = $image ? @$image[0] : '';
				$is_video = article_is_video( $id );
				$is_branded = article_is_branded( $id );
				?>
				
				<div class="editorialtag-older__card">
					<a href="<?= $permalink; ?>">
						<div class="editorialtag-older__card--image">
							<?php the_module('image', array(
								'image_src' => $image_url,
								'image_src_retina' => $retina_url,
								'image_alt' => $title,
								'is_video' => $is_video
							)); ?>
						</div>
					</a>
					<div class="editorialtag-older__card--content">
						<a href="<?= $permalink; ?>">
							<div class="editorialtag-older__card--title"><?= $title; ?></div>
						</a>
						<div class="editorialtag-older__card--meta">
							<div class="editorialtag-older__card--author">
								<?php if ( $is_branded ) : ?>
									Paid Content
								<?php else : ?>
									By <a href="<?= $author_url; ?>"><?= $author; ?></a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php 
			endforeach; ?>
		</div>
	</div>

<?php 
endif; ?>